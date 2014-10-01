<?php

class RemindersController extends BaseController {

	public function index(){
		return View::make('password.remind');
	}

	public function store(){

        $json_request = array('status'=>FALSE,'responseText'=>'','responseErrorText'=>'');
        if(Request::ajax()):
            $validator = Validator::make(Input::all(),array('email'=>'required|email'));
            if($validator->passes()):
                $remind_response = Password::remind(Input::only('email'),function($message, $user){
                    $message->from(Config::get('mail.from.address'),Config::get('mail.from.name'));
                    $message->subject('ТехВуз.рф - сброс пароля');
                });
                switch ($remind_response):
                    case Password::REMINDER_SENT:
                        $json_request['responseText'] = Lang::get('interface.PASSWORD_RESTORE.success');
                        $json_request['status'] = TRUE;
                        break;
                    case Password::INVALID_USER:
                        $json_request['responseText'] = Lang::get('interface.PASSWORD_RESTORE.fail');
                        break;
                    default:
                        $json_request['responseText'] = Lang::get('interface.PASSWORD_RESTORE.fail_send');
                endswitch;
            else:
                $json_request['responseText'] = 'Неверно заполнены поля';
                $json_request['responseErrorText'] = $validator->messages()->all();
            endif;
        else:
            return App::abort(404);
        endif;
        return Response::json($json_request,200);
	}

	public function show($token = null){

        if (is_null($token)):
            App::abort(404);
        endif;
        if ($email = DB::table(Config::get('auth.reminder.table'))->where('token',$token)->pluck('email')):
            $page_data = array();
            return View::make(Helper::layout('remind-password'),array('page'=>$page_data,'token'=>$token,'email'=>$email));
        else:
            App::abort(404);
        endif;

	}

	public function update(){

        $json_request = array('status'=>FALSE,'responseText'=>'','responseErrorText'=>'','redirect'=>FALSE);
        if(Request::ajax()):
            $rules = array('token'=>'required','email'=>'required|email','password'=>'required','password_confirmation'=>'required');
            $validator = Validator::make(Input::all(),$rules);
            if($validator->passes()):
                $response = Password::reset(Input::only('email','password','password_confirmation','token'), function($user, $password){
                    $user->password = Hash::make($password);
                    $user->save();
                    Config::set('temp.account_password', $password);
                    Mail::send('emails.auth.restore-password',array('account'=>$user),function($message){
                        $message->from(Config::get('mail.from.address'),Config::get('mail.from.name'));
                        $message->to(Input::get('email'))->subject('ТехВуз.рф - новый пароль');
                    });
                });
                switch ($response):
                    case Password::INVALID_PASSWORD:
                    case Password::INVALID_TOKEN:
                        $json_request['responseText'] = Lang::get('interface.PASSWORD_RESET.fail_token');
                        break;
                    case Password::INVALID_USER:
                        $json_request['responseText'] = Lang::get('interface.PASSWORD_RESET.fail');
                        break;
                    case Password::PASSWORD_RESET:
                        $json_request['responseText'] = Lang::get('interface.PASSWORD_RESET.success');
                        $json_request['status'] = TRUE;
                        break;
                endswitch;
            else:
                $json_request['responseText'] = 'Неверно заполнены поля';
                $json_request['responseErrorText'] = $validator->messages()->all();
            endif;
        else:
            return App::abort(404);
        endif;
        return Response::json($json_request,200);

		$credentials = Input::only(
			'email', 'password', 'password_confirmation', 'token'
		);
        print_r($credentials);
        exit;
		$response = Password::reset($credentials, function($user, $password){
			$user->password = Hash::make($password);
			$user->save();
		});

		switch ($response){
			case Password::INVALID_PASSWORD:
			case Password::INVALID_TOKEN:
			case Password::INVALID_USER:
				return Redirect::back()->with('error', Lang::get($response));

			case Password::PASSWORD_RESET:
				return Redirect::to('/');
		}
	}

}
