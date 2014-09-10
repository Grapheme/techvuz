<?php
/**
 * Extended Form
 * Класс для работы с нестандартными элементами форм административного интерфейса.
 * Пример: создание галерей, загрузка одиночных изображений, тэги и т.д.
 * @copyright Alexander Zelensky
 */
class ExtForm {

    private static $codes = array();
    private static $processes = array();
    private $instance = null;

    ## Singleton
    private function __construct() {}
    private function __clone() {}
    private function __wakeup() {}
    public static function getInstance() {
        if(self::$instance === null) {
            self::$instance = new self;   
        }
        return self::$instance;
    }

	/**
	 * Добавляет новый элемент расширенной формы. Название элемента, функция-замыкание для вывода представления элемента, функция-замыкание для обработки полученных данных.
     * Add Extended Form element to base: ExtForm::add(<element>, <element_closure>, <process_closure>);
     *
     * @param string $element
     * @param closure $element_closure
     * @param closure $process_closure
     * @return Exception/bool
	 */
    public static function add($element = '', $element_closure, $process_closure = false) {

        $element = trim($element);
        #var_dump($element);

        if (!$element)
            throw new Exception('Bad ExtForm element name.');
        
        if ( isset(self::$codes[$element]) || isset(self::$processes[$element]) )
            throw new Exception("Can't redeclare ExtForm element: " . $element . ". This element has already declared earlier.");
        
        if (isset($element_closure) && !is_callable($element_closure))
            throw new Exception('Bad ExtForm element closure.');
        else
            self::$codes[$element] = $element_closure;
        
        if (isset($process_closure) && !is_callable($process_closure))
            throw new Exception('Bad ExtForm process closure.');
        else
            self::$processes[$element] = $process_closure;
            
        return false;
    }

	/**
	 * Обрабатывает данные, переданные через представление элемента расширенной формы.
     * Process Extended Form element data: ExtForm::process(<element>, <value>);
     *
     * @param string $element
     * @param array $params
     * @return mixed
	 */
    public static function process($element = '', $params = null) {
        if (isset(self::$processes[$element]) && is_callable(self::$processes[$element])) {
            return call_user_func(self::$processes[$element], $params);
        }
    }

	/**
	 * Перехватываем все элементы расширенной формы, которые были добавлены.
     * Catch ALL Extended Form elements: ExtForm::<element>($params);
     *
     * @param string $element
     * @param array $params
     * @return mixed
	 */
    public static function __callStatic($element = '', array $params) {
        #echo 'Вы хотели вызвать '.__CLASS__.'::'.$element.', но его не существует, и сейчас выполняется '.__METHOD__.'()';
        $name   = isset($params[0]) ? $params[0] : false;
        $value  = isset($params[1]) ? $params[1] : false;
        $params = isset($params[2]) ? $params[2] : false;
        
        if (isset(self::$codes[$element]) && is_callable(self::$codes[$element])) {

            return call_user_func(self::$codes[$element], $name, $value, $params);

        } else {
            
            throw new Exception('Unknown ExtForm element: ' . $element);

        }
    }

}
