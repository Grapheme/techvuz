<?php

class BaseController extends Controller {

	var $breadcrumb = array();

	public function __construct(){

	}

	protected function setupLayout(){

		if(!is_null($this->layout)):
			$this->layout = View::make($this->layout);
		endif;
	}

	public static function moduleActionPermission($module_name,$module_action){

		if(Auth::check()):
			if(!Allow::action($module_name, $module_action)):
				return App::abort(403);
			endif;
		else:
			return App::abort(404);
		endif;
	}

	public static function stringTranslite($string){

		$rus = array("1","2","3","4","5","6","7","8","9","0","ё","й","ю","ь","ч","щ","ц","у","к","е","н","г","ш","з","х","ъ","ф","ы","в","а","п","р","о","л","д","ж","э","я","с","м","и","т","б","Ё","Й","Ю","Ч","Ь","Щ","Ц","У","К","Е","Н","Г","Ш","З","Х","Ъ","Ф","Ы","В","А","П","Р","О","Л","Д","Ж","Э","Я","С","М","И","Т","Б"," ");
		$eng = array("1","2","3","4","5","6","7","8","9","0","yo","iy","yu","","ch","sh","c","u","k","e","n","g","sh","z","h","","f","y","v","a","p","r","o","l","d","j","е","ya","s","m","i","t","b","Yo","Iy","Yu","CH","","SH","C","U","K","E","N","G","SH","Z","H","","F","Y","V","A","P","R","O","L","D","J","E","YA","S","M","I","T","B","-");
		$string = str_replace($rus,$eng,trim($string));
		if(!empty($string)):
			$string = preg_replace('/[^a-z0-9-]/','',strtolower($string));
//			$string = preg_replace('/[^a-z0-9-\.]/','',strtolower($string));
			$string = preg_replace('/[-]+/','-',$string);
			//$string = preg_replace('/[\.]+/','.',$string);
			return $string;
		else:
			return FALSE;
		endif;
	}
        
    public static function returnTpl($postfix = false) {
        #return static::__CLASS__;
        #return get_class(__CLASS__);
        #echo __DIR__;
        #return basename(__DIR__).".views.";   
        return static::$group.".views." . ($postfix ? $postfix."." : "");
    }

    public function dashboard() {

        $parts = array();
        $parts[] = 'templates';
        $parts[] = AuthAccount::getStartPage();
        $parts[] = 'dashboard';

        return View::make(implode('.', $parts));
    }

    public function templates($path = '', $post_path = '/views') {

        #Helper::dd($path . ' | ' . $post_path . ' | ' . "/*");

        $templates = array();
        $temp = glob($path.$post_path."/*");
        foreach ($temp as $t => $tmp) {
            if (is_dir($tmp))
                continue;
            $name = basename($tmp);
            $name = str_replace(".blade.php", "", $name);
            $templates[] = $name;
        }
        return $templates;
    }
}