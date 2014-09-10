$('select').customSelect();

window.matchMedia('only screen and (max-width: 1480px)').addListener(function(list){
    $('select').trigger('render');
});