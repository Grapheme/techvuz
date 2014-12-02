<?php

class AccountsOperationController extends BaseController {

    public static $name = 'operation';
    public static $group = 'accounts';
    public static $entity = 'operation';
    public static $entity_name = 'Действия над пользователеми';

    /****************************************************************************/

    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        if (Auth::check()):
            Route::group(array('before' => 'auth.status', 'prefix' => Auth::user()->group()->pluck('name')), function() use ($class) {
                Route::get('repeated-send-mail/activation', array('as'=>'activation-repeated-sending-letter', 'uses' => $class.'@ActivationRepeatedSendingLetter'));
                Route::any('settings/update/{setting_slug}/{value}', array('as'=>'setting-update', 'uses' => $class.'@saveUserSetting'));
            });
        endif;
    }

    public static function returnShortCodes() {
        return NULL;
    }

    public static function returnActions() {
        return NULL;
    }

    public static function returnInfo() {
        return NULL;
    }

    public static function returnMenu() {
        return NULL;
    }

    /****************************************************************************/

    public function __construct(){

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group,
            'tpl' => static::returnTpl(),
            'gtpl' => static::returnTpl(),
            'class' => __CLASS__,

            'entity' => self::$entity,
            'entity_name' => self::$entity_name,
        );
        View::share('module', $this->module);
    }

    public function ActivationRepeatedSendingLetter(){

        $user = Auth::user();
        $user->temporary_code = Str::random(24);
        $user->update();
        $user->touch();
        Mail::send('emails.repeated_sending.activation',array('account'=>$user),function($message) use ($user){
            $message->from(Config::get('mail.from.address'),Config::get('mail.from.name'));
            $message->to($user->email)->subject('ТехВуз.рф - Активация аккаунта');
        });

        return Redirect::back()->with('message.text',Lang::get('interface.REPEATED_SENDING_LETTER.success'))->with('message.status','activation');
    }

    public function saveUserSetting($setting_slug,$value,$changeDate = TRUE){

        if ($setting = User_settings::where('user_id',Auth::user()->id)->where('slug',$setting_slug)->first()):
            $setting->value = $value;
            $setting->save();
            if ($changeDate):
                $setting->touch();
            endif;
        else:
            User_settings::create(array('user_id'=>Auth::user()->id,'slug'=>$setting_slug,'value'=>$value));
        endif;
    }
    /**************************************************************************/

}