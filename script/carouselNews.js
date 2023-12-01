$(document).ready(function(){
    var block = false;

    $('body').everyTime(4000,function(i){
        if(!block){
            var next = $('.galerieSelected').next('img');
            var src = next.attr('src');
            if($('.galerieSelected').attr('last')=='true'){
                $('.galeriePicture').removeClass('galerieSelected');
                $('.first').addClass('galerieSelected');
                $('#titleblocimg').fadeTo(200,0.4,function(){
                    $('#titleblocimg').attr('src', $('.first').attr('src'));
                    $('#titleblocimg').fadeTo(400,1,null);
                });
            }else{
                $('.galeriePicture').removeClass('galerieSelected');
                next.addClass('galerieSelected');
                $('#titleblocimg').fadeTo(200,0.4,function(){
                    $('#titleblocimg').attr('src', src);
                    $('#titleblocimg').fadeTo(400,1,null);
                });
            }
        }else{
            block = false;
        }

    });
    
    
    $('.galeriePicture').live('click',function(){
        block = true;
        $('.galeriePicture').removeClass('galerieSelected');
        $(this).addClass('galerieSelected');
        $('#titleblocimg').fadeTo(200,0.4,function(){
            $('#titleblocimg').attr('src', $('.galerieSelected').attr('src'));
            $('#titleblocimg').fadeTo(400,1,null);
        });
    });
});


