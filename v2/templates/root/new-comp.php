<?php
use ChurchCRM\Utils\InputUtils;
use ChurchCRM\Utils\RedirectUtils;
use ChurchCRM\Authentication\AuthenticationManager;
use ChurchCRM\dto\SystemURLs;
use ChurchCRM\dto\SystemConfig;


require SystemURLs::getDocumentRoot() . '/Include/Config.php';
require SystemURLs::getDocumentRoot() . '/Include/Header.php';

if (!AuthenticationManager::GetCurrentUser()->isAdmin()) {
    RedirectUtils::Redirect('Menu.php');
    exit;
}
$comp_name = "";
$table = "master_dates_year";
switch ($added_comp){
    case "new_year":
        $comp_name = "New Year";
        $table = "master_dates_year";
        break;
    case "new_team":
        $comp_name = "New Visiting Team";
        $table = "master_teams";
        break;
    case "new_bag":
        $comp_name = "New Bag";
        $table = "master_bags";
        break;
    case "new_sup":
        $comp_name = "New Suppliment";
        $table = "master_suppliments";
        break;
    case "new_cash":
        $comp_name = "New Cash";
        $table = "master_cash";
        break;
    default:
        echo "default";
        break;
}
?>
<!-- Default box -->

<div class="box">
    <div class="box-header">
        <a href="/churchcrm/v2/newcomponent/new_team" class="btn btn-app"><i
                class="fa fa-users"></i><?= "New Visiting Team" ?></a>
        <a href="/churchcrm/v2/newcomponent/new_bag" class="btn btn-app"><i
                class="fa fa-list-alt"></i><?= "New Bag" ?></a>
        <a href="/churchcrm/v2/newcomponent/new_sup" class="btn btn-app"><i
                class="fa fa-list-alt"></i><?= "New Suppliment" ?></a>
        <a href="/churchcrm/v2/newcomponent/new_cash" class="btn btn-app"><i
                class="fa fa-cart-plus"></i><?= "New Cash" ?></a>
        <a href="/churchcrm/v2/newcomponent/new_year" class="btn btn-app"><i
                class="fa fa-plus-square"></i><?= "New Year" ?></a>
    </div>
</div>

<div class="callout callout-info">
    <?= "Adding $comp_name" ?>
</div>

<?php

// Get data for the form as it now exists..
$sSQL = "SELECT * FROM $table";
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
                    <th> Action </th>
                    <th> Name </th>
                    <th> Description </th>
                </tr>
            </thead>
            <tbody>

                <!--Populate the table with family details -->

                <?php $var=0; foreach ($years as $year) { ?>
                <tr>
                    <!-- Action -->
                    <td></td>
                    <!-- Name -->
                    <td> <?= $year[1] ?></td>
                    <!-- Year -->
                    <td> <?= $year[2]?></td>
                </tr>

                <?php } ?>
                <tr>
                    <form method="post"  action="<?= SystemURLs::getRootPath()?>/PostRedirect.php">
                        <td><input type="submit" class="btn btn-primary" value="Add New"></td>
                        <td><textarea name="name" rows='1' cols="30%" value=" "></textarea></td>
                        <input type="hidden" name="post_name" value="new_comp"></input>
                        <input type="hidden" name="table" value=<?= $table ?>></input>
                        <td><textarea name="desc" rows='1' cols="30%" value=" "></textarea></td>
                    </form>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<?php require SystemURLs::getDocumentRoot() . '/Include/Footer.php'; ?>