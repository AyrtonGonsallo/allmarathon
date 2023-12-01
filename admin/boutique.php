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

    if($_SESSION['admin'] == false){
        header('Location: login.php');
        exit();
    }

    require_once '../database/connexion.php';

    $sort = "<a href='./boutique.php?sort=a-z'><i class='fa fa-sort-alpha-asc' aria-hidden='true'></i></a>";

    try{
        if (!isset($_GET["sort"])) {
           // $_GET["sort"] = "dsfsd";
        }
        
            if (isset($_GET["sort"])) {
                if ($_GET["sort"] == "a-z") {
                    $sort = "<a href='./boutique.php?sort=z-a'><i class='fa fa-sort-alpha-desc' aria-hidden='true'></i></a>";
                    $all = $bdd->prepare("SELECT * FROM article ORDER BY type ASC");
                    $all->execute();
        
                    $excep = $bdd->prepare("SELECT * FROM `article` where type = 'excep' ORDER BY type ASC");
                    $excep->execute();
        
                    $perma = $bdd->prepare("SELECT * FROM `article` where type = 'perma' ORDER BY type ASC");
                    $perma->execute();
                    
                } else {
                    $sort = "<a href='./boutique.php?sort=a-z'><i class='fa fa-sort-alpha-asc' aria-hidden='true'></i></a>";
                    $all = $bdd->prepare("SELECT * FROM article ORDER BY type DESC");
                    $all->execute();
        
                    $excep = $bdd->prepare("SELECT * FROM `article` where type = 'excep' ORDER BY type DESC");
                    $excep->execute();
        
                    $perma = $bdd->prepare("SELECT * FROM `article` where type = 'perma' ORDER BY type DESC");
                    $perma->execute();
                    
                }
                
                
            } else {
                $all = $bdd->prepare("SELECT * FROM article ORDER BY id DESC");
                $all->execute();
    
                $excep = $bdd->prepare("SELECT * FROM `article` where type = 'excep' ORDER BY id DESC");
                $excep->execute();
    
                $perma = $bdd->prepare("SELECT * FROM `article` where type = 'perma' ORDER BY id DESC");
                $perma->execute();
            }
            
            
        
        }
        catch(Exception $e)
        {
            die('Erreur : ' . $e->getMessage());
        }

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="/Templates/modeleadmin.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link href="../styles/admin2009.css" rel="stylesheet" type="text/css" /><link rel="icon" type="image/x-icon" href="../images/favicon.ico">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="../../css/jquery.filer.css" type="text/css" rel="stylesheet" />
<link href="../../css/jquery.filer-dragdropbox-theme.css" type="text/css" rel="stylesheet" />
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js" integrity="sha384-b/U6ypiBEHpOf/4+1nzFpr53nxSS+GLCkfwBdFNTxtclqqenISfwAzpKaMNFNmj4" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
  <script type="text/javascript" src="../Scripts/tiny_mce/tiny_mce.js"></script>
  <script>
    tinyMCE.init({
    // General options
    convert_urls : false,
    mode : "textareas",
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
  </script>
  <script>
  $( function() {
    $( "#tabs" ).tabs();
  } );
  </script>
<!-- InstanceBeginEditable name="doctitle" -->
<title>allmarathon admin</title>


</head>

    <body>
<?php require_once "menuAdmin.php"; ?>
        <div class="container">
            <div class="col-12">
                <legend>Liste des articles</legend>
                <p data-placement="top" data-toggle="tooltip" title="Add"><button class="btn btn-success btn-xs" data-title="Add" data-toggle="modal" data-target="#add" >Ajouter un article</button></p>
                <div id="tabs">
                <ul>
                    <li><a href="#tabs-1">Toutes les Offres</a></li>
                    <li><a href="#tabs-2">Offres Permanentes</a></li>
                    <li><a href="#tabs-3">Offres Exceptionnelles</a></li>
                </ul>
                <div id="tabs-1">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>type <?php echo $sort; ?></th>
                        <th>Editer</th>
                        <th>Supprimer</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php 
                        while ( $row  = $all->fetch(PDO::FETCH_ASSOC)) {
                            $class = "";
                            if ($row['type'] == "perma") {
                                $class = "table-success";
                            } else {
                                if ($row['type'] == "excep") {
                                    $class = "table-danger";
                                }
                            }
                        
                            
                    ?>     
                    <tr class="<?php echo $class; ?>">
                    <td><span class="nom"><?php echo $row['nom']; ?></span></td>
                    <td><span class="prix"><?php echo $row['prix']; ?></span> €</td>
                    <td>Offre <span class="type"><?php echo $row['type']; ?></span></td>
                    <td style="display:none;"><span class="video"><?php echo $row['video']; ?></span></td>
                    <!-- <td style="display:none;"><span class="descr"><?php/* echo $row['descr'];*/ ?></span></td> -->
                    <td style="display:none;"><input type="text" value="<?php echo $row['descr']; ?>" class="descr"></td>
                    <td style="display:none;"><span class="paypal"><?php echo $row['code_paypal']; ?></span></td>
                    <td style="display:none;"><span class="offre"><?php echo $row['offre']; ?></span></td>
                    <td style="display:none;"><span class="marque"><?php echo $row['marque']; ?></span></td>
                    <td style="display:none;"><span class="livraison"><?php echo $row['livraison']; ?></span></td>
                    <td style="display:none;"><span class="transporteur"><?php echo $row['transporteur']; ?></span></td>
                    <td style="display:none;"><span class="old_prix"><?php echo $row['old_prix']; ?></span></td>
                        <td>
                            <p data-placement="top" data-toggle="tooltip" title="Edit"><button class="btn btn-primary btn-xs edit-article" id='edit<?php echo $row['id']; ?>' data-title="Edit" data-toggle="modal" data-target="#edit" ><i class="fa fa-align-justify" aria-hidden="true"></i></button></p>
                            <p data-placement="top" data-toggle="tooltip" title="Edit-img"><button class="btn btn-primary btn-xs edit-article-img" id='edit_img<?php echo $row['id']; ?>' data-title="Edit Images" data-toggle="modal" data-target="#edit_img" ><i class="fa fa-picture-o" aria-hidden="true"></i></button></p>
                        </td>
                        <td>
                            <p data-placement="top" data-toggle="tooltip" title="Delete"><button class="btn btn-danger btn-xs delete-article" id='delete<?php echo $row['id']; ?>' data-title="Delete" data-toggle="modal" data-target="#delete" ><i class="fa fa-trash" aria-hidden="true"></i></button></p>
                        </td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
                </div>
                <div id="tabs-2">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>type</th>
                        <th>Editer</th>
                        <th>Supprimer</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php 
                        while ( $row  = $perma->fetch(PDO::FETCH_ASSOC)) {
                                $class = "table-success";
                    ?>     
                    <tr class="<?php echo $class; ?>">
                    <td><span class="nom"><?php echo $row['nom']; ?></span></td>
                    <td><span class="prix"><?php echo $row['prix']; ?></span> €</td>
                    <td>Offre <span class="type"><?php echo $row['type']; ?></span></td>
                    <td style="display:none;"><span class="video"><?php echo $row['video']; ?></span></td>
                    <!-- <td style="display:none;"><span class="descr"><?php/* echo $row['descr'];*/ ?></span></td> -->
                    <td style="display:none;"><input type="text" value="<?php echo $row['descr']; ?>" class="descr"></td>
                    <td style="display:none;"><span class="paypal"><?php echo $row['code_paypal']; ?></span></td>
                    <td style="display:none;"><span class="offre"><?php echo $row['offre']; ?></span></td>
                    <td style="display:none;"><span class="marque"><?php echo $row['marque']; ?></span></td>
                    <td style="display:none;"><span class="livraison"><?php echo $row['livraison']; ?></span></td>
                    <td style="display:none;"><span class="transporteur"><?php echo $row['transporteur']; ?></span></td>
                    <td style="display:none;"><span class="old_prix"><?php echo $row['old_prix']; ?></span></td>
                        <td>
                            <p data-placement="top" data-toggle="tooltip" title="Edit"><button class="btn btn-primary btn-xs edit-article" id='edit<?php echo $row['id']; ?>' data-title="Edit" data-toggle="modal" data-target="#edit" ><i class="fa fa-align-justify" aria-hidden="true"></i></button></p>
                            <p data-placement="top" data-toggle="tooltip" title="Edit-img"><button class="btn btn-primary btn-xs edit-article-img" id='edit_img<?php echo $row['id']; ?>' data-title="Edit Images" data-toggle="modal" data-target="#edit_img" ><i class="fa fa-picture-o" aria-hidden="true"></i></button></p>
                        </td>
                        <td>
                            <p data-placement="top" data-toggle="tooltip" title="Delete"><button class="btn btn-danger btn-xs delete-article" id='delete<?php echo $row['id']; ?>' data-title="Delete" data-toggle="modal" data-target="#delete" ><i class="fa fa-trash" aria-hidden="true"></i></button></p>
                        </td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
                </div>
                <div id="tabs-3">
                <table class="table">
                    <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prix</th>
                        <th>type</th>
                        <th>Editer</th>
                        <th>Supprimer</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php 
                        while ( $row  = $excep->fetch(PDO::FETCH_ASSOC)) {
                                $class = "table-danger";
                    ?>     
                    <tr class="<?php echo $class; ?>">
                    <td><span class="nom"><?php echo $row['nom']; ?></span></td>
                    <td><span class="prix"><?php echo $row['prix']; ?></span> €</td>
                    <td>Offre <span class="type"><?php echo $row['type']; ?></span></td>
                    <td style="display:none;"><span class="video"><?php echo $row['video']; ?></span></td>
                    <!-- <td style="display:none;"><span class="descr"><?php/* echo $row['descr'];*/ ?></span></td> -->
                    <td style="display:none;"><input type="text" value="<?php echo $row['descr']; ?>" class="descr"></td>

                    <td style="display:none;"><span class="paypal"><?php echo $row['code_paypal']; ?></span></td>
                    <td style="display:none;"><span class="offre"><?php echo $row['offre']; ?></span></td>
                    <td style="display:none;"><span class="marque"><?php echo $row['marque']; ?></span></td>
                    <td style="display:none;"><span class="livraison"><?php echo $row['livraison']; ?></span></td>
                    <td style="display:none;"><span class="transporteur"><?php echo $row['transporteur']; ?></span></td>
                    <td style="display:none;"><span class="old_prix"><?php echo $row['old_prix']; ?></span></td>
                        <td>
                        <p data-placement="top" data-toggle="tooltip" title="Edit"><button class="btn btn-primary btn-xs edit-article" id='edit<?php echo $row['id']; ?>' data-title="Edit" data-toggle="modal" data-target="#edit" ><i class="fa fa-align-justify" aria-hidden="true"></i></button></p>
                            <p data-placement="top" data-toggle="tooltip" title="Edit-img"><button class="btn btn-primary btn-xs edit-article-img" id='edit_img<?php echo $row['id']; ?>' data-title="Edit Images" data-toggle="modal" data-target="#edit_img" ><i class="fa fa-picture-o" aria-hidden="true"></i></button></p>
                        </td>
                        <td>
                            <p data-placement="top" data-toggle="tooltip" title="Delete"><button class="btn btn-danger btn-xs delete-article" id='delete<?php echo $row['id']; ?>' data-title="Delete" data-toggle="modal" data-target="#delete" ><i class="fa fa-trash" aria-hidden="true"></i></button></p>
                        </td>
                    </tr>
                    <?php } ?>
                    </tbody>
                </table>
                </div>
                </div>
            </div>
            <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                            <h4 class="modal-title custom_align" id="Heading">Editer </h4>
                        </div>
                        <form action="operations_article.php" method="post">
                        <div class="modal-body">
                            <div class="form-group">
                            <input class="form-control " name="nom" type="text" placeholder="Nom de l'article" >
                            <input class="form-control " name="id" type="hidden" >
                            </div>
                            <div class="form-group">
                                <input class="form-control " name="offre" type="text" placeholder="Offre" >
                            </div>
                            <div class="form-group">
                                <input class="form-control " name="marque" type="text" placeholder="marque" >
                            </div>
                            <div class="form-group">
                                <input class="form-control " name="livraison" type="text" placeholder="livraison en jours" >    
                            </div>
                            <div class="form-group">
                                <input class="form-control " name="transporteur" type="text" placeholder="transporteur" >
                            </div>
                            <div class="form-group">
                                <input class="form-control " name="prix" type="number" step="0.01" placeholder="prix de l'article">
                            </div>
                            <div class="form-group">
                                <input class="form-control " name="old_prix" type="number" step="0.01" placeholder="prix avant promo" >
                            </div>
                            <div class="form-group">
                                <input class="form-control " name="video" type="text" placeholder="Code youtube" >
                            </div>
                            <div class="form-group">
                                <input class="form-control " name="paypal" type="text" placeholder="Code Paypal" >
                            </div>
                            <div class="form-group">
                            <textarea rows="2" class="form-control" name="descr" ></textarea>
                            </div>
                            <div class="form-group">
                                <label for="types">Type d'Offre</label>
                                <select name="type" class="form-control">
                                    <option value="normale">Nouvelle</option>
                                    <option value="excep">Exceptionnelle</option>
                                    <option value="perma">Permanente</option>
                                </select>   
                            </div>
                        </div>
                        <div class="modal-footer ">
                            <button type="submit" name="submit" value="edit_article" class="btn btn-warning btn-lg" style="width: 100%;"><i class="fa fa-floppy-o" aria-hidden="true"></i> Confirmer</button>
                        </div>
                        </form>
                    </div>
                    <!-- /.modal-content --> 
                </div>
                <!-- /.modal-dialog --> 
                </div>
                <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                            <h4 class="modal-title custom_align" id="Heading">Supprimer</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-danger"><i class="fa fa-trash-o" aria-hidden="true"></i> Vous voulez vraiment supprimer cet article?</div>
                        </div>
                        <div class="modal-footer ">
                                <form method="post" action="operations_article.php">
                                <input type='hidden' name='id' value='' required />
                                <button type="submit" name="submit" id="del-article" value="del_article" class="btn btn-danger" ><i class="fa fa-check" aria-hidden="true"></i> Confirmer</button>
                                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban" aria-hidden="true"></i> Annuler</button>
                            </form>
                        </div>
                    </div>
                    <!-- /.modal-content --> 
                </div>
                <!-- /.modal-dialog --> 
                </div>
                <div class="modal fade" id="add" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                            <h4 class="modal-title custom_align" id="Heading">Ajouter un article</h4>
                        </div>
                        <form action="operations_article.php" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-group">
                                <input class="form-control " name="nom" type="text" placeholder="Nom de l'article" >
                            </div>
                            <div class="form-group">
                                <input class="form-control " name="offre" type="text" placeholder="Offre" >
                            </div>
                            <div class="form-group">
                                <input class="form-control " name="marque" type="text" placeholder="marque" >
                            </div>
                            <div class="form-group">
                                <input class="form-control " name="livraison" type="text" placeholder="livraison en jours" >    
                            </div>
                            <div class="form-group">
                                <input class="form-control " name="transporteur" type="text" placeholder="transporteur" >
                            </div>
                            <div class="form-group">
                                <input class="form-control " name="prix" input type="number" step="0.01" placeholder="prix de l'article" >
                            </div>
                            <div class="form-group">
                                <input class="form-control " name="old_prix" type="number" step="0.01" placeholder="prix avant promo" >
                            </div>
                            <div class="form-group">
                                <label for="fileToUploadadd" class="btn">img présentative</label>
                                <input type="file" name="fileToUpload" id="fileToUploadadd" accept=".jpg, .jpeg, .png">
                            </div>
                            <div class="form-group">
                                <label for="galeryadd" class="btn">Galery</label>
                                <input type="file" name="files[]" id="galeryadd" multiple="multiple" accept=".jpg, .jpeg, .png">
                            </div>
                            <div class="form-group">
                                <input class="form-control " name="video" type="text" placeholder="Code youtube" >
                            </div>
                            <div class="form-group">
                                <input class="form-control " name="paypal" type="text" placeholder="Code Paypal">
                            </div>
                            <div class="form-group">
                                <textarea rows="2" class="form-control" name="descr"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="types">Type d'Offre</label>
                                <select name="type" class="form-control" id="type">
                                    <option value="normale">Nouvelle</option>
                                    <option value="excep">Exceptionnelle</option>
                                    <option value="perma">Permanente</option>
                                </select>   
                            </div>
                        </div>
                        <div class="modal-footer ">
                        <button type="submit" name="submit" value="add_article" class="btn btn-success" ><i class="fa fa-check" aria-hidden="true"></i> Ajouter</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-ban" aria-hidden="true"></i> Annuler</button>
                        </div>
                        </form>
                    </div>
                    <!-- /.modal-content --> 
                    
                </div>
                <!-- /.modal-dialog --> 
                </div>
                
            <div class="modal fade" id="edit_img" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times" aria-hidden="true"></i></button>
                            <h4 class="modal-title custom_align" id="Heading">Edition des medias</h4>
                        </div>
                        <form action="operations_article.php" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="fileToUploadadd" class="btn">img présentative</label>
                                <input type="file" name="fileToUpload" id="fileToUploadadd" accept=".jpg, .jpeg, .png">
                                <input class="form-control " name="id" type="hidden" required>
                            </div>
                            <div class="form-group" id="art_img">
                            </div>
                            <div class="form-group">
                                <label for="galeryadd" class="btn">Galery</label>
                                <input type="file" name="files[]" id="galeryadd" multiple="multiple" accept=".jpg, .jpeg, .png">
                            </div>
                            <div class="form-group" id="art_galery">
                            </div>
                        </div>
                        <div class="modal-footer ">
                            <button type="submit" name="submit" value="edit_article_img" class="btn btn-warning btn-lg" style="width: 100%;"><i class="fa fa-floppy-o" aria-hidden="true"></i> Confirmer</button>
                        </div>
                        </form>
                    </div>
                    <!-- /.modal-content --> 
                </div>
                <!-- /.modal-dialog --> 
                </div>
        </div>
        <script>
        jQuery(document).ready(function($){
            $('.edit-article').click(function(e) {
                //var id = $(this).attr('id');
                var id = $(this).attr('id').substring(4,$(this).attr('id').length);
                var nom = $(this).parents("tr").find(".nom").text();
                var prix = $(this).parents("tr").find(".prix").text();
                var old_prix = $(this).parents("tr").find(".old_prix").text();
                var descr = $(this).parents("tr").find(".descr").val();
                var video = $(this).parents("tr").find(".video").text();
                var type = $(this).parents("tr").find(".type").text();
                var paypal = $(this).parents("tr").find(".paypal").text();
                var offre = $(this).parents("tr").find(".offre").text();
                var marque = $(this).parents("tr").find(".marque").text();
                var livraison = $(this).parents("tr").find(".livraison").text();
                var transporteur = $(this).parents("tr").find(".transporteur").text();
                console.log(descr);
                $("#edit").find("[name='id']").val(id);
                $("#edit").find("[name='nom']").val(nom);
                $("#edit").find("[name='prix']").val(prix);
                $("#edit").find("[name='old_prix']").val(old_prix);
                $("#edit").find("[name='descr']").val(descr);
                $("#edit").find("[name='video']").val(video);
                $("#edit").find("[name='type']").val(type);
                $("#edit").find("[name='paypal']").val(paypal);
                $("#edit").find("[name='offre']").val(offre);
                $("#edit").find("[name='marque']").val(marque);
                $("#edit").find("[name='livraison']").val(livraison);
                $("#edit").find("[name='transporteur']").val(transporteur);
                tinyMCE.get('descr').setContent(descr);
            });
            $('.edit-article-img').click(function(e) {
                //var id = $(this).attr('id');
                var id = $(this).attr('id').substring(8,$(this).attr('id').length);
                $("#edit_img").find("[name='id']").val(id);
                $.ajax({
                    type: "POST",
                    url: 'operations_article.php',
                    data: {"data":"art_media" , "id": id},
                    success: function(response){
                        $( '#art_img' ).empty();
                        $( '#art_galery' ).empty();
                        $( "#art_img" ).append( "<img src='../images/articles/" + response.img + "' height='auto' width='80'>" );
                        response.galery.forEach(function(element) {
                            $( "#art_galery" ).append( "<div id='"+ element +"' ><img  src='../images/articles/" + element + "' height='auto' width='60'>"+
                            "<img class='delete-galery' id='delete"+element+"' src='../images/articles/close.png' height='auto' width='20'><br/></div>" );
                        }, this);
                        $('.delete-galery').click(function(e) {
                        var r = confirm("Vous Voulez vraiment supprimer la photo ?");
                        var nom = $(this).attr('id').substring(6,$(this).attr('id').length);
                        
                        if (r == true) {
                            $(this).parent().remove();
                            $.ajax({
                                type: "POST",
                                url: 'operations_article.php',
                                data: {"data":"delete_galery" , "nom": nom},
                                success: function(response){
                                    console.log(response);
                                },
                            });
                            }
                        })
                    }
               });
            });
            $('.delete-article').click(function(e) {
                //var id = $(this).attr('id');
                var id = $(this).attr('id').substring(6,$(this).attr('id').length);
                console.log(id);
                $("#delete").find("[name='id']").val(id);
            });

        });

        </script>
    </body>
</html>