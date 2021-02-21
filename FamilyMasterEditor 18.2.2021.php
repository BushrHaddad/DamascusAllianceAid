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
$sNameError = '';
$sEmailError = '';
$sWeddingDateError = '';

$sName = '';

$UpdateBirthYear = 0;

$aFirstNameError = [];
$aBirthDateError = [];
$aperFlags = [];
//print_r($_GET);
if($_GET['Add']=='Add')
    $add_method=1;
else
    $add_method=0;
//Is this the second pass?
if (isset($_POST['FamilySubmit']) || isset($_POST['FamilySubmitAndAdd'])) {
    //print_r($_POST); exit;
    $iFamilyMemberRows = InputUtils::LegacyFilterInput($_POST['FamCount']);
    //echo $iFamilyMemberRows; exit;
    //Loop through the Family Member 'quick entry' form fields
    for ($iCount = 1; $iCount <= $iFamilyMemberRows; $iCount++) {//echo 'here '; exit;
        // Assign everything to arrays
        $aFirstNames[$iCount] = InputUtils::LegacyFilterInput($_POST['FirstName'.$iCount]);
        $aMiddleNames[$iCount] = InputUtils::LegacyFilterInput($_POST['MiddleName'.$iCount]);
        $aLastNames[$iCount] = InputUtils::LegacyFilterInput($_POST['LastName'.$iCount]);
        $aSuffix[$iCount] = InputUtils::LegacyFilterInput($_POST['Suffix'.$iCount]);
        $aRoles[$iCount] = InputUtils::LegacyFilterInput($_POST['Role'.$iCount], 'int');
        $aGenders[$iCount] = InputUtils::LegacyFilterInput($_POST['Gender'.$iCount], 'int');
        $aBirthDays[$iCount] = InputUtils::LegacyFilterInput($_POST['BirthDay'.$iCount], 'int');
        $aBirthMonths[$iCount] = InputUtils::LegacyFilterInput($_POST['BirthMonth'.$iCount], 'int');
        $aBirthYears[$iCount] = InputUtils::LegacyFilterInput($_POST['BirthYear'.$iCount], 'int');
        $aClassification[$iCount] = InputUtils::LegacyFilterInput($_POST['Classification'.$iCount], 'int');
        $aPersonIDs[$iCount] = InputUtils::LegacyFilterInput($_POST['PersonID'.$iCount], 'int'); 
        
        //echo $iCount;
        
        $aUpdateBirthYear[$iCount] = InputUtils::LegacyFilterInput($_POST['UpdateBirthYear'], 'int');

        // Make sure first names were entered if editing existing family
        if ($iFamilyID > 0) {
            if (strlen($aFirstNames[$iCount]) == 0) {
                $aFirstNameError[$iCount] = gettext('First name must be entered');
                $bErrorFlag = true;
            }
        }
        // Validate any family member birthdays
        if ((strlen($aFirstNames[$iCount]) > 0) && (strlen($aBirthYears[$iCount]) > 0)) {
            if (($aBirthYears[$iCount] > 2155) || ($aBirthYears[$iCount] < 1901)) {
                $aBirthDateError[$iCount] = gettext('Invalid Year: allowable values are 1901 to 2155');
                $bErrorFlag = true;
            } elseif ($aBirthMonths[$iCount] > 0 && $aBirthDays[$iCount] > 0) {
                if (!checkdate($aBirthMonths[$iCount], $aBirthDays[$iCount], $aBirthYears[$iCount])) {
                    $aBirthDateError[$iCount] = gettext('Invalid Birth Date.');
                    $bErrorFlag = true;
                }
            }
        }
    }
    //If no errors, then let's update...
    if (!$bErrorFlag) {
        //If the user added a new record, we need to key back to the route to the FamilyView page
        //if ($bGetKeyBack) 
        if($_POST['add_method'] =='add_method'){
            //Get the key back
            //Run through the family member arrays...
            for ($iCount = 1; $iCount <= $iFamilyMemberRows; $iCount++) {
                if (strlen($aFirstNames[$iCount]) > 0) {
                    if (strlen($aBirthYears[$iCount]) < 4) {
                        $aBirthYears[$iCount] = 'NULL';
                    }
                    //If no last name is entered for a member, use the family name.
                    if (strlen($aLastNames[$iCount]) && $aLastNames[$iCount] != $sName) {
                        $sLastNameToEnter = $aLastNames[$iCount];
                    } else {
                        $sLastNameToEnter = $sName;
                    }

                    RunQuery('LOCK TABLES person_per WRITE, person_custom WRITE');
                    $sSQL = "INSERT INTO person_per (
								per_FirstName,
								per_MiddleName,
								per_LastName,
                        per_Suffix,
								per_fam_ID,
								per_fmr_ID,
								per_DateEntered,
								per_EnteredBy,
								per_Gender,
								per_BirthDay,
								per_BirthMonth,
								per_BirthYear,
								per_cls_ID)
							VALUES (
								'$aFirstNames[$iCount]',
								'$aMiddleNames[$iCount]',
								'$sLastNameToEnter',
								'$aSuffix[$iCount]',
								$iFamilyID,
								$aRoles[$iCount],
								'".date('YmdHis')."',
								".AuthenticationManager::GetCurrentUser()->getId().",
								$aGenders[$iCount],
								$aBirthDays[$iCount],
								$aBirthMonths[$iCount],
								$aBirthYears[$iCount],
								$aClassification[$iCount])";
                    
                    RunQuery($sSQL);
                    $dbPersonId = mysqli_insert_id($cnInfoCentral);
                    $note = new Note();
                    $note->setPerId($dbPersonId);
                    $note->setText(gettext('Created via Family'));
                    $note->setType('create');
                    $note->setEntered(AuthenticationManager::GetCurrentUser()->getId());
                    $note->save();
                    $sSQL = 'INSERT INTO person_custom (per_ID) VALUES ('
                                .$dbPersonId.')';
                    RunQuery($sSQL);
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
            for ($iCount = 1; $iCount <= $iFamilyMemberRows; $iCount++) {

                if (strlen($aFirstNames[$iCount]) > 0) {
                    if (strlen($aBirthYears[$iCount]) < 4) {
                        $aBirthYears[$iCount] = 'NULL';
                    }

                    //If no last name is entered for a member, use the family name.
                    if (strlen($aLastNames[$iCount]) && $aLastNames[$iCount] != $sName) {
                        $sLastNameToEnter = $aLastNames[$iCount];
                    } else {
                        $sLastNameToEnter = $sName;
                    }
                    $sBirthYearScript = ($aUpdateBirthYear[$iCount] & 1) ? 'per_BirthYear='.$aBirthYears[$iCount].', ' : '';
                    //RunQuery("LOCK TABLES person_per WRITE, person_custom WRITE");
                //if($_GET['Add'] !='Add')
                    $sSQL = "UPDATE person_per SET per_FirstName='".$aFirstNames[$iCount]."', per_MiddleName='".$aMiddleNames[$iCount]."',per_LastName='".$aLastNames[$iCount]."',per_Suffix='".$aSuffix[$iCount]."',per_Gender='".$aGenders[$iCount]."',per_fmr_ID='".$aRoles[$iCount]."',per_BirthMonth='".$aBirthMonths[$iCount]."',per_BirthDay='".$aBirthDays[$iCount]."', ".$sBirthYearScript."per_cls_ID='".$aClassification[$iCount]."' WHERE per_ID=".$aPersonIDs[$iCount];
                /*else
                    $sSQL = "UPDATE person_per SET per_FirstName='".$aFirstNames[$iCount]."', per_MiddleName='".$aMiddleNames[$iCount]."',per_LastName='".$aLastNames[$iCount]."',per_Suffix='".$aSuffix[$iCount]."',per_Gender='".$aGenders[$iCount]."',per_fmr_ID='".$aRoles[$iCount]."',per_BirthMonth='".$aBirthMonths[$iCount]."',per_BirthDay='".$aBirthDays[$iCount]."', ".$sBirthYearScript."per_cls_ID='".$aClassification[$iCount]."' WHERE per_ID=".$aPersonIDs[$iCount];*/

                    RunQuery($sSQL);
                    //RunQuery("UNLOCK TABLES");

                    $note = new Note();
                    $note->setPerId($aPersonIDs[$iCount]);
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

        // Update the custom person fields.
        if ($numCustomFields > 0) {
            $sSQL = 'REPLACE INTO family_custom SET ';
            mysqli_data_seek($rsCustomFields, 0);

            while ($rowCustomField = mysqli_fetch_array($rsCustomFields, MYSQLI_BOTH)) {
                extract($rowCustomField);
                if (AuthenticationManager::GetCurrentUser()->isEnabledSecurity($aSecurityType[$fam_custom_FieldSec])) {
                    $currentFieldData = trim($aCustomData[$fam_custom_Field]);

                    sqlCustomField($sSQL, $type_ID, $currentFieldData, $fam_custom_Field, $sCountry);
                }
            }

            // chop off the last 2 characters (comma and space) added in the last while loop iteration.
            $sSQL = mb_substr($sSQL, 0, -2);

            $sSQL .= ', fam_ID = '.$iFamilyID;

            //Execute the SQL
            RunQuery($sSQL);
        }

        //Which submit button did they press?
        if (isset($_POST['FamilySubmit'])) {
            //Send to the view of this person
            RedirectUtils::Redirect('v2/family/'.$iFamilyID);
        } else {
            //RedirectUtils::Redirect('v2/family/'.$iFamilyID);
            //Reload to editor to add another record
            RedirectUtils::Redirect('FamilyMasterEditor.php?FamilyID='.$iFamilyID);
        }
    }
} else {
    //FirstPass
    //Are we editing or adding?
    if (($iFamilyID > 0)&($_GET['Add'] !='Add')) {
        $sSQL = 'SELECT * FROM person_per LEFT JOIN family_fam ON per_fam_ID = fam_ID WHERE per_fam_ID ='.$iFamilyID.' ORDER BY per_fmr_ID';
        $rsMembers = RunQuery($sSQL);
        $iCount = 0;
        $iFamilyMemberRows = 0;
        while ($aRow = mysqli_fetch_array($rsMembers)) { 
            extract($aRow);
            $iCount++;
            $iFamilyMemberRows++;
            $aFirstNames[$iCount] = $per_FirstName;
            $aMiddleNames[$iCount] = $per_MiddleName;
            $aLastNames[$iCount] = $per_LastName;
            $aSuffix[$iCount] = $per_Suffix;
            $aGenders[$iCount] = $per_Gender;
            $aRoles[$iCount] = $per_fmr_ID;
            $aBirthMonths[$iCount] = $per_BirthMonth;
            $aBirthDays[$iCount] = $per_BirthDay;
            if ($per_BirthYear > 0) {
                $aBirthYears[$iCount] = $per_BirthYear;
            } else {
                $aBirthYears[$iCount] = '';
            }
            $aClassification[$iCount] = $per_cls_ID;
            $aPersonIDs[$iCount] = $per_ID;
            $aPerFlag[$iCount] = $per_Flags;
        } 

    } 
    elseif (($iFamilyID > 0)&($_GET['Add'] =='Add')) {
        $iFamilyMemberRows = 1; 
        //Loop through the Family Member 'quick entry' form fields
        for ($iCount = 1; $iCount <= $iFamilyMemberRows; $iCount++) { 
            // Assign everything to arrays
            $aFirstNames[$iCount] = '';
            $aMiddleNames[$iCount] = '';
            $aLastNames[$iCount] = '';
            $aSuffix[$iCount] = '';
            $aRoles[$iCount] = 0;
            $aGenders[$iCount] = '';
            $aBirthDays[$iCount] = 0;
            $aBirthMonths[$iCount] = 0;
            $aBirthYears[$iCount] = '';
            $aClassification[$iCount] = 0;
            $aPersonIDs[$iCount] = 0;
            $aUpdateBirthYear[$iCount] = 0;
        }

        $aCustomData = [];
        $aCustomErrors = [];
        if ($numCustomFields > 0) {
            mysqli_data_seek($rsCustomFields, 0);
            while ($rowCustomField = mysqli_fetch_array($rsCustomFields, MYSQLI_BOTH)) {
                extract($rowCustomField);
                $aCustomData[$fam_custom_Field] = '';
                $aCustomErrors[$fam_custom_Field] = false;
            }
        }
    }
}

require 'Include/Header.php';
//$iFamilyMemberRows+=$iFamilyMemberRows_new;

?>

<form method="post" action="FamilyMasterEditor.php?FamilyID=<?php echo $iFamilyID ?>" id="familyMasterEditor">
	<input type="hidden" name="iFamilyID" value="<?= $iFamilyID ?>">
	<input type="hidden" name="FamCount" value="<?= $iFamilyMemberRows ?>">
    <input type="hidden" id="stateType" name="stateType" value="">
	<div class="box box-info clearfix">
		
	<div class="box box-info clearfix">
		<div class="box-header">
			<h3 class="box-title"><?= gettext('Family Members') ?></h3>
			<div class="pull-right"><br/>
				<input type="submit" class="btn btn-primary" value="<?= gettext('Save') ?>" name="FamilySubmit">
			</div>
		</div><!-- /.box-header -->
		<div class="box-body">

	<?php if ($iFamilyMemberRows > 0) {
        ?>

	<tr>
		<td colspan="2">
		<div class="MediumText">
			<center><?= $iFamilyID < 0 ? gettext('You may create family members now or add them later.  All entries will become <i>new</i> person records.') : '' ?></center>
		</div><br><br>
            <div class="table-responsive">
		<table cellpadding="3" cellspacing="0" width="100%">
		<thead>
		<tr class="TableHeader" align="center">
			<th><?= gettext('First') ?></th>
			<th><?= gettext('Middle') ?></th>
			<th><?= gettext('Last') ?></th>
			<th><?= gettext('Suffix') ?></th>
			<th><?= gettext('Gender') ?></th>
			<th><?= gettext('Role') ?></th>
			<th><?= gettext('Birth Month') ?></th>
			<th><?= gettext('Birth Day') ?></th>
			<th><?= gettext('Birth Year') ?></th>
			<th><?= gettext('Classification') ?></th>
		</tr>
		</thead>
		<?php

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

        for ($iCount = 1; $iCount <= $iFamilyMemberRows; $iCount++) {
            ?>
		<input type="hidden" name="PersonID<?= $iCount ?>" value="<?= $aPersonIDs[$iCount] ?>">
		<tr>
			<td class="TextColumn">
				<input name="FirstName<?= $iCount ?>" type="text" value="<?= $aFirstNames[$iCount] ?>" size="10">
				<div><font color="red"><?php if (array_key_exists($iCount, $aFirstNameError)) {
                echo $aFirstNameError[$iCount];
            } ?></font></div>
			</td>
			<td class="TextColumn">
				<input name="MiddleName<?= $iCount ?>" type="text" value="<?= $aMiddleNames[$iCount] ?>" size="10">
			</td>
			<td class="TextColumn">
				<input name="LastName<?= $iCount ?>" type="text" value="<?= $aLastNames[$iCount] ?>" size="10">
			</td>
			<td class="TextColumn">
				<input name="Suffix<?= $iCount ?>" type="text" value="<?= $aSuffix[$iCount] ?>" size="10">
			</td>
			<td class="TextColumn">
				<select name="Gender<?php echo $iCount ?>">
					<option value="0" <?php if ($aGenders[$iCount] == 0) {
                echo 'selected';
            } ?> ><?= gettext('Select Gender') ?></option>
					<option value="1" <?php if ($aGenders[$iCount] == 1) {
                echo 'selected';
            } ?> ><?= gettext('Male') ?></option>
					<option value="2" <?php if ($aGenders[$iCount] == 2) {
                echo 'selected';
            } ?> ><?= gettext('Female') ?></option>
				</select>
			</td>

			<td class="TextColumn">
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
			</td>
			<td class="TextColumn">
				<select name="BirthMonth<?php echo $iCount ?>">
					<option value="0" <?php if ($aBirthMonths[$iCount] == 0) {
                    echo 'selected';
                } ?>><?= gettext('Unknown') ?></option>
					<option value="01" <?php if ($aBirthMonths[$iCount] == 1) {
                    echo 'selected';
                } ?>><?= gettext('January') ?></option>
					<option value="02" <?php if ($aBirthMonths[$iCount] == 2) {
                    echo 'selected';
                } ?>><?= gettext('February') ?></option>
					<option value="03" <?php if ($aBirthMonths[$iCount] == 3) {
                    echo 'selected';
                } ?>><?= gettext('March') ?></option>
					<option value="04" <?php if ($aBirthMonths[$iCount] == 4) {
                    echo 'selected';
                } ?>><?= gettext('April') ?></option>
					<option value="05" <?php if ($aBirthMonths[$iCount] == 5) {
                    echo 'selected';
                } ?>><?= gettext('May') ?></option>
					<option value="06" <?php if ($aBirthMonths[$iCount] == 6) {
                    echo 'selected';
                } ?>><?= gettext('June') ?></option>
					<option value="07" <?php if ($aBirthMonths[$iCount] == 7) {
                    echo 'selected';
                } ?>><?= gettext('July') ?></option>
					<option value="08" <?php if ($aBirthMonths[$iCount] == 8) {
                    echo 'selected';
                } ?>><?= gettext('August') ?></option>
					<option value="09" <?php if ($aBirthMonths[$iCount] == 9) {
                    echo 'selected';
                } ?>><?= gettext('September') ?></option>
					<option value="10" <?php if ($aBirthMonths[$iCount] == 10) {
                    echo 'selected';
                } ?>><?= gettext('October') ?></option>
					<option value="11" <?php if ($aBirthMonths[$iCount] == 11) {
                    echo 'selected';
                } ?>><?= gettext('November') ?></option>
					<option value="12" <?php if ($aBirthMonths[$iCount] == 12) {
                    echo 'selected';
                } ?>><?= gettext('December') ?></option>
				</select>
			</td>
			<td class="TextColumn">
				<select name="BirthDay<?= $iCount ?>">
					<option value="0"><?= gettext('Unk')?></option>
					<?php for ($x = 1; $x < 32; $x++) {
                    if ($x < 10) {
                        $sDay = '0'.$x;
                    } else {
                        $sDay = $x;
                    } ?>
					<option value="<?= $sDay ?>" <?php if ($aBirthDays[$iCount] == $x) {
                        echo 'selected';
                    } ?>><?= $x ?></option>
				<?php
                } ?>
				</select>
			</td>
			<td class="TextColumn">
			<?php	if (!array_key_exists($iCount, $aperFlags) || !$aperFlags[$iCount]) {
                    $UpdateBirthYear = 1; ?>
				<input name="BirthYear<?= $iCount ?>" type="text" value="<?= $aBirthYears[$iCount] ?>" size="4" maxlength="4">
				<div><font color="red"><?php if (array_key_exists($iCount, $aBirthDateError)) {
                        echo $aBirthDateError[$iCount];
                    } ?></font></div>
			<?php
                } else {
                    $UpdateBirthYear = 0;
                } ?>
			</td>
			<td>
				<select name="Classification<?php echo $iCount ?>">
					<option value="0" <?php if ($aClassification[$iCount] == 0) {
                    echo 'selected';
                } ?>><?= gettext('Unassigned') ?></option>
					<option value="0" disabled>-----------------------</option>
					<?php
                    //Get Classifications for the drop-down
                    $sSQL = 'SELECT * FROM list_lst WHERE lst_ID = 1 ORDER BY lst_OptionSequence';
            $rsClassifications = RunQuery($sSQL);

            //Display Classifications
            while ($aRow = mysqli_fetch_array($rsClassifications)) {
                extract($aRow);
                echo '<option value="'.$lst_OptionID.'"';
                if ($aClassification[$iCount] == $lst_OptionID) {
                    echo ' selected';
                }
                echo '>'.$lst_OptionName.'&nbsp;';
            }
            echo '</select></td></tr>';
        }
        echo '</table></div>';

        echo '</div></div>';
    }

    echo '<td colspan="2" align="center">';
    echo '<input type="hidden" Name="UpdateBirthYear" value="'.$UpdateBirthYear.'">';

    //print($_GET);
if ($add_method!=1)
        echo '<input type="hidden" Name="edit_method" value="edit_method">';
else
        echo '<input type="hidden" Name="add_method" value="add_method">';

    echo '<input type="submit" class="btn btn-primary" value="'.gettext('Save').'" Name="FamilySubmit" id="FamilySubmitBottom"> ';
    /*if (AuthenticationManager::GetCurrentUser()->isAddRecordsEnabled()) {
        echo ' <input type="submit" class="btn btn-info" value="'.gettext('Save and Add').'" name="FamilySubmitAndAdd"> ';
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
