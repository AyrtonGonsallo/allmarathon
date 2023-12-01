$(document).ready(function () {


    $("#boutonDeco").click(function () {
        $.get("/forum/ucp.php", {mode: "logout", sid: $(this).attr('sid')}, function (data) {
            location.reload();
        });

    });

    $("#loginCo").focus(function () {
        if ($("#loginCo").val() == "nom d'utilisateur")$(this).val("");
        $(this).css("font-style", "normal");
        $(this).css("color", "black");
    });

    $("#mdpCoTx").focus(function () {
        $(this).hide();
        $('#mdpCo').show();
        $('#mdpCo').focus();
    });

    $("#subCo").live('click', function () {
        $("#subCo").attr("disabled", "disabled");
        $("#subCo").val(" ... ");
        login = $('#loginCo').val();
        pass = $("#mdpCo").val();
        $.post("login.php", {
            login: "Login",
            username: login,
            password: pass,
            redirect: "log_target.php"
        }, function (data) {
            $.get("log_target.php", null, function (data) {
                if (data == "ok") {
                    location.reload();
                } else {
                    $("#subCo").val("OK");
                    $("#errorCo").html("Echec login.");
                    $(".inputCo").css("font-style", "italic");
                    $(".inputCo").css("color", "gray");
                    $("#loginCo").val("nom d'utilisateur");
                    $("#mdpCoTx").val("mot de passe");
                    $("#mdpCo").val("");
                    // $("#mdpCo").hide();
                    // $("#mdpCoTx").show();
                    $("#subCo").attr('disabled', false);

                }
            });
        });
    });


    $("#regl").live('click', function () {



        //  var challengeField=$('#recaptcha_challenge_field').val();
        //var responseField=$('#recaptcha_response_field').val();
        var username = $('#username').val();
        var pass1 = $('#pass1').val();
        var pass2 = $('#pass2').val();
        var nom = $('#nom').val();
        var prenom = $('#prenom').val();
        var mail = $('#mail').val();
        var dn = $('#datepicker-example10').val();
        var cp = $('#cp').val();
        var ville = $('#ville').val();
        var pays = $('#pays').val();
        var grade = $('#grade').val();
        var club = $('#club').val();
        var newsletter = 0;
        var offres = 0;

        if ($('#newsletter').is(":checked")) {
            var newsletter = 1;
        }
        if ($('#offres').is(":checked")) {
            var offres = 1;
        }

        $.ajax({

            url: "verification-user.php",
            type: "POST",
            //data: "challengeField="+challengeField+"&responseField="+responseField+"&username="+username+"&pass1="+pass1+"&pass2="+pass2+"&nom="+nom+"&prenom="+prenom+"&mail="+mail+"&dn="+dn+"&cp="+cp+"&ville="+ville+"&pays="+pays+"&grade="+grade+"&club="+club+"&newsletter="+newsletter+"&offres="+offres,
            data: "username=" + username + "&pass1=" + pass1 + "&pass2=" + pass2 + "&nom=" + nom + "&prenom=" + prenom + "&mail=" + mail + "&dn=" + dn + "&cp=" + cp + "&ville=" + ville + "&pays=" + pays + "&grade=" + grade + "&club=" + club + "&newsletter=" + newsletter + "&offres=" + offres,
            dataType: "json",
            success: function (data) {

                if (data.status == "success") {

                    $("#msg").html("<br><span style='color:#009966; font-size:0.8em'>" + data.message + "<br/><br/></span>");
                    $("#target").empty();
                }
                else {

                    $("#msg").html("<br><span style='color:#cc0000; font-size:0.8em'>" + data.message + "<br/><br/></span>");
                }

            },
            error: function () {
                alert("username=" + username + "&pass1=" + pass1 + "&pass2=" + pass2 + "&nom=" + nom + "&prenom=" + prenom + "&mail=" + mail + "&dn=" + dn + "&cp=" + cp + "&ville=" + ville + "&pays=" + pays + "&grade=" + grade + "&club=" + club + "&newsletter=" + newsletter + "&offres=" + offres);
            }
        });
        //Recaptcha.reload();
        return false;

    });


});


