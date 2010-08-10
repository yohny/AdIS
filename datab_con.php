<?php
$conn = mysql_connect('localhost','root','Neslira11') or die ('Nepodarilo sa pripojiť na databázu!');
mysql_select_db('adis',$conn) or die ('Nepodarilo sa vybrať databázu!');
//mysql_query("SET CHARACTER SET utf8");
//mysql_query("SET NAMES 'utf8'")
//bool mysql_set_charset( string $charset [, resource $link_identifier]) is the preferred way 
//to change the charset. Using mysql_query() to execute SET NAMES .. (SET CHARACTER SET ..) is not recommended. 
mysql_set_charset('utf8', $conn);
?>