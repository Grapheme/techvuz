<?php

class NestedSetModel {

    public $data, $id_left_right, $i;

	public function __construct() {

		$this->data = array();
        $this->id_left_right = array();
        $this->i = 0;
	}


	public function get_id_left_right($data){


        $this->data = $data;
        #Helper::dd($this->data);

        $this->id_left_right = array();
        $this->i = 0;

        $this->parse_level($this->data);

        #Helper::dd($this->id_left_right);
        return $this->id_left_right;
	}

    private function parse_level($array) {

        if (is_array($array) && count($array))
            foreach ($array as $key => $value) {
                if (isset($value['id'])) {
                    $this->id_left_right[$value['id']] = array();
                    $this->id_left_right[$value['id']]['left'] = ++$this->i;
                }
                if (isset($value['children'])) {
                    $this->parse_level($value['children']);
                }
                if (isset($value['id'])) {
                    $this->id_left_right[$value['id']]['right'] = ++$this->i;
                }
            }
    }



    public function get_hierarchy_from_id_left_right($id_left_right) {

        if (!isset($id_left_right) || !$id_left_right || !is_array($id_left_right) || !count($id_left_right))
            return false;

        $this->id_left_right = $id_left_right;

        $this->parent_id = 0;

        $dbg = 0;

        if ($dbg) {
            Helper::d($id_left_right);
            echo "<hr/>";
        }

        /**
         * Сначала проставим parent_id
         */
        $this->set_parent_id($this->id_left_right);

        if ($dbg) {
            Helper::d($this->id_left_right);
            echo "<hr/>";
        }

        /**
         * Сформируем древовидную структуру
         */
        $tree = $this->get_hierarchy($this->id_left_right);

        if ($dbg) {
            Helper::dd($tree);
            echo "<hr/>";
        }

        /**
         * Вернем дерево
         */
        return $tree;
    }


    /**
     * Рекурсивно устанавливает всем элементам parent_id
     *
     * @param $array
     * @param int $parent_id
     */
    private function set_parent_id($array, $parent_id = 0) {

        /**
         * Проходим по всем элементам списка
         */
        while (list($key, $value) = each($array)) {

            $this->id_left_right[$key]['parent_id'] = $parent_id;

            /**
             * Если текущий элемент имеет дочерние элементы...
             */
            if (
                isset($value['left']) && isset($value['right'])
                && $value['left'] && $value['right']
                && $value['left'] + 1 < $value['right']
            ) {

                /**
                 * Отбираем все дочерние элементы
                 */
                $children = array_filter(
                    $array,
                    create_function(
                        '$element, $left=' . $value['left'] . ', $right=' . $value['right'],
                        'return (
                            isset($element["left"]) && isset($element["right"])
                            && $element["left"] > $left && $element["right"] < $right
                        );'
                    )
                );

                /**
                 * Если нашлись дочерние элементы...
                 */
                if (count($children)) {

                    /**
                     * Удалим их ID из основного списка, чтобы ошибочно не обрабатывать их по несколько раз
                     */
                    foreach ($children as $k => $v)
                        unset($array[$k]);

                    /**
                     * Устанавливаем всем дочерним элементам родительский ID (текущего элемента)
                     */
                    $this->set_parent_id($children, $key);
                }
            }
        }
    }


    /**
     * Возвращает иерархическое дерево
     *
     * @param $array
     * @return array
     */
    private function get_hierarchy($array) {

        /**
         * В этом массиве:
         * ключи - ID родительской категории (у категорий верхнего уровня = 0)
         * значения - массив со списком значений ID дочерних категорий (у которых parent_id = ключу текущей записи массива)
         */
        $this->levels = array();

        /**
         * Заполним массив...
         */
        foreach ($array as $a => $arr)
            $this->levels[$arr['parent_id']][] = $a;
        #Helper::d($this->levels);

        /**
         * Сформируем дерево, начиная с самого верхнего уровня
         */
        $tree = $this->get_hierarchy_level($this->levels[0]);
        #Helper::d($tree);

        /**
         * Возвращаем дерево
         */
        return $tree;
    }


    /**
     * Рекурсивно формирует текущий уровень иерархического дерева
     *
     * @param $array
     * @return array
     */
    private function get_hierarchy_level($array) {

        /**
         * Текущий уровень дерева
         */
        $tree = array();

        /**
         * Перебираем все элементы, входящие в текущий уровень дерева
         */
        foreach($array as $id) {

            /**
             * Формируем массив с требуемыми значениями текущего элемента
             */
            $element = array('id' => $id);

            /**
             * Если есть запись о том, что текущий элемент - родительский по отношению к некоторым другим -
             * то построим новый уровень дерева, начиная с текущего элемента
             */
            if (isset($this->levels[$id]))
                $element['children'] = $this->get_hierarchy_level($this->levels[$id]);

            /**
             * Добавим текущий элемент (он также может быть деревом) к текущему дереву
             */
            $tree[] = $element;
        }

        /**
         * Возвращаем текущее дерево
         */
        return $tree;
    }



    public static function get_array_for_select($categories) {

        /**
         * Подсчитаем отступ для каждой категории
         */
        $indent_debug = 0;
        $indent = 0;
        $last_indent_increate_rgt = array();
        foreach ($categories as $category) {

            if ($indent_debug)
                Helper::ta($category);

            $category->indent = $indent;

            if ($indent_debug)
                Helper::d("Устанавливаем текущий отступ категории: " . $indent);

            if ($category->lft+1 < $category->rgt) {

                ++$indent;
                $last_indent_increate_rgt[] = $category->rgt;

                if ($indent_debug) {
                    Helper::d("Увеличиваем текущий уровень отступа: " . $indent . " (" . $category->lft . "+1 < " . $category->rgt . ")");
                    Helper::d("Добавляем RGT в массив 'RGT родительских категории': " . $category->rgt . " => " . implode(', ', $last_indent_increate_rgt));
                }
            }

            #/*

            $plus = 1;
            $exit = false;
            do {
                if (in_array(($category->lft+(++$plus)), $last_indent_increate_rgt)) {

                    --$indent;

                    /*
                    Helper::d("LFT категории + " . $plus . " (" . ($category->lft+$plus) . ") найдено в массиве 'RGT родительских категорий' => " . implode(', ', $last_indent_increate_rgt));
                    Helper::d("Уменьшаем текущий уровень отступа: " . $indent);
                    #*/

                } else {
                    $exit = true;
                }

            } while(!$exit);

            #Helper::d("<hr/>");
        }

        #Helper::tad($categories);

        /**
         * Соберем все категории в массив с отступами для select
         */
        $categories_for_select = array();
        foreach ($categories as $category) {
            $categories_for_select[$category->id] = str_repeat('&nbsp; &nbsp; &nbsp; ', $category->indent) . $category->name;
        }
        if ($indent_debug)
            Helper::dd($categories_for_select);

        return $categories_for_select;
    }



}
