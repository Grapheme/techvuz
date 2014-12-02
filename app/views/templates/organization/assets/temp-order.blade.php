<li class="orders-li non-paid-order">
    <div class="orders-li-head">
        <h4>Новый заказ</h4>
        <div class="orders-status">
            Не оформлен
        </div>
    </div>
    <div class="orders-li-body">
        <div class="orders-package">
            <div>В заказе {{ count(getJsonCookieData('ordering')) }} {{ Lang::choice('курс|курса|курсов',count(getJsonCookieData('ordering'))); }}</div>
            <div>для {{ count(getJsonCookieData('ordering','values_unique')) }} {{ Lang::choice('слушателя|слушателей|слушателей',count(getJsonCookieData('ordering','values_unique'))); }}</div>
        </div>
        <div class="orders-actions">
            @if(count(getJsonCookieData('ordering','values_unique')) > 0)
            <a href="{{ URL::route('ordering-select-listeners') }}" class="btn btn--bordered btn--blue">Оформить заказ</a>
            @else
            <a href="{{ URL::route('ordering-select-courses') }}" class="btn btn--bordered btn--blue">Оформить заказ</a>
            @endif
            <div title="Удалить заказ" class="icon-bag-btn js-delete-order">
                
            </div>
        </div>
    </div>