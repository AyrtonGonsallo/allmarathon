<?php
if($_POST['adresse'] != "" ) {
        $url = sprintf('http://maps.google.com/maps/api/geocode/json?address=%s&sensor=false',addslashes(str_replace(' ', '+',$_POST['adresse'])));
        exit($url);
        $r = file_get_contents($url);
        exit($r);
    }
exit('error');
?>
