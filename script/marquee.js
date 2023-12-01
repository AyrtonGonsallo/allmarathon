$(window).load(
    function(){
        marquee('marquee-wrapper','marquee',10);
    }
    );


function marquee(idWrapper,idMarquee,vitesse)
/*
* idWrapper : l'identifiant du div autour du span contenant le texte à faire défiler
*	idMarquee : l'identifiant du span autour du texte
*	vitesse : nombre de millisecondes entre chaque déplacement de 1px
*/
{
var oIdWrapper=$('#'+idWrapper);
var oIdMarquee=$('#'+idMarquee);

var width=oIdMarquee.width();
var width2=oIdWrapper.width();

id_inst=setTimeout(function() {marquee(idWrapper,idMarquee,vitesse)},vitesse);

var l=parseInt(oIdMarquee.css('left'));
oIdMarquee.css({left:(l-1)+'px'});

if((-parseInt(oIdMarquee.css('left')))>=(width))
{
oIdMarquee.css({left:(width2)+'px'});
}
}
