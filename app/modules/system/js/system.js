
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
                        // Find all child elements
                        var pls = $(this).find('tr');
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
                });
            }
        });
    }
