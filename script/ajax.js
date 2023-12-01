
/**
 *La fonction ajaxCollector permet de rapatri� des fichier distant en AJAX
 *@param  nom_fichier : path du fichier a charger en ajax
 *
*/

function ajaxCollector(nom_fichier)
{

 /*cr�ation du XMLHttpRequest : xhr*/
    var xhr=null;
	try{xhr = new XMLHttpRequest(); //FIREFOX
		} catch(e)
	   { 
		 try { xhr = new ActiveXObject('Msxml2.XMLHTTP'); } 
		 catch (e2)
		{ 
		   try { xhr = new ActiveXObject('Microsoft.XMLHTTP'); } //IE
		   catch (e) {}
		}
	  }


     xhr.open('GET', nom_fichier, false);
     xhr.send(null);
     if(xhr.readyState == 4)
        return(xhr.responseText);
     else
        return(false);


}