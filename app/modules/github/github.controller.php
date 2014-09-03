<?php

class GithubController extends \BaseController {

    public static $name = 'github';
    public static $group = 'production';

    public function __construct(){

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group . "/" . self::$name,
            'tpl'  => static::returnTpl(),
            'gtpl' => static::returnTpl(),
        );
        View::share('module', $this->module);
    }

    public static function returnRoutes($prefix = null) {

        $class = __CLASS__;
        Route::any('git-deploy/{git_branch}',$class.'@gitDeployProject');
        Route::any('git-deploy/{git_branch}/{extends}',$class.'@gitDeployProject');
    }

    public static function returnExtFormElements() {

        return null;
    }

    public static function returnActions() {

        return null;
    }

    public static function returnInfo() {

        return null;
    }

    public function gitDeployProject($git_branch,$extends = null){

        $config = Config::get('github');
        $config['branch'] = $git_branch;
        $config['post_data'] = Input::get('payload');

        if ($config['active'] === FALSE):
            return App::abort(403,'Модуль отключен');
        endif;
        if($config['test_mode_key'] == $extends):
            $config['test_mode'] = TRUE;
        else:
            $config['test_mode'] = FALSE;
        endif;
        $github = new GitHub();
        $github->init($config);
        if($extends == 'test'):
            echo $github->testConnect('/usr/bin/ssh -T git@github.com');
        else:
            echo $github->execute('git reset --hard HEAD');
            echo "\n";
            echo $github->pull();
            echo "\n";
            echo $github->setAccessMode();
            echo "\n";
            foreach ($config['directories'] as $directory):
                 echo $github->setAccessMode($directory,'0777');
            endforeach;
        endif;
    }
}