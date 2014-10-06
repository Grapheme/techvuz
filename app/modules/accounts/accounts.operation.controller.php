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
            });
            Route::group(array('before' => 'auth.status', 'prefix' => 'organization'), function() use ($class) {
                Route::get('registration/listener', array('as' => 'signup-listener', 'uses' => $class . '@signupListener'));

                Route::get('profile', array('as' => 'company-profile', 'uses' => $class . '@CompanyProfile'));
                Route::get('listeners/profile/{listener_id}', array('as' => 'company-listener-profile', 'uses' => $class . '@CompanyListenerProfile'));

                Route::get('orders', array('as' => 'company-orders', 'uses' => $class . '@CompanyOrdersList'));
                Route::get('listeners', array('as' => 'company-listeners', 'uses' => $class . '@CompanyListenersList'));
                Route::get('study', array('as' => 'company-study', 'uses' => $class . '@CompanyStudyProgressList'));
                Route::get('notifications', array('as' => 'company-notifications', 'uses' => $class . '@CompanyNotificationsList'));
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

    /****************************************************************************/
    /********************************* COMPANY **********************************/
    /****************************************************************************/

    public function CompanyProfile(){

        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_PROFILE.title'),
            'page_description'=> Lang::get('seo.COMPANY_PROFILE.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_PROFILE.keywords'),
        );
        return View::make(Helper::acclayout('profile'),$page_data);
    }

    public function CompanyListenerProfile($listener_id){

        Helper::dd($listener_id);
        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_PROFILE.title'),
            'page_description'=> Lang::get('seo.COMPANY_PROFILE.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_PROFILE.keywords'),
        );
        return View::make(Helper::acclayout('profile'),$page_data);
    }

    public function CompanyOrdersList(){

        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_ORDERS_LIST.title'),
            'page_description'=> Lang::get('seo.COMPANY_ORDERS_LIST.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_ORDERS_LIST.keywords'),
        );
        return View::make(Helper::acclayout('orders-lists'),$page_data);
    }

    public function CompanyListenersList(){

        $page_data = array(
            'page_title'=> Lang::get('seo.COMPANY_LISTENERS_LIST.title'),
            'page_description'=> Lang::get('seo.COMPANY_LISTENERS_LIST.description'),
            'page_keywords'=> Lang::get('seo.COMPANY_LISTENERS_LIST.keywords'),
        );
        return View::make(Helper::acclayout('listeners-lists'),$page_data);
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
       return Redirect::to(AuthAccount::getGroupStartUrl())->with('message',Lang::get('interface.REPEATED_SENDING_LETTER.success'));
    }

    public function signupListener(){

        $page_data = array(
            'page_title'=> Lang::get('seo.REGISTER_LISTENER.title'),
            'page_description'=> Lang::get('seo.REGISTER_LISTENER.description'),
            'page_keywords'=> Lang::get('seo.REGISTER_LISTENER.keywords'),
        );
        return View::make(Helper::acclayout('listener-registration'),$page_data);
    }
    /**************************************************************************/

}