// $(document).ready(function () {

$("#target").on('click','#register_button', function () {
	
	
	
	document.addEventListener('DOMContentLoaded', function() {
    const adImage = document.querySelector('.ad-image');
    const banDiv = document.querySelector('.ban');

    adImage.addEventListener('load', function() {
        banDiv.classList.add('transparent');
    });
});
	
	
	
	
	
	
    /* Check if the captcha is complete */
    if ($("#g-recaptcha").val()) {


		
        var captchaResponse = $("#g-recaptcha").val();
        var username = $('#pseudo').val();
        var pass1 = $('#mot_de_passe').val();
        var pass2 = $('#confirmePW').val();
        var nom = $('#nom').val();
        var prenom = $('#prenom').val();
        var mail = $('#mail').val();
        var dn = $('#naissance').val();
        var cp = $('#codePostal').val();
        var ville = $('#ville').val();
        var pays = $('#pays').val();
        var grade = $('#grade').val();
        var club = $('#club').val();
        var newsletter = 0;
        var offres = 0;
        //var datajson=
        

        if ($('#newsletter').is(":checked")) {
            var newsletter = 1;
        }
        if ($('#offres').is(":checked")) {
            var offres = 1;
        }

        var datajson = {

            "captchaResponse": captchaResponse, // The generated response from the widget sent as a POST parameter
            "username": username,
            "pass1": pass1,
            "pass2": pass2,
            "nom": nom,
            "prenom": prenom,
            "mail": mail,
            "dn": dn,
            "cp": cp,
            "ville": ville,
            "pays": pays,
            "grade": grade,
            "club": club,
            "newsletter": newsletter,
            "offres": offres
        }
        $.ajax({
			url: "/content/modules/verif-user.php",
            type: "POST",
            // data: {datajson: datajson},
            data: datajson,

            success: function (data) {
                // if( $.parseJSON(data).status == "success"){
                 // console.log(data);
                 // if (JSON.parse(data).status == "success") {
                 if (data['status'] == "success") {
                    
                    $("#msg").html("<br><span style='color:#009966; font-size:0.8em'>" + JSON.parse(data).message + "</span>");
                    $("#target").empty();
                }
                else {
                    // $("#msg").html("<br><span style='color:#cc0000; font-size:0.8em'>" + JSON.parse(data).message + "<br/><br/></span>");
                    $("#msg").html("<br><span style='color:#cc0000; font-size:0.8em'>" + JSON.parse(data).message + "</span>");

                }

            },
            error: function (data) {
                console.log("erreur");
                if (JSON.parse(data.responseText).status == "success") {
                    $("#msg").html("<br><span style='color:#009966; font-size:0.8em'>" + JSON.parse(data).message + "</span>");
                    $("#target").empty();
                   
                }
               else {
                    $("#msg").html("<br><span style='color:#cc0000; font-size:0.8em'>" + JSON.parse(data).message + "</span>");
                   
                }
            }
        });
    }

    else {
        $("#msg").html("<br><span style='color:#cc0000; font-size:0.8em'>Captcha invalide! "+$("#g-recaptcha").val()+"<br/><br/></span>");
        
    }
    return false;
});


// })

