<?php
include 'config_2.php';

## Read value
$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
$searchValue = $_POST['search']['value']; // Search value

## Custom Field value
$searchByName = $_POST['searchByName'];
$searchByGender = $_POST['searchByGender'];

## Search 
$searchQuery = " ";
if($searchByName != ''){
    $searchQuery .= " and (emp_name like '%".$searchByName."%' ) ";
}
if($searchByGender != ''){
    $searchQuery .= " and (gender='".$searchByGender."') ";
}
if($searchValue != ''){
	$searchQuery .= " and (emp_name like '%".$searchValue."%' or 
        email like '%".$searchValue."%' or 
        city like'%".$searchValue."%' ) ";
}

## Total number of records without filtering
$sel = mysqli_query($con,"select count(*) as allcount from employee");
$records = mysqli_fetch_assoc($sel);
$totalRecords = $records['allcount'];

## Total number of records with filtering
$sel = mysqli_query($con,"select count(*) as allcount from employee WHERE 1 ".$searchQuery);
$records = mysqli_fetch_assoc($sel);
$totalRecordwithFilter = $records['allcount'];

## Fetch records
$empQuery = "select * from employee WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$row.",".$rowperpage;
$empRecords = mysqli_query($con, $empQuery);
$data = array();

while ($row = mysqli_fetch_assoc($empRecords)) {
    $data[] = array(
    		"emp_name"=>$row['emp_name'],
    		"email"=>$row['email'],
    		"gender"=>$row['gender'],
    		"salary"=>$row['salary'],
    		"city"=>$row['city']
    	);
}

## Response
$response = array(
    "draw" => intval($draw),
    "iTotalRecords" => $totalRecords,
    "iTotalDisplayRecords" => $totalRecordwithFilter,
    "aaData" => $data
);

echo json_encode($response);
