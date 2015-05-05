<?php

class AccountsDocumentsController extends BaseController {

    public static $name = 'documents';
    public static $group = 'accounts';
    public static $entity = 'documents';
    public static $entity_name = 'Документы';

    protected $document_stream = FALSE;
    /****************************************************************************/

    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        if (isOrganization()):
            Route::group(array('before' => 'auth.status', 'prefix' => 'company'), function() use ($class) {
                Route::get('order/{order_id}/contract/{format}', array('as' => 'organization-order-contract', 'uses' => $class . '@organizationOrderContract'));
                Route::get('order/{order_id}/invoice/{format}', array('as' => 'organization-order-invoice', 'uses' => $class . '@organizationOrderInvoice'));
                Route::get('order/{order_id}/act/{format}', array('as' => 'organization-order-act', 'uses' => $class . '@organizationOrderAct'));
                Route::get('order/{order_id}/course/{course_id}/listener/{listener_id}/certificate/{format}', array('as' => 'organization-order-certificate', 'uses' => $class . '@organizationOrderCertificate'));
            });
        endif;
        if (isCompanyListener()):
            Route::group(array('before' => 'auth.status', 'prefix' => 'listener'), function() use ($class) {
                Route::get('order/{order_id}/{order_listener_id}/result-certification/{format}', array('as' => 'listener-order-result-certification', 'uses' => $class . '@listener_OrderResultCertification'));
            });
        endif;
        if (isIndividual()):
            Route::group(array('before' => 'auth.status', 'prefix' => 'individual'), function() use ($class) {
                Route::get('order/{order_id}/contract/{format}', array('as' => 'individual-listener-order-contract', 'uses' => $class . '@individual_listenerOrderContract'));
                Route::get('order/{order_id}/invoice/{format}', array('as' => 'individual-listener-order-invoice', 'uses' => $class . '@individual_listenerOrderInvoice'));
                Route::get('order/{order_id}/act/{format}', array('as' => 'individual-listener-order-act', 'uses' => $class . '@individual_listenerOrderAct'));
                Route::get('order/{order_id}/course/{course_id}/listener/{listener_id}/certificate/{format}', array('as' => 'individual-listener-order-certificate', 'uses' => $class . '@individual_listenerOrderCertificate'));
                Route::get('order/{order_id}/{order_listener_id}/result-certification/{format}', array('as' => 'individual-order-result-certification', 'uses' => $class . '@individual_OrderResultCertification'));
            });
        endif;
        if (isModerator()):
            Route::group(array('before' => 'auth', 'prefix' => 'moderator'), function() use ($class) {
                Route::get('order/{order_id}/contract/{format}', array('as' => 'moderator-order-contract', 'uses' => $class . '@moderatorOrderContract'));
                Route::get('order/{order_id}/invoice/{format}', array('as' => 'moderator-order-invoice', 'uses' => $class . '@moderatorOrderInvoice'));
                Route::get('order/{order_id}/act/{format}', array('as' => 'moderator-order-act', 'uses' => $class . '@moderatorOrderAct'));

                Route::get('order/{order_id}/course/{course_id}/listener/{listener_id}/certificate/{format}', array('as' => 'moderator-order-certificate', 'uses' => $class . '@moderatorOrderCertificate'));

                Route::get('order/{order_id}/request/{format}', array('as' => 'moderator-order-request', 'uses' => $class . '@moderatorOrderRequest'));
                Route::get('order/{order_id}/enrollment/{format}', array('as' => 'moderator-order-enrollment', 'uses' => $class . '@moderatorOrderEnrollment'));
                Route::get('order/{order_id}/completion/{format}', array('as' => 'moderator-order-completion', 'uses' => $class . '@moderatorOrderСompletion'));
                Route::get('order/{order_id}/class-schedule/{format}', array('as' => 'moderator-order-class-schedule', 'uses' => $class . '@moderatorOrderClassSchedule'));
                Route::get('order/{order_id}/statements/{format}', array('as' => 'moderator-order-statements', 'uses' => $class . '@moderatorOrderStatements'));
                Route::get('order/{order_id}/explanations/{format}', array('as' => 'moderator-order-explanations', 'uses' => $class . '@moderatorOrderExplanations'));
                Route::get('order/{order_id}/browsing-history/{format}', array('as' => 'moderator-order-browsing-history', 'uses' => $class . '@moderatorOrderBrowsingHistory'));
                Route::get('order/{order_id}/result-certification/{format}', array('as' => 'moderator-order-result-certification', 'uses' => $class . '@moderatorOrderResultCertification'));
                Route::get('order/{order_id}/attestation-sheet/{format}', array('as' => 'moderator-order-attestation-sheet', 'uses' => $class . '@moderatorOrderAttestationSheet'));
                Route::get('order/{order_id}/journal-issuance/{format}', array('as' => 'moderator-order-journal-issuance', 'uses' => $class . '@moderatorOrdersJournalIssuance'));
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

    public function setDocumentStream($stream = FALSE){

        $this->document_stream = $stream;
        return $this;
    }

    /****************************************************************************/
    public function organizationOrderContract($order_id,$format){

        if (!User_organization::where('id',Auth::user()->id)->pluck('moderator_approve')):
            return Redirect::route('organization-orders');
        endif;
        if (!$order = Orders::where('id',$order_id)->where('user_id',Auth::user()->id)->where('completed',1)->where('archived',0)->with('contract')->first()):
            return Redirect::route('organization-orders');
        endif;
        $document = Dictionary::valueBySlugs('order-documents','order-documents-contract');
        $document_app1 = Dictionary::valueBySlugs('order-documents','order-documents-contract-listeners');
        if ($order->contract->exists && File::exists(public_path($order->contract->path))):
            $headers = returnDownloadHeaders($order->contract);
            return Response::download(public_path($order->contract->path),'contract-№'.getOrderNumber($order).'.'.$order->contract->mime2,$headers);
        elseif($document->exists && !empty($document->fields)):
            $fields = modifyKeys($document->fields,'key');
            $fields_app1 = modifyKeys($document_app1->fields,'key');
            Config::set('show-document.order_id', $order_id);
            switch($format):
                case 'html':
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        return View::make('templates/assets/documents',$page_data);
                    endif;
                    $document_content_app1 = isset($fields_app1['content']) ? $fields_app1['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content_app1)):
                        #return View::make('templates/assets/contract-app1',$page_data);
                    endif;
                    break;
                case 'pdf' :
                    $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
                    $mpdf->SetDisplayMode('fullpage');
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        $page_data['page_title'] = '';
                        $mpdf->WriteHTML(View::make('templates/assets/documents', $page_data)->render(), 2);
                    endif;
                    $document_content_app1 = isset($fields_app1['content']) ? $fields_app1['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content_app1)):
                        $page_data['page_title'] = '';
                        $mpdf->AddPage('L');
                        $mpdf->WriteHTML(View::make('templates/assets/contract-app1', $page_data)->render(), 2);
                    endif;
                    return $mpdf->Output('contract-№'.getOrderNumber($order).'.pdf', 'D');
                case 'word':
                    return Redirect::back();
                    break;
            endswitch;
        endif;
        return Redirect::route('organization-orders');
    }

    public function organizationOrderInvoice($order_id,$format){

        $template = 'templates/assets/invoice';
        return $this->organizationShowDocument($order_id,$format,'invoice',$template);
    }

    public function organizationOrderAct($order_id,$format){

        $template = 'templates/assets/act';
        return $this->moderatorShowDocument($order_id, $format, 'act',$template);
    }

    public function organizationOrderCertificate($order_id,$course_id,$listener_id,$format){

        $account = User_organization::where('id',Auth::user()->id)->first();
        if (!$account->moderator_approve):
            return Redirect::route('organization-orders');
        endif;
        if (!$OrderListener = OrderListeners::where('id',$course_id)->where('order_id',$order_id)->where('user_id',$listener_id)->where('over_status',1)->first()):
            return Redirect::route('organization-orders');
        endif;
        if (!$order = Orders::where('id',$order_id)->where('user_id',Auth::user()->id)->where('completed',1)->where('archived',0)->first()):
            return Redirect::route('organization-orders');
        endif;
        if (!$course = OrderListeners::where('id',$course_id)->first()->course):
            return Redirect::route('organization-orders');
        endif;
        if (!$listener = User_listener::where('id',$listener_id)->where('organization_id',Auth::user()->id)->first()):
            return Redirect::route('organization-orders');
        endif;
        $template = 'templates/assets/certificates/certificate-';
        $slug = DicVal::where('id',$course->certificate)->pluck('slug');
        if (!empty($slug)):
            $words = explode('-',$slug);
            if (is_array($words) && isset($words[3])):
                $template .= $words[3];
            else:
                App::abort(404);
            endif;
        endif;
        if($document = DicVal::where('id',$course->certificate)->first()->allfields):
            $fields = modifyKeys($document,'key');
            $document_content = isset($fields['content']) ? $fields['content']->value : '';
            if (!empty($document_content)):
                Config::set('show-document.order_id', $order_id);
                if($page_data = self::parseOrderHTMLDocument($document_content)):
                    $page_data['FIO_listener'] = $listener->fio;
                    $page_data['FIO_listener_dat'] = $listener->fio_dat;
                    $page_data['NomerUdostovereniya'] = str_pad($OrderListener->certificate_number,4,'0',STR_PAD_LEFT);
                    $page_data['Nazvanie_kursa'] = $course->title;
                    $page_data['KolichestvoChasovObucheniyaPoKursu'] = $course->hours.' '.Lang::choice('час|часов|часов',$course->hours);
                    switch($format):
                        case 'html':
                            return View::make($template,$page_data);
                            break;
                        case 'pdf' :
                            $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
                            $mpdf->SetDisplayMode('fullpage');
                            $mpdf->AddPage('L');
                            $page_data['page_title'] = '';
                            $stylesheet = file_get_contents(Config::get('site.theme_path').'/css/documents.css');
                            $mpdf->WriteHTML($stylesheet,1);
                            $mpdf->WriteHTML(View::make($template, $page_data)->render(), 2);
                            return $mpdf->Output('certificate-№'.$page_data['NomerUdostovereniya'].'.pdf', 'D');
                        case 'word':
                            return Redirect::back();
                            break;
                    endswitch;
                endif;
            endif;
        endif;
        App::abort(404);
    }

    private function organizationShowDocument($order_id,$format,$document_type,$template){

        if (!User_organization::where('id',Auth::user()->id)->pluck('moderator_approve')):
            return Redirect::route('organization-orders');
        endif;
        if (!$order = Orders::where('id',$order_id)->where('user_id',Auth::user()->id)->where('completed',1)->where('archived',0)->first()):
            return Redirect::route('organization-orders');
        endif;
        if (!$order->close_status && $document_type == 'act'):
            return Redirect::route('organization-orders');
        endif;
        $document = Dictionary::valueBySlugs('order-documents','order-documents-'.$document_type);
        if ($order->$document_type->exists && File::exists(public_path($order->$document_type->path))):
            $headers = returnDownloadHeaders($order->contract);
            return Response::download(public_path($order->$document_type->path),$document_type.'-№'.getOrderNumber($order).'.'.$order->$document_type->mime2,$headers);
        elseif($document->exists && !empty($document->fields)):
            $fields = modifyKeys($document->fields,'key');
            Config::set('show-document.order_id', $order_id);
            switch($format):
                case 'html':
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        return View::make($template,$page_data);
                    endif;
                    break;
                case 'pdf' :
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        $page_data['page_title'] = '';
                        $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
                        $mpdf->SetDisplayMode('fullpage');
                        $mpdf->WriteHTML(View::make($template, $page_data)->render(), 2);
                        return $mpdf->Output($document_type.'-№'.getOrderNumber($order).'.pdf', 'D');
                    endif;
                    break;
                case 'word':
                    return Redirect::back();
                    break;
            endswitch;
        endif;
        return Redirect::route('organization-orders');
    }
    /****************************************************************************/
    public function listener_OrderResultCertification($order_id,$order_listener_id,$format){

        if (!$orderListener = OrderListeners::where('id',$order_listener_id)->where('order_id',$order_id)->where('user_id',Auth::user()->id)->with('course','final_test')->get()):
            return Redirect::back();
        endif;
        $account = User_organization::where('id',Auth::user()->id)->first();
        $account_type = 4;
        $template = 'templates.assets.result-certification';
        $document = Dictionary::valueBySlugs('order-documents','order-documents-result-certification');
        if($document->exists && !empty($document->fields)):
            $fields = modifyKeys($document->fields,'key');
            Config::set('show-document.order_id', $order_id);
            switch($format):
                case 'html':
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        return View::make($template,$page_data);
                    endif;
                    break;
                case 'pdf' :
                    $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
                    $mpdf->SetDisplayMode('fullpage');
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        $page_data['page_title'] = '';
                        foreach($orderListener as $index => $listener):
                            $listeners[$index]['FIO_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['fio'] : $listener['user_individual']['fio'];
                            $listeners[$index]['Phone_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['phone'] : $listener['user_individual']['phone'];
                            $listeners[$index]['Email_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['email'] : $listener['user_individual']['email'];
                            $listeners[$index]['Address_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['postaddress'] : $listener['user_individual']['postaddress'];
                            $listeners[$index]['FIO_initial_listener'] = preg_replace('/(\w+) (\w)\w+ (\w)\w+/iu', '$1 $2. $3.', $listeners[$index]['FIO_listener']);
                            $listeners[$index]['Kod_kursa'] = $listener['course']['code'];
                            $listeners[$index]['Nazvanie_kursa'] = $listener['course']['title'];
                            $listeners[$index]['DataProvedeniyaAttestacii'] = !empty($listener['final_test']) ? $listener['final_test']['created_at'] : '1970-01-01 00:00:00' ;
                            $listeners[$index]['OcenkaAttestacii'] = !empty($listener['final_test']) ? $listener['final_test']['result_attempt'] : '0' ;
                            $listeners[$index]['OcenkaAttestaciiSlovami'] = $listeners[$index]['OcenkaAttestacii'] >= $page_data['MinimalnayaOcenkaAttestacii']  ? 'Зачет' : 'Незачет' ;
                            $listeners[$index]['VremyaProhojdeniyaAttestacii'] = 0;
                            $listeners[$index]['DannueResultatovAttestacii'] = array();
                            if (!empty($listener['final_test']) && $listener['final_test']['result_attempt'] >= Config::get('site.success_test_percent')):
                                $order_listeners_id = $listener['id'];
                                $test = CoursesTests::where('id',$listener['final_test']['test_id'])
                                    ->where('active',1)
                                    ->with('questions.answers')
                                    ->with(array('user_final_test_success'=>function($query) use ($order_listeners_id){
                                        $query->where('order_listeners_id',$order_listeners_id);
                                    }))->first();
                                if($test && !empty($test->user_final_test_success)):
                                    $maxValueTest = array(); $result_attempt = 0;
                                    foreach ($test->user_final_test_success as $testSuccess):
                                        if ($testSuccess->result_attempt >= $result_attempt):
                                            $maxValueTest['id'] = $testSuccess->id;
                                            $maxValueTest['result_attempt'] = $testSuccess->result_attempt;
                                            $maxValueTest['time_attempt'] = $testSuccess->time_attempt;
                                            $maxValueTest['data_results'] = !empty($testSuccess['data_results']) ? json_decode($testSuccess['data_results'], TRUE) : array() ;
                                            $result_attempt = $testSuccess->result_attempt;
                                            $listeners[$index]['VremyaProhojdeniyaAttestacii'] = $testSuccess->time_attempt;
                                        endif;
                                    endforeach;
                                    foreach($test->questions as $question):
                                        $listeners[$index]['DannueResultatovAttestacii'][$question->id]['title'] = $question->title.$question->order;
                                        $listeners[$index]['DannueResultatovAttestacii'][$question->id]['description'] = $question->description;
                                        foreach($question->answers as $answer):
                                            $listeners[$index]['DannueResultatovAttestacii'][$question->id]['answers'][$answer->id]['title'] = $answer->title.$answer->order;
                                            $listeners[$index]['DannueResultatovAttestacii'][$question->id]['answers'][$answer->id]['description'] = $answer->description;
                                            $listeners[$index]['DannueResultatovAttestacii'][$question->id]['answers'][$answer->id]['correct'] = $answer->correct;
                                            $listeners[$index]['DannueResultatovAttestacii'][$question->id]['answers'][$answer->id]['user_correct'] = 0;
                                        endforeach;
                                    endforeach;
                                    if (isset($maxValueTest['data_results']) && !empty($maxValueTest['data_results'])):
                                        foreach($maxValueTest['data_results'] as $question_id => $answer_id):
                                            if (isset($listeners[$index]['DannueResultatovAttestacii'][$question_id]['answers'][$answer_id])):
                                                $listeners[$index]['DannueResultatovAttestacii'][$question_id]['answers'][$answer_id]['user_correct'] = 1;
                                            endif;
                                        endforeach;
                                    endif;
                                endif;
                            endif;
                        endforeach;
                        foreach($listeners as $listener_id => $listener):
                            $page_data['FIO_listener'] = $listener['FIO_listener'];
                            $page_data['Phone_listener'] = $listener['Phone_listener'];
                            $page_data['Email_listener'] = $listener['Email_listener'];
                            $page_data['Address_listener'] = $listener['Address_listener'];
                            $page_data['FIO_initial_listener'] = $listener['FIO_initial_listener'];
                            $page_data['Kod_kursa'] = $listener['Kod_kursa'];
                            $page_data['Nazvanie_kursa'] = $listener['Nazvanie_kursa'];
                            $page_data['DataProvedeniyaAttestacii'] = (new myDateTime())->setDateString($listener['DataProvedeniyaAttestacii'])->format('d.m.Y');
                            $page_data['OcenkaAttestacii'] = $listener['OcenkaAttestacii'];
                            $page_data['OcenkaAttestaciiSlovami'] = $listener['OcenkaAttestaciiSlovami'];
                            $page_data['DannueResultatovAttestacii'] = $listener['DannueResultatovAttestacii'];
                            if ($page_data['OcenkaAttestacii'] >= Config::get('site.success_test_percent')):
                                $mpdf->AddPage('P');
                                $mpdf->WriteHTML(View::make($template, $page_data)->render(), 2);
                            endif;
                        endforeach;
                    endif;
                    return $mpdf->Output('certification-№'.getOrderNumber($order_id).'.pdf', 'D');
                case 'word':
                    return Redirect::back();
                    break;
            endswitch;
        endif;
        App::abort(404);
    }
    /****************************************************************************/
    public function individual_listenerOrderContract($order_id,$format){

        if (!User_individual::where('id',Auth::user()->id)->pluck('moderator_approve')):
            return Redirect::route('individual-orders');
        endif;
        if (!$order = Orders::where('id',$order_id)->where('user_id',Auth::user()->id)->where('completed',1)->where('archived',0)->with('contract')->first()):
            return Redirect::route('individual-orders');
        endif;
        $document = Dictionary::valueBySlugs('order-documents','fiz-order-documents-contract');
        $document_app1 = Dictionary::valueBySlugs('order-documents','fiz-order-documents-contract-listeners');
        if ($order->contract->exists && File::exists(public_path($order->contract->path))):
            $headers = returnDownloadHeaders($order->contract);
            return Response::download(public_path($order->contract->path),'contract-№'.getOrderNumber($order).'.'.$order->contract->mime2,$headers);
        elseif($document->exists && !empty($document->fields)):
            $fields = modifyKeys($document->fields,'key');
            $fields_app1 = modifyKeys($document_app1->fields,'key');
            Config::set('show-document.order_id', $order_id);
            switch($format):
                case 'html':
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        return View::make('templates/assets/documents',$page_data);
                    endif;
                    $document_content_app1 = isset($fields_app1['content']) ? $fields_app1['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content_app1)):
                        #return View::make('templates/assets/contract-app1',$page_data);
                    endif;
                    break;
                case 'pdf' :
                    $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
                    $mpdf->SetDisplayMode('fullpage');
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        $page_data['page_title'] = '';
                        $mpdf->WriteHTML(View::make('templates/assets/documents', $page_data)->render(), 2);
                    endif;
                    $document_content_app1 = isset($fields_app1['content']) ? $fields_app1['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content_app1)):
                        $page_data['page_title'] = '';
                        $mpdf->AddPage('L');
                        $mpdf->WriteHTML(View::make('templates/assets/contract-app1', $page_data)->render(), 2);
                    endif;
                    return $mpdf->Output('contract-№'.getOrderNumber($order).'.pdf', 'D');
                case 'word':
                    return Redirect::back();
                    break;
            endswitch;
        endif;
        return Redirect::route('individual-orders');
    }

    public function individual_listenerOrderInvoice($order_id,$format){

        $template = 'templates/assets/invoice';
        return $this->individual_listenerShowDocument($order_id,$format,'invoice',$template);
    }

    public function individual_listenerOrderAct($order_id,$format){

        $template = 'templates/assets/act';
        return $this->individual_listenerShowDocument($order_id, $format, 'act',$template);
    }

    public function individual_listenerOrderCertificate($order_id,$course_id,$listener_id,$format){

        $listener = User_individual::where('id',Auth::user()->id)->first();
        if (!$listener->moderator_approve):
            return Redirect::route('individual-orders');
        endif;
        if (!$OrderListener = OrderListeners::where('id',$course_id)->where('order_id',$order_id)->where('user_id',$listener_id)->where('over_status',1)->first()):
            return Redirect::route('individual-orders');
        endif;
        if (!$order = Orders::where('id',$order_id)->where('user_id',Auth::user()->id)->where('completed',1)->where('archived',0)->first()):
            return Redirect::route('individual-orders');
        endif;
        if (!$course = OrderListeners::where('id',$course_id)->first()->course):
            return Redirect::route('individual-orders');
        endif;
        $template = 'templates/assets/certificates/certificate-';
        $slug = DicVal::where('id',$course->certificate)->pluck('slug');
        if (!empty($slug)):
            $words = explode('-',$slug);
            if (is_array($words) && isset($words[3])):
                $template .= $words[3];
            else:
                App::abort(404);
            endif;
        endif;
        if($document = DicVal::where('id',$course->certificate)->first()->allfields):
            $fields = modifyKeys($document,'key');
            $document_content = isset($fields['content']) ? $fields['content']->value : '';
            if (!empty($document_content)):
                Config::set('show-document.order_id', $order_id);
                if($page_data = self::parseOrderHTMLDocument($document_content)):
                    $page_data['FIO_listener'] = $listener->fio;
                    $page_data['FIO_listener_dat'] = $listener->fio_rod;
                    $page_data['NomerUdostovereniya'] = str_pad($OrderListener->certificate_number,4,'0',STR_PAD_LEFT);
                    $page_data['Nazvanie_kursa'] = $course->title;
                    $page_data['KolichestvoChasovObucheniyaPoKursu'] = $course->hours.' '.Lang::choice('час|часов|часов',$course->hours);
                    switch($format):
                        case 'html':
                            return View::make($template,$page_data);
                            break;
                        case 'pdf' :
                            $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
                            $mpdf->SetDisplayMode('fullpage');
                            $mpdf->AddPage('L');
                            $page_data['page_title'] = '';
                            $stylesheet = file_get_contents(Config::get('site.theme_path').'/css/documents.css');
                            $mpdf->WriteHTML($stylesheet,1);
                            $mpdf->WriteHTML(View::make($template, $page_data)->render(), 2);
                            return $mpdf->Output('certificate-№'.$page_data['NomerUdostovereniya'].'.pdf', 'D');
                        case 'word':
                            return Redirect::back();
                            break;
                    endswitch;
                endif;
            endif;
        endif;
        App::abort(404);
    }

    public function individual_OrderResultCertification($order_id,$order_listener_id,$format){

        if (!$orderListener = OrderListeners::where('id',$order_listener_id)->where('order_id',$order_id)->where('user_id',Auth::user()->id)->with('course','final_test')->get()):
            return Redirect::back();
        endif;
        $account = User_individual::where('id',Auth::user()->id)->first();
        $account_type = 4;
        $template = 'templates.assets.result-certification';
        $document = Dictionary::valueBySlugs('order-documents','order-documents-result-certification');
        if($document->exists && !empty($document->fields)):
            $fields = modifyKeys($document->fields,'key');
            Config::set('show-document.order_id', $order_id);
            switch($format):
                case 'html':
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        return View::make($template,$page_data);
                    endif;
                    break;
                case 'pdf' :
                    $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
                    $mpdf->SetDisplayMode('fullpage');
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        $page_data['page_title'] = '';
                        foreach($orderListener as $index => $listener):
                            $listeners[$index]['FIO_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['fio'] : $listener['user_individual']['fio'];
                            $listeners[$index]['Phone_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['phone'] : $listener['user_individual']['phone'];
                            $listeners[$index]['Email_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['email'] : $listener['user_individual']['email'];
                            $listeners[$index]['Address_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['postaddress'] : $listener['user_individual']['postaddress'];
                            $listeners[$index]['FIO_initial_listener'] = preg_replace('/(\w+) (\w)\w+ (\w)\w+/iu', '$1 $2. $3.', $listeners[$index]['FIO_listener']);
                            $listeners[$index]['Kod_kursa'] = $listener['course']['code'];
                            $listeners[$index]['Nazvanie_kursa'] = $listener['course']['title'];
                            $listeners[$index]['DataProvedeniyaAttestacii'] = !empty($listener['final_test']) ? $listener['final_test']['created_at'] : '1970-01-01 00:00:00' ;
                            $listeners[$index]['OcenkaAttestacii'] = !empty($listener['final_test']) ? $listener['final_test']['result_attempt'] : '0' ;
                            $listeners[$index]['OcenkaAttestaciiSlovami'] = $listeners[$index]['OcenkaAttestacii'] >= $page_data['MinimalnayaOcenkaAttestacii']  ? 'Зачет' : 'Незачет' ;
                            $listeners[$index]['VremyaProhojdeniyaAttestacii'] = 0;
                            $listeners[$index]['DannueResultatovAttestacii'] = array();
                            if (!empty($listener['final_test']) && $listener['final_test']['result_attempt'] >= Config::get('site.success_test_percent')):
                                $order_listeners_id = $listener['id'];
                                $test = CoursesTests::where('id',$listener['final_test']['test_id'])
                                    ->where('active',1)
                                    ->with('questions.answers')
                                    ->with(array('user_final_test_success'=>function($query) use ($order_listeners_id){
                                        $query->where('order_listeners_id',$order_listeners_id);
                                    }))->first();
                                if($test && !empty($test->user_final_test_success)):
                                    $maxValueTest = array(); $result_attempt = 0;
                                    foreach ($test->user_final_test_success as $testSuccess):
                                        if ($testSuccess->result_attempt >= $result_attempt):
                                            $maxValueTest['id'] = $testSuccess->id;
                                            $maxValueTest['result_attempt'] = $testSuccess->result_attempt;
                                            $maxValueTest['time_attempt'] = $testSuccess->time_attempt;
                                            $maxValueTest['data_results'] = !empty($testSuccess['data_results']) ? json_decode($testSuccess['data_results'], TRUE) : array() ;
                                            $result_attempt = $testSuccess->result_attempt;
                                            $listeners[$index]['VremyaProhojdeniyaAttestacii'] = $testSuccess->time_attempt;
                                        endif;
                                    endforeach;
                                    foreach($test->questions as $question):
                                        $listeners[$index]['DannueResultatovAttestacii'][$question->id]['title'] = $question->title.$question->order;
                                        $listeners[$index]['DannueResultatovAttestacii'][$question->id]['description'] = $question->description;
                                        foreach($question->answers as $answer):
                                            $listeners[$index]['DannueResultatovAttestacii'][$question->id]['answers'][$answer->id]['title'] = $answer->title.$answer->order;
                                            $listeners[$index]['DannueResultatovAttestacii'][$question->id]['answers'][$answer->id]['description'] = $answer->description;
                                            $listeners[$index]['DannueResultatovAttestacii'][$question->id]['answers'][$answer->id]['correct'] = $answer->correct;
                                            $listeners[$index]['DannueResultatovAttestacii'][$question->id]['answers'][$answer->id]['user_correct'] = 0;
                                        endforeach;
                                    endforeach;
                                    if (isset($maxValueTest['data_results']) && !empty($maxValueTest['data_results'])):
                                        foreach($maxValueTest['data_results'] as $question_id => $answer_id):
                                            if (isset($listeners[$index]['DannueResultatovAttestacii'][$question_id]['answers'][$answer_id])):
                                                $listeners[$index]['DannueResultatovAttestacii'][$question_id]['answers'][$answer_id]['user_correct'] = 1;
                                            endif;
                                        endforeach;
                                    endif;
                                endif;
                            endif;
                        endforeach;
                        foreach($listeners as $listener_id => $listener):
                            $page_data['FIO_listener'] = $listener['FIO_listener'];
                            $page_data['Phone_listener'] = $listener['Phone_listener'];
                            $page_data['Email_listener'] = $listener['Email_listener'];
                            $page_data['Address_listener'] = $listener['Address_listener'];
                            $page_data['FIO_initial_listener'] = $listener['FIO_initial_listener'];
                            $page_data['Kod_kursa'] = $listener['Kod_kursa'];
                            $page_data['Nazvanie_kursa'] = $listener['Nazvanie_kursa'];
                            $page_data['DataProvedeniyaAttestacii'] = (new myDateTime())->setDateString($listener['DataProvedeniyaAttestacii'])->format('d.m.Y');
                            $page_data['OcenkaAttestacii'] = $listener['OcenkaAttestacii'];
                            $page_data['OcenkaAttestaciiSlovami'] = $listener['OcenkaAttestaciiSlovami'];
                            $page_data['DannueResultatovAttestacii'] = $listener['DannueResultatovAttestacii'];
                            if ($page_data['OcenkaAttestacii'] >= Config::get('site.success_test_percent')):
                                $mpdf->AddPage('P');
                                $mpdf->WriteHTML(View::make($template, $page_data)->render(), 2);
                            endif;
                        endforeach;
                    endif;
                    return $mpdf->Output('certification-№'.getOrderNumber($order_id).'.pdf', 'D');
                case 'word':
                    return Redirect::back();
                    break;
            endswitch;
        endif;
        App::abort(404);
    }

    private function individual_listenerShowDocument($order_id,$format,$document_type,$template){

        if (!User_individual::where('id',Auth::user()->id)->pluck('moderator_approve')):
            return Redirect::route('individual-orders');
        endif;
        if (!$order = Orders::where('id',$order_id)->where('user_id',Auth::user()->id)->where('completed',1)->where('archived',0)->first()):
            return Redirect::route('organization-orders');
        endif;
        if (!$order->close_status && $document_type == 'act'):
            return Redirect::route('organization-orders');
        endif;
        $document = Dictionary::valueBySlugs('order-documents','order-documents-'.$document_type);
        if ($order->$document_type->exists && File::exists(public_path($order->$document_type->path))):
            $headers = returnDownloadHeaders($order->contract);
            return Response::download(public_path($order->$document_type->path),$document_type.'-№'.getOrderNumber($order).'.'.$order->$document_type->mime2,$headers);
        elseif($document->exists && !empty($document->fields)):
            $fields = modifyKeys($document->fields,'key');
            Config::set('show-document.order_id', $order_id);
            switch($format):
                case 'html':
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        return View::make($template,$page_data);
                    endif;
                    break;
                case 'pdf' :
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        $page_data['page_title'] = '';
                        $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
                        $mpdf->SetDisplayMode('fullpage');
                        $mpdf->WriteHTML(View::make($template, $page_data)->render(), 2);
                        return $mpdf->Output($document_type.'-№'.getOrderNumber($order).'.pdf', 'D');
                    endif;
                    break;
                case 'word':
                    return Redirect::back();
                    break;
            endswitch;
        endif;
        return Redirect::route('organization-orders');
    }
    /****************************************************************************/
    /****************************************************************************/
    public function moderatorOrderContract($order_id,$format){

        if (!$order = Orders::where('id',$order_id)->where('completed',1)->with('organization','individual','contract')->first()):
            return Redirect::route('moderator-orders-list');
        endif;
        if (!empty($order->organization)):
            $document = Dictionary::valueBySlugs('order-documents','order-documents-contract');
            $document_app1 = Dictionary::valueBySlugs('order-documents','order-documents-contract-listeners');
        elseif(!empty($order->individual)):
            $document = Dictionary::valueBySlugs('order-documents','fiz-order-documents-contract');
            $document_app1 = Dictionary::valueBySlugs('order-documents','fiz-order-documents-contract-listeners');
        endif;
        if ($order->contract->exists && File::exists(public_path($order->contract->path))):
            $headers = returnDownloadHeaders($order->contract);
            return Response::download(public_path($order->contract->path),'contract-№'.getOrderNumber($order).'.'.$order->contract->mime2,$headers);
        elseif($document->exists && !empty($document->fields)):
            $fields = modifyKeys($document->fields,'key');
            $fields_app1 = modifyKeys($document_app1->fields,'key');
            Config::set('show-document.order_id', $order_id);
            switch($format):
                case 'html':
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        #return View::make('templates/assets/documents',$page_data);
                    endif;
                    $document_content_app1 = isset($fields_app1['content']) ? $fields_app1['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content_app1)):
                        return View::make('templates/assets/contract-app1',$page_data);
                    endif;
                    break;
                case 'pdf' :
                    $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
                    $mpdf->SetDisplayMode('fullpage');
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        $page_data['page_title'] = '';
                        $mpdf->WriteHTML(View::make('templates/assets/documents', $page_data)->render(), 2);
                    endif;
                    $document_content_app1 = isset($fields_app1['content']) ? $fields_app1['content']->value : '';
                     if($page_data = self::parseOrderHTMLDocument($document_content_app1)):
                        $page_data['page_title'] = '';
                        $mpdf->AddPage('L');
                        $mpdf->WriteHTML(View::make('templates/assets/contract-app1', $page_data)->render(), 2);
                    endif;
                    return $mpdf->Output('contract-№'.getOrderNumber($order).'.pdf', 'D');
                case 'word':
                    return Redirect::back();
                    break;
            endswitch;
        endif;
        App::abort(404);
    }

    public function moderatorOrderInvoice($order_id,$format){

        $template = 'templates/assets/invoice';
        return $this->moderatorShowDocument($order_id,$format,'invoice',$template);
    }

    public function moderatorOrderAct($order_id,$format){

        $template = 'templates/assets/act';
        return $this->moderatorShowDocument($order_id, $format, 'act',$template);
    }

    public function moderatorOrderCertificate($order_id,$course_id,$listener_id,$format){

        if (!$OrderListener = OrderListeners::where('id',$course_id)->where('order_id',$order_id)->where('user_id',$listener_id)->where('over_status',1)->first()):
            return Redirect::route('organization-orders');
        endif;
        if (!$course = OrderListeners::where('id',$course_id)->first()->course):
            return Redirect::route('organization-orders');
        endif;
        $template = 'templates/assets/certificates/certificate-';
        $slug = DicVal::where('id',$course->certificate)->pluck('slug');
        if (!empty($slug)):
            $words = explode('-',$slug);
            if (is_array($words) && isset($words[3])):
                $template .= $words[3];
            else:
                App::abort(404);
            endif;
        endif;
        $FIO_listener = '';
        $group_id = User::where('id',$listener_id)->first()->group->id;
        if ($group_id == 5):
            $FIO_listener = User_listener::where('id',$listener_id)->pluck('fio');
            $FIO_listener_dat = User_listener::where('id',$listener_id)->pluck('fio_dat');
        else:
            $FIO_listener = User_individual::where('id',$listener_id)->pluck('fio');
            $FIO_listener_dat = User_individual::where('id',$listener_id)->pluck('fio_rod');
        endif;
        if($document = DicVal::where('id',$course->certificate)->first()->allfields):
            $fields = modifyKeys($document,'key');
            $document_content = isset($fields['content']) ? $fields['content']->value : '';
            if (!empty($document_content)):
                Config::set('show-document.order_id', $order_id);
                if($page_data = self::parseOrderHTMLDocument($document_content)):
                    $page_data['FIO_listener'] = @$FIO_listener;
                    $page_data['FIO_listener_dat'] = @$FIO_listener_dat;
                    $page_data['NomerUdostovereniya'] = str_pad($OrderListener->certificate_number,4,'0',STR_PAD_LEFT);
                    $page_data['Nazvanie_kursa'] = $course->title;
                    $page_data['KolichestvoChasovObucheniyaPoKursu'] = $course->hours.' '.Lang::choice('час|часов|часов',$course->hours);
                    switch($format):
                        case 'html':
                            return View::make($template,$page_data);
                            break;
                        case 'pdf' :
                            $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
                            $mpdf->SetDisplayMode('fullpage');
                            $mpdf->AddPage('L');
                            $page_data['page_title'] = '';
                            $stylesheet = file_get_contents(Config::get('site.theme_path').'/css/documents.css');
                            $mpdf->WriteHTML($stylesheet,1);
                            $mpdf->WriteHTML(View::make($template, $page_data)->render(), 2);
                            return $mpdf->Output('certificate-№'.$page_data['NomerUdostovereniya'].'.pdf', 'D');
                        case 'word':
                            return Redirect::back();
                            break;
                    endswitch;
                endif;
            endif;
        endif;
        App::abort(404);
    }
    /****************************************************************************/
    public function moderatorOrderRequest($order_id,$format){

        if (!$order = Orders::where('id',$order_id)->where('completed',1)->with('organization','individual')->first()):
            return Redirect::route('moderator-orders-list');
        endif;
        $account_type = NULL;
        $template = 'templates.assets.request';
        $document = Dictionary::valueBySlugs('order-documents','order-documents-request');
        if($document->exists && !empty($document->fields)):
            $fields = modifyKeys($document->fields,'key');
            Config::set('show-document.order_id', $order_id);
            switch($format):
                case 'html':
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        return View::make($template,$page_data);
                    endif;
                    break;
                case 'pdf' :
                    $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
                    $mpdf->SetDisplayMode('fullpage');
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        $page_data['page_title'] = '';
                        foreach($page_data['SpisokSluschateley']['listeners'] as $listener):
                            $listeners[$listener->user_id]['FIO_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['fio'] : $listener['user_individual']['fio'];
                            $listeners[$listener->user_id]['Phone_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['phone'] : $listener['user_individual']['phone'];
                            $listeners[$listener->user_id]['Email_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['email'] : $listener['user_individual']['email'];
                            $listeners[$listener->user_id]['Address_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['postaddress'] : $listener['user_individual']['postaddress'];
                            $listeners[$listener->user_id]['FIO_initial_listener'] = preg_replace('/(\w+) (\w)\w+ (\w)\w+/iu', '$1 $2. $3.', $listeners[$listener->user_id]['FIO_listener']);
                        endforeach;
                        foreach($listeners as $listener_id => $listener):
                            $page_data['FIO_listener'] = $listener['FIO_listener'];
                            $page_data['Phone_listener'] = $listener['Phone_listener'];
                            $page_data['Email_listener'] = $listener['Email_listener'];
                            $page_data['Address_listener'] = $listener['Address_listener'];
                            $page_data['FIO_initial_listener'] = $listener['FIO_initial_listener'];
                            $page = View::make($template, $page_data)->render();
                            $mpdf->AddPage('P');
                            $mpdf->WriteHTML(View::make($template, $page_data)->render(), 2);
                        endforeach;
                    endif;
                    return $mpdf->Output('request-№'.getOrderNumber($order).'.pdf', 'D');
                case 'word':
                    return Redirect::back();
                    break;
            endswitch;
        endif;
        App::abort(404);
    }

    public function moderatorOrderEnrollment($order_id,$format){

        $template = 'templates/assets/enrollment';
        return $this->moderatorShowCorporativeDocument($order_id,$format,'enrollment',$template);
    }

    public function moderatorOrderСompletion($order_id,$format){

        $template = 'templates/assets/completion';
        return $this->moderatorShowCorporativeDocument($order_id,$format,'completion',$template);
    }

    public function moderatorOrderClassSchedule($order_id,$format){

        if (!$order = Orders::where('id',$order_id)->where('completed',1)->with('organization','individual')->first()):
            return Redirect::route('moderator-orders-list');
        endif;
        $account = NULL; $account_type = NULL;
        $template = 'templates.assets.class-schedule';
        $document = Dictionary::valueBySlugs('order-documents','order-documents-class-schedule');
        if (!empty($order->organization)):
            $account = User_organization::where('id',$order->user_id)->first();
            $account_type = 4;
        elseif(!empty($order->individual)):
            $account = User_individual::where('id',$order->user_id)->first();
            $account_type = 6;
        endif;
        if($document->exists && !empty($document->fields)):
            $fields = modifyKeys($document->fields,'key');
            Config::set('show-document.order_id', $order_id);
            switch($format):
                case 'html':
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        return View::make($template,$page_data);
                    endif;
                    break;
                case 'pdf' :
                    $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
                    $mpdf->SetDisplayMode('fullpage');
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        $page_data['page_title'] = '';
                        foreach($page_data['SpisokSluschateley']['listeners'] as $listener_index =>  $listener):
                            $listeners[$listener_index]['FIO_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['fio'] : $listener['user_individual']['fio'];
                            $listeners[$listener_index]['Phone_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['phone'] : $listener['user_individual']['phone'];
                            $listeners[$listener_index]['Email_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['email'] : $listener['user_individual']['email'];
                            $listeners[$listener_index]['Address_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['postaddress'] : $listener['user_individual']['postaddress'];
                            $listeners[$listener_index]['FIO_initial_listener'] = preg_replace('/(\w+) (\w)\w+ (\w)\w+/iu', '$1 $2. $3.', $listeners[$listener->user_id]['FIO_listener']);
                            $listeners[$listener_index]['Kod_kursa'] = $listener->course->code;
                            $listeners[$listener_index]['Nazvanie_kursa'] = $listener->course->title;
                            $listeners[$listener_index]['hours'] = $listener->course->hours;
                            $listeners[$listener_index]['module'] = $listener->course->chapters;
                            $listeners[$listener_index]['final_test'] = $listener->course->test;
                            $listeners[$listener_index]['final_test']['test_title'] = $listener->course->test_title;
                            $listeners[$listener_index]['final_test']['test_hours'] = $listener->course->test_hours;
                        endforeach;
                        foreach($listeners as $course_id => $listener):
                            $page_data['FIO_listener'] = $listener['FIO_listener'];
                            $page_data['Phone_listener'] = $listener['Phone_listener'];
                            $page_data['Email_listener'] = $listener['Email_listener'];
                            $page_data['Address_listener'] = $listener['Address_listener'];
                            $page_data['FIO_initial_listener'] = $listener['FIO_initial_listener'];
                            $page_data['Kod_kursa'] = $listener['Kod_kursa'];
                            $page_data['Nazvanie_kursa'] = $listener['Nazvanie_kursa'];
                            $page_data['module'] = $listener['module'];
                            $page_data['final_test'] = $listener['final_test'];
                            $page_data['hours'] = $listener['hours'];
                            $page = View::make($template, $page_data)->render();
                            $mpdf->AddPage('P');

                            $stylesheet = file_get_contents(asset('theme/css/docs/default.css'));
                            $mpdf->WriteHTML($stylesheet,1);

                            $mpdf->WriteHTML(View::make($template, $page_data)->render(), 2);
                        endforeach;
                    endif;
                    return $mpdf->Output('schedule-№'.getOrderNumber($order).'.pdf', 'D');
                case 'word':
                    return Redirect::back();
                    break;
            endswitch;
        endif;
        App::abort(404);
    }

    public function moderatorOrderStatements($order_id,$format){

        if (!$order = Orders::where('id',$order_id)->where('completed',1)->with('organization','individual')->first()):
            return Redirect::route('moderator-orders-list');
        endif;
        $account = NULL; $account_type = NULL;
        $template = 'templates.assets.statements';
        $document = Dictionary::valueBySlugs('order-documents','order-documents-statements');
        if (!empty($order->organization)):
            $account = User_organization::where('id',$order->user_id)->first();
            $account_type = 4;
        elseif(!empty($order->individual)):
            $account = User_individual::where('id',$order->user_id)->first();
            $account_type = 6;
        endif;
        if($document->exists && !empty($document->fields)):
            $fields = modifyKeys($document->fields,'key');
            Config::set('show-document.order_id', $order_id);
            switch($format):
                case 'html':
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        return View::make($template,$page_data);
                    endif;
                    break;
                case 'pdf' :
                    $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
                    $mpdf->SetDisplayMode('fullpage');
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        $page_data['page_title'] = '';
                        foreach($page_data['SpisokSluschateley']['listeners'] as $index => $listener):
                            $listeners[$index]['FIO_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['fio'] : $listener['user_individual']['fio'];
                            $listeners[$index]['Phone_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['phone'] : $listener['user_individual']['phone'];
                            $listeners[$index]['Email_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['email'] : $listener['user_individual']['email'];
                            $listeners[$index]['Address_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['postaddress'] : $listener['user_individual']['postaddress'];
                            $listeners[$index]['FIO_initial_listener'] = preg_replace('/(\w+) (\w)\w+ (\w)\w+/iu', '$1 $2. $3.', $listeners[$index]['FIO_listener']);
                            $listeners[$index]['Kod_kursa'] = $listener['course']['code'];
                            $listeners[$index]['Nazvanie_kursa'] = $listener['course']['title'];
                            $listeners[$index]['KolichestvoChasovObucheniyaPoKursu'] = $listener['course']['hours'];
                        endforeach;
                        foreach($listeners as $listener_id => $listener):
                            $page_data['FIO_listener'] = $listener['FIO_listener'];
                            $page_data['Phone_listener'] = $listener['Phone_listener'];
                            $page_data['Email_listener'] = $listener['Email_listener'];
                            $page_data['Address_listener'] = $listener['Address_listener'];
                            $page_data['FIO_initial_listener'] = $listener['FIO_initial_listener'];
                            $page_data['Kod_kursa'] = $listener['Kod_kursa'];
                            $page_data['Nazvanie_kursa'] = $listener['Nazvanie_kursa'];
                            $page_data['KolichestvoChasovObucheniyaPoKursu'] = $listener['KolichestvoChasovObucheniyaPoKursu'];
                            $page_data['DataOkonchaniyaObucheniya'] = (new myDateTime())->setDateString($page_data['DataOplatuZakaza'])->addDays(floor($page_data['KolichestvoChasovObucheniyaPoKursu']/8))->format('d.m.Y');
                            $page = View::make($template, $page_data)->render();
                            $mpdf->AddPage('P');
                            $mpdf->WriteHTML(View::make($template, $page_data)->render(), 2);
                        endforeach;
                    endif;
                    return $mpdf->Output('statements-№'.getOrderNumber($order).'.pdf', 'D');
                case 'word':
                    return Redirect::back();
                    break;
            endswitch;
        endif;
        App::abort(404);
    }

    public function moderatorOrderExplanations($order_id,$format){

        $template = 'templates/assets/explanations';
        return $this->moderatorShowCorporativeDocument($order_id,$format,'explanations',$template);
    }

    public function moderatorOrderBrowsingHistory($order_id,$format){

        if (!$order = Orders::where('id',$order_id)->where('completed',1)->with('organization','individual')->first()):
            return Redirect::route('moderator-orders-list');
        endif;
        $account = NULL; $account_type = NULL;
        $template = 'templates.assets.browsing-history';
        $document = Dictionary::valueBySlugs('order-documents','order-documents-browsing-history');
        if (!empty($order->organization)):
            $account = User_organization::where('id',$order->user_id)->first();
            $account_type = 4;
        elseif(!empty($order->individual)):
            $account = User_individual::where('id',$order->user_id)->first();
            $account_type = 6;
        endif;
        if($document->exists && !empty($document->fields)):
            $fields = modifyKeys($document->fields,'key');
            Config::set('show-document.order_id', $order_id);
            switch($format):
                case 'html':
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        return View::make($template,$page_data);
                    endif;
                    break;
                case 'pdf' :
                    $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
                    $mpdf->SetDisplayMode('fullpage');
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        $page_data['page_title'] = '';
                        foreach($page_data['SpisokSluschateley']['listeners'] as $listener):
                            $listeners[$listener->course_id]['FIO_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['fio'] : $listener['user_individual']['fio'];
                            $listeners[$listener->course_id]['Phone_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['phone'] : $listener['user_individual']['phone'];
                            $listeners[$listener->course_id]['Email_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['email'] : $listener['user_individual']['email'];
                            $listeners[$listener->course_id]['Address_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['postaddress'] : $listener['user_individual']['postaddress'];
                            $listeners[$listener->course_id]['FIO_initial_listener'] = preg_replace('/(\w+) (\w)\w+ (\w)\w+/iu', '$1 $2. $3.', $listeners[$listener->user_id]['FIO_listener']);
                            $listeners[$listener->course_id]['Kod_kursa'] = $listener->course->code;
                            $listeners[$listener->course_id]['Nazvanie_kursa'] = $listener->course->title;
                            $listeners[$listener->course_id]['hours'] = $listener->course->hours;
                            $listeners[$listener->course_id]['module'] = $listener->course->chapters;
                            $listeners[$listener->course_id]['final_test'] = $listener->course->test;
                            $listeners[$listener->course_id]['final_test']['test_title'] = $listener->course->test_title;
                        endforeach;
                        foreach($listeners as $listener_id => $listener):
                            $page_data['FIO_listener'] = $listener['FIO_listener'];
                            $page_data['Phone_listener'] = $listener['Phone_listener'];
                            $page_data['Email_listener'] = $listener['Email_listener'];
                            $page_data['Address_listener'] = $listener['Address_listener'];
                            $page_data['FIO_initial_listener'] = $listener['FIO_initial_listener'];
                            $page_data['Kod_kursa'] = $listener['Kod_kursa'];
                            $page_data['Nazvanie_kursa'] = $listener['Nazvanie_kursa'];
                            $page_data['module'] = $listener['module'];
                            $page_data['final_test'] = $listener['final_test'];
                            $page_data['hours'] = $listener['hours'];
                            $page = View::make($template, $page_data)->render();
                            $mpdf->AddPage('P');
                            $mpdf->WriteHTML(View::make($template, $page_data)->render(), 2);
                        endforeach;
                    endif;
                    return $mpdf->Output('browsing-№'.getOrderNumber($order).'.pdf', 'D');
                case 'word':
                    return Redirect::back();
                    break;
            endswitch;
        endif;
        App::abort(404);
    }

    public function moderatorOrderAttestationSheet($order_id,$format){

        if (!$order = Orders::where('id',$order_id)->where('completed',1)->with('organization','individual')->first()):
            return Redirect::route('moderator-orders-list');
        endif;
        $account = NULL; $account_type = NULL;
        $template = 'templates.assets.attestation-sheet';
        $document = Dictionary::valueBySlugs('order-documents','order-documents-attestation-sheet');
        if (!empty($order->organization)):
            $account = User_organization::where('id',$order->user_id)->first();
            $account_type = 4;
        elseif(!empty($order->individual)):
            $account = User_individual::where('id',$order->user_id)->first();
            $account_type = 6;
        endif;
        if($document->exists && !empty($document->fields)):
            $fields = modifyKeys($document->fields,'key');
            Config::set('show-document.order_id', $order_id);
            switch($format):
                case 'html':
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        return View::make($template,$page_data);
                    endif;
                    break;
                case 'pdf' :
                    $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
                    $mpdf->SetDisplayMode('fullpage');
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        $page_data['page_title'] = '';
                        foreach($page_data['SpisokSluschateley']['listeners'] as $index => $listener):
                            $listeners[$index]['FIO_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['fio'] : $listener['user_individual']['fio'];
                            $listeners[$index]['Phone_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['phone'] : $listener['user_individual']['phone'];
                            $listeners[$index]['Email_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['email'] : $listener['user_individual']['email'];
                            $listeners[$index]['Address_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['postaddress'] : $listener['user_individual']['postaddress'];
                            $listeners[$index]['FIO_initial_listener'] = preg_replace('/(\w+) (\w)\w+ (\w)\w+/iu', '$1 $2. $3.', $listeners[$index]['FIO_listener']);
                            $listeners[$index]['Kod_kursa'] = $listener['course']['code'];
                            $listeners[$index]['Nazvanie_kursa'] = $listener['course']['title'];
                            $listeners[$index]['DataProvedeniyaAttestacii'] = !empty($listener['final_test']) ? $listener['final_test']['created_at'] : '1970-01-01 00:00:00' ;
                            $listeners[$index]['OcenkaAttestacii'] = !empty($listener['final_test']) ? $listener['final_test']['result_attempt'] : '0' ;
                            $listeners[$index]['OcenkaAttestaciiSlovami'] = $listeners[$index]['OcenkaAttestacii'] >= $page_data['MinimalnayaOcenkaAttestacii']  ? 'Зачет' : 'Незачет' ;
                        endforeach;
                        foreach($listeners as $listener_id => $listener):
                            $page_data['FIO_listener'] = $listener['FIO_listener'];
                            $page_data['Phone_listener'] = $listener['Phone_listener'];
                            $page_data['Email_listener'] = $listener['Email_listener'];
                            $page_data['Address_listener'] = $listener['Address_listener'];
                            $page_data['FIO_initial_listener'] = $listener['FIO_initial_listener'];
                            $page_data['Kod_kursa'] = $listener['Kod_kursa'];
                            $page_data['Nazvanie_kursa'] = $listener['Nazvanie_kursa'];
                            $page_data['DataProvedeniyaAttestacii'] = (new myDateTime())->setDateString($listener['DataProvedeniyaAttestacii'])->format('d.m.Y');
                            $page_data['OcenkaAttestacii'] = $listener['OcenkaAttestacii'];
                            $page_data['OcenkaAttestaciiSlovami'] = $listener['OcenkaAttestaciiSlovami'];
                            $page = View::make($template, $page_data)->render();
                            if ($page_data['OcenkaAttestacii'] >= Config::get('site.success_test_percent')):
                                $mpdf->AddPage('P');
                                $mpdf->WriteHTML(View::make($template, $page_data)->render(), 2);
                            endif;
                        endforeach;
                    endif;
                    return $mpdf->Output('attestation-№'.getOrderNumber($order).'.pdf', 'D');
                case 'word':
                    return Redirect::back();
                    break;
            endswitch;
        endif;
        App::abort(404);
    }

    public function moderatorOrderResultCertification($order_id,$format){

        if (!$order = Orders::where('id',$order_id)->where('completed',1)->with('organization','individual')->first()):
            return Redirect::route('moderator-orders-list');
        endif;
        $account = NULL; $account_type = NULL;
        $template = 'templates.assets.result-certification';
        $document = Dictionary::valueBySlugs('order-documents','order-documents-result-certification');
        if (!empty($order->organization)):
            $account = User_organization::where('id',$order->user_id)->first();
            $account_type = 4;
        elseif(!empty($order->individual)):
            $account = User_individual::where('id',$order->user_id)->first();
            $account_type = 6;
        endif;
        if($document->exists && !empty($document->fields)):
            $fields = modifyKeys($document->fields,'key');
            Config::set('show-document.order_id', $order_id);
            switch($format):
                case 'html':
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        return View::make($template,$page_data);
                    endif;
                    break;
                case 'pdf' :
                    $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
                    $mpdf->SetDisplayMode('fullpage');
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        $page_data['page_title'] = '';
                        foreach($page_data['SpisokSluschateley']['listeners'] as $index => $listener):
                            $listeners[$index]['FIO_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['fio'] : $listener['user_individual']['fio'];
                            $listeners[$index]['Phone_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['phone'] : $listener['user_individual']['phone'];
                            $listeners[$index]['Email_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['email'] : $listener['user_individual']['email'];
                            $listeners[$index]['Address_listener'] = !empty($listener['user_listener']) ? $listener['user_listener']['postaddress'] : $listener['user_individual']['postaddress'];
                            $listeners[$index]['FIO_initial_listener'] = preg_replace('/(\w+) (\w)\w+ (\w)\w+/iu', '$1 $2. $3.', $listeners[$index]['FIO_listener']);
                            $listeners[$index]['Kod_kursa'] = $listener['course']['code'];
                            $listeners[$index]['Nazvanie_kursa'] = $listener['course']['title'];
                            $listeners[$index]['DataProvedeniyaAttestacii'] = !empty($listener['final_test']) ? $listener['final_test']['created_at'] : '1970-01-01 00:00:00' ;
                            $listeners[$index]['OcenkaAttestacii'] = !empty($listener['final_test']) ? $listener['final_test']['result_attempt'] : '0' ;
                            $listeners[$index]['OcenkaAttestaciiSlovami'] = $listeners[$index]['OcenkaAttestacii'] >= $page_data['MinimalnayaOcenkaAttestacii']  ? 'Зачет' : 'Незачет' ;
                            $listeners[$index]['VremyaProhojdeniyaAttestacii'] = 0;
                            $listeners[$index]['DannueResultatovAttestacii'] = array();
                            if (!empty($listener['final_test']) && $listener['final_test']['result_attempt'] >= Config::get('site.success_test_percent')):
                                $order_listeners_id = $listener['id'];
                                $test = CoursesTests::where('id',$listener['final_test']['test_id'])
                                    ->where('active',1)
                                    ->with('questions.answers')
                                    ->with(array('user_final_test_success'=>function($query) use ($order_listeners_id){
                                        $query->where('order_listeners_id',$order_listeners_id);
                                    }))->first();
                                if($test && !empty($test->user_final_test_success)):
                                    $maxValueTest = array(); $result_attempt = 0;
                                    foreach ($test->user_final_test_success as $testSuccess):
                                        if ($testSuccess->result_attempt >= $result_attempt):
                                            $maxValueTest['id'] = $testSuccess->id;
                                            $maxValueTest['result_attempt'] = $testSuccess->result_attempt;
                                            $maxValueTest['time_attempt'] = $testSuccess->time_attempt;
                                            $maxValueTest['data_results'] = !empty($testSuccess['data_results']) ? json_decode($testSuccess['data_results'], TRUE) : array() ;
                                            $result_attempt = $testSuccess->result_attempt;
                                            $listeners[$index]['VremyaProhojdeniyaAttestacii'] = $testSuccess->time_attempt;
                                        endif;
                                    endforeach;
                                    foreach($test->questions as $question):
                                        $listeners[$index]['DannueResultatovAttestacii'][$question->id]['title'] = $question->title.$question->order;
                                        $listeners[$index]['DannueResultatovAttestacii'][$question->id]['description'] = $question->description;
                                        foreach($question->answers as $answer):
                                            $listeners[$index]['DannueResultatovAttestacii'][$question->id]['answers'][$answer->id]['title'] = $answer->title.$answer->order;
                                            $listeners[$index]['DannueResultatovAttestacii'][$question->id]['answers'][$answer->id]['description'] = $answer->description;
                                            $listeners[$index]['DannueResultatovAttestacii'][$question->id]['answers'][$answer->id]['correct'] = $answer->correct;
                                            $listeners[$index]['DannueResultatovAttestacii'][$question->id]['answers'][$answer->id]['user_correct'] = 0;
                                        endforeach;
                                    endforeach;
                                    if (isset($maxValueTest['data_results']) && !empty($maxValueTest['data_results'])):
                                        foreach($maxValueTest['data_results'] as $question_id => $answer_id):
                                            if (isset($listeners[$index]['DannueResultatovAttestacii'][$question_id]['answers'][$answer_id])):
                                                $listeners[$index]['DannueResultatovAttestacii'][$question_id]['answers'][$answer_id]['user_correct'] = 1;
                                            endif;
                                        endforeach;
                                    endif;
                                endif;
                            endif;
                        endforeach;
                        foreach($listeners as $listener_id => $listener):
                            $page_data['FIO_listener'] = $listener['FIO_listener'];
                            $page_data['Phone_listener'] = $listener['Phone_listener'];
                            $page_data['Email_listener'] = $listener['Email_listener'];
                            $page_data['Address_listener'] = $listener['Address_listener'];
                            $page_data['FIO_initial_listener'] = $listener['FIO_initial_listener'];
                            $page_data['Kod_kursa'] = $listener['Kod_kursa'];
                            $page_data['Nazvanie_kursa'] = $listener['Nazvanie_kursa'];
                            $page_data['DataProvedeniyaAttestacii'] = (new myDateTime())->setDateString($listener['DataProvedeniyaAttestacii'])->format('d.m.Y');
                            $page_data['OcenkaAttestacii'] = $listener['OcenkaAttestacii'];
                            $page_data['OcenkaAttestaciiSlovami'] = $listener['OcenkaAttestaciiSlovami'];
                            $page_data['DannueResultatovAttestacii'] = $listener['DannueResultatovAttestacii'];
                            if ($page_data['OcenkaAttestacii'] >= Config::get('site.success_test_percent')):
                                $mpdf->AddPage('P');
                                $mpdf->WriteHTML(View::make($template, $page_data)->render(), 2);
                            endif;
                        endforeach;
                    endif;
                    return $mpdf->Output('certification-№'.getOrderNumber($order).'.pdf', 'D');
                case 'word':
                    return Redirect::back();
                    break;
            endswitch;
        endif;
        App::abort(404);
    }

    public function moderatorOrdersJournalIssuance($order_id,$format){

        $template = 'templates/assets/journal-issuance';
        return $this->moderatorShowCorporativeDocument($order_id,$format,'journal-issuance',$template);

    }
    /****************************************************************************/
    /****************************************************************************/
    public function moderatorShowDocument($order_id,$format,$document_type,$template){

        if (!$order = Orders::where('id',$order_id)->where('completed',1)->with('organization','individual',$document_type)->first()):
            return Redirect::route('moderator-orders-list');
        endif;
        $account = NULL; $account_type = NULL;
        if (!empty($order->organization)):
            $account = User_organization::where('id',$order->user_id)->first();
            $account_type = 4;
            $document = Dictionary::valueBySlugs('order-documents','order-documents-'.$document_type);
        elseif(!empty($order->individual)):
            $account = User_individual::where('id',$order->user_id)->first();
            $account_type = 6;
            $document = Dictionary::valueBySlugs('order-documents','order-documents-'.$document_type);
        endif;
        if ($order->$document_type->exists && File::exists(public_path($order->$document_type->path))):
            $headers = returnDownloadHeaders($order->contract);
            return Response::download(public_path($order->$document_type->path),$document_type.'-№'.getOrderNumber($order).'.'.$order->$document_type->mime2,$headers);
        elseif($document->exists && !empty($document->fields)):
            $fields = modifyKeys($document->fields,'key');
            $word_template = FALSE;
            if($word_template_id = isset($fields['word_template']) ? $fields['word_template']->value : ''):
                $word_template = Upload::where('id',$word_template_id)->pluck('path');
            endif;
            Config::set('show-document.order_id', $order_id);
            switch($format):
                case 'html':
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        return View::make($template,$page_data);
                    endif;
                    break;
                case 'pdf' :
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        $page_data['page_title'] = '';
                        $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
                        $mpdf->SetDisplayMode('fullpage');
                        $mpdf->WriteHTML(View::make($template, $page_data)->render(), 2);
                        return $mpdf->Output($document_type.'-№'.getOrderNumber($order).'.pdf', 'D');
                    endif;
                    break;
                case 'word':
                    return Redirect::back();
                    break;
            endswitch;
        endif;
        App::abort(404);
    }

    public function moderatorShowCorporativeDocument($order_id,$format,$document_type,$template){

        if (!$order = Orders::where('id',$order_id)->where('completed',1)->with('organization','individual')->first()):
            return Redirect::route('moderator-orders-list');
        endif;
        $account = NULL; $account_type = NULL;
        if (!empty($order->organization)):
            $account = User_organization::where('id',$order->user_id)->first();
            $account_type = 4;
            $document = Dictionary::valueBySlugs('order-documents','order-documents-'.$document_type);
        elseif(!empty($order->individual)):
            $account = User_individual::where('id',$order->user_id)->first();
            $account_type = 6;
            $document = Dictionary::valueBySlugs('order-documents','order-documents-'.$document_type);
        endif;
        if($document->exists && !empty($document->fields)):
            $fields = modifyKeys($document->fields,'key');
            $word_template = FALSE;
            if($word_template_id = isset($fields['word_template']) ? $fields['word_template']->value : ''):
                $word_template = Upload::where('id',$word_template_id)->pluck('path');
            endif;
            Config::set('show-document.order_id', $order_id);
            switch($format):
                case 'html':
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        return View::make($template,$page_data);
                    endif;
                    break;
                case 'pdf' :
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        $page_data['page_title'] = '';
                        $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
                        $mpdf->SetDisplayMode('fullpage');
                        
                        $stylesheet = file_get_contents(asset('theme/css/docs/default.css'));
                        $mpdf->WriteHTML($stylesheet,1);

                        $mpdf->WriteHTML(View::make($template, $page_data)->render(), 2);
                        return $mpdf->Output($document_type.'-№'.getOrderNumber($order).'.pdf', 'D');
                    endif;
                    break;
                case 'word':
                    return Redirect::back();
                    break;
            endswitch;
        endif;
        App::abort(404);
    }

    public function generateAllDocuments($order_id){

        $order = Orders::where('id',$order_id)->where('close_status',1)->with('contract','invoice','act','organization','individual')->first();
        $contract_content = $contract_app1_content = $invoice_content = $act_content = '';
        if (!empty($order->organization)):
            $account_type = 4;
        elseif(!empty($order->individual)):
            $account_type = 6;
        endif;
        if (empty($order->contract_id)):
            if ($account_type == 4):
                $document = Dictionary::valueBySlugs('order-documents','order-documents-contract');
                $document_app1 = Dictionary::valueBySlugs('order-documents','order-documents-contract-listeners');
            elseif($account_type == 6):
                $document = Dictionary::valueBySlugs('order-documents','fiz-order-documents-contract');
                $document_app1 = Dictionary::valueBySlugs('order-documents','fiz-order-documents-contract-listeners');
            endif;
            if ($document->exists && !empty($document->fields)):
                $fields = modifyKeys($document->fields,'key');
                $contract_content = $fields['content']->value;
            endif;
            if ($document_app1->exists && !empty($document_app1->fields)):
                $fields = modifyKeys($document_app1->fields,'key');
                $contract_app1_content = $fields['content']->value;
            endif;
        endif;
        if (empty($order->invoice_id)):
            if ($account_type == 4):
                $document = Dictionary::valueBySlugs('order-documents','order-documents-invoice');
            elseif ($account_type == 6):
                $document = Dictionary::valueBySlugs('order-documents','order-documents-invoice');
            endif;
            if ($document->exists && !empty($document->fields)):
                $fields = modifyKeys($document->fields,'key');
                $invoice_content = $fields['content']->value;
            endif;
        endif;
        if (empty($order->act_id)):
            if ($account_type == 4):
                $document = Dictionary::valueBySlugs('order-documents','order-documents-act');
            else:
                $document = Dictionary::valueBySlugs('order-documents','order-documents-act');
            endif;
            if ($document->exists && !empty($document->fields)):
                $fields = modifyKeys($document->fields,'key');
                $act_content = $fields['content']->value;
            endif;
        endif;
        Config::set('show-document.order_id', $order->id);
        if (!empty($contract_content)):
            $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
            $mpdf->SetDisplayMode('fullpage');
            if($page_data = self::parseOrderHTMLDocument($contract_content)):
                $page_data['page_title'] = '';
                $mpdf->WriteHTML(View::make('templates/assets/documents', $page_data)->render(), 2);
            endif;
            if(!empty($contract_app1_content)):
                if($page_data = self::parseOrderHTMLDocument($contract_app1_content)):
                    $page_data['page_title'] = '';
                    $mpdf->AddPage('L');
                    $mpdf->WriteHTML(View::make('templates/assets/contract-app1', $page_data)->render(), 2);
                endif;
            endif;
            $fileName = 'uploads/orders/contract'.'-'.getOrderNumber($order).'.pdf';
            $mpdf->Output($fileName, 'F');
            $input = array(
                'path' => $fileName,'original_name' => 'contract-'.getOrderNumber($order).'.pdf','filesize' => filesize(public_path($fileName)),
                'mimetype' => 'application/pdf','mime1' => 'application','mime2' => 'pdf','module' => 'ordering','unit_id' => $order->id
            );
            if($document = (new Upload)->create($input)):
                $order->contract_id = $document->id;
                $order->save();
                $order->touch();
            endif;
            unset($mpdf);
        endif;
        if (!empty($invoice_content)):
            $page_data = self::parseOrderHTMLDocument($invoice_content);
            $page_data['page_title'] = '';
            $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(View::make('templates/assets/invoice', $page_data)->render(), 2);
            $fileName = 'uploads/orders/invoice'.'-'.getOrderNumber($order).'.pdf';
            $mpdf->Output($fileName, 'F');
            $input = array(
                'path' => $fileName,'original_name' => 'invoice-'.getOrderNumber($order).'.pdf','filesize' => filesize(public_path($fileName)),
                'mimetype' => 'application/pdf','mime1' => 'application','mime2' => 'pdf','module' => 'ordering','unit_id' => $order->id
            );
            if($document = (new Upload)->create($input)):
                $order->invoice_id = $document->id;
                $order->save();
                $order->touch();
            endif;
            unset($mpdf);
        endif;
        if (!empty($act_content)):
            $page_data = self::parseOrderHTMLDocument($act_content);
            $page_data['page_title'] = '';
            $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(View::make('templates/assets/act', $page_data)->render(), 2);
            $fileName = 'uploads/orders/act'.'-'.getOrderNumber($order).'.pdf';
            $mpdf->Output($fileName, 'F');
            unset($mpdf);
            $input = array(
                'path' => $fileName,'original_name' => 'act-'.getOrderNumber($order).'.pdf','filesize' => filesize(public_path($fileName)),
                'mimetype' => 'application/pdf','mime1' => 'application','mime2' => 'pdf','module' => 'ordering','unit_id' => $order->id
            );
            if($document = (new Upload)->create($input)):
                $order->act_id = $document->id;
                $order->save();
                $order->touch();
            endif;
        endif;
        return TRUE;
    }
    /****************************************************************************/
    private function getDocumentVariables($extract = FALSE){

        $order = Orders::where('id',Config::get('show-document.order_id'))
            ->with('organization','individual','payment','listeners.course.direction','listeners.user_listener','listeners.user_listener','listeners.user_individual','listeners.final_test','payment_numbers')
            ->with(array('listeners.course.chapters'=>function($query){
                $query->orderBy('order');
                $query->with(array('lectures'=>function($query_lecture){
                    $query_lecture->orderBy('order');
                }));
                $query->with('test');
            }))
            ->with('listeners.course.test')
            ->first();
        $SummaZakaza = 0; $SpisokSluschateleyDlyaDogovora = array(); $listenersID = array();
        foreach($order->listeners as $listener):
            $SummaZakaza += $listener->price;
            $listenersID[$listener->user_id] = $listener->user_id;
        endforeach;
        $dateTime = new myDateTime();
        $variables = array(
            'stringCompileTemplate' => '',
            'page_title' => '',
            'NomerZakaza' => getOrderNumber($order),
            'NomerZakazaKorotkiy' => getShortOrderNumber($order),
            'NomerPrikazaOZachislinii' => getShortOrderNumberEnrollment($order),
            'NomerPrikazaObOkonchanii' => getShortOrderNumberCompletion($order),
            'SummaZakaza' => number_format($SummaZakaza,0,'.',' '),
            'SummaZakazaSlovami' => price2str($SummaZakaza),
            'KolichestvoSluschateley' => $order->listeners->count(),
            'KolichestvoSluschateleySlovami' => count($listenersID).' ('.count2str(count($listenersID)).') '.Lang::choice('слушателя|слушателей|слушателей',count($listenersID)),
            'DataOplatuZakaza' => $dateTime->setDateString($order->payment_date)->format('d.m.Y'),
            'DataNachalaObucheniya' => $dateTime->setDateString($order->study_date)->format('d.m.Y'),
            'DataOformleniyaZakaza' => $order->created_at->format('d.m.Y'),
            'DataOformleniyaZakazaSlovami' => $dateTime->setDateString($order->created_at)->months(),
            'DataZakrutiyaZakaza' => $dateTime->setDateString($order->close_date)->format('d.m.Y'),
            'DataZakrutiyaZakazaSlovami' => $dateTime->setDateString($order->close_date)->months(),

            'NazvanieOrganizacii' => empty($order->organization) ? '' : $order->organization->title,
            'FIOIndividualnogoSlushatelya' => empty($order->individual) ? '' : $order->individual->fio,
            'FIOIndividualnogoSlushatelyaVRoditelnomPadije' => empty($order->individual) ? '' : $order->individual->fio_rod,
            'Zakazchik' => empty($order->organization) ? $order->individual->fio : $order->organization->title,

            'ImyaOtvetstvennogoLicaOrganizacii' => empty($order->organization) ? '' : $order->organization->fio_manager,

            'ImyaOtvetstvennogoLicaInicialu' => empty($order->organization) ? preg_replace('/(\w+) (\w)\w+ (\w)\w+/iu', '$1 $2. $3.',$order->individual->fio) : preg_replace('/(\w+) (\w)\w+ (\w)\w+/iu', '$1 $2. $3.',$order->organization->fio_manager),
            'ImyaOtvetstvennogoLicaObratnueInicialu' => empty($order->organization) ? preg_replace('/(\w+) (\w)\w+ (\w)\w+/iu', '$2. $3. $1',$order->individual->fio) : preg_replace('/(\w+) (\w)\w+ (\w)\w+/iu', '$2. $3. $1',$order->organization->fio_manager),

            'DoljnostOtvetstvennogoLicaOrganizacii' => empty($order->organization) ? '' : $order->organization->manager,
            'DeystvuyucheeOsnovanieOrganizacii' => empty($order->organization) ? '' : $order->organization->statutory,
            'UridicheskiyAdresOrganizacii' => empty($order->organization) ? '' : $order->organization->uraddress,
            'PochtovuyAdressZakazchika' => empty($order->organization) ? $order->individual->postaddress : $order->organization->postaddress,
            'OGNROrganizacii' => empty($order->organization) ? '' : $order->organization->ogrn,
            'INNOrganizacii' => empty($order->organization) ? '' : $order->organization->inn,
            'KPPOrganizacii' => empty($order->organization) ? '' : $order->organization->kpp,
            'RaschetnuySchetOrganizacii' => empty($order->organization) ? '' : $order->organization->account_number,
            'KorrespondentskuyChetOrganizacii' => empty($order->organization) ? '' : $order->organization->account_kor_number,
            'NazvanieBankaOrganizacii' => empty($order->organization) ? '' : $order->organization->bank,
            'BIKOrganizacii' => empty($order->organization) ? '' : $order->organization->bik,
            'KontaktnuyTelefonZakazchika' => empty($order->organization) ? $order->individual->phone : $order->organization->phone,

            'SpisokSluschateley' => $order,
            'TablicaSluschateleyDlyaDogovora' => '',
            'SpisokSluschateleyDlyaScheta' => '',
            'SpisokSluschateleyDlyaAkta' => '',
            'SpisokSluschateleyDlyaPrikaza' => '',
            'RaspisanieObucheniyaPoKursu' => '',
            'SpisokSluschateleyDlyaJurnala' => '',

            'ImyaIndividualnogoZakazchika' => empty($order->individual) ? '' : $order->individual->fio,
            'DoljnostIndividualnogoZakazchika' => empty($order->individual) ? '' : $order->individual->position,
            'SeriaPasportaIndividualnogoZakazchika' => empty($order->individual) ? '' : $order->individual->passport_seria,
            'NomerPasportaIndividualnogoZakazchika' => empty($order->individual) ? '' : $order->individual->passport_number,
            'KemVudanPasportIndividualnogoZakazchika' => empty($order->individual) ? '' : $order->individual->passport_data,
            'KogdaVadanPasportIndividualnogoZakazchika' => empty($order->individual) ? '' : $order->individual->passport_date,
            'KodPodrazdeleniyaIndividualnogoZakazchika' => empty($order->individual) ? '' : $order->individual->code,

            'FIO_listener' => '', 'Address_listener' => '',
            'Phone_listener' => '', 'Email_listener' => '',
            'FIO_initial_listener' => '',

            'Kod_kursa' => '',
            'Nazvanie_kursa' => '',
            'DataOkonchaniyaObucheniya' => '',
            'KolichestvoChasovObucheniyaPoKursu' => '',
            'TablicaResultatovAttestacii' => '',

            'DataProvedeniyaAttestacii' => '',
            'DataProvedeniyaAttestacii' => '',
            'OcenkaAttestacii' => '',
            'OcenkaAttestaciiSlovami' => '',
            'MinimalnayaOcenkaAttestacii' => Config::get('site.success_test_percent') ? Config::get('site.success_test_percent') : 70,
            'KolichestvoVoprosiv' => 0,
            'KolichestvoPravilnuhOtvetov' => 0,

            'VsegoNaimenovaliy' => 0,'KolichestvoNaimenovaliy' => 0, 'NomerUdostovereniya' => ''
        );
        if ($extract):
            return extract($variables);
        else:
            return $variables;
        endif;
    }

    private function parseOrderHTMLDocument($content){

        if (!empty($content)):
            $template = public_path('uploads/orders/temporary/'.sha1($content));
            $Filesystem = new Illuminate\Filesystem\Filesystem();
            $compileString = (new \Illuminate\View\Compilers\BladeCompiler($Filesystem,storage_path('cache')))->compileString($content);
            $Filesystem->put($template,$compileString);
            $page_data = self::getDocumentVariables();
            $page_data['stringCompileTemplate'] = $compileString;
            $page_data['page_title'] = 'HTML версия';
            $page_data['template'] = $template;
            return $page_data;
        else:
            return FALSE;
        endif;
    }

    private function parseOrderWordDocument($template){

        if (File::exists(public_path($template)) === FALSE):
            return FALSE;
        endif;
        $filePath = public_path('uploads/orders/temporary/'.sha1(time()).'.docx');
        $document = new PHPWord();
        $document->setDefaultFontName('Times New Roman');
        $document->setDefaultFontSize(12);
        $document = $document->loadTemplate(public_path($template));
        $variables = self::getDocumentVariables();
        foreach($variables as $variable_index => $variable_value):
            $document->setValue($variable_index,$variable_value);
        endforeach;
        $document->save($filePath);
        return $filePath;
    }

    private function parseOrderPDFDocument($template){

        if (File::exists(public_path($template)) === FALSE):
            return FALSE;
        endif;
        $filePath = public_path('uploads/orders/temporary/'.sha1(time()).'.docx');
        $document = new PHPWord();
        $document->setDefaultFontName('Times New Roman');
        $document->setDefaultFontSize(12);
        $document = $document->loadTemplate(public_path($template));
        $variables = self::getDocumentVariables();
        foreach($variables as $variable_index => $variable_value):
            $document->setValue($variable_index,$variable_value);
        endforeach;
        $document->save($filePath);
        return $filePath;
    }
    /****************************************************************************/
}