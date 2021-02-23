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


$sPageTitle = gettext('New Year Editor');

require 'Include/Header.php';
// Get data for the form as it now exists..
$sSQL = 'SELECT * FROM `dates_year`';
$rsOpps = RunQuery($sSQL);
$years= [];
while($row = mysqli_fetch_array($rsOpps))
{
    array_push($years, $row);
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
                    <form method="post" action="PostRedirect.php" name="AddNewYear">
                        <td><input type="submit" class="btn btn-primary" value="<?= gettext('Add New Year') ?>"Name="add_year"></td>
                        <td><label> <?= end($years)[1]+1 ?></label></td>
                         <input type="hidden" name="year" value=<?= end($years)[1]+1 ?>></input>
                        <td><textarea name="desc" rows='1' cols="30%"></textarea></td>
                    </form>
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