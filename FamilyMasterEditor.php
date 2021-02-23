<?php
/*******************************************************************************
 *
 *  filename    : FamilyEditor.php
 *  last change : 2003-01-04
 *  website     : http://www.churchcrm.io
 *  copyright   : Copyright 2001, 2002, 2003 Deane Barker, Chris Gebhardt
  *
 ******************************************************************************/
//Include the function library
require 'Include/Config.php';
require 'Include/Functions.php';
require 'Include/CanvassUtilities.php';

use ChurchCRM\dto\SystemConfig;
use ChurchCRM\Note;
use ChurchCRM\FamilyQuery;
use ChurchCRM\Utils\InputUtils;
use ChurchCRM\dto\SystemURLs;

use ChurchCRM\Emails\NewPersonOrFamilyEmail;
use ChurchCRM\Utils\RedirectUtils;
use ChurchCRM\Bootstrapper;
use ChurchCRM\Authentication\AuthenticationManager;

//Set the page title
$sPageTitle = gettext('Family Master Editor');

$iFamilyID = -1;
//Get the FamilyID from the querystring
if (array_key_exists('FamilyID', $_GET)) {
    $iFamilyID = InputUtils::LegacyFilterInput($_GET['FamilyID'], 'int');
}

// Security: User must have Add or Edit Records permission to use this form in those manners
// Clean error handling: (such as somebody typing an incorrect URL ?PersonID= manually)
if ($iFamilyID > 0) {
    if (!(AuthenticationManager::GetCurrentUser()->isEditRecordsEnabled() || (AuthenticationManager::GetCurrentUser()->isEditSelfEnabled() && $iFamilyID == AuthenticationManager::GetCurrentUser()->getPerson()->getFamId()))) {
        RedirectUtils::Redirect('Menu.php');
        exit;
    }

    $sSQL = 'SELECT fam_ID FROM family_fam WHERE fam_ID = '.$iFamilyID;
    if (mysqli_num_rows(RunQuery($sSQL)) == 0) {
        RedirectUtils::Redirect('Menu.php');
        exit;
    }
} elseif (!AuthenticationManager::GetCurrentUser()->isAddRecordsEnabled()) {
    RedirectUtils::Redirect('Menu.php');
    exit;
}

// Get Field Security List Matrix
$sSQL = 'SELECT * FROM list_lst WHERE lst_ID = 5 ORDER BY lst_OptionSequence';
$rsSecurityGrp = RunQuery($sSQL);

while ($aRow = mysqli_fetch_array($rsSecurityGrp)) {
    extract($aRow);
    $aSecurityType[$lst_OptionID] = $lst_OptionName;
}

$bErrorFlag = false;
$sName = '';
$UpdateBirthYear = 0;
//$selected_year=2021;

$aYearNameError = [];
$aBirthDateError = [];
$aperFlags = [];
//print_r($_GET);
if($_GET['Add']=='Add')
    $add_method=1;
else
    $add_method=0;
