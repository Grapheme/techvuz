
    <ul>
    @foreach($photos as $photo)
    	<li style="float:left;">
    		<a href="{{ $photo->full() }}" target="_blank"><img src="{{ $photo->thumb() }}" alt="" style="max-width: 150px;" /></a>
    	</li>
    @endforeach
    </ul>
    