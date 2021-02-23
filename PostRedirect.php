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

// Security: User must have proper permission
// For now ... require $bAdmin
// Future ... $bManageVol

if (!AuthenticationManager::GetCurrentUser()->isAdmin()) {
    RedirectUtils::Redirect('Menu.php');
    exit;
}

// top down design....
// title line
// separator line
// warning line
// first input line: [ Save Changes ] [ Exit ]
// column titles
// first record: text box with order, up, down, delete ; Name, Desc, Active radio buttons
// and so on
// action is change of order number, up, down, delete, Name, Desc, or Active, or Add New

$iOpp = -1;
$sAction = '';
$iRowNum = -1;
$bErrorFlag = false;
$aNameErrors = [];
$bNewNameError = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    print_r($_POST);
    // exit;
    // // collect value of input field
    // $year = $_POST['year'];
    // $desc = $_POST['desc'];
    // $sSQL = "INSERT INTO `dates_year` 
    // ( `year_name` , `year_desc` )
    // VALUES ( '".$year."', '".$desc."');";
    // RunQuery($sSQL);
    // header('Location: NewYearEditor.php'); // Either way, pass or fail, return to form.php
    // exit();
    echo json_encode(array('returned_val' => 'yoho'));
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['year']) {
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
