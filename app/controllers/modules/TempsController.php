<?php

class TempsController extends BaseController {
	
	protected $template;
	
	public function __construct(Template $template){
		
		$this->template = $template;
		$this->beforeFilter('templates');
	}
	
	public function getIndex(){
		
		$templates = $this->template->all();
		return View::make('modules.templates.index',compact('templates'));
	}

	public function getCreate(){
		
		return View::make('modules.templates.create');
	}

	public function postStore(){
		
		$this->moduleActionPermission('templates','create');
		$json_request = array('status'=>FALSE,'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);
		if(Request::ajax()):
			$validator = Validator::make(Input::all(),Template::$rules);
			if($validator->passes()):
				if(!File::exists(app_path('views/templates/'.$this->stringTranslite(Input::get('name')).'.blade.php'))):
					self::savePageModel();
					$json_request['responseText'] = 'Шаблон зарегистрирован';
					$json_request['redirect'] = slink::createAuthLink('templates');
					$json_request['status'] = TRUE;
				else:
					$json_request['responseText'] = 'Шаблон: templates/'.$this->stringTranslite(Input::get('name')).'.blade.php уже существует';
				endif;
			else:
				$json_request['responseText'] = 'Неверно заполнены поля';
				$json_request['responseErrorText'] = $validator->messages()->all();
			endif;
		else:
			return App::abort(404);
		endif;
		return Response::json($json_request,200);
	}
	
	public function getEdit($id){
		
		$this->moduleActionPermission('templates','edit');
		$template = $this->template->find($id);
		if(is_null($template)):
			return App::abort(404);
		endif;
		if(File::exists(app_path('views/templates/'.$template->name.'.blade.php'))):
			$template->content = File::get(app_path('views/templates/'.$template->name.'.blade.php'));
		else:
			App::abort(404,'Отсутсвует шаблон: templates/'.$template->template.'.blade.php');
		endif;
		return View::make('modules.templates.edit',compact('template'));
	}

	public function postUpdate($id){
		
		$this->moduleActionPermission('templates','edit');
		$json_request = array('status'=>FALSE,'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);
		if(Request::ajax()):
			$validator = Validator::make(Input::all(),Template::$rules);
			if($validator->passes()):
				$template = $this->template->find($id);
				self::savePageModel($template);
				$json_request['responseText'] = 'Шаблон сохранен';
				$json_request['redirect'] = slink::createAuthLink('templates');
				$json_request['status'] = TRUE;
			else:
				$json_request['responseText'] = 'Неверно заполнены поля';
				$json_request['responseErrorText'] = $validator->messages()->all();
			endif;
		else:
			return App::abort(404);
		endif;
		return Response::json($json_request,200);
	}

	public function deleteDestroy($id){
		
		$this->moduleActionPermission('templates','delete');
		$json_request = array('status'=>FALSE,'responseText'=>'');
		if(Request::ajax()):
			$template = $this->template->find($id);
			if(!is_null($template) && $template->delete()):
				if(File::exists(app_path('views/templates/'.$template->name.'.blade.php'))):
					File::delete(app_path('views/templates/'.$template->name.'.blade.php'));
				endif;
				$json_request['responseText'] = 'Шаблон удален';
				$json_request['status'] = TRUE;
			endif;
		else:
			return App::abort(404);
		endif;
		return Response::json($json_request,200);
	}

	private function savePageModel($template = NULL){
		
		if(is_null($template)):
			$template = $this->template;
		endif;
		$template->name = $this->stringTranslite(Input::get('name'));
		$content = Input::get('content');
		if(empty($content)):
			$content = '@if(isset($content)){{ $content }}@endif';
		endif;
		$template->content = $content;
		if($template->static != 1):
			$template->static = 0;
		endif;
		$template->save();
		$template->touch();
		File::put(app_path('views/templates/'.$this->stringTranslite($template->name).'.blade.php'),$template->content);
		return $template->id;
	}

}
