<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$hostname = "localhost";
$database = "sc1mala2782_preprod_allmarathon";
$username = "sc1mala2782_preprod_allmarathon_user";
$password = "q22CI@%3XC4MC@qy";
$allmarathon  = mysql_pconnect($hostname, $username, $password) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_select_db($database);
mysql_query("SET NAMES UTF8");
//mysql_query("SET NAMES latin1") 
