<?php
$connect = mysql_connect("localhost","root","admin");
if(!$connect){
	die('Could not connect: '.mysql_error());
}

mysql_select_db("fantasynote");
mysql_query("set names 'utf-8'");
?>