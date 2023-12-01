$(document).ready(function (){

    

    $("#boutonDeco").click(function(){
        $.get("/forum/ucp.php",{mode:"logout",sid:$(this).attr('sid')},function(data){
            location.reload();
        });

    });

    $("#loginCo").focus(function(){
        if($("#loginCo").val()=="nom d'utilisateur")$(this).val("");
        $(this).css("font-style","normal");
        $(this).css("color","black");
    });

    $("#mdpCoTx").focus(function(){
        $(this).hide();
        $('#mdpCo').show();
        $('#mdpCo').focus();
    });

    $("#subCo").live('click',function(){
        $("#subCo").attr("disabled","disabled");
        $("#subCo").val(" ... ");
        login = $('#loginCo').val();
        pass  = $("#mdpCo").val();
        $.post("login.php", {
            login:"Login",
            username:login,
            password:pass,
            redirect:"log_target.php"
        }, function(data){
            $.get("log_target.php",null,function(data){
                if(data == "ok"){
                    location.reload();
                }else{
                    $("#subCo").val("OK");
                    $("#errorCo").html("Echec login.");
                    $(".inputCo").css("font-style","italic");
                    $(".inputCo").css("color","gray");
                    $("#loginCo").val("nom d'utilisateur");
                    $("#mdpCoTx").val("mot de passe");
                    $("#mdpCo").val("");
                    // $("#mdpCo").hide();
                    // $("#mdpCoTx").show();
                    $("#subCo").attr('disabled',false);
                    
                }
            });
        });
    });



$("#reg").live('click',function(){



        var challengeField=$('#recaptcha_challenge_field').val();
        var responseField=$('#recaptcha_response_field').val();
        var username=$('#username').val();
        var pass1=$('#pass1').val();
        var pass2=$('#pass2').val();
        var nom=$('#nom').val();
        var prenom=$('#prenom').val();
        var mail=$('#mail').val();
        var dn=$('#datepicker-example10').val();
        var cp=$('#cp').val();
        var ville=$('#ville').val();
        var pays=$('#pays').val();
        var grade=$('#grade').val();
        var club =$('#club').val();
        var newsletter=0;
        var offres=0;

        if ( $('#newsletter').is( ":checked" ) ){var newsletter=1;}
        if ( $('#offres').is( ":checked" ) ){var offres=1;}
     
        $.ajax({

        url: "verification-user.php",
        type: "POST",
        data: "challengeField="+challengeField+"&responseField="+responseField+"&username="+username+"&pass1="+pass1+"&pass2="+pass2+"&nom="+nom+"&prenom="+prenom+"&mail="+mail+"&dn="+dn+"&cp="+cp+"&ville="+ville+"&pays="+pays+"&grade="+grade+"&club="+club+"&newsletter="+newsletter+"&offres="+offres, 
        dataType: "json",
        success: function(data) {
         
            if (data.status=="success") {

                $("#msg").html("<br><span style='color:#009966; font-size:0.8em'>"+data.message+"<br/><br/></span>");
                $("#target").empty();
            } 
            else{

                $("#msg").html("<br><span style='color:#cc0000; font-size:0.8em'>"+data.message+"<br/><br/></span>");
            }

        },
        error: function() {
          alert("Il y avait une erreur. Essayez à nouveau s'il vous plaît!");
        }
        });
        Recaptcha.reload();
        return false;

        });


  
});


