$(document).ready(function (){
    $("#btnLogin").live('click',function(){
        $("#btnLogin").attr("disabled","disabled");
        $("#btnLogin").val(" ... ");
        login = $("#loginComment").val();
        pass  = $("#passComment").val();
        $.post("login.php", {login:"Login",username:login,password:pass,redirect:"log_target.php"}, function(data){
             $.get("/log_target.php",null,function(data){
                if(data == "ok"){
                    location.reload();
                }else{
                    $("#btnLogin").attr("disabled","");
                    $("#btnLogin").val("ok");
                    $('#errorLogin').html(data);
                }
             });
        });
    });
});
