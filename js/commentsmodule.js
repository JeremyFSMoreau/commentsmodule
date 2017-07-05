$(document).ready(function(){
    if ($('#bloc_commentaires').attr('data-scroll') == 'true')
    {
        $('html, body').animate({
            scrollTop: $("#bloc_commentaires").offset().top
        },1200);
    }
});