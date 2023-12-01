<?php
require "ancien.php";
$a = new Ancien();
if(!isset($_GET['aim'])){
    echo "You forgot the aim";
    exit(-1);
}
if (isset($_GET['table']) && isset($_GET['nor']) && !isset($_GET['help'])&& ($_GET['aim']=="get")  ) {
    $nor=(isset($_GET['nor']) )?$_GET['nor']:null;
    $table=(isset($_GET['table']) )?$_GET['table']:null;
    $champ_tri=(isset($_GET['champ_tri']) )?$_GET['champ_tri']:null;
    $ordre=(isset($_GET['ordre']) )?$_GET['ordre']:null;
    $conds=(isset($_GET['conds']) )?$_GET['conds']:null;
     $a->getData($table,$nor,$champ_tri,$ordre,$conds);
}
if(($_GET['aim']=="help")){
    echo "command : <br>
    tester : http://localhost/allmarathontest/admin/ancient.php?table=champions&nor=3&champ_tri=Nom&ordre=DESC&conds=Nom_like_^@laurent@^&aim=test
    <br>
    recuperer : http://localhost/allmarathontest/admin/ancient.php?table=champions&nor=3&champ_tri=Nom&ordre=DESC&conds=Nom_like_^@laurent@^&aim=get
    <br>
    sup : http://localhost/allmarathontest/admin/ancient.php?table=champions&field=ID&value=100&aim=delete
    ";
}
if(($_GET['aim']=="test")){
    $nor=(isset($_GET['nor']) )?$_GET['nor']:null;
    $table=(isset($_GET['table']) )?$_GET['table']:null;
    $champ_tri=(isset($_GET['champ_tri']) )?$_GET['champ_tri']:null;
    $ordre=(isset($_GET['ordre']) )?$_GET['ordre']:null;
    $conds=(isset($_GET['conds']) )?$_GET['conds']:null;
    $a->getTestData($table,$nor,$champ_tri,$ordre,$conds);
}
if(($_GET['aim']=="delete") ){
    $value=(isset($_GET['value']) )?$_GET['value']:null;
    $table=(isset($_GET['table']) )?$_GET['table']:null;
    $field=(isset($_GET['field']) )?$_GET['field']:null;
    $a->deleteData($table, $field,$value);
}
if(($_GET['aim']=="checkDelete") ){
    $value=(isset($_GET['value']) )?$_GET['value']:null;
    $table=(isset($_GET['table']) )?$_GET['table']:null;
    $field=(isset($_GET['field']) )?$_GET['field']:null;
    $a->checkDeleteData($table, $field,$value);
}
?>