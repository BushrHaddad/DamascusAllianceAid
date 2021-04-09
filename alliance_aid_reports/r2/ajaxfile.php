<?php
session_start();
include 'config.php';
//echo 'exit'; exit;
## Read value
//$cnInfoCentral=$_SESSION['con_con'];
//require '../Include/Config.php';
//require '../Include/Functions.php';
//print_r($GLOBALS); exit;
//use ChurchCRM\Authentication\AuthenticationManager;
//$con=$_SESSION['aLocalInfo'];
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage =100;// $_POST['length']; // Rows display per page
 // Ordering
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
//main search value
$searchValue = $_POST['search']['value']; // Search value
//////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////
$columnIndex = $_POST['order'][0]['column']; // The index
$columnSortOrder = $_POST['order'][0]['dir']; // The kind of sorting: Asc, Desc
$columnName = $_POST['columns'][0]['data']; // The name of the column 

// Filtering Searching based on columns search value
$filtered_search = " (";
$searchQuery = " ";
$searchQuery = " (";


for($i=0;$i<7;$i++)
{
    $col_search_value = $_POST['columns'][$i]['search']['value'];  // the search value enterned for this column
        $col_name = $_POST['columns'][$i]['data'];  // the name of this column 
        if ($i==0){
            $searchQuery = $searchQuery . "(". $col_name. " like '%".$searchValue."%' ) ";
            $filtered_search = $filtered_search . "(". $col_name. " like '%".$col_search_value."%' ) ";
        }
        else{
            $searchQuery = $searchQuery . " or (". $col_name. " like '%".$searchValue."%' ) ";
            $filtered_search = $filtered_search . " and (". $col_name. " like '%".$col_search_value."%' ) ";
        }
}
$filtered_search = $filtered_search." )";
$searchQuery = $searchQuery." )";
## Search 
//////////////////////////////////////////////////////////////////////////////////////////////////////////
## Custom Field value
//$searchByName = $_POST['searchByName'];
//$searchByGender = $_POST['searchByGender'];

## Search

//if($searchByName != ''){    $searchQuery .= " and (family_Name like '%".$searchByName."%' or fam_add like '%".$searchByName."%' ) ";}
//if($searchByGender != ''){    $searchQuery .= " and (visiting_name='".$searchByGender."') ";}

//if($searchValue != ''){	$searchQuery .= " and (family_Name like '%".$searchValue."%' or fam_add like '%".$searchValue."%' or  sup_name like '%".$searchValue."%' or  team_name like'%".$searchValue."%' ) COLLATE utf8_bin ";}

## Total number of records without filtering
mysqli_query($con,"set character_set_server='utf8'");
mysqli_query($con,"set names utf8");

mysqli_query($con,"set character_set_database=utf8");//,$link);
mysqli_query($con,"set session character_set_server=utf8");
//mysqli_query($con,"set global character_set_server=utf8");
//mysql_query("set names utf8",$link);
mysqli_query($con,"SET CHARACTER SET 'utf8';");

$sel = mysqli_query($con,"select count(*) as allcount from master_general_view");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of records with filtering
$sel = mysqli_query($con,"select count(*) as allcount from master_general_view"); 
// $sel = mysqli_query($con,"select count(*) as allcount from master_view WHERE 1 and".$searchQuery. " and ".$filtered_search ); 
//echo $sel; exit;
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "select * from master_general_view limit ".$row.",".$rowperpage;
// $empQuery = "select * from master_view WHERE 1 and".$searchQuery. " and ".$filtered_search." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
//echo $empQuery; exit;
$empRecords = mysqli_query($con, $empQuery);
$data = array();
while ($row = mysqli_fetch_assoc($empRecords)) {
    $data[] = array(
            "master_id"=>$row['master_id'],
            "family_id"=>$row['family_id'],
    		"year_name"=>$row['year_name'],
    		"month_name"=>$row['month_name'],
            "team_name"=>$row['team_name'],
    		"cash_name"=>$row['cash_name'],
    		"bag_name"=>$row['bag_name'],
    		"sup_name"=>$row['sup_name']
    	);
}
//$_SESSION['query_is']=$empQuery;
## Response
$response = array(
    "draw" => intval($draw),
    "query"=>$empQuery,
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);
//print_r($data); //print_r($data); exit;
echo json_encode($response);