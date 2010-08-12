<?php
@$conn = new mysqli('localhost','root','Neslira11','adis'); //@ potlaci warning pri neuspechu lebo mame vlastne osetrenie
if ($conn->connect_errno)
{
    printf("Zlyhalo pripojenie: %s\n", $conn->connect_error);
    exit();
}
//mysql_query("SET CHARACTER SET utf8");
//mysql_query("SET NAMES 'utf8'")
//bool mysql_set_charset( string $charset [, resource $link_identifier]) is the preferred way 
//to change the charset. Using mysql_query() to execute SET NAMES .. (SET CHARACTER SET ..) is not recommended. 
//mysql_set_charset('utf8', $conn);
$conn->set_charset('utf8')
?>