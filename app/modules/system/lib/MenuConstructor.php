<?
class MenuConstructor {

    private $menu, $items, $order, $tpl, $pages_ids, $pages;

    public function __construct($slug) {

        $menu_item = Storage::where('module', 'menu')->where('name', $slug)->first();
        if (!is_object($menu_item) || !$menu_item->value)
            return false;

        $this->menu = json_decode($menu_item->value, 1);
        $this->items = @(array)$this->menu['items'];
        $this->order = @(array)json_decode($this->menu['order'], 1);
        /*
        $this->tpl = array(
            'container' => '<ul>%elements%</ul>',
            'element_container' => '<li%attr%>%element%%children%</li>',
            'element' => '<a href="%url%"%attr%>%text%</a>',
            'active_class' => 'active',
        );
        */
        $this->tpl = array(
            'container' => $this->menu['container'],
            'element_container' => $this->menu['element_container'],
            'element' => $this->menu['element'],
            'active_class' => $this->menu['active_class'],
        );
        #Helper::d($this->tpl);
        #Helper::d($this->menu);
        #Helper::d($this->items);
        #Helper::d($this->order);

        $this->pages_ids = array();
        $this->pages = array();

        /**
         * Предзагружаем нужные данные, чтобы избежать множественных однотипных запросов к БД
         */
        $this->prepare_data();
    }

    /**
     * Предзагружаем нужные данные, чтобы избежать множественных однотипных запросов к БД
     */
    private function prepare_data() {
        if (count($this->items)) {
            foreach ($this->items as $item) {
                /**
                 * Находим все Страницы в меню
                 */
                if ($item['type'] == 'page') {
                    #Helper::d($item);
                    $this->pages_ids[] = $item['page_id'];
                }
            }

            /**
             * Если в меню присутствуют страницы - загрузим их все сразу, одним запросом
             */
            if (count($this->pages_ids)) {
                $pages = Page::whereIn('id', $this->pages_ids)->get();
                #Helper::tad($pages);
                if (count($pages)) {
                    $array = new Collection();
                    foreach ($pages as $p => $page) {
                        $array[$page->id] = $page->extract(1);
                    }
                    #Helper::tad($array);
                    $this->pages = $array;
                    unset($pages);
                }
            }
        }
    }


    /**
     * Отрисовываем меню
     *
     * @return bool|mixed|string
     */
    public function draw() {

        if (
            !isset($this->tpl['container']) || !isset($this->tpl['element_container']) || !isset($this->tpl['element'])
            #|| !@is_array($menu['elements']) || !@count($menu['elements'])
        )
            return false;

        /**
         * Отрисовываем меню, начиная с самого верхнего уровня
         */
        $menu = $this->get_level($this->order);

        #echo $menu;
        #die;

        return $menu;
    }


    /**
     * Отрисовываем уровень меню
     *
     * @param $order
     * @return mixed|string
     */
    private function get_level($order) {

        $level = array();

        /**
         * Перебираем все элементы меню текущего уровня
         */
        foreach ($order as $element_array) {
            $id = $element_array['id'];

            /**
             * Отрисовываем элемент меню
             */
            $element = $this->get_element($id);
            #Helper::dd($element);

            if (!$element)
                continue;

            /**
             * Отрисовываем дочерние элементы текущего пункта меню, если они есть
             */
            $child_level = '';
            if (isset($element_array['children'])) {
                $children = $element_array['children'];
                $child_level = $this->get_level($element_array['children']);
            }

            /**
             * Отрисовываем текущий элемент меню
             */
            $element = strtr(
                $this->tpl['element_container'],
                array(
                    '%element%' => $element,
                    '%children%' => @$child_level ?: '',
                    '%attr%' => '',
                )
            );

            /**
             * Добавляем отрисованный элемент меню в текущий уровень
             */
            $level[] = $element;

        }
        #Helper::dd($level);

        /**
         * Отрисовываем текущий уровень меню
         */
        $return = implode('', $level);
        $return = strtr(
            $this->tpl['container'],
            array(
                '%elements%' => $return,
            )
        );
        $return = preg_replace("~\%[^\%]+?\%~is", '', $return);

        /**
         * Возвращаем текущий уровень меню
         */
        return $return;
    }


    /**
     * Отрисовываем элемент меню
     *
     * @param $element_id
     * @return mixed|string
     */
    private function get_element($element_id) {


        if (!isset($this->items[$element_id]))
            return false;

        /**
         * Получаем данные об элементе меню
         */
        $data = $this->items[$element_id];

        #Helper::d($data);
        if (@$data['hidden'])
            return;

        /**
         * Определяем атрибуты ссылки (class, title, target и т.д.)
         */
        $attr = array();
        if (isset($data['title']) && $data['title'] != '')
            $attr['title'] = $data['title'];
        if (isset($data['target']))
            $attr['target'] = $data['target'];

        /**
         * Определяем, активна ли ссылка или нет
         */
        if ($this->get_active($data))
            $attr['class'] = @trim(@trim($attr['class']) . ' ' . $this->tpl['active_class']);

        /**
         * Получаем URL ссылки
         */
        $url = $this->get_url($data);

        /**
         * Отрисовываем элемент меню
         */
        $return = strtr(
            $this->tpl['element'],
            array(
                '%url%' => $url,
                '%attr%' => ' ' . trim(Helper::arrayToAttributes($attr)),
                '%text%' => @$data['text'],
            )
        );
        $return = preg_replace("~\%[^\%]+?\%~is", '', $return);
        #Helper::d($return);

        /**
         * Возвращаем элемент меню
         */
        return $return;
    }


