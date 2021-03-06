<?php
/*******************************************************************************
 *
 *  filename    : PostRedirect.php
 *  Author      : Bushr Haddad
 *  Description : Process the cusomized datatables configuration, this file is responsible of processing local_master and global_master databales
 *                Process the inline editing of master tables.
 *                Get the options for bags, Teams, Suppliments, etc. 
 * 
*/
require 'Include/Config.php';
require 'Include/Functions.php';

use ChurchCRM\Utils\InputUtils;
use ChurchCRM\Utils\RedirectUtils;
use ChurchCRM\Authentication\AuthenticationManager;


function insert_into_global($criteria){

    // get year 
    $sSQL = "SELECT `id` FROM `master_dates_year` ;";

    if ($criteria == "new_year"){
        $sSQL = "SELECT * from `master_dates_year` order by id DESC LIMIT 1";
    }

    $rsOpps = RunQuery($sSQL);    
    $data= array();
    while($row = mysqli_fetch_array($rsOpps))
    {
        $year_id = (int)$row[0];
        if($year_id == 0){
            continue;
        }

        // get family
        $sSQL1 ="SELECT `fam_ID` FROM `family_fam` ;";
        if($criteria == "new_family"){
            $sSQL1 = "SELECT `fam_ID` from `family_fam` order by fam_ID DESC LIMIT 1";
        }
        
        $rsOpps1 = RunQuery($sSQL1);
        while($row1 = mysqli_fetch_array($rsOpps1))
        {
            $fam_id = (int)$row1[0];
            $result =[];
            $insert_query = "INSERT into `master_global` ( fam_id";
            for ($i=1;$i<=12;$i++){
                $col_name="month_".$i;
                $insert_query = $insert_query.", ".$col_name;
        
                // $query = "SELECT `id` from `master_family_master` where `month_id` = $i and `year_id` = $year_id and `family_id` = $fam_id";
                // $rsOpps2 = RunQuery($query);

                // if(mysqli_num_rows($rsOpps2) > 0){
                //     while($row2 = mysqli_fetch_array($rsOpps2)){
                //         $result[$i] = $row2[0];
                //     }
                // }
                // else{
                    $result[$i] = -1;
                // }
            }
            $insert_query = $insert_query.", year_id) VALUES ( $fam_id";
            for ($i=1;$i<=12;$i++){
                $insert_query = $insert_query.", ".$result[$i];
            }
            $insert_query = $insert_query.", $year_id);";

            RunQuery($insert_query);
        }
    }
}


function move_data($family_id, $month_id, $year_id, $name){
    
    $check_q= "SELECT month_$month_id from master_global where year_id =  $year_id  AND fam_id =  $family_id ;";
    $row = mysqli_fetch_array(RunQuery($check_q));
    $found = $row[0];

    // if this month is found for this family
    if($found != -1){
        $sSQL = "UPDATE `master_family_master` SET 
                    cash_id             =   $name
                    WHERE   year_id     = $year_id  AND 
                            month_id    = $month_id AND
                            family_id   = $family_id ;";

        RunQuery($sSQL);
        // echo $sSQL;
        return;
    }

    else{ 
        
        $sSQL = "INSERT INTO `master_family_master` ( year_id, month_id,
                                                    cash_id, family_id )
                VALUES ($year_id, $month_id, $name, $family_id);";

        RunQuery($sSQL);
        // get the mfm here
        $q = "SELECT id FROM `master_family_master` WHERE `family_id` = $family_id and `year_id` = $year_id and `month_id`  = $month_id;" ;
        $row = mysqli_fetch_array(RunQuery($q));
        $mfm = $row[0];

        // how to get the id of the new inserted row in master_family_master
        $q = "UPDATE `master_global` SET 
                month_$month_id      =  $mfm
                WHERE   year_id      =  $year_id  AND 
                        fam_id       =  $family_id ;";
                        
        RunQuery($q);
        return;
    }

}


