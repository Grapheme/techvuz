
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
                    },
                    cancel: ".not-sortable",
                    distance: 5
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


    function array_merge() {
        //  discuss at: http://phpjs.org/functions/array_merge/
        // original by: Brett Zamir (http://brett-zamir.me)
        // bugfixed by: Nate
        // bugfixed by: Brett Zamir (http://brett-zamir.me)
        //    input by: josh
        //   example 1: arr1 = {"color": "red", 0: 2, 1: 4}
        //   example 1: arr2 = {0: "a", 1: "b", "color": "green", "shape": "trapezoid", 2: 4}
        //   example 1: array_merge(arr1, arr2)
        //   returns 1: {"color": "green", 0: 2, 1: 4, 2: "a", 3: "b", "shape": "trapezoid", 4: 4}
        //   example 2: arr1 = []
        //   example 2: arr2 = {1: "data"}
        //   example 2: array_merge(arr1, arr2)
        //   returns 2: {0: "data"}

        var args = Array.prototype.slice.call(arguments),
            argl = args.length,
            arg,
            retObj = {},
            k = '',
            argil = 0,
            j = 0,
            i = 0,
            ct = 0,
            toStr = Object.prototype.toString,
            retArr = true;

        for (i = 0; i < argl; i++) {
            if (toStr.call(args[i]) !== '[object Array]') {
                retArr = false;
                break;
            }
        }

        if (retArr) {
            retArr = [];
            for (i = 0; i < argl; i++) {
                retArr = retArr.concat(args[i]);
            }
            return retArr;
        }

        for (i = 0, ct = 0; i < argl; i++) {
            arg = args[i];
            if (toStr.call(arg) === '[object Array]') {
                for (j = 0, argil = arg.length; j < argil; j++) {
                    retObj[ct++] = arg[j];
                }
            } else {
                for (k in arg) {
                    if (arg.hasOwnProperty(k)) {
                        if (parseInt(k, 10) + '' === k) {
                            retObj[ct++] = arg[k];
                        } else {
                            retObj[k] = arg[k];
                        }
                    }
                }
            }
        }
        return retObj;
    }