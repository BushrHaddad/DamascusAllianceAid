<?php
/*******************************************************************************
 *
 *  filename    : NewYearEditor.php
 *  website     : http://www.churchcrm.io
 *  copyright   : Copyright 2005 Michael Wilt
 *
 ******************************************************************************/

require 'Include/Config.php';
require 'Include/Functions.php';

use ChurchCRM\Utils\InputUtils;
use ChurchCRM\Utils\RedirectUtils;
use ChurchCRM\Authentication\AuthenticationManager;

if ($_SERVER["REQUEST_METHOD"] == "POST"){

    $post_name = $_POST['post_name'];

    switch($post_name){
        // get family local master using (family_id, year_id)
        case "local_master":
            $val1 = (int)$_POST['year_id'];
            $val2 = (int)$_POST['family_id'];

            // Get data for the form as it now exists..
            $sSQL = "SELECT * FROM `dates_year` WHERE `year_name`= '".$val1."' ;" ;
            $rsOpps = RunQuery($sSQL);

            $data= array();
            while($row = mysqli_fetch_array($rsOpps))
            {
                $year_id = $row[0];
                $year_name = $row[1];
                $year_desc = $row[2];
                // $year_note2 = $row[3];
                $row = array('year_id' => $year_id, 'year_name' =>  $year_name, 'year_desc' => $year_desc);
                $data[] = $row;
            }
            echo json_encode($data);
            break;

        // add new year using NewYearEditor.php
        case "add_year": 
            $year = $_POST['year'];
            $desc = $_POST['desc'];
            $sSQL = "INSERT INTO `dates_year` 
            ( `year_name` , `year_desc` )
            VALUES ( '".$year."', '".$desc."');";
            RunQuery($sSQL);
            header('Location: NewYearEditor.php'); // Either way, pass or fail, return to form.php
            exit();
            break;
        
        case "edit_local_master":
            $val1 = (int)$_POST['family_id'];
            $val2 = (int)$_POST['year_id'];
            $val3 = (int)$_POST['year_name'];
            $val4 = $_POST['year_desc'];

            $sSQL = "UPDATE `dates_year` SET `year_name` = '".$val3."', `year_desc` = '".$val4."' WHERE `year_id` = '".$val2."' ;";
            RunQuery($sSQL);
            break;

        // get global master
        case "global_master":
            break;

        default:
            break;

    }

}

?>