<?php

/*
* Инструкция
* 	см. https://gist.github.com/oodavid/1809044
*	sudo chown -R имя владельца:группа владельца /ПУТЬ К ПРОЕКТУ
*	sudo chmod -R 0744 /ПУТЬ К ПРОЕКТУ
*	Вернуть требуемые права на папки, к примеру downloads 
*	Проверить работу от имени владельца, к примеру www-data
*	sudo user=www-data git pull
*
*/

class GitHub {

    protected $test_mode;
    protected $post;

    protected $git_path;

    protected $remote;
    protected $branch;
    protected $repository_name;
    protected $repository_id;
    protected $user_group;
    protected $user_name;
    protected $access_mode;
    protected $project_path;
    protected $set_log;

    function __construct(){

    }

    public function init($params){

        $this->test_mode = isset($params['test_mode']) ? $params['test_mode'] : FALSE;
        $this->git_path = isset($params['git_path']) ? $params['git_path'] : '/usr/local/bin/git';

        $this->remote = isset($params['remote']) ? $params['remote'] : 'origin';
        $this->branch = isset($params['branch']) ? $params['branch'] : 'master';
        $this->user_group = isset($params['user_group']) ? $params['user_group'] : 'www-data';
        $this->user_name = isset($params['user_name']) ? $params['user_name'] : 'www-data';
        $this->access_mode = isset($params['access_mode']) ? $params['access_mode'] : '0774';
        $this->project_path = isset($params['project_path']) ? $params['project_path'] : '';
        $this->set_log = isset($params['set_log']) ? $params['set_log'] : TRUE;

        $this->post = isset($params['post_data']) ? $params['post_data'] : FALSE;
        $this->repository_name = isset($params['repository_name']) ? $params['repository_name'] : 'undefined';
        $this->repository_id = isset($params['repository_id']) ? $params['repository_id'] : -1;
    }

    public function config($item = NULL){

        $config = array(
            'test_mode' => $this->test_mode,
            'remote' => $this->remote,
            'branch' => $this->branch,
            'repository_name' => $this->repository_name,
            'repository_id' => $this->repository_id,
            'user_group' => $this->user_group,
            'user_name' => $this->user_name,
            'access_mode' => $this->access_mode,
            'project_path' => $this->project_path,
            'set_log' => $this->set_log
        );

        if(!is_null($item) && isset($config[$item])):
            return $config[$item];
        else:
            return $config;
        endif;
    }

    public function parseRepository($item){

        if($this->post):
            if($jsonPost = json_decode($this->post,TRUE)):
                if(isset($jsonPost['repository'][$item])):
                    return $jsonPost['repository'][$item];
                endif;
            endif;
        endif;
        return FALSE;
    }

    public function setAccessMode($path = NULL,$mode = NULL){

        if(is_null($path)):
            $path = $this->project_path;
        endif;
        if(is_null($mode)):
            $mode = $this->access_mode;
        endif;

        try {
            system("/bin/chown -R ".$this->user_name." ".base_path($path));
        } catch (Exception $e) {
            return $e->getMessage().'. Ошибка при смене владельца. Путь: '.base_path($path)."\n";
        }
        try {
            system("/bin/chgrp -R ".$this->user_group." ".base_path($path));
        } catch (Exception $e) {
            return $e->getMessage().'. Ошибка при смене группы владельца. Путь: '.base_path($path)."\n";
        }
        try {
            system("/bin/chmod -R ".$mode." ".base_path($path));
        } catch (Exception $e) {
            return $e->getMessage().'. Ошибка при смене прав доступа. Путь: '.base_path($path)."\n";
        }
    }

    public function testConnect($command = 'ssh -T git@github.com'){

        try {
            exec($command.' 2>&1',$result,$returnCode);
            echo "\nResult:\n"; print_r($result);
            echo "\nCode:\n"; print_r($returnCode);
        } catch (Exception $e) {

        }
    }

    public function execute($command = NULL){

        if(is_null($command)):
            return 'Отсутствует комманда GIT';
        endif;

        if(!$this->permission()):
            App::abort(403, 'В доступе отказано');
        endif;
        try {
            exec($this->git_path.$command.' 2>&1', $result, $returnCode);
        } catch (Exception $e) {
            return 'Невозможно вызвать комманду: '.$command;
        }
        if($this->set_log):
            echo "\nCommand:\n".$this->git_path.$command;
            echo "\nResult:\n"; print_r($result);
            echo "\nCode:\n"; print_r($returnCode);
        endif;
        return $returnCode;
    }

    public function pull(){

        if(!$this->permission()):
            App::abort(403, 'В доступе отказано');
        endif;

        try {
            exec($this->git_path.'git pull '.$this->remote.' '.$this->branch.' 2>&1',$result,$returnCode);
        } catch (Exception $e) {
            return 'Невозможно вызвать комманду git pull'. $this->remote.' '.$this->branch;
        }

        if($this->set_log):
            echo "\nCommand:\n".$this->git_path.'git pull '.$this->remote.' '.$this->branch;
            echo "\nResult:\n"; print_r($result);
            echo "\nCode:\n"; print_r($returnCode);
        endif;
        return $returnCode;
    }

    public function push(){

        if(!$this->permission()):
            App::abort(403, 'В доступе отказано');
        endif;

        try {
            exec($this->git_path.'git push '.$this->remote.' '.$this->branch.' 2>&1', $result, $returnCode);
        } catch (Exception $e) {
            return 'Невозможно вызвать комманду git push'. $this->remote.' '.$this->branch;
        }

        if($this->set_log):
            echo "\nCommand:\n".$this->git_path.'git push '.$this->remote.' '.$this->branch;
            echo "\nResult:\n"; print_r($result);
            echo "\nCode:\n"; print_r($returnCode);
        endif;
        return $returnCode;
    }

    private function permission(){

        if($this->test_mode):
            return TRUE;
        endif;

        if($this->post):
            if($jsonPost = json_decode($this->post,TRUE)):
                if(isset($jsonPost['repository']) && $jsonPost['repository']['name'] == $this->repository_name && $jsonPost['repository']['id'] == $this->repository_id):
                    return TRUE;
                endif;
            endif;
        endif;
        return FALSE;
    }
}