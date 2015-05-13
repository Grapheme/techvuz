$(function() {
    function countLinks($items) {
        return Math.ceil($items.size()/itemsOnPage);
    }
    
    var $items = $('.tech-table tr.content');
    var $paginator = $('.pagination');
    var itemsOnPage = 50;
    
    var pageCount = countLinks($items);
    
    $items.hide();
    
    for (i = 1; i <= pageCount; i++ ) {
        $paginator.append('<a href="#" class="pagen">'+i+'</a>');
    }
    
    var $links = $paginator.find('a.pagen');
    
    $links.click(function(e){
        if (!$(this).is('.active')) {
            $items.hide();
            $links.removeClass('active');
            $(this).addClass('active');
            
            var page = $links.index($(this))+1;
            var startPos = ((page*itemsOnPage)-itemsOnPage+1);
            var endPos = startPos+itemsOnPage-1;
            
            for (i=startPos; i<=endPos; i++) {
                $items.eq(i-1).show();
            }
        }
        e.preventDefault();
    });
    
    $links.eq(0).click();
    
    $('<a href="#" class="prev">«</a>').prependTo($paginator).click(function(e){
        $links.filter('a.active').prev('a.pagen').click();
        e.preventDefault();
    });
    $('<a href="#" class="next">»</a>').appendTo($paginator).click(function(e){
        $links.filter('a.active').next('a.pagen').click();
        e.preventDefault();
    });
});