<aside class="main-aside">
    @if (Request::is('/'))
    <h1 class="logo">Техвуз.рф</h1>
    @else
    <div class="logo"><a class="logo-link" href="{{ URL::route('mainpage') }}">Техвуз.рф</a></div>
    @endif
    <div class="logo-desc">
        Образовательный портал
    </div>
    <nav>
       <ul class="nav-ul">
           <li class="nav-li">
               <a href="{{ URL::route('page', 'about') }}">О портале</a>
           </li>
           <li class="nav-li">
               <a href="{{ URL::route('page', 'catalog') }}">Каталог курсов</a>
           </li>
           <li class="nav-li">
               <a href="{{ URL::route('page', 'how-it-works') }}">Как это работает</a>
           </li>
           <li class="nav-li">
               <a href="{{ URL::route('page', 'snips') }}">Переписка (СНИПЫ)</a>
           </li>
           <li class="nav-li">
               <a href="{{ URL::route('page', 'contacts') }}">Контактная информация</a>
           </li>
       </ul>
    </nav>
    @include('sphinxsearch/views/search-form')
    @include(Helper::layout('footer'))
</aside>