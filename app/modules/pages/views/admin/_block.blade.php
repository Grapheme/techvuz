
<?
$name = $block->id ? 'blocks' : 'blocks_new';
$class = $block->id ? '' : ' unsaved';
$id = $block->id ? $block->id : '%i%';
?>
<div class="block clearfix{{ $class }}" data-block_id="{{ $block->id }}">
    <input type="text" name="{{ $name }}[{{ $id }}][name]" value="{{ $block->name }}" placeholder="Название блока" class="block_name">
    <input type="text" name="{{ $name }}[{{ $id }}][slug]" value="{{ $block->slug }}" placeholder="Системное имя" class="block_desc">
    <input type="hidden" name="{{ $name }}[{{ $id }}][order]" value="{{ is_int($block->order) ? $block->order : '%p%' }}" class="block_order">
    <a href="javascript:void(0)" class="remove_block"><i class="fa fa-trash-o"></i> удалить</a>
    @if ($block->id)
    <a href="javascript:void(0)" class="edit_block" data-id="{{ $block->id }}"><i class="fa fa-pencil"></i> править</a>
    @endif
</div>
