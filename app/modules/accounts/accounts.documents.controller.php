<?php

class AccountsDocumentsController extends BaseController {

    public static $name = 'documents';
    public static $group = 'accounts';
    public static $entity = 'documents';
    public static $entity_name = 'Документы';

    /****************************************************************************/

    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;
        if (Auth::check()):
            Route::group(array('before' => 'auth.status', 'prefix' => 'organization'), function() use ($class) {
                Route::get('order/{order_id}/contract/{format}', array('as' => 'company-order-contract', 'uses' => $class . '@CompanyOrderContract'));
                Route::get('order/{order_id}/invoice/{format}', array('as' => 'company-order-invoice', 'uses' => $class . '@CompanyOrderInvoice'));
                Route::get('order/{order_id}/act/{format}', array('as' => 'company-order-act', 'uses' => $class . '@CompanyOrderAct'));
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

    public function CompanyOrderContract($order_id,$format){

        $account = User_organization::where('id',Auth::user()->id)->first();
        if (!$account->moderator_approve):
            return Redirect::route('company-orders');
        endif;
        if (!$order = Orders::where('id',$order_id)->where('user_id',Auth::user()->id)->where('completed',1)->where('archived',0)->first()):
            return Redirect::route('company-orders');
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
                    'page_title' => Lang::get('seo.COMPANY_ORDER.title'),
                    'page_description' => Lang::get('seo.COMPANY_ORDER.description'),
                    'page_keywords' => Lang::get('seo.COMPANY_ORDER.keywords'),
                    'order' => $order->toArray(),
                    'account' => $account->toArray(),
                    'count_listeners' => $count_listeners,
                    'total_summa' => $total_summa,
                    'template' => storage_path('views/'.sha1($order_id.'order-documents-contract'))
                );
                self::parseOrderDocument($page_data['template'],$document_content);
                switch($format):
                    case 'html': return View::make(Helper::acclayout('documents'),$page_data);
                    case 'pdf' :
                                 $pdf = PDF::loadView(Helper::acclayout('documents'), $page_data);
                                 return $pdf->download('contract-'.$order_id.'.pdf');
                    case 'word':
                                 break;
                    default: App:abort(404);
                endswitch;

            endif;
        endif;
        App::abort(404);
    }

    public function CompanyOrderInvoice($order_id,$format){

        $account = User_organization::where('id',Auth::user()->id)->first();
        if (!$account->moderator_approve):
            return Redirect::route('company-orders');
        endif;
        if (!$order = Orders::where('id',$order_id)->where('user_id',Auth::user()->id)->where('completed',1)->where('archived',0)->first()):
            return Redirect::route('company-orders');
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
                return View::make(Helper::acclayout('documents'),$page_data);
            endif;
        endif;
        App::abort(404);
    }

    public function CompanyOrderAct($order_id,$format){

        $account = User_organization::where('id',Auth::user()->id)->first();
        if (!$account->moderator_approve):
            return Redirect::route('company-orders');
        endif;
        if (!$order = Orders::where('id',$order_id)->where('user_id',Auth::user()->id)->where('completed',1)->where('archived',0)->first()):
            return Redirect::route('company-orders');
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
                return View::make(Helper::acclayout('documents'),$page_data);
            endif;
        endif;
        App::abort(404);
    }

    public function CompanyOrderCertificateFirst($order_id,$course_id,$listener_id){

        $account = User_organization::where('id',Auth::user()->id)->first();
        if (!$account->moderator_approve):
            return Redirect::route('company-orders');
        endif;
        if (!OrderListeners::where('order_id',$order_id)->where('course_id',$course_id)->where('user_id',$listener_id)->where('over_status',1)->exists()):
            return Redirect::route('company-orders');
        endif;
        if (!$order = Orders::where('id',$order_id)->where('user_id',Auth::user()->id)->where('completed',1)->where('archived',0)->first()):
            return Redirect::route('company-orders');
        endif;
        if (!$listener = User_listener::where('id',$listener_id)->where('organization_id',Auth::user()->id)->first()):
            return Redirect::route('company-orders');
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

    public function CompanyOrderCertificateSecond($order_id,$course_id,$listener_id){

        $account = User_organization::where('id',Auth::user()->id)->first();
        if (!$account->moderator_approve):
            return Redirect::route('company-orders');
        endif;
        if (!OrderListeners::where('order_id',$order_id)->where('course_id',$course_id)->where('user_id',$listener_id)->where('over_status',1)->exists()):
            return Redirect::route('company-orders');
        endif;
        if (!$order = Orders::where('id',$order_id)->where('user_id',Auth::user()->id)->where('completed',1)->where('archived',0)->first()):
            return Redirect::route('company-orders');
        endif;
        if (!$listener = User_listener::where('id',$listener_id)->where('organization_id',Auth::user()->id)->first()):
            return Redirect::route('company-orders');
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

    private function parseOrderDocument($template,$content){

        $Filesystem = new Illuminate\Filesystem\Filesystem();
        $compileString = (new \Illuminate\View\Compilers\BladeCompiler($Filesystem,storage_path('cache')))->compileString($content);
        $Filesystem->put($template,$compileString);
        return $compileString;
    }
}