function move_data_global(){

    for($i=1; $i<=12; $i++){
        echo $i;
        if($i<=9){
            $query = "SELECT `ID Name`, `kash2014-0$i` from `p2_1` ";
        }
        else{
            $query = "SELECT `ID Name`,  `kash2014-$i` from `p2_1` ";    
        }
        // $query = "SELECT `ID Name`,  `kash2014-$i` from `p2_1` ";    

        $rsOpps = RunQuery($query);
        while($row = mysqli_fetch_array($rsOpps))
        {
            $old_id = (int) $row[0]; // Old Id
            $dd = "SELECT `fam_ID` from `family_fam` Where `old_id` = $old_id AND `imported_p` = 2 ";
            $run = RunQuery($dd);
            $xax = mysqli_fetch_array($run);
            $id = $xax[0]; // fam_ID
            $name = $row[1]; //  value
            if($name!=NULL && $id!= NULL){ // if there is a value here 
                // if($name == 1){
                    // fam_id, $month_id, $year_id, $value 
                    // $value = (int)$name + 42;
                    // move_data($id, $i, 4, $value);
                    // move_data($id, $i, 1, 1);
                // }
                if($name == 20000){
                    move_data($id, $i, 1, 5);
                }
                elseif($name == 15000){
                    move_data($id, $i, 1, 4);
                }
                elseif($name == 10000){
                    move_data($id, $i, 1, 3);
                }
                elseif($name == 8000){
                    move_data($id, $i, 1, 2);
                }   
                elseif($name == 5000){
                    move_data($id, $i, 1, 1);
                }
                else{
                    echo " Cash: ";
                    echo $name;
                }
            }
        }
    }
}

function _get($table){
 
    $sSQL = "SELECT  `id`, `name` FROM $table ";
    $rsOpps = RunQuery($sSQL);

    $data= array();
    // $row = mysqli_fetch_array($rsOpps);
    while($row = mysqli_fetch_array($rsOpps))
    {
        $row1 = array('id' => $row[0], 'name' => $row[1]);
        $data[] = $row1;
    }

    return $data;
}

