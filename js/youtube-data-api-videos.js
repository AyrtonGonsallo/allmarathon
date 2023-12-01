function reqListener () {

    var obj = JSON.parse(this.responseText);
    if(obj["code"]==403){
      alert("erreur "+obj["code"]+" : "+obj["erreur"])
      return 0
    }
    var totalrecu=obj.length
    alert(totalrecu+' résultats reçus')
    parent=document.getElementById("listeResultats")

    parent.innerHTML="";

    for(j=0;j<totalrecu;j++){

        var objet=obj[j][1];

        var element=""
        element+='<li class="list-group-item" ><div class="row justify-content-center align-items-center"><div class="col-lg-2 col-4"><h6><u>Chaine:</u>'
        element+=objet.channelTitle
        element+='</h6><img src="'+objet.image+'" class="img-fluid" alt="serveur youtube surchargé"></div><div class="col-lg-5 col-5"><h5 class="mt-2 ">'
        element+=objet.title
        element+='</h5>'+objet.description.substring(0, 150)+'...</div><div class="col-lg-5 col-3"><div class="row justify-content-center align-items-center"><div class="col-lg-3 col-12">'
        element+='<a href="'+objet.url+'" target="_blank" class="btn btn-outline-success">Voir</a></div><div class="col-lg-4 col-12">'
        element+='<a href="#"  onclick="ajouterVideo(&#34;'+objet.videoID+'&#34;)" class="btn btn-outline-primary">Ajouter</a></div><div class="col-lg-3 col-12">'
        element+='<button onclick="deleteVideoRecherche(this)"  type="button" class="btn btn-outline-danger">Supprimer</button></div></div></div></div></li>'

        parent.innerHTML+=element;

    }
}
function reqListenerVR () {

  var obj = JSON.parse(this.responseText);
  if(obj["code"]==403){
    alert("erreur "+obj["code"]+" : "+obj["erreur"])
    return 0
  }
  var videos=obj[obj.length-1][1]
  //console.log(obj)
  totalrecu=videos.length
  //console.log("recu",totalrecu)
  
  alert(totalrecu+" vidéos reçues !")
  textehaut=document.getElementById("texte")
  textehaut.innerHTML="les "+totalrecu+" derniéres vidéos"
  parent=document.getElementById("listeVR")
  parent.innerHTML="";

    for(j=0;j<totalrecu;j++){

        var objet=videos[j][1];

        var element=""
        element+='<li class="list-group-item" ><div class="row justify-content-center align-items-center"><div class="col-lg-2 col-4"><h6><u>Chaine:</u>'
        element+=objet.channelTitle
        element+='</h6>'
        element+='<h6>le <b>'+objet.date_pub.split('T')[0]+'</b></h6>'
        element+='<img src="'+objet.image+'" class="img-fluid" alt="serveur youtube surchargé"></div><div class="col-lg-5 col-5"><h5 class="mt-2 ">'
        element+=objet.title
        element+='</h5>'+objet.description.substring(0, 150)+'...</div><div class="col-lg-5 col-3"><div class="row justify-content-center align-items-center"><div class="col-lg-3 col-12">'
        element+='<a href="'+objet.url+'" target="_blank" class="btn btn-outline-success">Voir</a></div><div class="col-lg-4 col-12">'
        element+='<a href="#"  onclick="ajouterVideo(&#34;'+objet.videoID+'&#34;)" class="btn btn-outline-primary">Ajouter</a></div><div class="col-lg-3 col-12">'
        element+='<button onclick="deleteVideoRecherche(this)"  type="button" class="btn btn-outline-danger">Supprimer</button></div></div></div></div></li>'

        parent.innerHTML+=element;

    }

}

