$(document).ready(function(){
    var block = false;
    
    $('body').everyTime(4000,function(i){
        if(!block){
            var id = $('.selectedVignette').attr('guid');
            id++;
            if(id == 6)
                id = 1;
            $('.vignettes').removeClass('selectedVignette');
            $('#img-princ-'+id+'_mini').addClass('selectedVignette');
            $('.vignettes').fadeTo(0, 0.3, null);
            $('#img-princ-'+id+'_mini').fadeTo(300,1,null);
            $('#img-princ-1 img, #img-princ-2 img, #img-princ-3 img, #img-princ-4 img, #img-princ-5 img').hide();
            $('#pointeur-1, #pointeur-2, #pointeur-3, #pointeur-4, #pointeur-5').hide();
            $('#img-princ-'+id+' img').show();
            $('#pointeur-'+id).show();
            $('#titreNews').html('<a href="actualite-judo-'+$('#idNews'+id).val()+'.html">'+$('#titreNews'+id).val()+'</a>');
            $('#typeNews').html($('#typeNews'+id).val()+'&nbsp;&nbsp;&nbsp;commentaires ('+$('#nbrCom'+id).val()+')');
            $('#chapoNews').html('<a href="actualite-judo-'+$('#idNews'+id).val()+'.html">'+$('#chapoNews'+id).val()+'</a>');

        }else{
            block = false;
        }
    });

    $('.vignettes').hover(function(){
        block = true;
        var id = $(this).attr('guid');
        $('.vignettes').removeClass('selectedVignette');
        $(this).addClass('selectedVignette');
        $('.vignettes').fadeTo(0, 0.3, null);
        $(this).fadeTo(300,1,null);
        $('#img-princ-1 img, #img-princ-2 img, #img-princ-3 img, #img-princ-4 img, #img-princ-5 img').hide();
        $('#pointeur-1, #pointeur-2, #pointeur-3, #pointeur-4, #pointeur-5').hide();
        $('#img-princ-'+id+' img').show();
        $('#pointeur-'+id).show();
        $('#titreNews').html('<a href="actualite-judo-'+$('#idNews'+id).val()+'.html">'+$('#titreNews'+id).val()+'</a>');
        $('#typeNews').html($('#typeNews'+id).val()+'&nbsp;&nbsp;&nbsp;commentaires ('+$('#nbrCom'+id).val()+')');
        $('#chapoNews').html('<a href="actualite-judo-'+$('#idNews'+id).val()+'.html">'+$('#chapoNews'+id).val()+'</a>');
    });
});