$("#testCaptcha").live('click', function () {
    /* Check if the captcha is complete */
    if ($("#g-recaptcha-response").val()) {
        var captchaResponse = $("#g-recaptcha-response").val();
        var username = $('#username').val();
        var pass1 = $('#pass1').val();
        var pass2 = $('#pass2').val();
        var nom = $('#nom').val();
        var prenom = $('#prenom').val();
        var mail = $('#mail').val();
        var dn = $('#datepicker-example10').val();
        var cp = $('#cp').val();
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
        // var dataString = JSON.stringify(datajson);
        $.ajax({

            url: "verification-user.php",
            type: "POST",
            // dataType: "json",

            /* data: JSON.stringify({
             //  data:{
             captchaResponse: captchaResponse, // The generated response from the widget sent as a POST parameter
             username: username,
             pass1: pass1,
             pass2: pass2,
             nom: nom,
             prenom: prenom,
             mail: mail,
             dn: dn,
             cp: cp,
             ville: ville,
             pays: pays,
             grade: grade,
             club: club,
             newsletter: newsletter,
             offres: offres
             }),*/
            data: {datajson: datajson},

            success: function (data) {

                if (JSON.parse(data.responseText).status == "success") {
                    $("#msg").html("<br><span style='color:#009966; font-size:0.8em'>" + JSON.parse(data.responseText).message + "<br/><br/></span>");
                    $("#target").empty();
                  //  console.log("success", JSON.parse(data.responseText).message);
                    /*if (msgJson.status=="success") {

                     }*/
                }
                else {
                    $("#msg").html("<br><span style='color:#cc0000; font-size:0.8em'>" + JSON.parse(data.responseText).message + "<br/><br/></span>");

                }

            },
            error: function (data) {
                /*  $("#msg").html("<br><span style='color:#cc0000; font-size:0.8em'>" + JSON.parse(data.responseText).message + "<br/><br/></span>");
                 console.log("function fail", JSON.parse(data.responseText).message);
                 //console.log("function fail ",data);
                 console.log(datajson);*/
                if (JSON.parse(data.responseText).status == "success") {
                    $("#msg").html("<br><span style='color:#009966; font-size:0.8em'>" + JSON.parse(data.responseText).message + "<br/><br/></span>");
                    $("#target").empty();
                   // console.log("success", JSON.parse(data.responseText).message);
                    //console.log("statut ",JSON.parse(data.responseText).status);
                }
               else {
                    $("#msg").html("<br><span style='color:#cc0000; font-size:0.8em'>" + JSON.parse(data.responseText).message + "<br/><br/></span>");
                   // console.log("fail", JSON.parse(data.responseText).message);
                   // console.log("statut fail ", JSON.parse(data.responseText).status);
                }
            }
        });
    }

    else {
        $("#msg").html("<br><span style='color:#cc0000; font-size:0.8em'>Captcha invalide!<br/><br/></span>");
        // alert("Vérifiez le captcha!");
    }// alert("recapthca");
    return false;
});


$("#testCaptchaZ").live('click', function () {
    /* Check if the captcha is complete */
    if ($("#g-recaptcha-response").val()) {
        var captchaResponse = $("#g-recaptcha-response").val();
        var username = $('#username').val();
        var pass1 = $('#pass1').val();
        var pass2 = $('#pass2').val();
        var nom = $('#nom').val();
        var prenom = $('#prenom').val();
        var mail = $('#mail').val();
        var dn = $('#datepicker-example10').val();
        var cp = $('#cp').val();
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
        // var dataString = JSON.stringify(datajson);
        $.ajax({

            url: "verification-user.php",
            type: "POST",
            // dataType: "json",

            /* data: JSON.stringify({
             //  data:{
             captchaResponse: captchaResponse, // The generated response from the widget sent as a POST parameter
             username: username,
             pass1: pass1,
             pass2: pass2,
             nom: nom,
             prenom: prenom,
             mail: mail,
             dn: dn,
             cp: cp,
             ville: ville,
             pays: pays,
             grade: grade,
             club: club,
             newsletter: newsletter,
             offres: offres
             }),*/
            data: {datajson: datajson},

            success: function (data) {

                if (JSON.parse(data.responseText).status == "success") {
                    $("#msg").html("<br><span style='color:#009966; font-size:0.8em'>" + JSON.parse(data.responseText).message + "<br/><br/></span>");
                    $("#target").empty();
                  //  console.log("success", JSON.parse(data.responseText).message);
                    /*if (msgJson.status=="success") {

                     }*/
                }
                else {
                    $("#msg").html("<br><span style='color:#cc0000; font-size:0.8em'>" + JSON.parse(data.responseText).message + "<br/><br/></span>");

                }

            },
            error: function (data) {
                /*  $("#msg").html("<br><span style='color:#cc0000; font-size:0.8em'>" + JSON.parse(data.responseText).message + "<br/><br/></span>");
                 console.log("function fail", JSON.parse(data.responseText).message);
                 //console.log("function fail ",data);
                 console.log(datajson);*/
                if (JSON.parse(data.responseText).status == "success") {
                    $("#msg").html("<br><span style='color:#009966; font-size:0.8em'>" + JSON.parse(data.responseText).message + "<br/><br/></span>");
                    $("#target").empty();
                   // console.log("success", JSON.parse(data.responseText).message);
                    //console.log("statut ",JSON.parse(data.responseText).status);
                }
               else {
                    $("#msg").html("<br><span style='color:#cc0000; font-size:0.8em'>" + JSON.parse(data.responseText).message + "<br/><br/></span>");
                   // console.log("fail", JSON.parse(data.responseText).message);
                   // console.log("statut fail ", JSON.parse(data.responseText).status);
                }
            }
        });
    }

    else {
        $("#msg").html("<br><span style='color:#cc0000; font-size:0.8em'>Captcha invalide!<br/><br/></span>");
        // alert("Vérifiez le captcha!");
    }// alert("recapthca");
    return false;
});

