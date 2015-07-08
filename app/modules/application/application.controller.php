<?php

class ApplicationController extends BaseController {

    public static $name = 'application';
    public static $group = 'application';

    /****************************************************************************/

    ## Routing rules of module
    public static function returnRoutes($prefix = null) {

        Route::group(array(), function() {

            Route::any('/ajax/send_form/quick_record', array('as' => 'app.form.quick_record', 'uses' => __CLASS__.'@quickRecord'));
            Route::any('/ajax/send_form/course_request', array('as' => 'app.form.course_request', 'uses' => __CLASS__.'@courseRequest'));

            #Route::any('/ajax/some-action', array('as' => 'ajax.some-action', 'uses' => __CLASS__.'@postSomeAction'));
        });
    }


    /****************************************************************************/


	public function __construct(){
        #
	}


    public function quickRecord() {

        if(!Request::ajax())
            App::abort(404);

        $json_request = ['status' => true, 'responseText' => ''];

        $data = Input::all();
        #Helper::tad($data);

        Mail::send('emails.quick_record', $data, function ($message) use ($data) {


            $message->from(Config::get('mail.from.address'), Config::get('mail.from.name'));
            $message->to(Config::get('mail.feedback.address'));
            $message->subject(Config::get('mail.forms.quick_record.subject'));

            $ccs = Config::get('mail.feedback.cc');
            if (isset($ccs) && is_array($ccs) && count($ccs))
                foreach ($ccs as $cc)
                    $message->cc($cc);

            /**
             * Прикрепляем файл
             */
            /*
            if (Input::hasFile('file') && ($file = Input::file('file')) !== NULL) {
                #Helper::dd($file->getPathname() . ' / ' . $file->getClientOriginalName() . ' / ' . $file->getClientMimeType());
                $message->attach($file->getPathname(), array('as' => $file->getClientOriginalName(), 'mime' => $file->getClientMimeType()));
            }
            #*/
        });

        #Helper::dd($result);
        return Response::json($json_request, 200);
    }


    public function courseRequest() {

        if(!Request::ajax())
            App::abort(404);

        $json_request = ['status' => true, 'responseText' => ''];

        $data = Input::all();

        Mail::send('emails.course_request', $data, function ($message) use ($data) {


            $message->from(Config::get('mail.from.address'), Config::get('mail.from.name'));
            $message->to(Config::get('mail.feedback.address'));
            $message->subject(Config::get('mail.forms.course_request.subject'));

            $ccs = Config::get('mail.feedback.cc');
            if (isset($ccs) && is_array($ccs) && count($ccs))
                foreach ($ccs as $cc)
                    $message->cc($cc);

            /**
             * Прикрепляем файл
             */
            /*
            if (Input::hasFile('file') && ($file = Input::file('file')) !== NULL) {
                #Helper::dd($file->getPathname() . ' / ' . $file->getClientOriginalName() . ' / ' . $file->getClientMimeType());
                $message->attach($file->getPathname(), array('as' => $file->getClientOriginalName(), 'mime' => $file->getClientMimeType()));
            }
            #*/
        });

        #Helper::dd($result);
        return Response::json($json_request, 200);
    }

}