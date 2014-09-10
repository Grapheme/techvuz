<?php
	$presenter = new Illuminate\Pagination\PaginationClass($paginator);
?>

<?php if ($paginator->getLastPage() > 1): ?>
<div class="pagination">
	<ul class="pages-list">
		{{ $presenter->render() }}
	</ul>
</div>
<?php endif; ?>