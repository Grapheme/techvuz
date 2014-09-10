/*! http://mrclay.org/index.php/2010/11/14/using-jquery-before-its-loaded/ */
(function (w) { 
    if (w.$) // jQuery already loaded, we don't need this script 
        return; 
    var _funcs = []; 
    w.$ = function (f) { // add functions to a queue 
        _funcs.push(f); 
    }; 
    w.defer$ = function () { // move the queue to jQuery's DOMReady 
        while (f = _funcs.shift()) 
            $(f); 
    }; 
})(window);