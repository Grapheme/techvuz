<aside class="main-aside">
    <div class="mobile-aside-header">
        <div class="phone phone-stack">
            <a class="js-phone" data-name="Москва"  data-type="moscow" href="tel:+74997058688">8 (499) 705-86-88</a>
            <a class="js-phone" data-name="Ростов-на-Дону" data-type="rostov" href="tel:+78632990714">8 (863) 299-07-14</a>
            <a class="js-phone" data-type="other" href="tel:+78003338654">8 (800) 333-86-54</a>
        </div>
        <div class="phone-desc phone-links">
            <a class="js-phone-link" data-name="Москва" data-type="moscow" href="#">Москва</a>
            <a class="js-phone-link" data-name="Ростов-на-Дону" data-type="rostov" href="#">Ростов-на-Дону</a>
            <a class="js-phone-link" data-type="other" href="#">Другие регионы</a>
        </div>
        <div class="mobile-aside-close"></div>
    </div>

    @if (Request::is('/'))
    <div class="logo"></div>
    @else
    <div class="logo"><a class="logo-link" href="{{ URL::route('mainpage') }}"></a></div>
    @endif
    <div class="logo-desc">
        Образовательный портал
    </div>
    @if(!Auth::check())
      <a href="#" class="btn btn--bordered btn-quick" onclick="Popup.show('quick'); return false;">
        <span>Онлайн заявка</span>
      </a>
      <script>
        $(window).on('load', function(){setTimeout(function(){ Popup.show('quick'); return false; }, 15000)});
      </script>
    @endif
    <nav>
       <ul class="nav-ul">

           <li class="nav-li{{ Helper::isRoute('page', 'about',' active') }}">
               <a href="{{ pageurl('about') }}">О портале</a>
           </li>
           <li class="nav-li{{ Helper::isRoute('page', 'catalog',' active') }}">
               <a href="{{ pageurl('catalog') }}">Каталог курсов</a>
           </li>
           <li class="nav-li{{ Helper::isRoute('page', 'how-it-works',' active') }}">
               <a href="{{ pageurl('how-it-works') }}">Как это работает</a>
           </li>
           <li class="nav-li{{ Helper::isRoute('page', 'snips',' active') }}">
               {{--<a href="{{ pageurl('snips') }}">Переписка (СНИПЫ)</a>--}}
           </li>
           <li class="nav-li{{ Helper::isRoute('page', 'contacts',' active') }}">
               <a href="{{ pageurl('contacts') }}">Контактная информация</a>
           </li>
       </ul>
    </nav>

    @include('sphinxsearch/views/search-form')
    @include(Helper::layout('footer'))
</aside>