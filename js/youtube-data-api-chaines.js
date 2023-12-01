function reqListener () {

    // convertit la chaîne du fichier en objet avec JSON.parse

    var obj = JSON.parse(this.responseText);
    if(obj["code"]==403){
        alert("erreur "+obj["code"]+" : "+obj["erreur"])
        return 0
      }
    // affiche les données comme un objet quelconque

    

    //console.log(obj[0][1])

    var totalrecu=obj.length
console.log(totalrecu,"chaines recues")
    /*for(i=0;i<totalrecu;i++){

        console.log("Nº ",i+1,obj[i][1])

    }*/
    
    parent=document.getElementById("listeChaines")

    parent.innerHTML="";

    //ajouter une chaine
    for(j=0;j<totalrecu;j++){

        var objet=obj[j][1];

        var element='<li class="list-group-item " ><div class="row justify-content-center align-items-center"><div class="col-lg-1 col-2"><img src="'
        element+=objet.image
        element+='" class="img-fluid" alt="serveur youtube surchargé"></div><div class="col-lg-6 col-6"><h4 class="mt-2 ">'
        element+=objet.titre+'</h4>'+objet.description
        element+='</div><div class="col-lg-4 col-4"><div class="row justify-content-center align-items-center"><div class="col-lg-4 col-12"><a href="'
        element+=objet.url
        var idElement="lien"+j
        element+='" target="_blank" class="btn btn-outline-success">Voir</a></div><div class="col-lg-4 col-12"><a href="#" id="'+idElement+'"  class="btn btn-outline-primary">Ajouter</a></div><div class="col-lg-4 col-12"><a href="#" class="supprimer btn btn-outline-danger">Supprimer</a></div></div></div></div></li>'
        parent.innerHTML+=element
    }
    for(j=0;j<totalrecu;j++){
        var idElement="lien"+j
        var a1=document.getElementById(idElement);
        a1.addEventListener('click', function (event) {

            var pos=event.target.id[4];

            console.log("position",pos);

            //console.log("element",obj[pos][1]);

            var dataObject=JSON.stringify(obj[pos][1])


            $.ajax({

                type: 'POST',

                url: 'youtube-data-api-database.php',

                data : { data: dataObject },

                success: function(response) {

                    alert(response);

                },

                error: function(){

                  alert('error!');

                }

            });

           

          },false)
}

//supprimer une chaine
Asupprimer=document.getElementsByClassName("supprimer")
for(j=0;j<totalrecu;j++){
    var a1=Asupprimer[j]
    a1.addEventListener('click', function (event) {
        var pos=event.target.parentElement.parentElement.parentElement.parentElement.parentElement
        pos.remove()
        // console.log("element a supprimer",pos);
      },false)
}
}

    function getAPIChannels(keyword,total){

        var oReq = new XMLHttpRequest();

        oReq.onload = reqListener;

        url="https://alljudo-python-youtube-api.onrender.com/getChannelsByKeyword/"+keyword+"/"+total+"/0";

        oReq.open("get", url, true);

        oReq.send();

    }
    function getAPIChannelsByIds(ids){

        var oReq = new XMLHttpRequest();

        oReq.onload = reqListener;

        url="https://alljudo-python-youtube-api.onrender.com/getChannelsDetailsByIds/"+ids;

        oReq.open("get", url, true);

        oReq.send();

    }

    

function loadvalidator() {

    var checkbox = document.querySelector("#byIds");
    checkbox.addEventListener('change', function() {
    if (this.checked) {
        document.getElementById("motcle").style="display:none"
        document.getElementById("nombredechaines").style="display:none"
        document.getElementById("ids").style="display:flex"
        document.getElementById("total").required=false
        document.getElementById("keyword").required=false
        document.getElementById("listeIDs").required=true
    } 
    });
    var checkbox = document.querySelector("#byKeyword");
    checkbox.addEventListener('change', function() {
    if (this.checked) {
        document.getElementById("total").required=true
        document.getElementById("keyword").required=true
        document.getElementById("listeIDs").required=false
        document.getElementById("ids").style="display:none"
        document.getElementById("motcle").style="display:flex"
        document.getElementById("nombredechaines").style="display:flex"
    } 
    });

  

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

           
            if(document.querySelector("#byIds").checked){
                var ids=document.querySelector("#listeIDs").value;
                var total=document.querySelector("#total").value;
                console.log("listeIDs: "+ids);
                console.log("total: "+total);
                getAPIChannelsByIds(ids);
            }else if(document.querySelector("#byKeyword").checked){
                var mot_cle=document.querySelector("#keyword").value;
                var total=document.querySelector("#total").value;
                console.log("keyword: "+mot_cle);
                console.log("total: "+total);
                getAPIChannels(mot_cle,total);
            }

          }

          form.classList.add('was-validated')

        }, false)

      })

      console.log("validator loaded");

  }



function deleteChannel(element,idChaine){
            var todelete=element.parentElement.parentElement.parentElement.parentElement.parentElement
            //console.log("chaine a supprimer: ",todelete);
            todelete.remove()
            $.ajax({

                type: 'POST',

                url: 'youtube-data-api-database.php',

                data : { channel_to_delete: idChaine },

                success: function(response) {

                    alert(response);

                },

                error: function(){

                  alert('error!');

                }

            });

}