function reqListenerSuggestions () {
  
    var obj = JSON.parse(this.responseText);
    if(obj["code"]==403){
      alert("erreur "+obj["code"]+" : "+obj["erreur"])
      return 0
    }var total=obj[obj.length-1][1]
    alert(total+' videos trouvées')
    //console.log("videos trouvées: ",obj)
    //console.log("videos trouvées: ",obj["video trouvées"])
    alert("liste des suggestions mise a jour")
    

}

    function getAPIVideos(keyword,total){

        var oReq = new XMLHttpRequest();

        oReq.onload = reqListener;

        url="https://alljudo-python-youtube-api.onrender.com/getVideosByKeyword/"+keyword+"/"+total;

        oReq.open("get", url, true);

        oReq.send();

    }
    function addVideosRecentes(chaine,totalvr){

      var oReq = new XMLHttpRequest();

      oReq.onload = reqListenerVR;
      if(chaine=="Toutes"){
        url="https://alljudo-python-youtube-api.onrender.com/ajouterVideosRecentes2/all/"+totalvr+"/0";
      }else{
        url="https://alljudo-python-youtube-api.onrender.com/ajouterVideosRecentes2/"+chaine+"/"+totalvr+"/0";
      }
      

      oReq.open("get", url, true);

      oReq.send();

  }

    function getAPISuggestions(){

        var oReq = new XMLHttpRequest();

        oReq.onload = reqListenerSuggestions;

        url="https://alljudo-python-youtube-api.onrender.com/getChannelsVideosSuggestions2/"+nombreSuggestions+"/1";

        oReq.open("get", url, true);

        oReq.send();

    }

    

function loadvalidator() {


    // Fetch all the forms we want to apply custom Bootstrap validation styles to

    var forms = document.querySelectorAll('.needs-validation')

  

    // Loop over them and prevent submission

    Array.prototype.slice.call(forms)

      .forEach(function (form) {

        form.addEventListener('submit', function (event) {

          if (!form.checkValidity()) {

            event.preventDefault()

            event.stopPropagation()

          }else{

            event.preventDefault()

            event.stopPropagation()

            if(this.id){
              
              var totalvr=document.querySelector("#totalvr").value;
              var chaine=document.querySelector("#chaine").value;
              console.log("id chaine: "+chaine)
              console.log("total: "+totalvr);
              addVideosRecentes(chaine,totalvr);


            }else{
              var mot_cle=document.querySelector("#keyword").value;

              var total=document.querySelector("#total").value;

              console.log("keyword: "+mot_cle);

              console.log("total: "+total);

              getAPIVideos(mot_cle,total);
            }

            

          }

          form.classList.add('was-validated')

        }, false)

      })

      console.log("validator loaded");

      

  }





function randomIntFromInterval(min, max) { // min and max included 
    return Math.floor(Math.random() * (max - min + 1) + min)
  }

const nombreSuggestions=randomIntFromInterval(4, 14)

function deleteVideoSuggestion(element,idvideo){
    var todelete=element.parentElement.parentElement.parentElement.parentElement.parentElement
    console.log("video a supprimer: ",todelete);
    todelete.remove()
    $.ajax({

        type: 'POST',

        url: 'youtube-data-api-database.php',

        data : { video_to_delete: idvideo },

        success: function(response) {

            alert(response);

        },

        error: function(){

          alert('error!');

        }

    });
}
function deleteLastVideo(element,idvideo){
    var todelete=element.parentElement.parentElement.parentElement.parentElement.parentElement
    console.log("derniere video a supprimer: ",todelete);
    todelete.remove()
    $.ajax({

        type: 'POST',

        url: 'youtube-data-api-database.php',

        data : { last_video_to_delete: idvideo },

        success: function(response) {

            alert(response);

        },

        error: function(){

          alert('error!');

        }

    });
}
function deleteVideoRecherche(element){
    var todelete=element.parentElement.parentElement.parentElement.parentElement.parentElement
    console.log("video a supprimer: ",todelete);
    todelete.remove()
}
function ajouterVideo(vid) {

    console.log("id de la video",vid);

    var target_url = './youtube-data-api-ajouter-video.php?youtubeVideoID='+vid;
    window.open(target_url, '_blank');

   

  }


