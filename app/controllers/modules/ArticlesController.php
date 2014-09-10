<?php

class ArticlesController extends \BaseController {
	
	protected $articles;

	public function __construct(Article $articles){
		
		$this->articles = $articles;
		$this->beforeFilter('articles');
	}
	
	public function getIndex(){
		
		$articles = $this->articles->all();
		return View::make('modules.articles.index',compact('articles'));
	}

	public function getCreate(){

		$this->moduleActionPermission('articles','create');
		return View::make('modules.articles.create',array('templates'=>Template::all(),'languages'=>Language::retArray()));
	}

	public function postStore(){

		$this->moduleActionPermission('articles','create');
		$json_request = array('status'=>FALSE,'responseText'=>'','redirect'=>FALSE,'responseErrorText'=>'');
		if(Request::ajax()):
			if(Article::validate(Input::all())):
				self::savePageModel();
				$json_request['responseText'] = 'Статья создана';
				$json_request['redirect'] = slink::createAuthLink('articles');
				$json_request['status'] = TRUE;
			else:
				$json_request['responseText'] = 'Неверно заполнены поля';
				$json_request['responseErrorText'] = Article::$errors;
			endif;
		else:
			return App::abort(404);
		endif;
		return Response::json($json_request,200);
	}

	public function getEdit($id){
		
		$this->moduleActionPermission('articles','edit');
		$articles = $this->articles->find($id);
		if(is_null($articles)):
			return App::abort(404);
		endif;
		return View::make('modules.articles.edit',array('articles'=>$articles,'templates'=>Template::all(),'languages'=>Language::retArray()));
	}

	public function postUpdate($id){
		
		$this->moduleActionPermission('articles','edit');
		$json_request = array('status'=>FALSE,'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);
		if(Request::ajax()):
			if(Article::validate(Input::all())):
				$articles = $this->articles->find($id);
				self::savePageModel($articles);
				$json_request['responseText'] = 'Статья сохранена';
				$json_request['redirect'] = slink::createAuthLink('articles');
				$json_request['status'] = TRUE;
			else:
				$json_request['responseText'] = 'Неверно заполнены поля';
				$json_request['responseErrorText'] = Article::$errors;
			endif;
		else:
			return App::abort(404);
		endif;
		return Response::json($json_request,200);
		
		$article = Article::findOrFail($id);

		$validator = Validator::make($data = Input::all(), Article::$rules);

		if ($validator->fails())
		{
			return Redirect::back()->withErrors($validator)->withInput();
		}

		$article->update($data);

		return Redirect::route('articles.index');
	}

	public function deleteDestroy($id){
		
		$this->moduleActionPermission('articles','delete');
		$json_request = array('status'=>FALSE,'responseText'=>'');
		if(Request::ajax()):
			if($this->articles->find($id)->delete()):
				$json_request['responseText'] = 'Статья удалена';
				$json_request['status'] = TRUE;
			endif;
		else:
			return App::abort(404);
		endif;
		return Response::json($json_request,200);
	}
	
	private function savePageModel($article = NULL){
		
		if(is_null($article)):
			$article = $this->articles;
		endif;
		$article->title = trim(Input::get('title'));
		$article->sort = (int)Article::max('sort')+1;
		$article->preview = Input::get('preview');
		$article->content = Input::get('content');
		$article->publication = 1;
		if(Allow::enabled_module('languages')):
			$article->language = Input::get('language');
		else:
			$article->language = App::getLocale();
		endif;
		if(Allow::enabled_module('templates')):
			$article->template = Input::get('template');
		else:
			$article->template = 'articles';
		endif;
		if(Allow::enabled_module('seo')):
			if(is_null(Input::get('seo_url'))):
				$article->seo_url = '';
			elseif(Input::get('seo_url') == ''):
				$article->seo_url = $this->stringTranslite(Input::get('title'));
			else:
				$article->seo_url = $this->stringTranslite(Input::get('seo_url'));
			endif;
			if(Input::get('seo_title') == ''):
				$article->seo_title = $article->title;
			else:
				$article->seo_title = trim(Input::get('seo_title'));
			endif;
			$article->seo_description = Input::get('seo_description');
			$article->seo_keywords = Input::get('seo_keywords');
			$article->seo_h1 = Input::get('seo_h1');
		else:
			$article->seo_url = $this->stringTranslite(Input::get('title'));
			$article->seo_title = Input::get('title');
			$article->seo_description =$article->seo_keywords = $article->seo_h1 = '';
		endif;
		$article->save();
		$article->touch();
		return $article->id;
	}

}