
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- InstanceBegin template="/Templates/modeleadmin.dwt" codeOutsideHTMLIsLocked="false" -->

<head>
    <meta charset="utf-8">
    <link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
    <meta http-equiv="Content-Type" content="text/html;" /> <!-- charset=iso-8859-1 -->
    <script src="../fonction/ui/js/jquery-1.3.2.min.js" type="text/javascript"></script>
    <script src="../fonction/ui/js/datepicker_time.min.js" type="text/javascript"></script>
    <script src="../fonction/ui/js/ui.datepicker-fr.js" type="text/javascript"></script>
    <link href="../fonction/ui/css/timepicker.css" rel="stylesheet" type="text/css" />
    <link href="../fonction/ui/css/ui-darkness/jquery-ui-1.7.1.custom.css" rel="stylesheet" type="text/css" />
    <script src="../fonction/ui/js/timepicker.js" type="text/javascript"></script>
    <script type="text/javascript" src="../fonction/tablesorter/jquery-1.3.2.min.js"></script>
    <script type="text/javascript" src="../fonction/tablesorter/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="../fonction/tablesorter/jquery.tablesorter.pager.js"></script>


    <!-- InstanceBeginEditable name="doctitle" -->
    <title>allmarathon admin</title>
    

   
    

</head>

<body>
    <?php require_once "menuAdmin.php"; ?>
    <div class="row justify-content-center">
        <div>
            <fieldset>
                <legend>Mettre à jour une URL</legend>
                <form>
                    
                    <table>
                        
                        <tr>
                            <td align="right"><label for="url">url : </label></td>
                            <td><input  type="text" name="Url" id="url-maj" value="" /></td>
                        </tr>
                        
                        <tr align="center">
                            <td colspan="2">
                                <button type="button" id="envoyer-maj" class="envoyer">
                                    mettre à jour
                                </button>
                            </td>
                        </tr>
                    </table>
                </form>
            
                <div>
                    <div id="success-maj" class="success">
                        
                    </div>
                    <div id="failure-maj" class="failure">
                        
                    </div>
                
                </div>
            </fieldset>
        </div>
        <div>
            <fieldset>
                <legend>Supprimer une URL</legend>
                <form>
                    
                    <table>
                        
                        <tr>
                            <td align="right"><label for="url">url : </label></td>
                            <td><input  type="text" name="Url" id="url-sup" value="" /></td>
                        </tr>
                        
                        <tr align="center">
                            <td colspan="2">
                                <button type="button" id="envoyer-sup" class="envoyer">
                                Supprimer
                                </button>
                            </td>
                        </tr>
                    </table>
                </form>
            
                <div>
                    <div id="success-sup" class="success">
                        
                    </div>
                    <div id="failure-sup" class="failure">
                        
                    </div>
                
                </div>
            </fieldset>
        </div>
        
    </div>
    

    
</body>
<!-- InstanceEnd -->

</html>


<script type="text/javascript">
    $(document).ready(function() {
        $("#success-maj").hide()
        $("#failure-maj").hide()
        $("#envoyer-maj").click(function() {
            url=$("#url-maj").val()
            
           
            
           $.ajax({
               type: "POST",
               url: "api-indexation-functions.php",
               data: {
                   function: "update_url",
                   url:url,
                   
               },
               success: function(html) {
                   var obj=JSON.parse(html)
                    code=obj.code
                    response=JSON.parse(obj.repsonse)
                   console.log("code", code)
                   console.log("reponse",response ) 
                   if(code==200){
                        time=response.urlNotificationMetadata.latestUpdate.notifyTime
                        var date = new Date(time);
                        var year = date.getFullYear();
                        var month = date.getMonth()+1;
                        var day = date.getDate();
                        var h = date.getHours();
                        var m = date.getMinutes();
                        var s = date.getSeconds();
                        url=response.urlNotificationMetadata.latestUpdate.url
                        $("#success-maj").html(date.toLocaleString("fr-FR")+" - L'url \""+url+"\" a été mise à jour !")
                        $("#success-maj").show()
                    }else{
                        message=response.error.message
                        $("#failure-maj").html(message)
                        $("#failure-maj").show()
                   }
                   
               },
               error: function (jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    console.log("error",msg)
                },
           });
           
        })

        $("#success-sup").hide()
        $("#failure-sup").hide()
        $("#envoyer-sup").click(function() {
            url=$("#url-sup").val()
            
           
            
           $.ajax({
               type: "POST",
               url: "api-indexation-functions.php",
               data: {
                   function: "delete_url",
                   url:url,
                   
               },
               success: function(html) {
                   var obj=JSON.parse(html)
                    code=obj.code
                    response=JSON.parse(obj.repsonse)
                   console.log("code", code)
                   console.log("reponse",response ) 
                   if(code==200){
                        time=response.urlNotificationMetadata.latestRemove.notifyTime
                        var date = new Date(time);
                        var year = date.getFullYear();
                        var month = date.getMonth()+1;
                        var day = date.getDate();
                        var h = date.getHours();
                        var m = date.getMinutes();
                        var s = date.getSeconds();
                        url=response.urlNotificationMetadata.latestRemove.url
                        $("#success-sup").html(date.toLocaleString("fr-FR")+" - L'url \""+url+"\" a été suprimée ! ")
                        $("#success-sup").show()
                    }else{
                        message=response.error.message
                        $("#failure-sup").html(message)
                        $("#failure-sup").show()
                   }
                   
               },
               error: function (jqXHR, exception) {
                    var msg = '';
                    if (jqXHR.status === 0) {
                        msg = 'Not connect.\n Verify Network.';
                    } else if (jqXHR.status == 404) {
                        msg = 'Requested page not found. [404]';
                    } else if (jqXHR.status == 500) {
                        msg = 'Internal Server Error [500].';
                    } else if (exception === 'parsererror') {
                        msg = 'Requested JSON parse failed.';
                    } else if (exception === 'timeout') {
                        msg = 'Time out error.';
                    } else if (exception === 'abort') {
                        msg = 'Ajax request aborted.';
                    } else {
                        msg = 'Uncaught Error.\n' + jqXHR.responseText;
                    }
                    console.log("error",msg)
                },
           });
           
        })
    })
</script>

<style>
    .success{
        background-color: #90d390;
        color: white;
        font-size: 16px;
        font-weight: bolder;
        padding: 20px;
    }
    .failure{
        background-color: #dd0c0c;
        color: white;
        font-size: 16px;
        font-weight: bolder;
        padding: 20px;
    }
    .envoyer{
        padding: 10px 20px;
        background-color: #fbff0b;
        border: none;
        font-weight: bold;
        text-transform: uppercase;
    }
    .row{
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap:50px;
    }
    input{
        width:90% !important;
    }
    table{
        width:95% !important;
    }

    @media only screen and (max-width:801px) {
        .row{
             grid-template-columns: 1fr !important;
        }
    }
    </style>

