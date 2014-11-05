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
        if (!Orders::where('id',$order_id)->where('user_id',Auth::user()->id)->where('completed',1)->where('archived',0)->exists()):
            return Redirect::route('organization-orders');
        endif;
        if($document = Dictionary::valueBySlugs('order-documents','order-documents-contract')):
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
                    if($filePath = self::parseOrderWordDocument($word_template)):
                        return Response::download($filePath,'Договор.docx');
                    endif;
                    break;
                case 'word':
                    if($filePath = self::parseOrderWordDocument($word_template)):
                        return Response::download($filePath,'Договор.docx');
                    endif;
                    break;
                default: App:abort(404);
            endswitch;
        endif;
        return Redirect::route('organization-orders');
    }

    public function organizationOrderInvoice($order_id,$format){

        if (!User_organization::where('id',Auth::user()->id)->pluck('moderator_approve')):
            return Redirect::route('organization-orders');
        endif;
        if (!Orders::where('id',$order_id)->where('user_id',Auth::user()->id)->where('completed',1)->where('archived',0)->exists()):
            return Redirect::route('organization-orders');
        endif;
        if($document = Dictionary::valueBySlugs('order-documents','order-documents-invoice')):
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
                    if($filePath = self::parseOrderWordDocument($word_template)):
                        return Response::download($filePath,'Счет.docx');
                    endif;
                    break;
                case 'word':
                    if($filePath = self::parseOrderWordDocument($word_template)):
                        return Response::download($filePath,'Счет.docx');
                    endif;
                    break;
                default: App:abort(404);
            endswitch;
        endif;
        return Redirect::route('organization-orders');
    }

    public function organizationOrderAct($order_id,$format){

        $account = User_organization::where('id',Auth::user()->id)->first();
        if (!$account->moderator_approve):
            return Redirect::route('organization-orders');
        endif;
        if (!$order = Orders::where('id',$order_id)->where('user_id',Auth::user()->id)->where('completed',1)->where('archived',0)->first()):
            return Redirect::route('organization-orders');
        endif;
        $order_listeners = Orders::where('id',$order->id)->first()->listeners()->with('course','user_listener')->get();
        $count_listeners = $order_listeners->count();
        $total_summa = 0;
        foreach($order_listeners as $order_listener):
            $total_summa += $order_listener->price;
        endforeach;
        if($document = Dictionary::valueBySlugs('order-documents','order-documents-act')):
            $fields = modifyKeys($document->fields,'key');
            $document_content = isset($fields['content']) ? $fields['content']->value : '';
            if (!empty($document_content)):
                $page_data = array(
                    'page_title' => Lang::get('seo.COMPANY_ORDER.title'),
                    'page_description' => Lang::get('seo.COMPANY_ORDER.description'),
                    'page_keywords' => Lang::get('seo.COMPANY_ORDER.keywords'),
                    'order' => $order->toArray(),
                    'account' => $account->toArray(),
                    'count_listeners' => $count_listeners,
                    'total_summa' => $total_summa,
                    'template' => storage_path('views/'.sha1($order_id.'order-documents-act'))
                );
                self::parseOrderDocument($page_data['template'],$document_content);
                switch($format):
                    case 'html': return View::make(Helper::acclayout('documents'),$page_data);
                    case 'pdf' :
                        $pdf = PDF::loadView(Helper::acclayout('documents'), $page_data);
                        if ($this->document_stream):
                            return $pdf->stream('act-'.$order_id.'.pdf');
                        else:
                            return $pdf->download('act-'.$order_id.'.pdf');
                        endif;
                    case 'word':
                        break;
                    default: App:abort(404);
                endswitch;
            endif;
        endif;
        App::abort(404);
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

        if (!$order = Orders::where('id',$order_id)->where('completed',1)->with('organization','individual')->first()):
            return Redirect::route('moderator-orders');
        endif;
        $account = NULL; $account_type = NULL; $template = '';
        if (!empty($order->organization)):
            $account = User_organization::where('id',$order->user_id)->first();
            $account_type = 4;
            $template = 'templates.organization.documents';
        elseif(!empty($order->individual)):
            $account = User_individual::where('id',$order->user_id)->first();
            $account_type = 6;
            $template = 'templates.individual.documents';
        endif;
        $order_listeners = Orders::where('id',$order->id)->first()->listeners()->with('course','user_listener')->get();
        $count_listeners = $order_listeners->count();
        $total_summa = 0;
        foreach($order_listeners as $order_listener):
            $total_summa += $order_listener->price;
        endforeach;
        if($document = Dictionary::valueBySlugs('order-documents','order-documents-contract')):
            $fields = modifyKeys($document->fields,'key');
            $document_content = isset($fields['content']) ? $fields['content']->value : '';
            if (!empty($document_content)):
                $page_data = array(
                    'page_title' => 'Договор',
                    'page_description' => '',
                    'page_keywords' => '',
                    'order' => $order->toArray(),
                    'account' => $account->toArray(),
                    'count_listeners' => $count_listeners,
                    'total_summa' => $total_summa,
                    'template' => storage_path('views/'.sha1($order_id.'order-documents-contract'))
                );
                self::parseOrderDocument($page_data['template'],$document_content);
                switch($format):
                    case 'html':return View::make($template,$page_data);
                    case 'pdf' :
                        $pdf = PDF::loadView($template, $page_data);
                        if ($this->document_stream):
                            return $pdf->stream('contract-'.$order_id.'.pdf');
                        else:
                            return $pdf->download('contract-'.$order_id.'.pdf');
                        endif;
                    case 'word':
                        break;
                    default: App:abort(404);
                endswitch;
            endif;
        endif;
        App::abort(404);
    }

    public function moderatorOrderInvoice($order_id,$format){

        $account = User_organization::where('id',Auth::user()->id)->first();
        if (!$account->moderator_approve):
            return Redirect::route('organization-orders');
        endif;
        if (!$order = Orders::where('id',$order_id)->where('user_id',Auth::user()->id)->where('completed',1)->where('archived',0)->first()):
            return Redirect::route('organization-orders');
        endif;
        $order_listeners = Orders::where('id',$order->id)->first()->listeners()->with('course','user_listener')->get();
        $count_listeners = $order_listeners->count();
        $total_summa = 0;
        foreach($order_listeners as $order_listener):
            $total_summa += $order_listener->price;
        endforeach;
        if($document = Dictionary::valueBySlugs('order-documents','order-documents-invoice')):
            $fields = modifyKeys($document->fields,'key');
            $document_content = isset($fields['content']) ? $fields['content']->value : '';
            if (!empty($document_content)):
                $page_data = array(
                    'page_title' => Lang::get('seo.COMPANY_ORDER.title'),
                    'page_description' => Lang::get('seo.COMPANY_ORDER.description'),
                    'page_keywords' => Lang::get('seo.COMPANY_ORDER.keywords'),
                    'order' => $order->toArray(),
                    'account' => $account->toArray(),
                    'count_listeners' => $count_listeners,
                    'total_summa' => $total_summa,
                    'template' => storage_path('views/'.sha1($order_id.'order-documents-invoice'))
                );
                self::parseOrderDocument($page_data['template'],$document_content);
                switch($format):
                    case 'html': return View::make(Helper::acclayout('documents'),$page_data);
                    case 'pdf' :
                        $pdf = PDF::loadView(Helper::acclayout('documents'), $page_data);
                        if ($this->document_stream):
                            return $pdf->stream('invoice-'.$order_id.'.pdf');
                        else:
                            return $pdf->download('invoice-'.$order_id.'.pdf');
                        endif;
                    case 'word':
                        break;
                    default: App:abort(404);
                endswitch;
            endif;
        endif;
        App::abort(404);
    }

    public function moderatorOrderAct($order_id,$format){

        $account = User_organization::where('id',Auth::user()->id)->first();
        if (!$account->moderator_approve):
            return Redirect::route('organization-orders');
        endif;
        if (!$order = Orders::where('id',$order_id)->where('user_id',Auth::user()->id)->where('completed',1)->where('archived',0)->first()):
            return Redirect::route('organization-orders');
        endif;
        $order_listeners = Orders::where('id',$order->id)->first()->listeners()->with('course','user_listener')->get();
        $count_listeners = $order_listeners->count();
        $total_summa = 0;
        foreach($order_listeners as $order_listener):
            $total_summa += $order_listener->price;
        endforeach;
        if($document = Dictionary::valueBySlugs('order-documents','order-documents-act')):
            $fields = modifyKeys($document->fields,'key');
            $document_content = isset($fields['content']) ? $fields['content']->value : '';
            if (!empty($document_content)):
                $page_data = array(
                    'page_title' => Lang::get('seo.COMPANY_ORDER.title'),
                    'page_description' => Lang::get('seo.COMPANY_ORDER.description'),
                    'page_keywords' => Lang::get('seo.COMPANY_ORDER.keywords'),
                    'order' => $order->toArray(),
                    'account' => $account->toArray(),
                    'count_listeners' => $count_listeners,
                    'total_summa' => $total_summa,
                    'template' => storage_path('views/'.sha1($order_id.'order-documents-act'))
                );
                self::parseOrderDocument($page_data['template'],$document_content);
                switch($format):
                    case 'html': return View::make(Helper::acclayout('documents'),$page_data);
                    case 'pdf' :
                        $pdf = PDF::loadView(Helper::acclayout('documents'), $page_data);
                        if ($this->document_stream):
                            return $pdf->stream('act-'.$order_id.'.pdf');
                        else:
                            return $pdf->download('act-'.$order_id.'.pdf');
                        endif;
                    case 'word':
                        break;
                    default: App:abort(404);
                endswitch;
            endif;
        endif;
        App::abort(404);
    }
    /****************************************************************************/

    private function getDocumentVariables($extract = FALSE){

        $order = Orders::where('id',Config::get('show-document.order_id'))->with('organization','individual','payment','listeners.course','listeners.listener','listeners.final_test','payment_numbers')->first();
        $dateTime = new myDateTime();
        $variables = array(
            'page_title' => '',
            'NomerZakaza' => getOrderNumber($order),
            'SummaZakaza' => 1547,
            'SummaZakazaSlovami' => num2str(1547),
            'DataOplatuZakaza' => $dateTime->setDateString($order->payment_date)->format('d.m.y'),
            'DataOformleniyaZakaza' => $order->created_at->format('d.m.y'),
            'DataOformleniyaZakazaSlovami' => $dateTime->setDateString($order->created_at)->months(),
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
}