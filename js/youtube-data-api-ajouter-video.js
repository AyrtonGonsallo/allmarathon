function reqListener () {

    // convertit la chaîne du fichier en objet avec JSON.parse
    
    var rep = JSON.parse(this.responseText);
    //console.log(rep)
    if(rep["code"]==403){
        alert("erreur "+rep["code"]+" : "+rep["erreur"])
        return 0
      }
    // affiche les données comme un objet quelconque
    var obj = rep["res"]
    

    

    document.getElementById("titre").value=obj.title;

    document.getElementById("duree").value=obj.duration;

    document.getElementById("objet").value='<iframe width="640" height="345" src="https://www.youtube.com/embed/'+obj.videoID+'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
    
    document.getElementById("vignette").value=obj.imagehigh;

}

    function getAPIVideoDetails(vid){

        var oReq = new XMLHttpRequest();

        oReq.onload = reqListener;

        url="https://alljudo-python-youtube-api.onrender.com/getVideoDetailsById/"+vid;

        oReq.open("get", url, true);

        oReq.send();

    }

   