<?php
/*******************************************************************************
 *
 *  filename    : PostRedirect.php
 *  Author      : Bushr Haddad
 *  Description : Process the cusomized datatables configuration, this file is responsible of processing local_master and global_master databales
 *                Process the inline editing of master tables.
 *                Get the options for bags, Teams, Suppliments, etc.
 * 
 * 
CREATE VIEW master_general_view
    AS 
    SELECT 
        master_family_master.id as master_id,
        master_family_master.family_id as family_id,
        master_bags.name AS bag_name,
        master_cash.name AS cash_name,
        master_dates_months.name AS month_id,
        master_dates_months.note1 AS month_name,
        master_dates_year.id as year_id,
        master_dates_year.name as year_name,
        master_suppliments.name AS sup_name,
        master_teams.name AS team_name,
        master_visiting.name as visiting_name
        FROM master_family_master
        LEFT JOIN master_bags
            ON master_bags.id = master_family_master.bag_id
        LEFT JOIN master_cash
            ON master_cash.id = master_family_master.cash_id
        LEFT JOIN master_dates_months 
            ON master_dates_months.id = master_family_master.month_id
        LEFT JOIN master_dates_year
            ON master_dates_year.id = master_family_master.year_id
        LEFT JOIN master_suppliments
            ON master_suppliments.id = master_family_master.sup_id
        LEFT JOIN master_teams
            ON master_teams.id = master_family_master.team_id
        LEFT JOIN master_visiting
            ON master_visiting.id = master_family_master.visited_id
        ORDER BY master_family_master.id;
 ******************************************************************************/

require 'Include/Config.php';
require 'Include/Functions.php';

use ChurchCRM\Utils\InputUtils;
use ChurchCRM\Utils\RedirectUtils;
use ChurchCRM\Authentication\AuthenticationManager;