//Is this the second pass?
if (isset($_POST['FamilyMasterSubmit']) || isset($_POST['FamilyMasterSubmitAndAdd'])) {
    $iFamilyMasterMemberRows = InputUtils::LegacyFilterInput($_POST['FamCount']);
    //Loop through the Family Member 'quick entry' form fields
    for ($iCount = 1; $iCount <= $iFamilyMasterMemberRows; $iCount++) {
        // Assign everything to arrays
       /* year_id,  month_id,     visited_id,     team_id,       cash_id, bag_id, sup_id,  role_id,  created_date,    family_id,  user_id*/
        $aYearNames[$iCount]    = InputUtils::LegacyFilterInput($_POST['YearName']);//.$iCount]);

        $aDatesMonths[$iCount]  = InputUtils::LegacyFilterInput($_POST['month'.$iCount], 'int');
        $aVisited[$iCount]      = InputUtils::LegacyFilterInput($_POST['Visit'.$iCount], 'int');
        $aTeams[$iCount]        = InputUtils::LegacyFilterInput($_POST['Team'.$iCount], 'int');
        $aCashes[$iCount]       = InputUtils::LegacyFilterInput($_POST['Cash'.$iCount], 'int');
        $aBags[$iCount]         = InputUtils::LegacyFilterInput($_POST['Bag'.$iCount], 'int');
        $aSups[$iCount]         = InputUtils::LegacyFilterInput($_POST['Sup'.$iCount], 'int');
        $aRoles[$iCount]        = -1;//InputUtils::LegacyFilterInput($_POST['Role'.$iCount], 'int');
        $aFamiliesMaster[$iCount]        = InputUtils::LegacyFilterInput($_POST['FamilyID'.$iCount], 'int');
        /*$aClassification[$iCount] = InputUtils::LegacyFilterInput($_POST['Classification'.$iCount], 'int');*/
        //$aPersonIDs[$iCount] = InputUtils::LegacyFilterInput($_POST['PersonID'.$iCount], 'int');
        //$aUpdateBirthYear[$iCount] = InputUtils::LegacyFilterInput($_POST['UpdateBirthYear'], 'int');

        // Make sure first names were entered if editing existing family
        if ($iFamilyID > 0) {
           /* if (strlen($aYearNames[$iCount]) == 0) {
                $aFirstNameError[$iCount] = gettext('First name must be entered');
                $bErrorFlag = true;
            }*/
        }
        // Validate any family member birthdays
        /*if ((strlen($aFirstNames[$iCount]) > 0) && (strlen($aTeams[$iCount]) > 0)) {
            if (($aTeams[$iCount] > 2155) || ($aTeams[$iCount] < 1901)) {
                $aBirthDateError[$iCount] = gettext('Invalid Year: allowable values are 1901 to 2155');
                $bErrorFlag = true;
            } elseif ($aDatesMonths[$iCount] > 0 && $aBirthDays[$iCount] > 0) {
                if (!checkdate($aDatesMonths[$iCount], $aBirthDays[$iCount], $aTeams[$iCount])) {
                    $aBirthDateError[$iCount] = gettext('Invalid Birth Date.');
                    $bErrorFlag = true;
                }
            }
        }*/
        //print_r($aVisited);
        //print_r($aTeams);
        //print_r($_POST);
    }
    //If no errors, then let's update...
    if (!$bErrorFlag) {
        //If the user added a new record, we need to key back to the route to the FamilyView page
        //if ($bGetKeyBack) 
        if($_POST['add_method'] =='add_method'){
            //Get the key back
            //Run through the family member arrays...
            for ($iCount = 1; $iCount <= $iFamilyMasterMemberRows; $iCount++) {
                //if (strlen($aFirstNames[$iCount]) > 0) 
                {
                    /*if (strlen($aTeams[$iCount]) < 4) {
                        $aTeams[$iCount] = 'NULL';
                    }*/
                    //If no last name is entered for a member, use the family name.
                    /*if (strlen($aLastNames[$iCount]) && $aLastNames[$iCount] != $sName) {
                        $sLastNameToEnter = $aLastNames[$iCount];
                    } else {
                        $sLastNameToEnter = $sName;
                    }*/
                  
                    RunQuery('LOCK TABLES master_family_master WRITE ');
                    $sSQL = "INSERT INTO master_family_master (
								year_id, month_id, visited_id, team_id,
								cash_id,bag_id,	sup_id,
                                role_id, created_day,  family_id, user_id
								)
							VALUES (
								$aYearNames[$iCount], $aDatesMonths[$iCount], $aVisited[$iCount], $aTeams[$iCount],
                                $aCashes[$iCount], $aBags[$iCount], $aSups[$iCount],
								$aRoles[$iCount],
                                '".date('YmdHis')."',
                                $iFamilyID,
								".AuthenticationManager::GetCurrentUser()->getId()
                                .")";
                    
                    RunQuery($sSQL);
                    $dbPersonId = mysqli_insert_id($cnInfoCentral);
                    $note = new Note();
                    $note->setPerId($dbPersonId);
                    $note->setText(gettext('Created via Family'));
                    $note->setType('create');
                    $note->setEntered(AuthenticationManager::GetCurrentUser()->getId());
                    $note->save();
                    /*$sSQL = 'INSERT INTO person_custom (per_ID) VALUES ('
                                .$dbPersonId.')';
                    RunQuery($sSQL);*/
                    RunQuery('UNLOCK TABLES');
                }
            }
            $family = FamilyQuery::create()->findPk($iFamilyID);
            $family->createTimeLineNote('create');
            $family->updateLanLng();

            if (!empty(SystemConfig::getValue("sNewPersonNotificationRecipientIDs"))) {
                $NotificationEmail = new NewPersonOrFamilyEmail($family);
                if (!$NotificationEmail->send()) {
                    $logger->warning($NotificationEmail->getError());
                }
            }
        } else {
            //here are the submit
            for ($iCount = 1; $iCount <= $iFamilyMasterMemberRows; $iCount++) {
                //print_r($aYearNames); exit;
                if (strlen($aYearNames[$iCount]) > 0) {
                    //echo'ddd<br />';     //print_r($_POST); exit;
                    /*if (strlen($aTeams[$iCount]) < 4) { $aTeams[$iCount] = 'NULL'; }*/

                    //If no last name is entered for a member, use the family name.
                    /*if (strlen($aLastNames[$iCount]) && $aLastNames[$iCount] != $sName) {
                        $sLastNameToEnter = $aLastNames[$iCount];
                    } else {
                        $sLastNameToEnter = $sName;
                    }*/
                    //$sBirthYearScript = ($aUpdateBirthYear[$iCount] & 1) ? 'per_BirthYear='.$aTeams[$iCount].', ' : '';
                    //RunQuery("LOCK TABLES person_per WRITE, person_custom WRITE");
                    //if($_GET['Add'] !='Add')
                    $sSQL = "
                    UPDATE master_family_master SET 
                    year_id='".$aYearNames[$iCount].
                    "',month_id='".$aDatesMonths[$iCount].
                    "',visited_id='".$aVisited[$iCount].
                    "',team_id='".$aTeams[$iCount].
                    "',cash_id='".$aCashes[$iCount].
                    "',bag_id='".$aBags[$iCount].
                    "',sup_id='".$aSups[$iCount].
                    "',role_id='".$aRoles[$iCount].
                    "',created_day='".date('YmdHis').
                    "',family_id='".$iFamilyID.
                    "',user_id='".AuthenticationManager::GetCurrentUser()->getId().
                    "' WHERE id=".$aFamiliesMaster[$iCount];
                /*else
                    $sSQL = "UPDATE person_per SET per_FirstName='".$aFirstNames[$iCount]."', per_MiddleName='".$aMiddleNames[$iCount]."',per_LastName='".$aLastNames[$iCount]."',per_Suffix='".$aSuffix[$iCount]."',per_Gender='".$aVisited[$iCount]."',per_fmr_ID='".$aRoles[$iCount]."',per_BirthMonth='".$aDatesMonths[$iCount]."',per_BirthDay='".$aBirthDays[$iCount]."', ".$sBirthYearScript."per_cls_ID='".$aClassification[$iCount]."' WHERE per_ID=".$aPersonIDs[$iCount];*/
                   // echo $aFamiliesMaster[$iCount].'<br />';
            /*        print_r($aFamiliesMaster); echo '<br />';
echo $aFamiliesMaster[$iCount];
echo $sSQL; exit;*/
                    RunQuery($sSQL);
                    //RunQuery("UNLOCK TABLES");

                    $note = new Note();
                    $note->setPerId($aFamiliesMaster[$iCount]);
                    $note->setText(gettext('Updated via Family'));
                    $note->setType('edit');
                    $note->setEntered(AuthenticationManager::GetCurrentUser()->getId());
                    $note->save();
                }
            }
            $family = FamilyQuery::create()->findPk($iFamilyID);
            $family->updateLanLng();
            $family->createTimeLineNote('edit');
        }

        //Which submit button did they press?
        if (isset($_POST['FamilyMasterSubmit'])) {
            //Send to the view of this person
            RedirectUtils::Redirect('v2/family/'.$iFamilyID);
        } else {
            //RedirectUtils::Redirect('v2/family/'.$iFamilyID);
            //Reload to editor to add another record
            RedirectUtils::Redirect('FamilyMasterEditor.php?FamilyID='.$iFamilyID);
        }
    }
}
else {
    //FirstPass
    //Are we editing or adding?
    if (($iFamilyID > 0)&($_GET['Add'] !='Add')) {
        $sSQL = 'SELECT * FROM master_family_master LEFT JOIN family_fam ON family_id = fam_ID WHERE family_id ='.$iFamilyID.' ORDER BY family_id';
        $rsMembers = RunQuery($sSQL);
        $iCount = 0;
        $iFamilyMasterMemberRows = 0;
        while ($aRow = mysqli_fetch_array($rsMembers)) {
           // print_r($aRow);
            extract($aRow);
            $iCount++;
            $iFamilyMasterMemberRows++;
            /*$aFirstNames[$iCount] = $per_FirstName; $aMiddleNames[$iCount] = $per_MiddleName; $aLastNames[$iCount] = $per_LastName; $aSuffix[$iCount] = $per_Suffix;*/
            //$selected_year=2021;
            $aYearNames[$iCount]=$year_id;
            $aDatesMonths[$iCount] = $month_id;
            $aVisited[$iCount] = $visited_id;
            $aTeams[$iCount] = $team_id;
            $aCashes[$iCount] = $cash_id;
            $aBags[$iCount]=$bag_id;
            $aSups[$iCount]=$sup_id;
            $aRoles[$iCount] = $role_id;
            $aCreatedDates[$iCount] = $created_day;
            $aFamiliesMaster[$iCount] = $id;
            $aUsers[$iCount] = $user_id;
        } 


    } 
    elseif (($iFamilyID > 0)&($_GET['Add'] =='Add')) {
        $iFamilyMasterMemberRows = 1; 
        //Loop through the Family Member 'quick entry' form fields
        for ($iCount = 1; $iCount <= $iFamilyMasterMemberRows; $iCount++) { 
            // Assign everything to array
            $aDatesMonths[$iCount] = 0;
            $aVisited[$iCount] = 0;
            $aTeams[$iCount] = 0;
            $aCashes[$iCount] = 0;
            $aBags[$iCount] = 0;
            $aSups[$iCount] = 0; 
            $aRoles[$iCount] = 0;
            /*$aCreatedDates[$iCount] = 0;*/
            /*$aFamilies[$iCount] = 0;*/
            /*$aUsers[$iCount] = 0;*/
        }
    }
}
require 'Include/Header.php';
//$iFamilyMasterMemberRows+=$iFamilyMasterMemberRows_new;
?>
<form method="post" action="FamilyMasterEditor.php?FamilyID=<?php echo $iFamilyID ?>" id="familyMasterEditor">
	<input type="hidden" name="iFamilyID" value="<?= $iFamilyID ?>">
	<input type="hidden" name="FamCount" value="<?= $iFamilyMasterMemberRows ?>">
    <input type="hidden" id="YearName" name="YearName" value="2020">
	<div class="box box-info clearfix">
		
	<div class="box box-info clearfix">
		<div class="box-header">
			<h3 class="box-title"><?= gettext('Family Master Details') ?></h3>
			<div class="pull-right"><br/>
				<input type="submit" class="btn btn-primary" value="<?= gettext('Save') ?>" name="FamilyMasterSubmit">
			</div>
		</div><!-- /.box-header -->
		<div class="box-body">

	<?php if ($iFamilyMasterMemberRows > 0)
    {
    ?>
	<tr>
		<td colspan="2">
		<div class="MediumText">
			<center><?= $iFamilyID < 0 ? gettext('You may create family members now or add them later.  All entries will become <i>new</i> person records.') : '' ?></center>
		</div><br><br>
            <div class="table-responsive">
		<table cellpadding="3" cellspacing="0" width="100%">
		<!-- header -->
        <!-- end of header -->
		<thead>
        <tr class='TableHeader' align='center'> 
            <th><?= gettext('Month') ?></th>
            <th><?= gettext('Visited') ?></th>
            <th><?= gettext('Team') ?></th>
            <th><?= gettext('Cash') ?></th>
            <th><?= gettext('Bags') ?></th>
            <th><?= gettext('Sup') ?></th>
           <!--  <th><?= gettext('Role') ?></th> -->
        </tr>
        </thead>
        <?php
        //Get Cash
        $sSQL = 'SELECT * FROM master_teams';// WHERE lst_ID = 2 ORDER BY lst_OptionSequence';
        $rsteam = RunQuery($sSQL);
        $numTeam = mysqli_num_rows($rsteam);
        for ($c = 1; $c <= $numTeam; $c++) {
            $aRow = mysqli_fetch_array($rsteam);
            extract($aRow);
            $aTeamNames[$c] = $aRow['name'];
            $aTeamIDs[$c] = $aRow['id'];//$lst_OptionID;
        }

        //Get Cash
        $sSQL = 'SELECT * FROM master_cash';// WHERE lst_ID = 2 ORDER BY lst_OptionSequence';
        $rscash = RunQuery($sSQL);
        $numCash = mysqli_num_rows($rscash);
        for ($c = 1; $c <= $numCash; $c++) {
            $aRow = mysqli_fetch_array($rscash);
            extract($aRow);
            $aCashNames[$c] = $aRow['name'];
            $aCashIDs[$c] = $aRow['id'];//$lst_OptionID;
        }

        //Get Bags
        $sSQL = 'SELECT * FROM master_bags';// WHERE lst_ID = 2 ORDER BY lst_OptionSequence';
        $rsbag = RunQuery($sSQL);
        $numBag = mysqli_num_rows($rsbag);
        for ($c = 1; $c <= $numBag; $c++) {
            $aRow = mysqli_fetch_array($rsbag);
            extract($aRow);
            $aBagNames[$c] = $aRow['name'];
            $aBagIDs[$c] = $aRow['id'];//$lst_OptionID;
        }

        //Get Sups
        $sSQL = 'SELECT * FROM master_suppliments';// WHERE lst_ID = 2 ORDER BY lst_OptionSequence';
        $rssup = RunQuery($sSQL);
        $numSup = mysqli_num_rows($rssup);
        for ($c = 1; $c <= $numSup; $c++) {
            $aRow = mysqli_fetch_array($rssup);
            extract($aRow);
            $aSupNames[$c] = $aRow['name'];
            $aSupIDs[$c] = $aRow['id'];//$lst_OptionID;
        }

        //Get family roles
        $sSQL = 'SELECT * FROM list_lst WHERE lst_ID = 2 ORDER BY lst_OptionSequence';
        $rsFamilyRoles = RunQuery($sSQL);
        $numFamilyRoles = mysqli_num_rows($rsFamilyRoles);
        for ($c = 1; $c <= $numFamilyRoles; $c++) {
            $aRow = mysqli_fetch_array($rsFamilyRoles);
            extract($aRow);
            $aFamilyRoleNames[$c] = $lst_OptionName;
            $aFamilyRoleIDs[$c] = $lst_OptionID;
        }

        for ($iCount = 1; $iCount <= $iFamilyMasterMemberRows; $iCount++){
            ?>
		<input style="text-align: center" type="hidden" name="FamilyID<?= $iCount ?>" value="<?= $aFamiliesMaster[$iCount] ?>">
		<tr>
            <!-- TD: Birth -->
            <td class="TextColumn">
                <select name="month<?php echo $iCount ?>">
                    <option value="0" <?php if ($aDatesMonths[$iCount] == 0) {
                    echo 'selected';
                } ?>><?= gettext('Unknown') ?></option>
                    <option value="01" <?php if ($aDatesMonths[$iCount] == 1) {
                    echo 'selected';
                } ?>><?= gettext('January') ?></option>
                    <option value="02" <?php if ($aDatesMonths[$iCount] == 2) {
                    echo 'selected';
                } ?>><?= gettext('February') ?></option>
                    <option value="03" <?php if ($aDatesMonths[$iCount] == 3) {
                    echo 'selected';
                } ?>><?= gettext('March') ?></option>
                    <option value="04" <?php if ($aDatesMonths[$iCount] == 4) {
                    echo 'selected';
                } ?>><?= gettext('April') ?></option>
                    <option value="05" <?php if ($aDatesMonths[$iCount] == 5) {
                    echo 'selected';
                } ?>><?= gettext('May') ?></option>
                    <option value="06" <?php if ($aDatesMonths[$iCount] == 6) {
                    echo 'selected';
                } ?>><?= gettext('June') ?></option>
                    <option value="07" <?php if ($aDatesMonths[$iCount] == 7) {
                    echo 'selected';
                } ?>><?= gettext('July') ?></option>
                    <option value="08" <?php if ($aDatesMonths[$iCount] == 8) {
                    echo 'selected';
                } ?>><?= gettext('August') ?></option>
                    <option value="09" <?php if ($aDatesMonths[$iCount] == 9) {
                    echo 'selected';
                } ?>><?= gettext('September') ?></option>
                    <option value="10" <?php if ($aDatesMonths[$iCount] == 10) {
                    echo 'selected';
                } ?>><?= gettext('October') ?></option>
                    <option value="11" <?php if ($aDatesMonths[$iCount] == 11) {
                    echo 'selected';
                } ?>><?= gettext('November') ?></option>
                    <option value="12" <?php if ($aDatesMonths[$iCount] == 12) {
                    echo 'selected';
                } ?>><?= gettext('December') ?></option>
                </select>
            </td>
            <!-- TD: Visit -->
            <td class="TextColumn">
                <select name="Visit<?php echo $iCount ?>">
                    <option value="0" <?php if ($aVisited[$iCount] == 0) {
                echo 'selected';
            } ?> ><?= gettext('Select Visit') ?></option>
                    <option value="1" <?php if ($aVisited[$iCount] == 1) {
                echo 'selected';
            } ?> ><?= gettext('Visited') ?></option>
                    <option value="2" <?php if ($aVisited[$iCount] == 2) {
                echo 'selected';
            } ?> ><?= gettext('Not Visited') ?></option>
                </select>
            </td>
            <!--  -->
            <td class="TextColumn">
                <select name="Team<?php echo $iCount ?>">
                    <option value="0" <?php if ($aTeams[$iCount] == 0) {
                echo 'selected';
            } ?> ><?= gettext('Select Team') ?></option>
                <?php
                //Build the role select box
                for ($c = 1; $c <= $numTeam; $c++) {
                    echo '<option value="'.$aTeamIDs[$c].'"';
                    if ($aTeams[$iCount] == $aTeamIDs[$c]) {
                        echo ' selected';
                    }
                    echo '>'.$aTeamNames[$c].'</option>';
                } ?>
                </select>
            </td>
            <!--  -->
            <td class="TextColumn">
                <select name="Cash<?php echo $iCount ?>">
                    <option value="0" <?php if ($aCashes[$iCount] == 0) {
                echo 'selected';
            } ?> ><?= gettext('Select Cash') ?></option>
                <?php
                //Build the role select box
                for ($c = 1; $c <= $numCash; $c++) {
                    echo '<option value="'.$aCashIDs[$c].'"';
                    if ($aCashes[$iCount] == $aCashIDs[$c]) {
                        echo ' selected';
                    }
                    echo '>'.$aCashNames[$c].'</option>';
                } ?>
                </select>
            </td>
            <!--  -->
			<td class="TextColumn">
                <select name="Bag<?php echo $iCount ?>">
                    <option value="0" <?php if ($aBags[$iCount] == 0) {
                echo 'selected';
            } ?> ><?= gettext('Select Bag') ?></option>
                <?php
                //Build the role select box
                for ($c = 1; $c <= $numBag; $c++) {
                    echo '<option value="'.$aBagIDs[$c].'"';
                    if ($aBags[$iCount] == $aBagIDs[$c]) {
                        echo ' selected';
                    }
                    echo '>'.$aBagNames[$c].'</option>';
                } ?>
                </select>
            </td>

			<td class="TextColumn">
				<select name="Sup<?php echo $iCount ?>">
					<option value="0" <?php if ($aSups[$iCount] == 0) {
                echo 'selected';
            } ?> ><?= gettext('Select Role') ?></option>
				<?php
                //Build the role select box
                for ($c = 1; $c <= $numSup; $c++) {
                    echo '<option value="'.$aSupIDs[$c].'"';
                    if ($aSups[$iCount] == $aSupIDs[$c]) {
                        echo ' selected';
                    }
                    echo '>'.$aSupNames[$c].'</option>';
                } ?>
				</select>
			</td>
<!-- 
             <td class="TextColumn" style="display: none">
                <select name="Role<?php echo $iCount ?>">
                    <option value="0" <?php if ($aRoles[$iCount] == 0) {
                echo 'selected';
            } ?> ><?= gettext('Select Role') ?></option>
                <?php
                //Build the role select box
                for ($c = 1; $c <= $numFamilyRoles; $c++) {
                    echo '<option value="'.$aFamilyRoleIDs[$c].'"';
                    if ($aRoles[$iCount] == $aFamilyRoleIDs[$c]) {
                        echo ' selected';
                    }
                    echo '>'.$aFamilyRoleNames[$c].'</option>';
                } ?>
                </select>
            </td> -->

			<?php
           // for ($c = 1; $c <= $numFamilyRoles; $c++)
             //   echo '<input type="text" Name="Role'.$iCount.'" value="'.$aFamilyRoleIDs[$c].'">';
            echo '</tr>';
        }
        echo '</table></div>';

        echo '</div></div>';
    }
    echo '<td colspan="2" align="center">';
    
        

    //print($_GET);
    if ($add_method!=1)
        echo '<input type="hidden" Name="edit_method" value="edit_method">';
    else
        echo '<input type="hidden" Name="add_method" value="add_method">';

    echo '<input type="submit" class="btn btn-primary" value="'.gettext('Save').'" Name="FamilyMasterSubmit" id="FamilyMasterSubmitBottom"> ';
    /*if (AuthenticationManager::GetCurrentUser()->isAddRecordsEnabled()) {
        echo ' <input type="submit" class="btn btn-info" value="'.gettext('Save and Add').'" name="FamilyMasterSubmitAndAdd"> ';
    }*/
    echo ' <input type="button" class="btn btn-default" value="'.gettext('Cancel').'" Name="FamilyCancel"';
    if ($iFamilyID > 0) {
        echo " onclick=\"javascript:document.location='v2/family/$iFamilyID';\">";
    } else {
        echo " onclick=\"javascript:document.location='".SystemURLs::getRootPath()."/v2/family';\">";
    }
    echo '</td></tr></form></table>';
?>
<script src="<?= SystemURLs::getRootPath() ?>/skin/js/FamilyMasterEditor.js"></script>

<?php require 'Include/Footer.php' ?>
<?php 
function header_master(){
    echo "<thead>
        <tr class='TableHeader' align='center'>
            <th><?= gettext('First') ?></th>
            <th><?= gettext('Middle') ?></th>
            <th><?= gettext('Last') ?></th>
            <th><?= gettext('Suffix') ?></th>
            <th><?= gettext('Visited') ?></th>
            <th><?= gettext('Role') ?></th>
        </tr>
        </thead>";
}
function generate_view (){

header_master();


}
?>