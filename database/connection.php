<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$hostname = "allmarcadmin.mysql.db";
$database = "allmarcadmin";
$username = "allmarcadmin";
$password = "J5Sswy879aX5cB";

$allmarathon  = mysql_pconnect($hostname, $username, $password) or trigger_error(mysql_error(),E_USER_ERROR);
mysql_select_db($database);
//mysql_query("SET NAMES UTF8");
