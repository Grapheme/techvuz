@extends('templates.organization')
@section('style') @stop
@section('content')
<main class="cabinet">
    @if(@$page['active_status']['status'] === FALSE)
    <div class="banner banner--red">
        @if(Session::get('message'))
        <span>{{ Session::get('message') }}</span>
        @else
        <span>{{ @$page['active_status']['message'] }}</span>
        <div>Для повторной отправки активационных данных нажмите на <a href="{{ URL::route('activation-repeated-sending-letter') }}">ссылку</a>.</div>
        @endif
    </div>
    @endif
    <h2>{{ User_organization::where('id',Auth::user()->id)->first()->title }}</h2>
    <div class="tabs cabinet-tabs">
        <ul>
            <li><a href="#tabs-1"><span class="icon icon-zakaz"></span> Заказы</a></li>
            <li><a href="#tabs-2"><span class="icon icon-slysh"></span> Сотрудники</a></li>
            <li><a href="#tabs-3"><span class="icon icon-obych"></span> Обучение</a></li>
            <li><a href="#tabs-4"><span class="icon icon-yved"></span> Уведомления</a></li>
        </ul>
        <div id="tabs-1">
            <a href="{{ URL::route('page','catalog') }}" class="btn btn-top-margin btn--bordered btn--blue pull-right">Новый заказ</a>
            <h3>Заказы</h3>
            <div class="tabs usual-tabs">
                <ul>
                    <li>
                        <a href="#tabs-11">Новые <span class="filter-count">1</span></a>
                    </li>
                    <li>
                        <a href="#tabs-12">Активные <span class="filter-count">13</span></a>
                    </li>
                    <li>
                        <a href="#tabs-13">Завершенные <span class="filter-count">9</span></a>
                    </li>
                    <li>
                        <a href="#tabs-14">Все <span class="filter-count">25</span></a>
                    </li>
                </ul>
                <div id="tabs-11">
                    <ul class="orders-ul">
                        <li class="orders-li new-order">
                            <div class="orders-li-head">
                                <h4>
                                    Заказ №432
                                </h4>
                                <div class="orders-status">
                                    Не оформлен
                                </div>
                            </div>
                            <div class="orders-li-body">
                                <div class="orders-price">
                                    <span class="start-price">0.-</span> | <span class="end-price">12 000.-</span>
                                </div>
                                <div class="orders-date">
                                    Заказ создан:
                                    <div>
                                        29.09.14 в 10:15
                                    </div>
                                </div>
                                <div class="orders-package">
                                    <div>В заказе <a href="#">12 курсов</a></div>
                                    <div>для <a href="#">4 слушателей</a></div>
                                </div>
                                <div class="orders-actions">
                                    <div class="btn btn--bordered btn--blue">Оформить заказ</div>
                                    <div class="orders-delete js-delete-order" title="Удалить заказ">
                                        <span class="icon icon-korzina"></span>
                                    </div>
                                </div>
                            </div>
                        <li class="orders-li new-order">
                            <div class="orders-li-head">
                                <h4>
                                    Заказ №432
                                </h4>
                                <div class="orders-status">
                                    Не оформлен
                                </div>
                            </div>
                            <div class="orders-li-body">
                                <div class="orders-price">
                                    <span class="start-price">0.-</span> | <span class="end-price">12 000.-</span>
                                </div>
                                <div class="orders-date">
                                    Заказ создан:
                                    <div>
                                        29.09.14 в 10:15
                                    </div>
                                </div>
                                <div class="orders-package">
                                    <div>В заказе <a href="#">12 курсов</a></div>
                                    <div>для <a href="#">4 слушателей</a></div>
                                </div>
                                <div class="orders-actions">
                                    <div class="btn btn--bordered btn--blue">Оформить заказ</div>
                                    <div class="orders-delete js-delete-order" title="Удалить заказ">
                                        <span class="icon icon-korzina"></span>
                                    </div>
                                </div>
                            </div>
                        <li class="orders-li new-order">
                            <div class="orders-li-head">
                                <h4>
                                    Заказ №432
                                </h4>
                                <div class="orders-status">
                                    Не оформлен
                                </div>
                            </div>
                            <div class="orders-li-body">
                                <div class="orders-price">
                                    <span class="start-price">0.-</span> | <span class="end-price">12 000.-</span>
                                </div>
                                <div class="orders-date">
                                    Заказ создан:
                                    <div>
                                        29.09.14 в 10:15
                                    </div>
                                </div>
                                <div class="orders-package">
                                    <div>В заказе <a href="#">12 курсов</a></div>
                                    <div>для <a href="#">4 слушателей</a></div>
                                </div>
                                <div class="orders-actions">
                                    <div class="btn btn--bordered btn--blue">Оформить заказ</div>
                                    <div class="orders-delete js-delete-order" title="Удалить заказ">
                                        <span class="icon icon-korzina"></span>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div id="tabs-12">
                    <ul class="orders-ul">
                        <li class="orders-li active-order">
                            <div class="orders-li-head">
                                <h4>
                                    Заказ №432
                                </h4>
                                <div class="orders-status">
                                    Ожидает оплаты
                                </div>
                            </div>
                            <div class="orders-li-body">
                                <div class="orders-price">
                                    <span class="start-price">0.-</span> | <span class="end-price">12 000.-</span>
                                </div>
                                <div class="orders-date">
                                    Заказ создан:
                                    <div>
                                        29.09.14 в 10:15
                                    </div>
                                </div>
                                <div class="orders-package">
                                    <div>В заказе <a href="#">12 курсов</a></div>
                                    <div>для <a href="#">4 слушателей</a></div>
                                </div>
                                <div class="orders-docs">
                                    Посмотреть <a href="#">документы</a>
                                </div>
                            </div>
                        <li class="orders-li active-order">
                            <div class="orders-li-head">
                                <h4>
                                    Заказ №432
                                </h4>
                                <div class="orders-status">
                                    Ожидает оплаты
                                </div>
                            </div>
                            <div class="orders-li-body">
                                <div class="orders-price">
                                    <span class="start-price">0.-</span> | <span class="end-price">12 000.-</span>
                                </div>
                                <div class="orders-date">
                                    Заказ создан:
                                    <div>
                                        29.09.14 в 10:15
                                    </div>
                                </div>
                                <div class="orders-package">
                                    <div>В заказе <a href="#">12 курсов</a></div>
                                    <div>для <a href="#">4 слушателей</a></div>
                                </div>
                                <div class="orders-docs">
                                    Посмотреть <a href="#">документы</a>
                                </div>
                            </div>
                        <li class="orders-li active-order">
                            <div class="orders-li-head">
                                <h4>
                                    Заказ №432
                                </h4>
                                <div class="orders-status">
                                    Ожидает оплаты
                                </div>
                            </div>
                            <div class="orders-li-body">
                                <div class="orders-price">
                                    <span class="start-price">0.-</span> | <span class="end-price">12 000.-</span>
                                </div>
                                <div class="orders-date">
                                    Заказ создан:
                                    <div>
                                        29.09.14 в 10:15
                                    </div>
                                </div>
                                <div class="orders-package">
                                    <div>В заказе <a href="#">12 курсов</a></div>
                                    <div>для <a href="#">4 слушателей</a></div>
                                </div>
                                <div class="orders-docs">
                                    Посмотреть <a href="#">документы</a>
                                </div>
                            </div>
                        <li class="orders-li active-order">
                            <div class="orders-li-head">
                                <h4>
                                    Заказ №432
                                </h4>
                                <div class="orders-status">
                                    Ожидает оплаты
                                </div>
                            </div>
                            <div class="orders-li-body">
                                <div class="orders-price">
                                    <span class="start-price">0.-</span> | <span class="end-price">12 000.-</span>
                                </div>
                                <div class="orders-date">
                                    Заказ создан:
                                    <div>
                                        29.09.14 в 10:15
                                    </div>
                                </div>
                                <div class="orders-package">
                                    <div>В заказе <a href="#">12 курсов</a></div>
                                    <div>для <a href="#">4 слушателей</a></div>
                                </div>
                                <div class="orders-docs">
                                    Посмотреть <a href="#">документы</a>
                                </div>
                            </div>
                        <li class="orders-li active-order">
                            <div class="orders-li-head">
                                <h4>
                                    Заказ №432
                                </h4>
                                <div class="orders-status">
                                    Ожидает оплаты
                                </div>
                            </div>
                            <div class="orders-li-body">
                                <div class="orders-price">
                                    <span class="start-price">0.-</span> | <span class="end-price">12 000.-</span>
                                </div>
                                <div class="orders-date">
                                    Заказ создан:
                                    <div>
                                        29.09.14 в 10:15
                                    </div>
                                </div>
                                <div class="orders-package">
                                    <div>В заказе <a href="#">12 курсов</a></div>
                                    <div>для <a href="#">4 слушателей</a></div>
                                </div>
                                <div class="orders-docs">
                                    Посмотреть <a href="#">документы</a>
                                </div>
                            </div>
                        <li class="orders-li active-order">
                            <div class="orders-li-head">
                                <h4>
                                    Заказ №432
                                </h4>
                                <div class="orders-status">
                                    Ожидает оплаты
                                </div>
                            </div>
                            <div class="orders-li-body">
                                <div class="orders-price">
                                    <span class="start-price">0.-</span> | <span class="end-price">12 000.-</span>
                                </div>
                                <div class="orders-date">
                                    Заказ создан:
                                    <div>
                                        29.09.14 в 10:15
                                    </div>
                                </div>
                                <div class="orders-package">
                                    <div>В заказе <a href="#">12 курсов</a></div>
                                    <div>для <a href="#">4 слушателей</a></div>
                                </div>
                                <div class="orders-docs">
                                    Посмотреть <a href="#">документы</a>
                                </div>
                            </div>
                        <li class="orders-li active-order">
                            <div class="orders-li-head">
                                <h4>
                                    Заказ №432
                                </h4>
                                <div class="orders-status">
                                    Ожидает оплаты
                                </div>
                            </div>
                            <div class="orders-li-body">
                                <div class="orders-price">
                                    <span class="start-price">0.-</span> | <span class="end-price">12 000.-</span>
                                </div>
                                <div class="orders-date">
                                    Заказ создан:
                                    <div>
                                        29.09.14 в 10:15
                                    </div>
                                </div>
                                <div class="orders-package">
                                    <div>В заказе <a href="#">12 курсов</a></div>
                                    <div>для <a href="#">4 слушателей</a></div>
                                </div>
                                <div class="orders-docs">
                                    Посмотреть <a href="#">документы</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div id="tabs-13">
                    <ul class="orders-ul">
                        <li class="orders-li active-order">
                            <div class="orders-li-head">
                                <h4>
                                    Заказ №432
                                </h4>
                                <div class="orders-status">
                                    Ожидает оплаты
                                </div>
                            </div>
                            <div class="orders-li-body">
                                <div class="orders-price">
                                    <span class="start-price">0.-</span> | <span class="end-price">12 000.-</span>
                                </div>
                                <div class="orders-date">
                                    Заказ создан:
                                    <div>
                                        29.09.14 в 10:15
                                    </div>
                                </div>
                                <div class="orders-package">
                                    <div>В заказе <a href="#">12 курсов</a></div>
                                    <div>для <a href="#">4 слушателей</a></div>
                                </div>
                                <div class="orders-docs">
                                    Посмотреть <a href="#">документы</a>
                                </div>
                            </div>
                        <li class="orders-li active-order">
                            <div class="orders-li-head">
                                <h4>
                                    Заказ №432
                                </h4>
                                <div class="orders-status">
                                    Ожидает оплаты
                                </div>
                            </div>
                            <div class="orders-li-body">
                                <div class="orders-price">
                                    <span class="start-price">0.-</span> | <span class="end-price">12 000.-</span>
                                </div>
                                <div class="orders-date">
                                    Заказ создан:
                                    <div>
                                        29.09.14 в 10:15
                                    </div>
                                </div>
                                <div class="orders-package">
                                    <div>В заказе <a href="#">12 курсов</a></div>
                                    <div>для <a href="#">4 слушателей</a></div>
                                </div>
                                <div class="orders-docs">
                                    Посмотреть <a href="#">документы</a>
                                </div>
                            </div>
                        <li class="orders-li active-order">
                            <div class="orders-li-head">
                                <h4>
                                    Заказ №432
                                </h4>
                                <div class="orders-status">
                                    Ожидает оплаты
                                </div>
                            </div>
                            <div class="orders-li-body">
                                <div class="orders-price">
                                    <span class="start-price">0.-</span> | <span class="end-price">12 000.-</span>
                                </div>
                                <div class="orders-date">
                                    Заказ создан:
                                    <div>
                                        29.09.14 в 10:15
                                    </div>
                                </div>
                                <div class="orders-package">
                                    <div>В заказе <a href="#">12 курсов</a></div>
                                    <div>для <a href="#">4 слушателей</a></div>
                                </div>
                                <div class="orders-docs">
                                    Посмотреть <a href="#">документы</a>
                                </div>
                            </div>
                        <li class="orders-li active-order">
                            <div class="orders-li-head">
                                <h4>
                                    Заказ №432
                                </h4>
                                <div class="orders-status">
                                    Ожидает оплаты
                                </div>
                            </div>
                            <div class="orders-li-body">
                                <div class="orders-price">
                                    <span class="start-price">0.-</span> | <span class="end-price">12 000.-</span>
                                </div>
                                <div class="orders-date">
                                    Заказ создан:
                                    <div>
                                        29.09.14 в 10:15
                                    </div>
                                </div>
                                <div class="orders-package">
                                    <div>В заказе <a href="#">12 курсов</a></div>
                                    <div>для <a href="#">4 слушателей</a></div>
                                </div>
                                <div class="orders-docs">
                                    Посмотреть <a href="#">документы</a>
                                </div>
                            </div>
                        <li class="orders-li active-order">
                            <div class="orders-li-head">
                                <h4>
                                    Заказ №432
                                </h4>
                                <div class="orders-status">
                                    Ожидает оплаты
                                </div>
                            </div>
                            <div class="orders-li-body">
                                <div class="orders-price">
                                    <span class="start-price">0.-</span> | <span class="end-price">12 000.-</span>
                                </div>
                                <div class="orders-date">
                                    Заказ создан:
                                    <div>
                                        29.09.14 в 10:15
                                    </div>
                                </div>
                                <div class="orders-package">
                                    <div>В заказе <a href="#">12 курсов</a></div>
                                    <div>для <a href="#">4 слушателей</a></div>
                                </div>
                                <div class="orders-docs">
                                    Посмотреть <a href="#">документы</a>
                                </div>
                            </div>
                        <li class="orders-li active-order">
                            <div class="orders-li-head">
                                <h4>
                                    Заказ №432
                                </h4>
                                <div class="orders-status">
                                    Ожидает оплаты
                                </div>
                            </div>
                            <div class="orders-li-body">
                                <div class="orders-price">
                                    <span class="start-price">0.-</span> | <span class="end-price">12 000.-</span>
                                </div>
                                <div class="orders-date">
                                    Заказ создан:
                                    <div>
                                        29.09.14 в 10:15
                                    </div>
                                </div>
                                <div class="orders-package">
                                    <div>В заказе <a href="#">12 курсов</a></div>
                                    <div>для <a href="#">4 слушателей</a></div>
                                </div>
                                <div class="orders-docs">
                                    Посмотреть <a href="#">документы</a>
                                </div>
                            </div>
                        <li class="orders-li active-order">
                            <div class="orders-li-head">
                                <h4>
                                    Заказ №432
                                </h4>
                                <div class="orders-status">
                                    Ожидает оплаты
                                </div>
                            </div>
                            <div class="orders-li-body">
                                <div class="orders-price">
                                    <span class="start-price">0.-</span> | <span class="end-price">12 000.-</span>
                                </div>
                                <div class="orders-date">
                                    Заказ создан:
                                    <div>
                                        29.09.14 в 10:15
                                    </div>
                                </div>
                                <div class="orders-package">
                                    <div>В заказе <a href="#">12 курсов</a></div>
                                    <div>для <a href="#">4 слушателей</a></div>
                                </div>
                                <div class="orders-docs">
                                    Посмотреть <a href="#">документы</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div id="tabs-14">
                    <ul class="orders-ul">
                        <li class="orders-li active-order">
                            <div class="orders-li-head">
                                <h4>
                                    Заказ №432
                                </h4>
                                <div class="orders-status">
                                    Ожидает оплаты
                                </div>
                            </div>
                            <div class="orders-li-body">
                                <div class="orders-price">
                                    <span class="start-price">0.-</span> | <span class="end-price">12 000.-</span>
                                </div>
                                <div class="orders-date">
                                    Заказ создан:
                                    <div>
                                        29.09.14 в 10:15
                                    </div>
                                </div>
                                <div class="orders-package">
                                    <div>В заказе <a href="#">12 курсов</a></div>
                                    <div>для <a href="#">4 слушателей</a></div>
                                </div>
                                <div class="orders-docs">
                                    Посмотреть <a href="#">документы</a>
                                </div>
                            </div>
                        <li class="orders-li active-order">
                            <div class="orders-li-head">
                                <h4>
                                    Заказ №432
                                </h4>
                                <div class="orders-status">
                                    Ожидает оплаты
                                </div>
                            </div>
                            <div class="orders-li-body">
                                <div class="orders-price">
                                    <span class="start-price">0.-</span> | <span class="end-price">12 000.-</span>
                                </div>
                                <div class="orders-date">
                                    Заказ создан:
                                    <div>
                                        29.09.14 в 10:15
                                    </div>
                                </div>
                                <div class="orders-package">
                                    <div>В заказе <a href="#">12 курсов</a></div>
                                    <div>для <a href="#">4 слушателей</a></div>
                                </div>
                                <div class="orders-docs">
                                    Посмотреть <a href="#">документы</a>
                                </div>
                            </div>
                        <li class="orders-li active-order">
                            <div class="orders-li-head">
                                <h4>
                                    Заказ №432
                                </h4>
                                <div class="orders-status">
                                    Ожидает оплаты
                                </div>
                            </div>
                            <div class="orders-li-body">
                                <div class="orders-price">
                                    <span class="start-price">0.-</span> | <span class="end-price">12 000.-</span>
                                </div>
                                <div class="orders-date">
                                    Заказ создан:
                                    <div>
                                        29.09.14 в 10:15
                                    </div>
                                </div>
                                <div class="orders-package">
                                    <div>В заказе <a href="#">12 курсов</a></div>
                                    <div>для <a href="#">4 слушателей</a></div>
                                </div>
                                <div class="orders-docs">
                                    Посмотреть <a href="#">документы</a>
                                </div>
                            </div>
                        <li class="orders-li active-order">
                            <div class="orders-li-head">
                                <h4>
                                    Заказ №432
                                </h4>
                                <div class="orders-status">
                                    Ожидает оплаты
                                </div>
                            </div>
                            <div class="orders-li-body">
                                <div class="orders-price">
                                    <span class="start-price">0.-</span> | <span class="end-price">12 000.-</span>
                                </div>
                                <div class="orders-date">
                                    Заказ создан:
                                    <div>
                                        29.09.14 в 10:15
                                    </div>
                                </div>
                                <div class="orders-package">
                                    <div>В заказе <a href="#">12 курсов</a></div>
                                    <div>для <a href="#">4 слушателей</a></div>
                                </div>
                                <div class="orders-docs">
                                    Посмотреть <a href="#">документы</a>
                                </div>
                            </div>
                        <li class="orders-li active-order">
                            <div class="orders-li-head">
                                <h4>
                                    Заказ №432
                                </h4>
                                <div class="orders-status">
                                    Ожидает оплаты
                                </div>
                            </div>
                            <div class="orders-li-body">
                                <div class="orders-price">
                                    <span class="start-price">0.-</span> | <span class="end-price">12 000.-</span>
                                </div>
                                <div class="orders-date">
                                    Заказ создан:
                                    <div>
                                        29.09.14 в 10:15
                                    </div>
                                </div>
                                <div class="orders-package">
                                    <div>В заказе <a href="#">12 курсов</a></div>
                                    <div>для <a href="#">4 слушателей</a></div>
                                </div>
                                <div class="orders-docs">
                                    Посмотреть <a href="#">документы</a>
                                </div>
                            </div>
                        <li class="orders-li active-order">
                            <div class="orders-li-head">
                                <h4>
                                    Заказ №432
                                </h4>
                                <div class="orders-status">
                                    Ожидает оплаты
                                </div>
                            </div>
                            <div class="orders-li-body">
                                <div class="orders-price">
                                    <span class="start-price">0.-</span> | <span class="end-price">12 000.-</span>
                                </div>
                                <div class="orders-date">
                                    Заказ создан:
                                    <div>
                                        29.09.14 в 10:15
                                    </div>
                                </div>
                                <div class="orders-package">
                                    <div>В заказе <a href="#">12 курсов</a></div>
                                    <div>для <a href="#">4 слушателей</a></div>
                                </div>
                                <div class="orders-docs">
                                    Посмотреть <a href="#">документы</a>
                                </div>
                            </div>
                        <li class="orders-li active-order">
                            <div class="orders-li-head">
                                <h4>
                                    Заказ №432
                                </h4>
                                <div class="orders-status">
                                    Ожидает оплаты
                                </div>
                            </div>
                            <div class="orders-li-body">
                                <div class="orders-price">
                                    <span class="start-price">0.-</span> | <span class="end-price">12 000.-</span>
                                </div>
                                <div class="orders-date">
                                    Заказ создан:
                                    <div>
                                        29.09.14 в 10:15
                                    </div>
                                </div>
                                <div class="orders-package">
                                    <div>В заказе <a href="#">12 курсов</a></div>
                                    <div>для <a href="#">4 слушателей</a></div>
                                </div>
                                <div class="orders-docs">
                                    Посмотреть <a href="#">документы</a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="tabs-2" class="employees">
            <h3>Сотрудники</h3>
            <form class="employee-search">
                <fieldset>
                    <input type="text" placeholder="Укажите ФИО сотрудника, название компании или курса">
                    <button type="submit"><span class="icon icon-search"></span></button>
                </fieldset>
            </form>
            <div class="count-add">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 no-gutter">
                            <div class="count-add-sign">Обучается</div>
                            <div class="count-add-num">5</div>
                            <div class="count-add-dots"></div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <div class="count-add-sign">Всего</div>
                            <div class="count-add-num">21</div>
                            <div class="count-add-dots"></div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 no-gutter">
                            <div class="btn btn--bordered btn--blue pull-right js-btn-add-emp">
                                <span class="icon icon-slysh_dob"></span> Добавить
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h3>Курсы</h3>
            <div class="count-add">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 no-gutter">
                            <div class="count-add-sign">Активно</div>
                            <div class="count-add-num">8</div>
                            <div class="count-add-dots"></div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                            <div class="count-add-sign">Завершено</div>
                            <div class="count-add-num">10</div>
                            <div class="count-add-dots"></div>
                        </div>
                        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 no-gutter">
                            <div class="btn btn--bordered btn--blue pull-right js-btn-add-emp">
                                <span class="icon icon-kurs_dob"></span> Купить курс
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h3>Заказы</h3>
            <ul class="orders-ul">
                <li class="orders-li new-order">
                    <div class="orders-li-head">
                        <h4>
                            Заказ №432
                        </h4>
                        <div class="orders-status">
                            Ожидает оплаты
                        </div>
                    </div>
                    <div class="orders-li-body">
                        <div class="orders-price">
                            <span class="start-price">0.-</span> | <span class="end-price">12 000.-</span>
                        </div>
                        <div class="orders-date">
                            Заказ создан:
                            <div>
                                29.09.14 в 10:15
                            </div>
                        </div>
                        <div class="orders-package">
                            <div>В заказе <a href="#">12 курсов</a></div>
                            <div>для <a href="#">4 слушателей</a></div>
                        </div>
                        <div class="orders-docs">
                            Посмотреть <a href="#">документы</a>
                        </div>
                    </div>
                <li class="orders-li active-order">
                    <div class="orders-li-head">
                        <h4>
                            Заказ №432
                        </h4>
                        <div class="orders-status">
                            Ожидает оплаты
                        </div>
                    </div>
                    <div class="orders-li-body">
                        <div class="orders-price">
                            <span class="start-price">0.-</span> | <span class="end-price">12 000.-</span>
                        </div>
                        <div class="orders-date">
                            Заказ создан:
                            <div>
                                29.09.14 в 10:15
                            </div>
                        </div>
                        <div class="orders-package">
                            <div>В заказе <a href="#">12 курсов</a></div>
                            <div>для <a href="#">4 слушателей</a></div>
                        </div>
                        <div class="orders-docs">
                            Посмотреть <a href="#">документы</a>
                        </div>
                    </div>
                <li class="orders-li active-order">
                    <div class="orders-li-head">
                        <h4>
                            Заказ №432
                        </h4>
                        <div class="orders-status">
                            Ожидает оплаты
                        </div>
                    </div>
                    <div class="orders-li-body">
                        <div class="orders-price">
                            <span class="start-price">0.-</span> | <span class="end-price">12 000.-</span>
                        </div>
                        <div class="orders-date">
                            Заказ создан:
                            <div>
                                29.09.14 в 10:15
                            </div>
                        </div>
                        <div class="orders-package">
                            <div>В заказе <a href="#">12 курсов</a></div>
                            <div>для <a href="#">4 слушателей</a></div>
                        </div>
                        <div class="orders-docs">
                            Посмотреть <a href="#">документы</a>
                        </div>
                    </div>
            </ul>
            <h3>Ход обучения</h3>
            <ul class="learning-ul container-fluid">
                <li class="row">
                    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 no-gutter">
                        <div class="name">
                            <a href="#">Авазаров Михаил Юрьевич</a>
                        </div>
                        <div class="status">
                            Начать изучение курса «Не самое длинное название одного из курсов системы»
                        </div>
                        <div class="font-sm">
                            24.09.14
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                        <div class="progress-bar bar-1 completed-1 clearfix">
                            <div class="bar-part bar-part-1"></div>
                            <div class="bar-part bar-part-2"></div>
                            <div class="bar-part bar-part-3"></div>
                        </div>
                    </div>
                </li>
                <li class="row">
                    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 no-gutter">
                        <div class="name">
                            <a href="#">Авазаров Михаил Юрьевич</a>
                        </div>
                        <div class="status">
                            Начать изучение курса «Не самое длинное название одного из курсов системы»
                        </div>
                        <div class="font-sm">
                            24.09.14
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                        <div class="progress-bar bar-1 completed-1 clearfix">
                            <div class="bar-part bar-part-1"></div>
                            <div class="bar-part bar-part-2"></div>
                            <div class="bar-part bar-part-3"></div>
                        </div>
                    </div>
                </li>
                <li class="row">
                    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 no-gutter">
                        <div class="name">
                            <a href="#">Авазаров Михаил Юрьевич</a>
                        </div>
                        <div class="status">
                            Начать изучение курса «Не самое длинное название одного из курсов системы»
                        </div>
                        <div class="font-sm">
                            24.09.14
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                        <div class="progress-bar bar-1 completed-1 clearfix">
                            <div class="bar-part bar-part-1"></div>
                            <div class="bar-part bar-part-2"></div>
                            <div class="bar-part bar-part-3"></div>
                        </div>
                    </div>
                </li>
                <li class="row">
                    <div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 no-gutter">
                        <div class="name">
                            <a href="#">Авазаров Михаил Юрьевич</a>
                        </div>
                        <div class="status">
                            Начать изучение курса «Не самое длинное название одного из курсов системы»
                        </div>
                        <div class="font-sm">
                            24.09.14
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
                        <div class="progress-bar bar-1 completed-1 clearfix">
                            <div class="bar-part bar-part-1"></div>
                            <div class="bar-part bar-part-2"></div>
                            <div class="bar-part bar-part-3"></div>
                        </div>
                    </div>
                </li>
            </ul>
            <h3>Уведомления</h3>
            <div class="notifications">
                <div class="notifications-nav">
                    <span class="icon icon-angle-left js-notif-left"></span>
                    <span class="notifications-count">
                        <span class="current">5</span> / <span class="all"></span>
                    </span>
                    <span class="icon icon-angle-right js-notif-right"></span>
                </div>
                <ul class="notifications-ul">
                    <li class="notifications-li container-fluid">
                        <div class="row">
                            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                                <div class="notif-type">
                                    Системное сообщение 1
                                </div>
                                <div class="notif-cont">
                                    Заказ №400 не оплачен, но доступ к обучению предоставлен.
                                </div>
                                <div class="margin-top-20">
                                    <button class="btn btn--bordered btn--blue">
                                        Загрузить счет
                                    </button>
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                <div class="notif-date font-sm">
                                    24.09.14
                                </div>
                                <div class="notif-delete js-notif-delete">
                                    удалить
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="notifications-li container-fluid">
                        <div class="row">
                            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                                <div class="notif-type">
                                    Системное сообщение 2
                                </div>
                                <div class="notif-cont">
                                    Заказ №400 не оплачен, но доступ к обучению предоставлен.
                                </div>
                                <div class="margin-top-20">
                                    <button class="btn btn--bordered btn--blue">
                                        Загрузить счет
                                    </button>
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                <div class="notif-date font-sm">
                                    24.09.14
                                </div>
                                <div class="notif-delete js-notif-delete">
                                    удалить
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="notifications-li container-fluid">
                        <div class="row">
                            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                                <div class="notif-type">
                                    Системное сообщение 3
                                </div>
                                <div class="notif-cont">
                                    Заказ №400 не оплачен, но доступ к обучению предоставлен.
                                </div>
                                <div class="margin-top-20">
                                    <button class="btn btn--bordered btn--blue">
                                        Загрузить счет
                                    </button>
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                <div class="notif-date font-sm">
                                    24.09.14
                                </div>
                                <div class="notif-delete js-notif-delete">
                                    удалить
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="notifications-li container-fluid">
                        <div class="row">
                            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                                <div class="notif-type">
                                    Системное сообщение 4
                                </div>
                                <div class="notif-cont">
                                    Заказ №400 не оплачен, но доступ к обучению предоставлен.
                                </div>
                                <div class="margin-top-20">
                                    <button class="btn btn--bordered btn--blue">
                                        Загрузить счет
                                    </button>
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                <div class="notif-date font-sm">
                                    24.09.14
                                </div>
                                <div class="notif-delete js-notif-delete">
                                    удалить
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="notifications-li container-fluid">
                        <div class="row">
                            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                                <div class="notif-type">
                                    Системное сообщение 5
                                </div>
                                <div class="notif-cont">
                                    Заказ №400 не оплачен, но доступ к обучению предоставлен.
                                </div>
                                <div class="margin-top-20">
                                    <button class="btn btn--bordered btn--blue">
                                        Загрузить счет
                                    </button>
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                <div class="notif-date font-sm">
                                    24.09.14
                                </div>
                                <div class="notif-delete js-notif-delete">
                                    удалить
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="notifications-li container-fluid">
                        <div class="row">
                            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                                <div class="notif-type">
                                    Системное сообщение 6
                                </div>
                                <div class="notif-cont">
                                    Заказ №400 не оплачен, но доступ к обучению предоставлен.
                                </div>
                                <div class="margin-top-20">
                                    <button class="btn btn--bordered btn--blue">
                                        Загрузить счет
                                    </button>
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                <div class="notif-date font-sm">
                                    24.09.14
                                </div>
                                <div class="notif-delete js-notif-delete">
                                    удалить
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="notifications-li container-fluid">
                        <div class="row">
                            <div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
                                <div class="notif-type">
                                    Системное сообщение 7
                                </div>
                                <div class="notif-cont">
                                    Заказ №400 не оплачен, но доступ к обучению предоставлен.
                                </div>
                                <div class="margin-top-20">
                                    <button class="btn btn--bordered btn--blue">
                                        Загрузить счет
                                    </button>
                                </div>
                            </div>
                            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                                <div class="notif-date font-sm">
                                    24.09.14
                                </div>
                                <div class="notif-delete js-notif-delete">
                                    удалить
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
            <table class="table sortable">
                <tr>
                    <th class="sort sort--asc">Ф.И.О. <span class="sort--icon"></span> </th>
                    <th class="sort sort--asc">Название курса <span class="sort--icon"></span> </th>
                    <th class="sort sort--asc">Прогресс <span class="sort--icon"></span> </th>
                </tr>
                <tr>
                    <td>
                        <a href="#">Васильев Валерий Михайлович</a>
                    </td>
                    <td>
                        Не самое длинное название одного из курсов системы
                        <a class="more-courses" href="#">показать еще 2 курса</a>
                    </td>
                    <td class="td-status-bar">
                        <div class="progress-bar bar-1 completed-1 clearfix">
                            <div class="bar-part bar-part-1"></div>
                            <div class="bar-part bar-part-2"></div>
                            <div class="bar-part bar-part-3"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a href="#">Ильичева Анна Михайловна</a>
                    </td>
                    <td>
                        Это тоже не самое длинное название одного из курсов системы
                    </td>
                    <td class="td-status-bar">
                        <div class="progress-bar bar-2 completed-2 clearfix">
                            <div class="bar-part bar-part-1"></div>
                            <div class="bar-part bar-part-2"></div>
                            <div class="bar-part bar-part-3"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a href="#">Семенова Ирина Олеговна</a>
                    </td>
                    <td>
                        <span class="no-courses">Для этого сотрудника курсы не покупались</span>
                    </td>
                    <td class="td-status-bar">
                        <div class="progress-bar bar-0 completed-3 clearfix">
                            <div class="bar-part bar-part-1"></div>
                            <div class="bar-part bar-part-2"></div>
                            <div class="bar-part bar-part-3"></div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <a href="#">Гвоздь Александр Бедросович</a>
                    </td>
                    <td>
                        <span class="no-courses">Для этого сотрудника курсы не покупались</span>
                    </td>
                    <td class="td-status-bar">
                        <div class="progress-bar bar-3 clearfix">
                            <div class="bar-part bar-part-1"></div>
                            <div class="bar-part bar-part-2"></div>
                            <div class="bar-part bar-part-3"></div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div id="tabs-3">

        </div>
        <div id="tabs-4">

        </div>
    </div>
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop