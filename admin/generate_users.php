<?php
// output headers so that the file is downloaded rather than displayed
header('Content-Encoding: UTF-8');

header('Content-type: text/csv; charset=UTF-8');

header("Content-Disposition: attachment; filename=users_allmarathon.csv");

header("Pragma: no-cache");

header("Expires: 0");

header('Content-Transfer-Encoding: binary');
echo "\xEF\xBB\xBF";  // BOM header UTF-8



// fetch the data


// $rows = mysql_query('SELECT field1,field2,field3 FROM table');

include("../content/classes/user.php");

$user=new user();
$user->genererCsvNewsletterUsers();


// loop over the rows, outputting them
// while ($row = mysql_fetch_assoc($rows)) fputcsv($output, $row);