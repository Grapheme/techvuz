<aside class="main-aside">
    <div class="moder-logo hidden">

    </div>
    <div class="mobile-aside-header">
        <div class="phone">
            <a href="tel:+78632990714">8 (863) 299 07 14</a>
        </div>
        <div class="phone-desc">
            Звонок бесплатный
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
           <li class="nav-li mobile-full-version">
               <a href="#">Полная версия</a>
           </li>
       </ul>
    </nav>
    @include('sphinxsearch/views/search-form')
    @include(Helper::layout('footer'))
</aside>