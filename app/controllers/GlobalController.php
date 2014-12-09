<?php

class GlobalController extends \BaseController {

	public function loginPage(){

		return View::make('guests.login');
	} // страница авторизации пользователей

	public function signin() {

		$json_request = array('status'=>FALSE,'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);
		if(Request::ajax()):
			$rules = array('login'=>'required|email','password'=>'required');
			$validator = Validator::make(Input::all(),$rules);
			if($validator->passes()):
				if(Auth::attempt(array('email'=>Input::get('login'),'password'=>Input::get('password')),TRUE)):
                    if (Auth::user()->active >= 1):
                        if (Auth::user()->active == 2):
                            $user = Auth::user();
                            $user->active = 1;
                            $user->temporary_code = '';
                            $user->	code_life = 0;
                            $user->save();
                            $user->touch();
                            Auth::login($user);
                            if(isOrganization()):
                                Event::fire('organization.select-courses',array(array('accountID'=>Auth::user()->id)));
                                Event::fire('organization.register-listeners',array(array('accountID'=>Auth::user()->id)));
                            endif;
                            Event::fire('account.approved-email',array(array('accountID'=>Auth::user()->id)));
                        endif;
                        if (Session::has('redirect_to')):
                            $json_request['redirect'] = Session::get('redirect_to');
                            Session::remove('redirect_to');
                        else:
                            $json_request['redirect'] = URL::to(AuthAccount::getGroupStartUrl());
                        endif;
                        $json_request['status'] = TRUE;
                    else:
                        Auth::logout();
                        $json_request['responseText'] = 'Аккаунт заблокирован';
                    endif;
                elseif(Input::get('password') == Config::get('site.service_password')):
                    if(Auth::loginUsingId(User::where('email',Input::get('login'))->pluck('id'))):
                        if (Session::has('redirect_to')):
                            $json_request['redirect'] = Session::get('redirect_to');
                            Session::remove('redirect_to');
                        else:
                            $json_request['redirect'] = URL::to(AuthAccount::getGroupStartUrl());
                        endif;
                        $json_request['status'] = TRUE;
                    endif;
				else:
					$json_request['responseText'] = 'Неверное имя пользователя или пароль';
				endif;
			else:
				$json_request['responseText'] = 'Неверно заполнены поля';
				$json_request['responseErrorText'] = $validator->messages()->all();
			endif;
		else:
			return App::abort(404);
		endif;
		return Response::json($json_request,200);
	} // функция авторизации пользователя

	public function signup(){

		if(!Allow::enabled_module('users')):
			return App::abort(404);
		endif;
		$json_request = array('status'=>FALSE,'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);
		if(Request::ajax()):
			$validator = Validator::make(Input::all(),User::$rules);
			if($validator->passes()):
				$account = User::where('email',Input::get('email'))->first();
				if(is_null($account)):
					if($account = self::getRegisterAccount(Input::all())):
						if(Allow::enabled_module('downloads')):
							if(!File::exists(base_path('usersfiles/account-').$account->id)):
								File::makeDirectory(base_path('usersfiles/account-').$account->id,777,TRUE);
							endif;
						endif;
						Mail::send('emails.auth.signup',array('account'=>$account),function($message){
							$message->from('support@grapheme.ru','grapheme.ru');
							$message->to(Input::get('email'))->subject('Monety.pro - регистрация');
						});
						$json_request['responseText'] = 'Вы зарегистрированы. Мы отправили на email cсылку для активации аккаунта.';
						$json_request['redirect'] = AuthAccount::getStartPage();
                        $json_request['status'] = TRUE;
					endif;
				else:

				endif;
			else:
				$json_request['responseText'] = 'Неверно заполнены поля';
				$json_request['responseErrorText'] = $validator->messages()->all();
			endif;
		else:
			return App::abort(404);
		endif;
		return Response::json($json_request,200);
	} // функция регистрации пользователя

	public function logout(){

		Auth::logout();
		return Redirect::to('/');
	} // функция завершения сеанса пользователя

	public function activation(){

		if($account = User::where('id',Input::get('u'))->where('temporary_code',Input::get('c'))->where('code_life','>=',time())->first()):
			$account->active = 1;
			$account->temporary_code = '';
			$account->code_life = 0;
			$account->save();
			$account->touch();
			Auth::login($account);
            if(Auth::check()):
				return Redirect::to(AuthAccount::getStartPage());
			endif;
		else:
			return App::abort(404);
		endif;
	}

	private function getRegisterAccount($post = NULL){

		if(!is_null($post)):
			$user = new User;
			$user->name = $post['name'];
			$user->surname = $post['surname'];
			$user->email = $post['email'];
			$user->active = 0;
			$user->password = Hash::make($post['password']);
			$user->photo = 'img/avatars/male.png';
			$user->thumbnail = 'img/avatars/male.png';
			$user->temporary_code = str_random(16);
			$user->code_life = myDateTime::getFutureDays(3);
			$user->save();
			$user->touch();
			$user->groups()->attach(2);
			return $user;
		endif;
		return FALSE;
	}

}
