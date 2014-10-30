<aside class="main-aside">
    @if (Request::is('/'))
    <h1 class="logo"><div class="logo-sign">Техвуз.рф</div></h1>
    @else
    <div class="logo"><a class="logo-link" href="{{ URL::route('mainpage') }}">Техвуз.рф</a></div>
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