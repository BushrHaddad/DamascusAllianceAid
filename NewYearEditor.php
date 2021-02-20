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

// get the action
if (array_key_exists('act', $_GET)) {
    $sAction = InputUtils::LegacyFilterInput($_GET['act']);
}
// get the opp
if (array_key_exists('Opp', $_GET)) {
    $iOpp = InputUtils::LegacyFilterInput($_GET['Opp'], 'int');
}
// get the row_num
if (array_key_exists('row_num', $_GET)) {
    $iRowNum = InputUtils::LegacyFilterInput($_GET['row_num'], 'int');
}

$sDeleteError = '';

if ($iRowNum == 0) {

    $sSQL = "SELECT `vol_ID` FROM `volunteeropportunity_vol` WHERE vol_Order = '0' ";
    $sSQL .= 'ORDER BY `vol_ID`';
    $rsOrder = RunQuery($sSQL);
    $numRows = mysqli_num_rows($rsOrder);
    if ($numRows) {
        $sSQL = 'SELECT MAX(`vol_Order`) AS `Max_vol_Order` FROM `volunteeropportunity_vol`';
        $rsMax = RunQuery($sSQL);
        $aRow = mysqli_fetch_array($rsMax);
        extract($aRow);
        for ($row = 1; $row <= $numRows; $row++) {
            $aRow = mysqli_fetch_array($rsOrder);
            extract($aRow);
            $num_vol_Order = $Max_vol_Order + $row;
            $sSQL = 'UPDATE `volunteeropportunity_vol` '.
                    "SET `vol_Order` = '".$num_vol_Order."' ".
                    "WHERE `vol_ID` = '".$vol_ID."'";
            RunQuery($sSQL);
        }
    }   
}

$sPageTitle = gettext('New Year Editor');

require 'Include/Header.php';
// Get data for the form as it now exists..
$sSQL = 'SELECT * FROM `dates_year`';

$rsOpps = RunQuery($sSQL);
$numRows = mysqli_num_rows($rsOpps);

$years= [];
// Create arrays of Years.
for ($row = 1; $row <= $numRows; $row++) {
    $aRow = mysqli_fetch_array($rsOpps, MYSQLI_BOTH);
    extract($row);
    array_push($years, $aRow);
}

?>

<div class="box">
    <div class="box-body">
        <table class="table table-striped table-bordered data-table" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th><?= gettext('Action') ?></th>
                    <th><?= gettext('Name') ?></th>
                    <th><?= gettext('Description') ?></th>
                </tr>
            </thead>
            <tbody>

                <!--Populate the table with family details -->
                
                <?php $var=0; foreach ($years as $year) { ?>
                <tr>
                    <!-- Name -->
                    <td></td> 
                    <td> <?= $year[1] ?></td>
                    <!-- Year -->
                    <td> <?= $year[2]?></td>
                </tr>
                
                <?php } ?>
                <tr>
                    <td></td>
                    <td><?= $years[0]->year_desc ?></td>
                    <td><textarea name="Positive" rows='1' cols="30%"></textarea></td>
                </tr>
     
             
            </tbody>
        </table>
    </div>
</div>
<!-- <form action="NewYearEditor.php" method="post">

    <p>
        <label>Add a description for this year:</label>
        <input type="text" name="gpa" id="gpa">
    </p>
    <input type="submit" name="submit" value="Add New Year:"> -->

<?php require 'Include/Footer.php' ?>