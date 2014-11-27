@extends(Helper::layout())


@section('style')
@stop


@section('footer-class') white-footer @stop


@section('content')
<?
$years = array();
?>
    <section class="normal-page">
        <div class="wrapper">
            <h1>Новости</h1>

            @if(isset($news) && is_object($news) && $news->count())
            <ul class="news-list">
                @foreach($news as $new)
                <? $years[date('Y', strtotime($new->published_at))] = 1; ?>
                <li data-year="{{ date('Y', strtotime($new->published_at)) }}">
                    <h3>
                        <a name="{{ $new->slug }}">{{ $new->meta->title }}</a>
                    </h3>
                    <div class="news-date">{{ Helper::rdate('j M Y', $new->published_at) }}</div>
                    <div class="news-text">
                        <p>{{ $new->meta->content }}</p>
                    </div>
                @endforeach
            </ul>
            @endif

            <ul class="news-year">
                @foreach ($years as $year => $null)
                <li{{ date('Y') == $year ? ' class="active"' : '' }} data-year="{{ $year }}"><a href="#{{ $year }}">{{ $year }}</a>
                @endforeach
            </ul>
        </div>

        {{ $news->links() }}

    </section>
@stop



@section('scripts')
<script>
    var hash = window.location.hash.replace('\#', '');
    //alert(hash);
    if (isNumber(hash)) {
        activate_news(hash);
    } else {
        var news = $('a[name=' + hash + ']');
        if (typeof(news) != 'undefined' && news.length) {
            //console.log(news);
            var year = $(news).parents('li').data('year');
            activate_news(year);
            $('html, body').animate({
                scrollTop: $(news).offset().top
            }, 1000);
        } else {
            //alert('2');
            var year = new Date();
            activate_news(year.getFullYear());
        }
    }

    function activate_news(hash) {
        if (isNumber(hash)) {
            //alert('year: ' + hash);
            $('.news-list li').each(function(key, val){
                if ($(val).data('year') != hash)
                    $(val).addClass('hidden');
                else
                    $(val).removeClass('hidden');
            });
            $('.news-year li').removeClass('active');
            $('.news-year li[data-year=' + hash + ']').addClass('active');
        }
    }

    $(document).on('click', '.news-year li a', function(){
        //alert($(this).attr('href'));
        hash = $(this).attr('href').replace('\#', '');
        activate_news(hash);
    });

</script>
@stop