<aside class="main-aside">
    <div class="mobile-aside-header">
        <div class="phone phone-stack">
            <a class="js-phone" data-type="moscow" href="tel:+74997058688">8 (499) 705-86-88</a>
            <a class="js-phone" data-type="rostov" href="tel:+78632990714">8 (863) 299-07-14</a>
            <a class="js-phone" data-type="other" href="tel:+78003338654">8 (800) 333-86-54</a>
        </div>
        <div class="phone-desc phone-links">
            <a class="js-phone-link" data-type="moscow" href="#">Москва</a>
            <a class="js-phone-link" data-type="rostov" href="#">Ростов-на-Дону</a>
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
    <nav>
       <ul class="nav-ul">

           <li class="nav-li{{ Helper::isRoute('page', 'about',' active') }}">
               <a href="{{ URL::route('page', 'about') }}">О портале</a>
           </li>
           <li class="nav-li{{ Helper::isRoute('page', 'catalog',' active') }}">
               <a href="{{ URL::route('page', 'catalog') }}">Каталог курсов</a>
           </li>
           <li class="nav-li{{ Helper::isRoute('page', 'how-it-works',' active') }}">
               <a href="{{ URL::route('page', 'how-it-works') }}">Как это работает</a>
           </li>
           <li class="nav-li{{ Helper::isRoute('page', 'snips',' active') }}">
               {{--<a href="{{ URL::route('page', 'snips') }}">Переписка (СНИПЫ)</a>--}}
           </li>
           <li class="nav-li{{ Helper::isRoute('page', 'contacts',' active') }}">
               <a href="{{ URL::route('page', 'contacts') }}">Контактная информация</a>
           </li>
       </ul>
    </nav>
    @include('sphinxsearch/views/search-form')
    @include(Helper::layout('footer'))
</aside>