function get_filtering_options($column, $table){
    $sSQL = "SELECT  Distinct $column FROM $table WHERE $column IS NOT NULL; ";
    $rsOpps = RunQuery($sSQL);
    $data= array();
    while($row = mysqli_fetch_array($rsOpps))
    {
        $data[] = $row[0];
    }
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){

    $post_name = $_POST['post_name'];
    $months= [];
    $months[1]="January";
    $months[2]="Febraury";
    $months[3]="March";
    $months[4]="April";
    $months[5]="May";
    $months[6]="June";
    $months[7]="July";
    $months[8]="August";
    $months[9]="September";
    $months[10]="October";
    $months[11]="November";
    $months[12]="December";


    switch($post_name){

        case "local_master":     // get family local master using (family_id, year_id)
            
            $data = [];
            $val1 = (int)$_POST['year_id'];
            $val2 = (int)$_POST['family_id'];

            $sSQL = "SELECT * FROM master_global WHERE year_id = $val1 AND  fam_id = $val2 ;" ;
            $row = mysqli_fetch_array(RunQuery($sSQL));

            for ($i=2;$i<14;$i++){ // Loop over month_1 to month_12 
                $mfm = (int)$row[$i];
                $found = TRUE;
                if($mfm == -1){
                    $found = FALSE;
                }
                $q1 = "SELECT  family_id, bag_name, cash_name, month_name, year_id, year_name, sup_name,
                team_name FROM `master_general_view` WHERE `master_id` = $mfm ;" ;
                $result =  mysqli_fetch_array(RunQuery($q1));
                $data[] = array(
                    'master_id' => $mfm, 
                    'family_id' => $result[0], 
                    'bag_name' =>  $result[1], 
                    'cash_name' => $result[2],
                    'month_id' => ($i-1),
                    'found' => $found, 
                    'month_name' =>  $months[($i-1)],
                    'year_id' => $result[4], 
                    'year_name' => $result[5], 
                    'sup_name' =>  $result[6],
                    'team_name' => $result[7]
                );
            }
            echo json_encode($data);
            break;

        case "all_families":
         
            $all_fam_q = "SELECT * from `families_view`; ";
            
            if($sMode == "active"){
                // $all_fam_q = "SELECT * from `families_view`  where 1 and $searchQuery and $filtered_search and status='active' ORDER BY $columnName  $columnSortOrder LIMIT $start, $rowperpage;   ";
                $all_fam_q = "SELECT * from `families_view`  where status='active'; ";

            }elseif($sMode == "inactive"){
                // $all_fam_q = "SELECT * from `families_view`  where 1 and $searchQuery and $filtered_search and status='cancelled' ORDER BY $columnName  $columnSortOrder LIMIT $start, $rowperpage;   ";
                $all_fam_q = "SELECT * from `families_view`  where status='cancelled'; ";
            }

            $d = RunQuery($all_fam_q);
       
            $all_data = array();
            while ($row = mysqli_fetch_array($d)) {
                $all_data[] = array( 
                    "id" => $row['id'],
                    "old_id" => $row['old_id'],
                    "p" => $row['p'],
                    "address1" => $row['address1'],
                    "address2" => $row['address2'],
                    "city" => $row['city'],
                    "state" => $row['state'],
                    "home_phone" => $row['home_phone'],
                    "aid_phone" => $row['aid_phone'],
                    "mobile_phone" => $row['mobile_phone'],
                    "status" => $row['status'],
                    "aid_note" => $row['aid_note'],
                    "general_note" => $row['general_note'],
                    "team_note" => $row['team_note'],
                    "ref" => $row['ref'],
                    "membership_status" => $row['membership_status'],
                    "members_num" => $row['members_num'],
                    "children" => $row['children'],
                    "poverty_rate" => $row['poverty_rate'],
                    "main_name" => $row['main_name'],
                    "partner_name" => $row['partner_name'],
                    "main_id" => $row['main_id'],
                    "partner_id" => $row['partner_id'],
                    "no_money" => $row['no_money'],
                    "other_notes" => $row['other_notes'],
                    "question" => $row['verifying_question'],
                );
            }
            $response = array(
                // "draw" => intval($draw),
                // "iTotalRecords" => $totalRecords,
                // "iTotalDisplayRecords" => $totalRecords,
                "aaData" => $all_data,
                );
    
            echo json_encode($response);
            break;
        
        case "global_master":  
     
            $month_id = $_POST['month_id'];
            $year_id = $_POST['year_id'];
            $prev = $_POST['prev'];        

            $all_data = array();
            $query = "SELECT v.master_id, t.fam_id, v.bag_name, v.cash_name, v.sup_name, v.team_name from 
            master_general_view as v
            INNER JOIN 
            master_global as t
            on t.month_$month_id = v.master_id
            where t.year_id = $year_id ";

            $empRecords = RunQuery($query);
            while ($master_row = mysqli_fetch_array($empRecords) ) {
                $id = $master_row['fam_id'];
                // family view query
                $all_fam_q = "SELECT * from `families_view` where id=$id;";
                $d = RunQuery($all_fam_q);
                $row = mysqli_fetch_array($d);

                $all_data[] = array( 
                    "id" => $row['id'],
                    "old_id" => $row['old_id'],
                    "p" => $row['p'],
                    "address1" => $row['address1'],
                    "address2" => $row['address2'],
                    "city" => $row['city'],
                    "state" => $row['state'],
                    "home_phone" => $row['home_phone'],
                    "aid_phone" => $row['aid_phone'],
                    "mobile_phone" => $row['mobile_phone'],
                    "status" => $row['status'],
                    "aid_note" => $row['aid_note'],
                    "general_note" => $row['general_note'],
                    "team_note" => $row['team_note'],
                    "ref" => $row['ref'],
                    "membership_status" => $row['membership_status'],
                    "members_num" => $row['members_num'],
                    "children" => $row['children'],
                    "poverty_rate" => $row['poverty_rate'],
                    "main_name" => $row['main_name'],
                    "partner_name" => $row['partner_name'],
                    "main_id" => $row['main_id'],
                    "partner_id" => $row['partner_id'],
                    "no_money" => $row['no_money'],
                    "other_notes" => $row['other_notes'],
                    "question" => $row['verifying_question'],
                    "master_id"=>$master_row['master_id'],
                    "fam_id"=>$master_row['fam_id'],
                    "bag_name1"=>$master_row['bag_name'],
                    "cash_name1"=>$master_row['cash_name'],
                    "sup_name1"=>$master_row['sup_name'],
                    "team_name1"=>$master_row['team_name'],
                );
            }
            
            // concatenate family data with prev-months 
            for($i=2;$i<=$prev;$i++){
                if ($month_id == 1) {
                    $month_id = 12;
                    $year_id = $year_id - 1;
                    if ($year_id == 0) {
                        $year_id = 1;
                        $month_id = 1;
                    }
                } else {
                    $month_id = $month_id - 1;
                }

                $data = array();
                $query = "SELECT v.master_id, t.fam_id, v.bag_name, v.cash_name, v.sup_name, v.team_name from 
                master_general_view as v
                INNER JOIN 
                master_global as t
                on t.month_$month_id = v.master_id
                where t.year_id = $year_id ";
    
                $empRecords = RunQuery($query);
                while ($row = mysqli_fetch_array($empRecords) ) {
                    $data[] = array( 
                        "bag_name$i"=>$row['bag_name'],
                        "cash_name$i"=>$row['cash_name'],
                        "sup_name$i"=>$row['sup_name'],
                        "team_name$i"=>$row['team_name'],
                    );
                }
                $count=0;
                $new=array();
                foreach($all_data as $d){
                    $new[] = array_merge($d,$data[$count]);
                    $count++;
                }
                $all_data=$new;
            }


            // echo json_encode($all_data);
            // break;

            $response = array(
                "aaData" => $all_data
            );
            echo json_encode($response);
            break;
           
        case "edit_local_master": 
    
                $family_id = (int)$_POST['family_id']; // the family id 
                $month_id = (int)$_POST['month_id']; // the month id 
                $year_id = (int)$_POST['year_id'];// the year id
    
                $check_q= "SELECT month_$month_id from master_global where  year_id =  $year_id  AND fam_id =  $family_id ;";
                $row = mysqli_fetch_array(RunQuery($check_q));
                $found = $row[0];
    
                if ($year_id<=0){
                    break;
                }
                // Updated values 
                $team_id = (int)$_POST['team_id']; 
                $cash_id = (int)$_POST['cash_id'];
                $bag_id = (int)$_POST['bag_id'];  
                $sup_id = (int)$_POST['sup_id']; 
                
                // if this month is found for this family
                if($found != -1){
                    $sSQL = "UPDATE `master_family_master` SET 
                            team_id     =   $team_id,
                            cash_id     =   $cash_id,
                            bag_id      =   $bag_id,
                            sup_id      =   $sup_id
                            
                             WHERE  year_id     = $year_id  AND 
                                    month_id    = $month_id AND
                                    family_id   = $family_id ;";
    
                    RunQuery($sSQL);
                    echo $sSQL;
                    break;
                }
                else{ 
                    
                    $sSQL = "INSERT INTO `master_family_master` ( year_id, month_id,
                                                             team_id, cash_id, bag_id, sup_id, family_id )
                            VALUES ($year_id, $month_id, $team_id,
                                 $cash_id, $bag_id, $sup_id, $family_id);";
    
                    RunQuery($sSQL);
                    // get the mfm here
                    $q = "SELECT id FROM `master_family_master` WHERE `family_id` = $family_id and `year_id` = $year_id and `month_id`  = $month_id;" ;
                    $row = mysqli_fetch_array(RunQuery($q));
                    $mfm = $row[0];
    
                    // how to get the id of the new inserted row in master_family_master
                    $q = "UPDATE `master_global` SET 
                            month_$month_id      =  $mfm
                            WHERE  year_id       =  $year_id  AND 
                                    fam_id       =  $family_id ;";
                                    
                    RunQuery($q);
                    break;    
                }
           
        default:
                break;
    
    }
}

?>