<?php
/*return array(
    "automaker"=>array(
        "connectionString"=>"mysql:host=sampledb.koolreport.com;dbname=automaker",
        "username"=>"expusr",
        "password"=>"koolreport sampledb",
        "charset"=>"utf8"
    ),
    "sakila"=>array(
        "connectionString"=>"mysql:host=sampledb.koolreport.com;dbname=sakila",
        "username"=>"expusr",
        "password"=>"koolreport sampledb",
        "charset"=>"utf8"
    ),
    "world"=>array(
        "connectionString"=>"mysql:host=sampledb.koolreport.com;dbname=world",
        "username"=>"expusr",
        "password"=>"koolreport sampledb",
        "charset"=>"utf8"
    ),  
    "employees"=>array(
        "connectionString"=>"mysql:host=sampledb.koolreport.com;dbname=employees",
        "username"=>"expusr",
        "password"=>"koolreport sampledb",
        "charset"=>"utf8"
    ),  
    "salesCSV"=>array(
        'filePath' => '../../../databases/customer_product_dollarsales2.csv',
        'class' => "\koolreport\datasources\CSVDataSource",      
        'fieldSeparator' => ';',
    )       
);*/
$dbname='alliance_aid';$host='localhost';
$user='root'; $password='';

return array(
    "automaker"=>array(
        "connectionString"=>"mysql:host=localhost;dbname=automaker",
        "username"=>"root",
        "password"=>"",
        "charset"=>"utf8"
    ),
    "sakila"=>array(
        "connectionString"=>"mysql:host=localhost;dbname=sakila",
        "username"=>"root",
        "password"=>"",
        "charset"=>"utf8"
    ),
    "world"=>array(
        "connectionString"=>"mysql:host=localhost;dbname=world",
        "username"=>"root",
        "password"=>"",
        "charset"=>"utf8"
    ),  
    "employees"=>array(
        "connectionString"=>"mysql:host=localhost;dbname=employees",
        "username"=>"root",
        "password"=>"",
        "charset"=>"utf8"
    ),  
    "salesCSV"=>array(
        'filePath' => '../../../databases/customer_product_dollarsales2.csv',
        'class' => "\koolreport\datasources\CSVDataSource",      
        'fieldSeparator' => ';',
    ),  
    "churchcrm"=>array(
        "connectionString"=>"mysql:host=localhost;dbname=alliance_aid",
        "host"=>"localhost",
        "dbname"=>"alliance_aid",
        'con'=> mysqli_connect($host, $user, $password,$dbname),
        "username"=>"root",
        "password"=>"",
        "charset"=>"utf8"
    ), 
);



//mysqli_query($con,"set global character_set_server=utf8");
//array_push($churchcrm, $con);
/*//mysqli_query($con,"set names utf8");
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
}*/