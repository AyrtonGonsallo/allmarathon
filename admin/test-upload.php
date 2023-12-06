<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
    //verif de validiter session
    if(!isset($_SESSION['admin']) || !isset($_SESSION['login']))
    {
        header('Location: login.php');
                exit();
    }

    // require_once '../database/connection.php';
    require_once '../database/connexion.php';


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta http-equiv="Content-Type" content="text/html; " /><!--  charset=iso-8859-1 -->
<!-- <script src="../fonction/ui/js/jquery-1.3.2.min.js" type="text/javascript"></script> -->
<script type = "text/javascript" src = "https://code.jquery.com/jquery-1.12.0.min.js"></script>
<!-- <script src="../fonction/ui/js/datepicker_time.min.js" type="text/javascript"></script>
<script src="../fonction/ui/js/ui.datepicker-fr.js" type="text/javascript"></script>
<link href="../fonction/ui/css/timepicker.css" rel="stylesheet" type="text/css" /> -->
<link href="http://code.jquery.com/ui/1.10.2/jquery-ui.js" rel="stylesheet" type="text/css" />
<!-- <script src="../fonction/ui/js/timepicker.js" type="text/javascript"></script> -->
<link rel="stylesheet" href="../fonction/nyroModal-1.5.0/styles/nyroModal.css" type="text/css" media="screen" />
<script type="text/javascript" src="../fonction/nyroModal-1.5.0/js/jquery.nyroModal-1.5.0.min.js"></script>
<script type="text/javascript" src="../Scripts/tiny_mce/tiny_mce.js"></script>

<link href="../css/jquery.filer.css" type="text/css" rel="stylesheet" />
<link href="../css/jquery.filer-dragdropbox-theme.css" type="text/css" rel="stylesheet" />


<script src="../fonction/jquery-filer/jquery.filer.min.js"></script>
<script src="../fonction/jquery-filer/custom_filer.js"></script>

<!-- <script type="text/javascript" src="../Scripts/tiny_mce_init.js"></script> -->

<script type="text/javascript">
tinyMCE.init({
    // General options
    convert_urls : false,
    mode : "exact",
    elements : "Texte",
    theme : "advanced",
    plugins : "safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
    // Theme options
    theme_advanced_toolbar_location : "top",
    theme_advanced_toolbar_align : "left",
    theme_advanced_statusbar_location : "bottom",
    theme_advanced_resizing : true,
    theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,formatselect,fontselect,fontsizeselect",
    theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,link,image,cleanup,code,|,forecolor,backcolor",
    theme_advanced_buttons3 : "undo,redo,|,visualaid,|,tablecontrols"
});
     function modal(name)
    {
        $.nyroModalManual({
                 url : name
           });
        return false;
    };
</script>

<link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">

<title>allmarathon admin</title>

<script type="text/javascript">
    // $(function() {
    //      $('#timepicker').datetime({
    //         userLang    : 'en',
    //         americanMode: false
    //     });
    // });

</script>

<script type="text/javascript">
    function addCompletion(str,index){
        tab     = str.split(':');
        idChamp  = tab[0];
        name     = tab[1];
        nameLink = tab[2];
        document.getElementById("autocomp"+index).style.display = "none";
        $('#temp1').val('');
        $('#result').html('<a href="/athlete-'+idChamp+'-'+nameLink+'.html">'+name+'</a> ');
    }

    $(document).ready(function(){
        $('#temp1').keyup(function() {
            if($(this).val().length > 3)
                $.get('resultatAutoCompletionLien.php',{id: 1, str: $(this).val()},function(data){
                    $('#autocomp1').show();
                    $('#autocomp1').html(data);
                });
        });

        $('#cut').click(function(){
            $('#temp1').select();

        });
    });
</script>
</head>

<body>
<?php require_once "menuAdmin.php"; ?>

<fieldset style="float:left;">
<form action="" method="post" enctype="multipart/form-data">
                              <input type="file" name="files[]" id="filer_input2" multiple="multiple">
                              <input type="submit" value="Envoyer" class="btn_custom pull-right" name="photo_sub"><br>
                        </form>
</fieldset>
</body>
</html>


