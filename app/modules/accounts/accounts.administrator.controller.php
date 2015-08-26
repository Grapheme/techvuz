<?php

class AccountsAdministratorController extends BaseController {

    public static $name = 'admin';
    public static $group = 'accounts';

    /****************************************************************************/

    public static function returnRoutes($prefix = null) {
        $class = __CLASS__;

        if (Auth::check()):
            Route::group(array('before' => 'auth.status', 'prefix' => self::$name), function () use ($class) {
                Route::get('companies', array('as' => 'admin-companies-list', 'uses' => $class . '@CompaniesList'));
                Route::get('companies/profile/{company_id}', array('as' => 'admin-company-profile',
                    'uses' => $class . '@CompanyProfile'));
                Route::delete('companies/profile/{company_id}/delete', array('as' => 'admin-company-profile-delete',
                    'uses' => $class . '@CompanyProfileDelete'));

                Route::get('listeners', array('as' => 'admin-listeners-list', 'uses' => $class . '@ListenersList'));
                Route::get('listeners/profile/{listener_id}', array('as' => 'admin-listener-profile',
                    'uses' => $class . '@ListenerProfile'));
                Route::patch('listeners/profile/{listener_id}/delete', array('before' => 'csrf',
                    'as' => 'admin-listener-profile-delete', 'uses' => $class . '@ListenerProfileDelete'));
            });
        endif;
    }

    public static function returnShortCodes() {
        return NULL;
    }

    public static function returnActions() {
        return array(
            'view'   => 'Просмотр',
            'create' => 'Создание',
            'edit'   => 'Редактирование',
            'delete' => 'Удаление',
        );
    }

    public static function returnInfo() {
        return array(
            'name' => self::$name,
            'group' => self::$group,
            'title' => 'Пользователи Техвуза',
            'visible' => 1,
        );
    }

    public static function returnMenu() {
        return array(
            array(
                'title' => 'Компании',
                'link' => 'companies',
                'class' => 'fa-users',
                'permit' => 'view',
            ),
            array(
                'title' => 'Слушатели',
                'link' => 'listeners',
                'class' => 'fa-users',
                'permit' => 'view',
            ),
        );
    }

    /****************************************************************************/
    public function __construct() {

        $this->module = array(
            'name' => self::$name,
            'group' => self::$group,
            'rest' => self::$group,
            'tpl' => static::returnTpl(),
            'gtpl' => static::returnTpl(),
            'class' => __CLASS__,
        );
        View::share('module', $this->module);
    }
    /****************************************************************************/
    /******************************* КОМПАНИИ ***********************************/
    /****************************************************************************/
    public function CompaniesList() {

        $page_data = array(
            'page_title' => 'Список компаний',
            'page_description' => '',
            'page_keywords' => '',
            'companies' => array()
        );
        if ($companies_list = User_organization::orderBy('created_at', 'DESC')->with('orders.listeners', 'orders.payment_numbers')->get()):
            $companies = array();
            foreach ($companies_list as $index => $company):
                $companies[$index]['id'] = $company->id;
                $companies[$index]['title'] = $company->title;
                $companies[$index]['created_at'] = $company->created_at->timezone(Config::get('site.time_zone'));
                $companies[$index]['manager'] = $company->manager;
                $companies[$index]['fio_manager'] = $company->fio_manager;
                $companies[$index]['email'] = $company->email;
                $companies[$index]['orders_count'] = $company->orders->count();
                $companies[$index]['discount'] = $company->discount;
                $companies[$index]['orders_earnings'] = array('total_earnings' => 0, 'real_earnings' => 0);
                if ($company->orders->count()):
                    foreach ($company->orders as $order):
                        if ($order->listeners->count()):
                            foreach ($order->listeners as $listener):
                                $companies[$index]['orders_earnings']['total_earnings'] += $listener->price;
                            endforeach;
                        endif;
                        if ($order->payment_numbers->count()):
                            foreach ($order->payment_numbers as $payment_number):
                                $companies[$index]['orders_earnings']['real_earnings'] += $payment_number->price;
                            endforeach;
                        endif;
                    endforeach;
                endif;
            endforeach;
            $page_data['companies'] = $companies;
        endif;
        return View::make(Helper::acclayout('companies'), $page_data);
    }

    public function CompanyProfile($company_id) {

        $page_data = array(
            'page_title' => 'Просмотр профиля компании',
            'page_description' => '',
            'page_keywords' => '',
            'profile' => array(),
            'listeners' => array(),
            'orders' => array(),

        );
        if ($page_data['profile'] = User_organization::where('id', $company_id)->first()):
            $page_data['listeners'] = User_listener::where('organization_id', $company_id)->orderBy('created_at', 'DESC')->get();
            $page_data['orders'] = Orders::where('user_id', $company_id)->with('payment')->with('listeners')->get();
            return View::make(Helper::acclayout('company-profile'), $page_data);
        else:
            App::abort(404);
        endif;
    }

    /****************************************************************************/
    /****************************** СЛУШАТЕЛИ ***********************************/
    /****************************************************************************/
    public function ListenersList() {

        $page_data = array(
            'page_title' => 'Список слушателей',
            'page_description' => '',
            'page_keywords' => '',
            'listeners' => array()
        );
        $listeners = array();
        if ($companies_listeners = User_listener::orderBy('created_at', 'DESC')->with('organization')->get()):
            $listeners = array_merge($listeners, $companies_listeners->toArray());
        endif;
        if ($individual_listeners = User_individual::orderBy('created_at', 'DESC')->get()):
            $listeners = array_merge($listeners, $individual_listeners->toArray());
        endif;
        if (count($listeners)):
            foreach ($listeners as $key => $row):
                @$created_at[$key] = $row['created_at'];
            endforeach;
            array_multisort($created_at, SORT_DESC, $listeners);
        endif;
        $page_data['listeners'] = $listeners;
        return View::make(Helper::acclayout('listeners'), $page_data);
    }

    public function ListenerProfile($listener_id) {

        $page_data = array(
            'page_title' => 'Профиль слушателя',
            'page_description' => '',
            'page_keywords' => '',
            'profile' => array()
        );
        if ($account = User::where('id', $listener_id)->first()):
            if ($account->group_id == 5):
                $page_data['profile'] = User_listener::where('id', $listener_id)->with('organization')->first();
                $page_data['profile']['group_id'] = 5;
            elseif ($account->group_id == 6):
                $page_data['profile'] = User_individual::where('id', $listener_id)->first();
                $page_data['profile']['group_id'] = 6;
            endif;
            return View::make(Helper::acclayout('listener-profile'), $page_data);
        else:
            App::abort(404);
        endif;
    }
}