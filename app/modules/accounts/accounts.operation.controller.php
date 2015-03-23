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
            Route::group(array('before' => 'auth.status', 'prefix' => Auth::user()->group()->pluck('dashboard')), function() use ($class) {
                Route::get('repeated-send-mail/activation', array('as'=>'activation-repeated-sending-letter', 'uses' => $class.'@ActivationRepeatedSendingLetter'));
                Route::any('settings/update/{setting_slug}/{value}', array('as'=>'setting-update', 'uses' => $class.'@saveUserSetting'));
            });
            Event::listen('listener.start.course.study', function ($data) {
                OrderListeners::where('id',$data['listener_course_id'])
                    ->where('access_status',1)
                    ->where('start_status',0)
                    ->where('user_id',Auth::user()->id)
                    ->update(array('start_status'=>1,'start_date'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')));
            });
            Event::listen('listener.over.course.study', function ($data) {
                OrderListeners::where('id',$data['listener_course_id'])
                    ->where('access_status',1)
                    ->where('over_status',0)
                    ->where('user_id',Auth::user()->id)
                    ->update(array('start_status'=>1,'over_status'=>1,'over_date'=>date('Y-m-d H:i:s'),'updated_at'=>date('Y-m-d H:i:s')));
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
    public function createSiteMap(){

        $sitemap = App::make("sitemap");
        if($pages = Page::where('publication',1)->where('version_of',null)->select(array('name','slug','updated_at'))->get()):
            foreach($pages as $page):
                $sitemap->add(URL::route('page', $page->slug),$page->updated_at->toW3cString(),'1.0','monthly');
                if($page->slug == 'catalog'):
                    foreach(Courses::where('active',true)->with('seo')->get() as $course):
                        $sitemap->add(URL::route('course-page',$course->seo->url),$course->seo->updated_at->toW3cString(),'0.8','weekly');
                    endforeach;
                endif;
            endforeach;
            $sitemap->add(URL::route('page','news'),Carbon\Carbon::now()->toW3cString(),'1.0','weekly');
            $sitemap->add(URL::route('page','reviews'),Carbon\Carbon::now()->toW3cString(),'1.0','weekly');
        endif;
        $sitemap->store('xml','sitemap');
        return $sitemap->render('xml');
    }
}