function _get($table){
 
    $sSQL = "SELECT  `id`, `name` FROM $table ";
    $rsOpps = RunQuery($sSQL);

    $data= array();
    while($row = mysqli_fetch_array($rsOpps))
    {
        $row1 = array('id' => $row[0], 'name' => $row[1]);
        $data[] = $row1;
    }

    return $data;
    
}
if ($_SERVER["REQUEST_METHOD"] == "POST"){

    $post_name = $_POST['post_name'];

    switch($post_name){
        // get family local master using (family_id, year_id)
        case "local_master":
            $val1 = (int)$_POST['year_id'];
            $val2 = (int)$_POST['family_id'];

            // Get data for the form as it now exists..
            $sSQL = "SELECT * FROM `master_general_view` WHERE `year_id`= '".$val1."' AND  `family_id`= '".$val2."';" ;
            $rsOpps = RunQuery($sSQL);

            $data= array();
            while($row = mysqli_fetch_array($rsOpps))
            {
                $row = array('master_id' => $row[0], 'family_id' => $row[1], 'bag_name' =>  $row[2], 
                            'cash_name' => $row[3], 'month_id' => $row[4],'found' => TRUE, 'month_name' =>  $row[5],
                             'year_id' => $row[6], 'year_name' => $row[7], 'sup_name' =>  $row[8],
                              'team_name' => $row[9], 'visiting_name' => $row[10]);
                $data[] = $row;
            }
            echo json_encode($data);
            break;

        //  Get global Master table
        // Bushr: todo-added
        case "global_master":


            $month_id = $_POST['month_id'];
            $year_id = $_POST['year_id'];
            $months_back = $_POST['months_back'];
            
            $draw = $_POST['draw'];
            $start = $_POST['start'];
            $rowperpage = $_POST['length']; // Rows display per page
            $columnIndex = $_POST['order'][0]['column']; // Column index
            $columnName = $_POST['columns'][$columnIndex]['data']; // Column name
            $columnSortOrder = $_POST['order'][0]['dir']; // asc or desc
            $searchValue = $_POST['search']['value']; // Search value
            // $searchValue = mysqli_real_escape_string($con,$_POST['search']['value']); // Search value

            ## Search 
            $searchQuery = " ";
            if($searchValue != ''){
                // search name of household name, the partner name, address
            $searchQuery = " and (name like '%".$searchValue."%' or 
                    note1 like '%".$searchValue."%' ) ";
            }

            ## Total number of records without filtering
            $sel = "select count(*) as allcount from master_dates_months; ";
            $records = RunQuery($sel);

            while ($row = mysqli_fetch_array($records)) {
                $totalRecords = $row['allcount'];
            }

            ## Total number of record with filtering
            $sel = "select count(*) as allcount from master_dates_months WHERE 1 ".$searchQuery;
            $records = RunQuery($sel);

            while ($row = mysqli_fetch_array($records)) {
                $totalRecordwithFilter = $row['allcount'];
            }

            ## Fetch records
            $empQuery = "select * from master_dates_months WHERE 1 ".$searchQuery." order by ".$columnName." ".$columnSortOrder." limit ".$start.",".$rowperpage;
 
            $empRecords = RunQuery($empQuery);
            $data = array();

            while ($row = mysqli_fetch_array($empRecords)) {
                $data[] = array( 
                    "id"=>$row['id'],
                    "name"=>$row['name'],
                    "note1"=>$row['note1']
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
            break;


        // add new year using NewYearEditor.php
        case "add_year": 
            $year = $_POST['year'];
            $desc = $_POST['desc'];
            $sSQL = "INSERT INTO `master_dates_year` 
            ( `name` , `year_desc` )
            VALUES ( '".$year."', '".$desc."');";
            RunQuery($sSQL);
            header('Location: NewYearEditor.php'); // Either way, pass or fail, return to form.php
            exit();
            break;
        
        case "edit_local_master":
            // year id
            // month id
            // family id 
            $found = $_POST['found']; // 
            $family_id = (int)$_POST['family_id'];
            $month_id = (int)$_POST['month_id'];
            $year_id = (int)$_POST['year_id'];
            // check to see if year selected is null means (==========)
            if ($year_id<=0){
                break;
            }
            $visited_id = (int)$_POST['visited_id']; 
            $team_id = (int)$_POST['team_id']; 
            $bag_id = (int)$_POST['bag_id'];  
            $cash_id = (int)$_POST['cash_id'];
            $sup_id = (int)$_POST['sup_id']; 
            
            // if this month is found for this family
            if($found == "true"){
                          
                $sSQL = "UPDATE `master_family_master` SET 
                        visited_id  =   $visited_id,
                        team_id     =   $team_id,
                        cash_id     =   $cash_id,
                        bag_id      =   $bag_id,
                        sup_id      =   $sup_id
                        
                         WHERE  year_id     = $year_id  AND 
                                month_id    = $month_id AND
                                family_id   = $family_id ;";

                RunQuery($sSQL);

                // echo $sSQL;
                break;
            }
            else{ 
                
                $sSQL = "INSERT INTO `master_family_master` ( year_id, month_id, visited_id,
                                                         team_id, cash_id, bag_id, sup_id, family_id )
                        VALUES ($year_id, $month_id, $visited_id, $team_id,
                             $cash_id, $bag_id, $sup_id, $family_id);";

                RunQuery($sSQL);
                // echo $sSQL;
                break;    
            }

       
        // get the option for teams, bags, suppliments and other.
        case "get_vars":
            $_bags = _get('master_bags');
            $_cash = _get('master_cash');
            $_suppliments = _get('master_suppliments');
            $_teams = _get('master_teams');
            $_visiting = _get('master_visiting');
            $data = Array('all_bags' => $_bags, 'all_cash' => $_cash, 'all_suppliments' =>  $_suppliments, 
            'all_teams' => $_teams, 'all_visitings' => $_visiting);

            echo json_encode($data);
            break;

        
        default:
            break;

    }

}

?>