$("#testCaptchaNew").live('click', function () {
	
    /* Check if the captcha is complete */
    if ($("#g-recaptcha-response").val()) {
		
        var captchaResponse = $("#g-recaptcha-response").val();
        var username = $('#username').val();
        var pass1 = $('#pass1').val();
        var pass2 = $('#pass2').val();
        var nom = $('#nom').val();
        var prenom = $('#prenom').val();
        var mail = $('#mail').val();
        var dn = $('#datepicker-example10').val();
        var cp = $('#cp').val();
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
        // var dataString = JSON.stringify(datajson);
        $.ajax({
			url: "verif-user.php",
            type: "POST",
            // dataType: "json",

            /* data: JSON.stringify({
             //  data:{
             captchaResponse: captchaResponse, // The generated response from the widget sent as a POST parameter
             username: username,
             pass1: pass1,
             pass2: pass2,
             nom: nom,
             prenom: prenom,
             mail: mail,
             dn: dn,
             cp: cp,
             ville: ville,
             pays: pays,
             grade: grade,
             club: club,
             newsletter: newsletter,
             offres: offres
             }),*/
            data: {datajson: datajson},

            success: function (data) {
              //  if (JSON.parse(data.responseText).status == "success") {
                if (JSON.parse(data).status == "success") {
                    $("#msg").html("<br><span style='color:#009966; font-size:0.8em'>" + JSON.parse(data).message + "<br/><br/></span>");
                    $("#target").empty();
                  //  console.log("success", JSON.parse(data.responseText).message);
                    /*if (msgJson.status=="success") {

                     }*/
                }
                else {
                    $("#msg").html("<br><span style='color:#cc0000; font-size:0.8em'>" + JSON.parse(data).message + "<br/><br/></span>");

                }

            },
            error: function (data) {
                /*  $("#msg").html("<br><span style='color:#cc0000; font-size:0.8em'>" + JSON.parse(data.responseText).message + "<br/><br/></span>");
                 console.log("function fail", JSON.parse(data.responseText).message);
                 //console.log("function fail ",data);
                 console.log(datajson);*/
                if (JSON.parse(data.responseText).status == "success") {
                    $("#msg").html("<br><span style='color:#009966; font-size:0.8em'>" + JSON.parse(data).message + "<br/><br/></span>");
                    $("#target").empty();
                   // console.log("success", JSON.parse(data.responseText).message);
                    //console.log("statut ",JSON.parse(data.responseText).status);
                }
               else {
                    $("#msg").html("<br><span style='color:#cc0000; font-size:0.8em'>" + JSON.parse(data).message + "<br/><br/></span>");
                   // console.log("fail", JSON.parse(data.responseText).message);
                   // console.log("statut fail ", JSON.parse(data.responseText).status);
                }
            }
        });
    }

    else {
        $("#msg").html("<br><span style='color:#cc0000; font-size:0.8em'>Captcha invalide!<br/><br/></span>");
        // alert("Vérifiez le captcha!");
    }// alert("recapthca");
    return false;
});


/*$("#formcaptchaaa").live('submit', function () {
    /* Check if the captcha is complete */
  /*  if ($("#g-recaptcha-response").val()) {
        var username = $('#username').val();
        var pass1 = $('#pass1').val();
        var pass2 = $('#pass2').val();
        var nom = $('#nom').val();
        var prenom = $('#prenom').val();
        var mail = $('#mail').val();
        var dn = $('#datepicker-example10').val();
        var cp = $('#cp').val();
        var ville = $('#ville').val();
        var pays = $('#pays').val();
        var grade = $('#grade').val();
        var club = $('#club').val();
        var newsletter = 0;
        var offres = 0;

        if ($('#newsletter').is(":checked")) {
            var newsletter = 1;
        }
        if ($('#offres').is(":checked")) {
            var offres = 1;
        }
        $.ajax({
            type: 'POST',
            url: "verifCaptcha.php", // The file we're making the request to
            dataType: 'html',
            async: true,
            data: {
                captchaResponse: $("#g-recaptcha-response").val() // The generated response from the widget sent as a POST parameter
            },
            success: function (data) {
                alert("everything looks ok");
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                alert("You're a bot");
            }
        });
    } else {
        alert("Please fill the captcha!");
    }// alert("recapthca");
});*/


