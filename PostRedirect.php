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


if (isset($_POST['val']) ) {

    $val = (int)$_POST['val'];
    // Get data for the form as it now exists..
    $sSQL = "SELECT * FROM `dates_year` WHERE `year_name`= '".$val."' ;" ;
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

}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['year']) ) {
    // collect value of input field
    $year = $_POST['year'];
    $desc = $_POST['desc'];
    $sSQL = "INSERT INTO `dates_year` 
    ( `year_name` , `year_desc` )
    VALUES ( '".$year."', '".$desc."');";
    RunQuery($sSQL);
    header('Location: NewYearEditor.php'); // Either way, pass or fail, return to form.php
    exit();
}

?>
