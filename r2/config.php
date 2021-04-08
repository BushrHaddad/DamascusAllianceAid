<?php

$host = "localhost"; /* Host name */
$user = "root"; /* User */
$password = ""; /* Password */
$dbname = "alliance_aid"; /* Database name */

$con =$cnInfoCentral= mysqli_connect($host, $user, $password,$dbname);
//mysqli_query($con,"set names utf8");
mysqli_query($con,"set character_set_server='utf8'");
mysqli_query($con,"set names utf8");

mysqli_query($con,"set character_set_database=utf8");//,$link);
mysqli_query($con,"set session character_set_server=utf8");
//mysqli_query($con,"set global character_set_server=utf8");
//mysql_query("set names utf8",$link);
mysqli_query($con,"SET CHARACTER SET 'utf8';");


$_SESSION['con_con']=$cnInfoCentral;
// Check connection
if (!$con) {
 die("Connection failed: " . mysqli_connect_error());
}