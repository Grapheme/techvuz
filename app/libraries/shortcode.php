<?php

class shortcode {

    private static $codes = array();
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

    ## Add shortcode to base
    public static function add($name = '', $closure) {
        $name = trim($name);
        #var_dump($name);
        if (!$name || !is_callable($closure)) {
            throw new Exception('Bad shortcode closure');
            return false;
        }
        self::$codes[$name] = $closure;
    }

    ## Apply shortcode: shortcode::run(<name>, $params);
    public static function run($name = '', $params = '') {
        if (isset(self::$codes[$name]) && is_callable(self::$codes[$name])) {
            #self::$codes[$name]($params);
            #echo "RUN shortcode [{$name}] with params: " . print_r($params, 1); die;
            return call_user_func(self::$codes[$name], $params);
        }

    }

    ## Catch ALL shortcodes: shortcode::<name>($params);
    public static function __callStatic($name, array $params) {
        $params = $params[0];
        #print_r($params); #die;
        #echo 'Вы хотели вызвать '.__CLASS__.'::'.$name.', но его не существует, и сейчас выполняется '.__METHOD__.'()';
        
        #print_r(self::$codes);
        #echo $name . "<br/>\n";
        #var_dump(self::$codes[$name]);
        #die;
        
        if (isset(self::$codes[$name]) && is_callable(self::$codes[$name])) {

            #return self::$codes[$name]($params);
            return call_user_func(self::$codes[$name], $params);

        } else {
            $return = array();
            if (is_array($params))
                foreach($params as $key=>$val)
                    $return[] = "$key=$val";
            $return = implode(" ", $return);
            if ($return != '')
                $return = ' ' . $return;

            return "[" . $name . $return . "]";
        }
    }

}