    /**
     * Возвращаем URL элемента меню
     *
     * @param $element
     * @return bool
     */
    private function get_url($element) {
        #return '#';
        #Helper::d($element);

        /**
         * Возвращаем URL элемента меню, в зависимости от типа элемента меню
         */
        switch($element['type']) {

            case 'page':
                if (isset($this->pages[$element['page_id']]) && is_object($this->pages[$element['page_id']]))
                    return URL::route('page', $this->pages[$element['page_id']]->slug);
                return false;
                break;

            case 'link':
                return @$element['url'] ?: false;
                break;

            case 'route':
                $route_params = array();
                if ('' != ($element['route_params'] = trim($element['route_params']))) {
                    $temp = explode("\n", $element['route_params']);
                    if (@count($temp)) {
                        foreach ($temp as $tmp) {
                            $tmp = trim($tmp);
                            if (!$tmp)
                                continue;
                            if (strpos($tmp, '=')) {
                                $tmp_params = explode('=', $tmp, 2);
                                $route_params[trim($tmp_params[0])] = trim($tmp_params[1]);
                            } else {
                                $route_params[] = $tmp;
                            }
                        }
                    }
                }
                return URL::route($element['route_name'], $route_params);
                break;

            case 'function':
                #Helper::dd($element);
                $function = Config::get('menu.functions.' . $element['function_name']);
                if (isset($function) && is_callable($function)) {
                    $result = $function();
                    return @$result['url'] ?: false;
                }
                return false;
                break;

            default:
                return false;
                break;
        }
    }


    /**
     * Возвращаем пометку об активности текущего пункта меню
     *
     * @param $element
     * @return bool
     */
    private function get_active($element) {
        #return false;

        /**
         * Возвращаем пометку об активности ссылки, в зависимости от типа элемента меню
         */
        switch($element['type']) {

            case 'page':
                return $this->isRoute('page', $this->pages[$element['page_id']]->slug);
                break;

            case 'link':
                return (bool)preg_match('~' . $element['url'] . '$~s', Request::fullUrl());
                break;

            case 'route':
                $route_params = array();
                if ('' != ($element['route_params'] = trim($element['route_params']))) {
                    $temp = explode("\n", $element['route_params']);
                    if (@count($temp)) {
                        foreach ($temp as $tmp) {
                            $tmp = trim($tmp);
                            if (!$tmp) {
                                continue;
                            }
                            if (strpos($tmp, '=')) {
                                $tmp_params = explode('=', $tmp, 2);
                                $route_params[trim($tmp_params[0])] = trim($tmp_params[1]);
                            } else {
                                $route_params[] = $tmp;
                            }
                        }
                    }
                }
                return $this->isRoute($element['route_name'], $route_params);
                break;

            case 'function':
                #Helper::dd($element);
                $function = Config::get('menu.functions.' . $element['function_name']);
                if (isset($function) && is_callable($function)) {
                    $result = $function();
                    #return $result['url'];
                    return (bool)preg_match('~' . $result['url'] . '$~s', Request::fullUrl());
                }
                return false;
                break;

            default:
                return false;
                break;
        }
    }

    /**
     * Функция проверяет, сопадает ли проверяемый маршрут (и его параметры) с текущим положением пользователя на сайте
     *
     * @param bool $route_name
     * @param array $route_params
     * @return bool
     */
    private function isRoute($route_name = false, $route_params = array()) {

        if (Route::currentRouteName() != $route_name)
            return false;

        $match = true;
        $route = Route::getCurrentRoute();

        /**
         * Парсим параметры текущего маршрута
         */
        if (is_string($route_params)) {
            preg_match("~\{([^\}]+?)\}~is", $route->getPath(), $matches);
            #Helper::dd($matches);
            if (@$matches[1] != '') {
                $route_params = array($matches[1] => $route_params);
            } else {
                $route_params = array();
            }
        }
        #Helper::d($route_params);

        /**
         * Если есть параметры у текущего роута - проверяем их
         */
        if (count($route_params)) {

            /**
             * Если объявлен модификатор url-адреса для текущего роута - пробуем его применить
             */
            $route_params = URL::get_modified_parameters($route_name, $route_params);
            #Helper::dd($route_params);

            foreach ($route_params as $key => $value) {
                #Helper::d("[" . $key . "] => " . $route->getParameter($key) . " = " . $value);
                /**
                 * Если хотя бы один из параметров текущего маршрута не совпадает с проверяемым - возвращаем FALSE
                 */
                if ($route->getParameter($key) != $value) {
                    $match = false;
                    break;
                }
            }
        }
        #Helper::d((int)$match);

        /**
         * Возвращаем результат
         */
        return (bool)$match;
    }
}
