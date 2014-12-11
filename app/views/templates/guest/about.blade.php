@extends(Helper::layout())
@section('style') @stop
@section('content')
<main class="contacts">
    <h1>{{ $page->block('top_h2') }}</h1>
    <div class="desc">
    {{ $page->block('top_desc') }}
    </div>
    <h2 class="h3">{{ $page->block('center_h3') }}</h2>
    <?php
        $licenses = Dictionary::whereSlugValues('licenses-certificates');
        $images = array();
        if($licenses->count()):
            $images_ids = array();
            foreach($licenses as $index => $license):
                $license['fields'] = modifyKeys($license['fields'],'key');
                if(!empty($license->fields['document']->value)):
                    $images_ids[] = $license->fields['document']->value;
                endif;
            endforeach;
            if(!empty($images_ids)):
                $images = Photo::whereIn('id',$images_ids)->get();
                $images = modifyKeys($images,'id',true);
            else:
                $images = array();
            endif;
        endif;
    ?>
    @if($licenses->count())
    <ul class="lic-ul margin-top-30">
        @foreach($licenses as $license)
        <?php $license['fields'] = modifyKeys($license['fields'],'key'); ?>
        @if(isset($license->fields['document']->value) && isset($images[$license->fields['document']->value]))
            <li class="lic-li">
                <a src="{{ asset(Config::get('site.galleries_photo_public_dir').'/'.$images[$license->fields['document']->value]->name) }}" alt="{{ $license->name }}">{{ $license->name }}</a>
            </li>
        @endif
        @endforeach
    </ul>
    @endif
</main>
@stop
@section('overlays')
@stop
@section('scripts')
@stop