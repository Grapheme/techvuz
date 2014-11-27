<?
$years = array();
foreach ($news as $new) {
    $years[date('Y', strtotime($new->published_at))] = 1;
}
?>

        @if(isset($news) && is_object($news) && $news->count())
        <ul class="js-tabs year-tabs" data-tabs="news">
            @foreach ($years as $year => $null)
            <li{{ date('Y') == $year ? ' class="active"' : '' }} data-tab="{{ $year }}"><span>{{ $year }}</span>
            @endforeach
        </ul>
        <div class="js-parent" data-tabs="news">
            <?
            $last_year = false;
            ?>
            @foreach($news as $new)
            <?
            $current_year = date('Y', strtotime($new->published_at));
            ?>
            @if ($current_year != $last_year)
            <ul data-block="{{ $current_year }}" class="js-tab news-ul">
            @endif
                <li>
                    <span>{{ Helper::rdate('j M', $new->published_at) }}</span>
                    <span>{{ $new->meta->preview }}</span>
            @if ($current_year != $last_year)
                <?
                $last_year = $current_year;
                ?>
                </ul>
                @endif
            @endforeach
        </div>
        @endif
