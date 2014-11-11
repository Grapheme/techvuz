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
        if (Auth::check()):
            $prefix = Auth::user()->group()->pluck('name');
            Route::group(array('before' => 'auth.status', 'prefix' => $prefix), function() use ($class,$prefix) {
                Route::get('order/{order_id}/contract/{format}', array('as' => $prefix.'-order-contract', 'uses' => $class . '@'.$prefix.'OrderContract'));
                Route::get('order/{order_id}/invoice/{format}', array('as' => $prefix.'-order-invoice', 'uses' => $class . '@'.$prefix.'OrderInvoice'));
                Route::get('order/{order_id}/act/{format}', array('as' => $prefix.'-order-act', 'uses' => $class . '@'.$prefix.'OrderAct'));
                Route::get('order/{order_id}/course/{course_id}/listener/{listener_id}/certificate/first', array('as' => $prefix.'-order-certificate-first', 'uses' => $class . '@'.$prefix.'OrderCertificateFirst'));
                Route::get('order/{order_id}course/{course_id}/listener/{listener_id}certificate/second', array('as' => $prefix.'-order-certificate-second', 'uses' => $class . '@'.$prefix.'OrderCertificateSecond'));
            });
//            Route::get('order/{order_id}/{document}/save',array('as' => $prefix.'-order-contract', 'uses' => $class . '@'.$prefix.'OrderContract'));
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
        return $this->organizationShowDocument($order_id,$format,'contract');
    }

    public function organizationOrderInvoice($order_id,$format){

        return $this->organizationShowDocument($order_id,$format,'invoice');
    }

    public function organizationOrderAct($order_id,$format){

        return $this->organizationShowDocument($order_id,$format,'act');
    }

    public function organizationOrderCertificateFirst($order_id,$course_id,$listener_id){

        $account = User_organization::where('id',Auth::user()->id)->first();
        if (!$account->moderator_approve):
            return Redirect::route('organization-orders');
        endif;
        if (!OrderListeners::where('order_id',$order_id)->where('course_id',$course_id)->where('user_id',$listener_id)->where('over_status',1)->exists()):
            return Redirect::route('organization-orders');
        endif;
        if (!$order = Orders::where('id',$order_id)->where('user_id',Auth::user()->id)->where('completed',1)->where('archived',0)->first()):
            return Redirect::route('organization-orders');
        endif;
        if (!$listener = User_listener::where('id',$listener_id)->where('organization_id',Auth::user()->id)->first()):
            return Redirect::route('organization-orders');
        endif;
        if($document = Dictionary::valueBySlugs('order-documents','order-documents-certificate-first')):
            $fields = modifyKeys($document->fields,'key');
            $document_content = isset($fields['content']) ? $fields['content']->value : '';
            if (!empty($document_content)):
                $page_data = array(
                    'page_title' => Lang::get('seo.COMPANY_ORDER.title'),
                    'page_description' => Lang::get('seo.COMPANY_ORDER.description'),
                    'page_keywords' => Lang::get('seo.COMPANY_ORDER.keywords'),
                    'order' => $order->toArray(),
                    'account' => $account->toArray(),
                    'listener' => $listener->toArray(),
                    'template' => storage_path('views/'.sha1($order_id.$listener_id.'order-documents-certificate-first'))
                );
                self::parseOrderDocument($page_data['template'],$document_content);
                return View::make(Helper::acclayout('documents'),$page_data);
            endif;
        endif;
        App::abort(404);
    }

    public function organizationOrderCertificateSecond($order_id,$course_id,$listener_id){

        $account = User_organization::where('id',Auth::user()->id)->first();
        if (!$account->moderator_approve):
            return Redirect::route('organization-orders');
        endif;
        if (!OrderListeners::where('order_id',$order_id)->where('course_id',$course_id)->where('user_id',$listener_id)->where('over_status',1)->exists()):
            return Redirect::route('organization-orders');
        endif;
        if (!$order = Orders::where('id',$order_id)->where('user_id',Auth::user()->id)->where('completed',1)->where('archived',0)->first()):
            return Redirect::route('organization-orders');
        endif;
        if (!$listener = User_listener::where('id',$listener_id)->where('organization_id',Auth::user()->id)->first()):
            return Redirect::route('organization-orders');
        endif;
        if($document = Dictionary::valueBySlugs('order-documents','order-documents-certificate-first')):
            $fields = modifyKeys($document->fields,'key');
            $document_content = isset($fields['content']) ? $fields['content']->value : '';
            if (!empty($document_content)):
                $page_data = array(
                    'page_title' => Lang::get('seo.COMPANY_ORDER.title'),
                    'page_description' => Lang::get('seo.COMPANY_ORDER.description'),
                    'page_keywords' => Lang::get('seo.COMPANY_ORDER.keywords'),
                    'order' => $order->toArray(),
                    'account' => $account->toArray(),
                    'listener' => $listener->toArray(),
                    'template' => storage_path('views/'.sha1($order_id.$listener_id.'order-documents-certificate-first'))
                );
                self::parseOrderDocument($page_data['template'],$document_content);
                return View::make(Helper::acclayout('documents'),$page_data);
            endif;
        endif;
        App::abort(404);
    }

    private function organizationShowDocument($order_id,$format,$document_type = 'contract'){

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
            $word_template = FALSE;
            if($word_template_id = isset($fields['word_template']) ? $fields['word_template']->value : ''):
                $word_template = Upload::where('id',$word_template_id)->pluck('path');
            endif;
            Config::set('show-document.order_id', $order_id);
            switch($format):
                case 'html':
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        return View::make(Helper::acclayout('documents'),$page_data);
                    endif;
                    break;
                case 'pdf' :
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        $page_data['page_title'] = '';
                        $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
                        $mpdf->charset_in = 'cp1251';
                        $mpdf->SetDisplayMode('fullpage');
                        $mpdf->WriteHTML(View::make(Helper::acclayout('documents'), $page_data)->render(), 2);
                        return $mpdf->Output($document_type.'-№'.getOrderNumber($order).'.pdf', 'D');
                        #$pdf = PDF::loadView(Helper::acclayout('documents'), $page_data);
                        #return $pdf->download($document_type.'-№'.getOrderNumber($order).'.pdf');
                        #return $pdf->stream('act-'.$order_id.'.pdf');
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
    public function individualOrderContract($order_id,$format){

        $account = User_individual::where('id',Auth::user()->id)->first();
        if (!$account->moderator_approve):
//            return Redirect::route('individual-orders');
        endif;
        App::abort(404);
    }

    public function individualOrderInvoice($order_id,$format){

        $account = User_individual::where('id',Auth::user()->id)->first();
        if (!$account->moderator_approve):
//            return Redirect::route('individual-orders');
        endif;
        App::abort(404);
    }

    public function individualOrderAct($order_id,$format){

        $account = User_individual::where('id',Auth::user()->id)->first();
        if (!$account->moderator_approve):
//            return Redirect::route('individual-orders');
        endif;
        App::abort(404);
    }
    /****************************************************************************/
    public function moderatorOrderContract($order_id,$format){
        return $this->moderatorShowDocument($order_id,$format,'contract');
    }

    public function moderatorOrderInvoice($order_id,$format){

        return $this->moderatorShowDocument($order_id,$format,'invoice');

    }

    public function moderatorOrderAct($order_id,$format){

        return $this->moderatorShowDocument($order_id, $format, 'act');

    }

    public function moderatorShowDocument($order_id,$format,$document_type){

        if (!$order = Orders::where('id',$order_id)->where('completed',1)->with('organization','individual',$document_type)->first()):
            return Redirect::route('moderator-orders-list');
        endif;
        $account = NULL; $account_type = NULL; $template = '';
        if (!empty($order->organization)):
            $account = User_organization::where('id',$order->user_id)->first();
            $account_type = 4;
            $template = 'templates.organization.documents';
            $document = Dictionary::valueBySlugs('order-documents','order-documents-'.$document_type);
        elseif(!empty($order->individual)):
            $account = User_individual::where('id',$order->user_id)->first();
            $account_type = 6;
            $template = 'templates.individual.documents';
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
                        return View::make(Helper::acclayout('documents'),$page_data);
                    endif;
                    break;
                case 'pdf' :
                    $document_content = isset($fields['content']) ? $fields['content']->value : '';
                    if($page_data = self::parseOrderHTMLDocument($document_content)):
                        $page_data['page_title'] = '';
                        $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
                        $mpdf->charset_in = 'cp1251';
                        $mpdf->SetDisplayMode('fullpage');
                        $mpdf->WriteHTML(View::make(Helper::acclayout('documents'), $page_data)->render(), 2);
                        return $mpdf->Output($document_type.'-№'.getOrderNumber($order).'.pdf', 'D');
                        #$pdf = PDF::loadView(Helper::acclayout('documents'), $page_data);
                        #return $pdf->download($document_type.'-№'.getOrderNumber($order).'.pdf');
                        #return $pdf->stream('act-'.$order_id.'.pdf');
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

        $order = Orders::where('id',$order_id)->where('close_status',0)->with('contract','invoice','act','organization','individual')->first();
        $contract_content = $invoice_content = $act_content = '';
        if (!empty($order->organization)):
            $account = $order->organization;
            $account_type = 4;
            $template = 'templates.organization.documents';
        elseif(!empty($order->individual)):
            $account = $order->individual;
            $account_type = 6;
            $template = 'templates.individual.documents';
        endif;
        if (empty($order->contract_id)):
            if ($account_type == 4):
                $document = Dictionary::valueBySlugs('order-documents','order-documents-contract');
            else:
                $document = Dictionary::valueBySlugs('order-documents','fiz-order-documents-contract');
            endif;
            if ($document->exists && !empty($document->fields)):
                $fields = modifyKeys($document->fields,'key');
                $contract_content = $fields['content']->value;
            endif;
        endif;
        if (empty($order->invoice_id)):
            if ($account_type == 4):
                $document = Dictionary::valueBySlugs('order-documents','order-documents-invoice');
            else:
                $document = Dictionary::valueBySlugs('order-documents','fiz-order-documents-invoice');
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
                $document = Dictionary::valueBySlugs('order-documents','fiz-order-documents-act');
            endif;
            if ($document->exists && !empty($document->fields)):
                $fields = modifyKeys($document->fields,'key');
                $act_content = $fields['content']->value;
            endif;
        endif;
        Config::set('show-document.order_id', $order->id);
        if (!empty($contract_content)):
            $page_data = self::parseOrderHTMLDocument($contract_content);
            $fileName = 'uploads/orders/contract'.'-'.getOrderNumber($order).'.pdf';
            $page_data['page_title'] = '';
            $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
            $mpdf->charset_in = 'cp1251';
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(View::make($template, $page_data)->render(), 2);
            $mpdf->Output($fileName, 'F');
            unset($mpdf);
            $input = array(
                'path' => $fileName,'original_name' => 'contract-'.getOrderNumber($order).'.pdf','filesize' => filesize(public_path($fileName)),
                'mimetype' => 'application/pdf','mime1' => 'application','mime2' => 'pdf','module' => 'ordering','unit_id' => $order->id
            );
            if($document = (new Upload)->create($input)):
                $order->contract_id = $document->id;
                $order->save();
                $order->touch();
            endif;
        endif;
        if (!empty($invoice_content)):
            $page_data = self::parseOrderHTMLDocument($invoice_content);
            $fileName = 'uploads/orders/invoice'.'-'.getOrderNumber($order).'.pdf';
            $page_data['page_title'] = '';
            $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
            $mpdf->charset_in = 'cp1251';
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(View::make($template, $page_data)->render(), 2);
            $mpdf->Output($fileName, 'F');
            unset($mpdf);
            $input = array(
                'path' => $fileName,'original_name' => 'contract-'.getOrderNumber($order).'.pdf','filesize' => filesize(public_path($fileName)),
                'mimetype' => 'application/pdf','mime1' => 'application','mime2' => 'pdf','module' => 'ordering','unit_id' => $order->id
            );
            if($document = (new Upload)->create($input)):
                $order->invoice_id = $document->id;
                $order->save();
                $order->touch();
            endif;
        endif;
        if (!empty($act_content)):
            $page_data = self::parseOrderHTMLDocument($act_content);
            $page_data['page_title'] = '';
            $fileName = 'uploads/orders/act'.'-'.getOrderNumber($order).'.pdf';
            $page_data['page_title'] = '';
            $mpdf = new mPDF('utf-8', 'A4', '8', '', 10, 10, 7, 7, 10, 10);
            $mpdf->charset_in = 'cp1251';
            $mpdf->SetDisplayMode('fullpage');
            $mpdf->WriteHTML(View::make($template, $page_data)->render(), 2);
            $mpdf->Output($fileName, 'F');
            unset($mpdf);
            $input = array(
                'path' => $fileName,'original_name' => 'contract-'.getOrderNumber($order).'.pdf','filesize' => filesize(public_path($fileName)),
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

        $order = Orders::where('id',Config::get('show-document.order_id'))->with('organization','individual','payment','listeners.course','listeners.user_listener','listeners.user_listener','listeners.user_individual','listeners.final_test','payment_numbers')->first();
        $SummaZakaza = 0; $SpisokSluschateleyDlyaDogovora = array();
        foreach($order->listeners as $listener):
            $SummaZakaza += $listener->price;
            $SpisokSluschateleyDlyaDogovora[$listener->user_id]['listener'] = !empty($listener->user_listener) ? $listener->user_listener->toArray() : array();
            $SpisokSluschateleyDlyaDogovora[$listener->user_id]['individual'] = !empty($listener->user_individual) ? $listener->user_individual->toArray() : array();
            $SpisokSluschateleyDlyaDogovora[$listener->user_id]['course'][] = !empty($listener->course) ? $listener->course->toArray() : array();
        endforeach;
        $dateTime = new myDateTime();
        $variables = array(
            'stringCompileTemplate' => '',
            'page_title' => '',
            'NomerZakaza' => getOrderNumber($order),
            'SummaZakaza' => number_format($SummaZakaza,0,'.',' '),
            'SummaZakazaSlovami' => num2str($SummaZakaza),
            'KolichestvoSluschateley' => $order->listeners->count(),
            'DataOplatuZakaza' => $dateTime->setDateString($order->payment_date)->format('d.m.y'),
            'DataOformleniyaZakaza' => $order->created_at->format('d.m.y'),
            'DataOformleniyaZakazaSlovami' => $dateTime->setDateString($order->created_at)->months(),
            'DataZakrutiyaZakaza' => $dateTime->setDateString($order->close_date)->format('d.m.y'),
            'DataZakrutiyaZakazaSlovami' => $dateTime->setDateString($order->close_date)->months(),
            'NazvanieOrganizacii' => empty($order->organization) ? '' : $order->organization->title,
            'ImyaOtvetstvennogoLicaOrganizacii' => empty($order->organization) ? '' : $order->organization->fio_manager,
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

            'SpisokSluschateleyDlyaDogovora' => $SpisokSluschateleyDlyaDogovora,
            'TablicaSluschateleyDlyaDogovora' => '',

            'ImyaIndividualnogoZakazchika' => empty($order->individual) ? '' : $order->individual->fio,
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