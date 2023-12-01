$(document).ready(function() {

    $("#formResult").submit(function() {

        var result = {
            rang:$("#rang").val(),
            poids:$("#poids").val(),
            date:$("#datepicker").val(),
            lieu:$("#lieu").val(),
            catAge:$("#catAge").val(),
            compType:$("#compType").val(),
            compNiv:$("#compNiv").val(),
            compDep:$("#compDep").val(),
            compReg:$("#compReg").val(),
            compFr:$("#compFr").val(),
            user:$("#userResult").val(),
            championID:$("#championID").val()
        }


        if(result.compType == "championnat" && result.compNiv ==""){
            alert("Veuillez choisir un niveau svp.")
        }else{
            bool = controle(result);
            if(bool == true){
                $.post("./php/adminResultat.php", result,
                    function(data) {
                            alert('Votre résultat a bien été energistré!');
                            $("#formResult")[0].reset();
                        
                    });
            }
        }

    
        return false;
    });
});


function controle(form){
    var rang = $.trim(form.rang);
    var poids = $.trim(form.poids);
    var date = form.date;
    var lieu = $.trim(form.lieu);
    var msg = new Array();
    var msgErreur = new Array();

    if(!rang.length){
        msg.push($('#rang'));
        msgErreur.push("Renseignez votre rang.");
    }

    if(!poids.length){
        msg.push($('#poids'));
        msgErreur.push("Renseignez votre poids.");
    }

    if(!date.length){
        msg.push($('#datepicker'));
        msgErreur.push("Renseignez une date.");
    }

    if(!lieu.length){
        msg.push($('#lieu'));
        msgErreur.push("Renseignez un lieu.");
    }

    if( msgErreur.length ) {
        alert(msgErreur.join('\n'));
        msg[0].focus();
        bool = false;
    } else {
        bool = true;
    }
    return(bool);
}