
<!doctype html>
<html class="no-js" lang="fr">

    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport"><meta http-equiv="x-ua-compatible" content="ie=edge">
        <?php require_once("../content/scripts/header_script.php") ?>
        <title>Requêtes google indexing</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

        <link rel="apple-touch-icon" href="apple-favicon.png">
        <!-- Place favicon.ico in the root directory -->
        <link rel="icon" type="image/x-icon" href="../../images/favicon.ico" />
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/font-awesome.min.css">
        <link rel="stylesheet" href="css/fonts.css">
        <link rel="stylesheet" href="css/slider-pro.min.css" />
        <link rel="stylesheet" href="css/main.css">
        <link rel="stylesheet" href="css/responsive.css">
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
        <script src='https://www.google.com/recaptcha/api.js'></script>


        <!--<script src="js/vendor/modernizr-2.8.3.min.js"></script>-->
    </head>

    <body>
        <div class="container page-content athlètes form-inscription">
            <div class="row">
                <div class="col-sm-8 left-side">
                    <div class="row">
                        <div class="col-sm-12">
                            <h1>Indexer une page</h1>
                            <br />
                            <br />
                            <form>
                                <div class="form-group">
                                    <label class="col-sm-5" for="Url">url</label>
                                    <div class="col-sm-7">
                                        <input class="form-control" type="text" name="Url" id="url" value="" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-1 col-sm-11">
                                        <input value="Envoyer" type="submit" name="register_button" class="btn_custom" id="envoyer"/>
                                    </div>
                                </div>
                            </form>
                            <div class="col-sm-7" id="res">
                    
                            </div>
                        </div>
                    </div>
                </div> <!-- End left-side -->

                <aside class="col-sm-4">
                </aside>

            </div>

        </div> <!-- End container page-content -->



        <script src="https://code.jquery.com/jquery-1.12.0.min.js"></script>
        <script>
        window.jQuery || document.write('<script src="js/vendor/jquery-1.12.0.min.js"><\/script>')
        </script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/plugins.js"></script>
        <script src="js/jquery.jcarousel.min.js"></script>
        <script src="js/jquery.sliderPro.min.js"></script>
        <script src="js/easing.js"></script>
        <script src="js/jquery.ui.totop.min.js"></script>
        <script src="js/herbyCookie.min.js"></script>
        <script src="js/main.js"></script>
        <script type="text/javascript" src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js">
        </script>

        
    </body>

</html>

<script type="text/javascript">
    $(document).ready(function() {
        $("#envoyer").click(function() {
            url=$("#url").text
            console.log(url)
            $("#res").html(url)
            /*
           $.ajax({
               type: "POST",
               url: "api-indexation-functions.php",
               data: {
                   function: "get_data",
                   url:url,
                   
               },
               success: function(html) {
                   $("#res").html(html)
                   //console.log("success",html)
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
           */
        })
    })
</script>