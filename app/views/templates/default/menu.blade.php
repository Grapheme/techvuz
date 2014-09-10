@if(!empty($menu))
<ul class="nav-list list-unstyled max-width-class text-center">
	@foreach($menu as $url => $name)
		<li class="nav-item"><a href="{{ link::to($url) }}">{{$name}}</a>
	@endforeach
	</ul>
@endif