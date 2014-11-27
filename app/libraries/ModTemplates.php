<?php
/**
 * Created by PhpStorm.
 * User: Alexander
 * Date: 30.09.2014
 * Time: 15:56
 */

class ModTemplates {

    private static $templates;

    private function __construct() {
        self::$templates = array();
    }

    public static function addDir($mod, $dir = '') {
        $full_dir = app_path('modules/' . $mod . '/views/' . $dir);
        if (file_exists($full_dir) && is_dir($full_dir)) {
            $files = glob($full_dir . '/*.blade.php');
            if (count($files)) {
                if (!isset(self::$templates[$mod]))
                    self::$templates[$mod] = array();
                foreach ($files as $file) {
                    $file = basename($file);
                    $file = str_replace('.blade.php', '', $file);
                    self::$templates[$mod][] = $dir . ($dir ? '/' : '') . $file;
                }
            }
        }
    }

    public static function addTplDir($dir = '') {
        $mod = 'layout';
        $full_dir = Helper::inclayout($dir);
        #Helper::dd($full_dir);
        if (file_exists($full_dir) && is_dir($full_dir)) {
            $files = glob($full_dir . '/*.blade.php');
            if (count($files)) {
                if (!isset(self::$templates[$mod]))
                    self::$templates[$mod] = array();
                foreach ($files as $file) {
                    $file = basename($file);
                    $file = str_replace('.blade.php', '', $file);
                    self::$templates[$mod][] = $dir . ($dir ? '/' : '') . $file;
                }
            }
        }
    }

    public static function addFiles($mod, $file = '') {
        if (!isset(self::$templates[$mod]))
            self::$templates[$mod] = array();

        if (is_string($file))
            self::$templates[$mod][] = $file;
        elseif (is_array($file))
            foreach ($file as $f)
                self::$templates[$mod][] = $f;
    }

    public static function get($mod = false) {
        if ($mod && isset(self::$templates[$mod])) {
            array_unique(self::$templates[$mod]);
            return self::$templates[$mod];
        } else {
            if (isset(self::$templates) && is_array(self::$templates) && count(self::$templates))
                foreach (self::$templates as $module => $files)
                    self::$templates[$module] = array_unique(self::$templates[$module]);
            return (array)self::$templates;
        }
    }

    private static function add_any_dir($full_dir) {

    }

}