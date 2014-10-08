
    function init_sortable(url, selector, success) {
        if (typeof success != 'function')
            success = function(){};
        $(document).on("mouseover", ".sortable" + selector, function(e){
            // Check flag of sortable activated
            if ( !$(this).data('sortable') ) {
                // Activate sortable, if flag is not initialized
                $(this).sortable({
                    // On finish of sorting
                    stop: function() {
                        if (url) {
                            // Find all child elements
                            var pls = $(this).find('tr, .sortable_item');
                            var poss = [];
                            // Make array with current sorting order
                            $(pls).each(function(i, item) {
                                poss.push($(item).data('id'));
                            });
                            // Send ajax request to server for saving sorting order
                            $.ajax({
                                url: url,
                                type: "post",
                                data: { poss: poss },
                                success: success
                            });
                        }
                    }
                });
            }
        });
    }



    Array.max = function( array ){
        return Math.max.apply( Math, array );
    };

    // Function to get the Min value in Array
    Array.min = function( array ){
        return Math.min.apply( Math, array );
    };

    /*
    //updated as per Sime Vidas comment.
    var widths= $('img').map(function() {
        return $(this).width();
    }).get();

    alert("Max Width: " + Array.max(widths));
    alert("Min Width: " + Array.min(widths));
    */

    jQuery.fn.tagName = function() {
        return this.prop("tagName");
    };