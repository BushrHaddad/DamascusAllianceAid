<?php


use ChurchCRM\Authentication\AuthenticationManager;
use ChurchCRM\dto\Classification;
use ChurchCRM\dto\SystemConfig;
use ChurchCRM\dto\SystemURLs;
use ChurchCRM\Service\MailChimpService;

//Set the page title
$sPageTitle =  $family->getName() . " - " . gettext("Family");
include SystemURLs::getDocumentRoot() . '/Include/Header.php';

$curYear = (new DateTime)->format("Y");
$mailchimp = new MailChimpService();
?>

<script nonce="<?= SystemURLs::getCSPNonce() ?>">
window.CRM.currentFamily = <?= $family->getId() ?>;
window.CRM.currentFamilyName = "<?= $family->getName() ?>";
window.CRM.currentActive = <?= $family->isActive() ? "true" : "false" ?>;
window.CRM.currentFamilyView = 2;
window.CRM.plugin.mailchimp = <?= $mailchimp->isActive()? "true" : "false" ?>;
</script>


<div id="family-deactivated" class="alert alert-warning hide">
    <strong><?= gettext("This Family is Deactivated") ?> </strong>
</div>

<div class="row">

    <div class="col-lg-5">
        <div class="row">
            <div>
                <div class="box box-primary">
                    <div class="box-header">
                        <i class="fa fa-info"></i>
                        <h3 class="box-title"><?= $family->getName() ?></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool edit-family"><i class="fa fa-edit"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="image-container">
                            <img src="<?= SystemURLs::getRootPath() ?>/api/family/<?= $family->getId() ?>/photo"
                                class="img-responsive profile-user-img profile-family-img" />
                            <div class="after">
                                <div class="buttons">
                                    <a id="view-larger-image-btn" href="#" title="<?= gettext("View Photo") ?>">
                                        <i class="fa fa-search-plus"></i>
                                    </a>
                                    <?php if (AuthenticationManager::GetCurrentUser()->isEditRecordsEnabled()): ?>
                                    &nbsp;
                                    <a href="#" data-toggle="modal" data-target="#upload-image"
                                        title="<?= gettext("Upload Photo") ?>">
                                        <i class="fa fa-camera"></i>
                                    </a>&nbsp;
                                    <a href="#" data-toggle="modal" data-target="#confirm-delete-image"
                                        title="<?= gettext("Delete Photo") ?>">
                                        <i class="fa fa-trash-o"></i>
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div>
                <div class="box">
                    <br />
                    <div class="text-center">
                        <a class="btn btn-app" id="lastFamily"><i
                                class="fa fa-hand-o-left"></i><?= gettext('Previous Family') ?></a>

                        <a class="btn btn-app btn-danger" role="button"
                            href="<?= SystemURLs::getRootPath()?>/v2/family"><i
                                class="fa fa-list-ul"></i><?= gettext('Family List') ?></a>

                        <a class="btn btn-app" role="button" id="nextFamily"><i
                                class="fa fa-hand-o-right"></i><?= gettext('Next Family') ?> </a>
                    </div>
                    <hr />
                    <a class="btn btn-app" href="#" data-toggle="modal" data-target="#confirm-verify"><i
                            class="fa fa-check-square"></i> <?= gettext("Verify Info") ?></a>
                    <a class="btn btn-app bg-olive"
                        href="<?= SystemURLs::getRootPath() ?>/PersonEditor.php?FamilyID=<?=$family->getId()?>"><i
                            class="fa fa-plus-square"></i> <?= gettext('Add New Member') ?></a>

                    <?php if (AuthenticationManager::GetCurrentUser()->isEditRecordsEnabled()) { ?>
                    <button class="btn btn-app bg-orange" id="activateDeactivate">
                        <i
                            class="fa <?= (empty($family->isActive()) ? 'fa-times-circle-o' : 'fa-check-circle-o') ?> "></i><?php echo(($family->isActive() ? _('Deactivate') : _('Activate')) . _(' this Family')); ?>
                    </button>
                    <?php }
                        if (AuthenticationManager::GetCurrentUser()->isDeleteRecordsEnabled()) {
                            ?>
                    <a class="btn btn-app bg-maroon"
                        href="<?= SystemURLs::getRootPath() ?>/SelectDelete.php?FamilyID=<?=$family->getId()?>"><i
                            class="fa fa-trash-o"></i><?= gettext('Delete this Family') ?></a>
                    <?php
                        }
                        if (AuthenticationManager::GetCurrentUser()->isNotesEnabled()) {
                            ?>
                    <a class="btn btn-app"
                        href="<?= SystemURLs::getRootPath() ?>/NoteEditor.php?FamilyID=<?= $family->getId()?>"><i
                            class="fa fa-sticky-note"></i><?= gettext("Add a Note") ?></a>
                    <?php
                        } ?>
                    <a class="btn btn-app" id="AddFamilyToCart" data-familyid="<?= $family->getId() ?>"> <i
                            class="fa fa-cart-plus"></i> <?= gettext("Add All Family Members to Cart") ?></a>
                    <?php if (AuthenticationManager::GetCurrentUser()->isCanvasserEnabled()) { ?>
                    <a class="btn btn-app"
                        href="<?= SystemURLs::getRootPath()?>/CanvassEditor.php?FamilyID=<?= $family->getId() ?>&FYID=<?= MakeFYString($_SESSION['idefaultFY']) ?>&amp;linkBack=v2/family/<?= $family->getId() ?>">
                        <i
                            class="fa fa-refresh"></i><?= MakeFYString($_SESSION['idefaultFY']) . gettext(" Canvass Entry") ?></a>
                    <?php } ?>

                    <?php if (AuthenticationManager::GetCurrentUser()->isFinanceEnabled()) { ?>
                    <a class="btn btn-app"
                        href="<?= SystemURLs::getRootPath()?>/PledgeEditor.php?FamilyID=<?= $family->getId() ?>&amp;linkBack=v2/family/<?= $family->getId() ?>&amp;PledgeOrPayment=Pledge">
                        <i class="fa fa-check-circle-o"></i><?= gettext("Add a new pledge") ?></a>
                    <a class="btn btn-app"
                        href="<?= SystemURLs::getRootPath()?>/PledgeEditor.php?FamilyID=<?= $family->getId() ?>&amp;linkBack=v2/family/<?= $family->getId() ?>&amp;PledgeOrPayment=Payment">
                        <i class="fa fa-money"></i><?= gettext("Add a new payment") ?></a>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="row">
            <div>
                <div class="box box-primary">
                    <div class="box-header">
                        <i class="fa fa-id-badge"></i>
                        <h3 class="box-title"><?= gettext("Metadata") ?></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool edit-family"><i class="fa fa-edit"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <ul class="fa-ul">
                            <?php if (!empty($family->getAddress())) { ?>
                            <li> <i class="fa-li fa fa-map"></i><?= gettext("Address") ?>: <span> <a
                                        href="http://maps.google.com/?q=<?= $family->getAddress() ?>"
                                        target="_blank"><?= $family->getAddress() ?></a></span>
                            </li>
                            <?php
                            }
                            if (!empty($family->getHomePhone())) { ?>
                            <li><i class="fa-li fa fa-phone"></i><?= gettext("Home Phone") ?>: <span><a
                                        href="tel:<?= $family->getHomePhone() ?>"><?= $family->getHomePhone() ?></a></span>
                            </li>
                            <?php
                            }
                            if ($family->getWorkPhone() != "") {
                                ?>
                            <li><i class="fa-li fa fa-building"></i><?= gettext("Work Phone") ?>: <span><a
                                        href="tel:<?= $family->getWorkPhone() ?>"><?= $family->getWorkPhone() ?></a></span>
                            </li>
                            <?php
                            }
                            if ($family->getCellPhone() != "") {
                                ?>
                            <li><i class="fa-li fa fa-mobile"></i><?= gettext("Mobile Phone") ?>: <span><a
                                        href="tel:<?= $family->getCellPhone() ?>"><?= $family->getCellPhone() ?></a></span>
                            </li>
                            <?php
                            }
                            if ($family->getEmail() != "") {
                                ?>
                            <li><i class="fa-li fa fa-envelope"></i><?= gettext("Email") ?>:<a
                                    href="mailto:<?= $family->getEmail() ?>">
                                    <span><?= $family->getEmail() ?></span></a></li>
                            <?php if ($mailchimp->isActive()) { ?>
                            <li><i class="fa-li fa fa-send"></i><?= gettext("Mailchimp") ?>:
                                <span id="<?= md5($family->getEmail())?>">... <?= gettext("loading")?> ...</span></a>
                            </li>
                            <?php }
                            }
                            
                            foreach ($familyCustom as $customField) {
                                echo '<li><i class="fa-li ' . $customField->getIcon() . '"></i>'. $customField->getDisplayValue().': <span>';
                                if ($customField->getLink()) {
                                    echo "<a href=\"" . $customField->getLink() . "\">" . $customField->getFormattedValue() . "</a>";
                                } else {
                                    echo $customField->getFormattedValue();
                                }
                                echo '</span></li>';
                            }  
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div>
                <div class="box">
                    <div class="box-header">
                        <i class="fa fa-group"></i>
                        <h3 class="box-title"><?= gettext("Family Members") ?></h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-minus"></i>
                            </button>

                        </div>
                    </div>
                    <div class="box-body row row-flex row-flex-wrap">
                        <?php foreach ($family->getPeople() as $person) { ?>
                        <div class="col-sm-6">
                            <div class="box box-primary">
                                <div class="box-body box-profile">
                                    <a href="<?= $person->getViewURI()?>" ?>
                                        <img class="profile-user-img img-responsive img-circle initials-image"
                                            src="data:image/png;base64,<?= base64_encode($person->getPhoto()->getThumbnailBytes()) ?>">
                                        <h3 class="profile-username text-center"><?= $person->getTitle() ?>
                                            <?= $person->getFullName() ?></h3>
                                    </a>
                                    <p class="text-muted text-center"><i
                                            class="fa fa-fw fa-<?= ($person->isMale() ? "male" : "female") ?>"></i>
                                        <?= $person->getFamilyRoleName() ?>
                                    </p>

                                    <p class="text-center">
                                        <a class="AddToPeopleCart" data-cartpersonid="<?= $person->getId() ?>">
                                            <button type="button" class="btn btn-xs btn-primary"><i
                                                    class="fa fa-cart-plus"></i></button>
                                        </a>

                                        <a href="<?= SystemURLs::getRootPath()?>/PersonEditor.php?PersonID=<?= $person->getID()?>"
                                            class="table-link">
                                            <button type="button" class="btn btn-xs btn-primary"><i
                                                    class="fa fa-pencil"></i></button>
                                        </a>
                                        <a class="delete-person" data-person_name="<?= $person->getFullName() ?>"
                                            data-person_id="<?= $person->getId() ?>" data-view="family">
                                            <button type="button" class="btn btn-xs btn-danger"><i
                                                    class="fa fa-trash"></i></button>
                                        </a>
                                    </p>
                                    <?php if ($person->getClsId()) { ?>
                                    <li class="list-group">
                                        <b>Classification:</b> <?= Classification::getName($person->getClsId()) ?>
                                    </li>
                                    <?php } ?>
                                    <ul class="list-group list-group-unbordered">
                                        <li class="list-group-item">
                                            <?php if (!empty($person->getHomePhone())) { ?>
                                            <i class="fa fa-fw fa-phone" title="<?= gettext("Home Phone") ?>"></i>(H)
                                            <?= $person->getHomePhone() ?>
                                            <br />
                                            <?php }
                                                if (!empty($person->getWorkPhone())) { ?>
                                            <i class="fa fa-fw fa-briefcase"
                                                title="<?= gettext("Work Phone") ?>"></i>(W)
                                            <?= $person->getWorkPhone() ?>
                                            <br />
                                            <?php }
                                                if (!empty($person->getCellPhone())) { ?>
                                            <i class="fa fa-fw fa-mobile" title="<?= gettext("Mobile Phone") ?>"></i>(M)
                                            <?= $person->getCellPhone() ?>
                                            <br />
                                            <?php }
                                                if (!empty($person->getEmail())) { ?>
                                            <i class="fa fa-fw fa-envelope" title="<?= gettext("Email") ?>"></i>(H)
                                            <?= $person->getEmail() ?>
                                            <br />
                                            <?php }
                                                if (!empty($person->getWorkEmail())) { ?>
                                            <i class="fa fa-fw fa-envelope-o"
                                                title="<?= gettext("Work Email") ?>"></i>(W)
                                            <?= $person->getWorkEmail() ?>
                                            <br />
                                            <?php }
                                                $formatedBirthday = $person->getFormattedBirthDate();
                                                if ($formatedBirthday) {?>
                                            <i class="fa fa-fw fa-birthday-cake" title="<?= gettext("Birthday") ?>"></i>
                                            <?= $formatedBirthday ?> <?= $person->getAge()?>
                                            </i>
                                            <?php } ?>
                                        </li>
                                    </ul>

                                </div>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">

        <!-- Master Table -->
        <div class="box">

            <div class="box-header">
                <i class="fa fa-history"></i>
                <h3 class="box-title"><?= gettext("Master Table") ?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label>Choose a Year:</label>
                    <select id="year_status" class="form-control" name="c5">
                        <option selected="">--------------------</option>
                        <option>2016</option>
                        <option>2017</option>
                        <option>2018</option>
                        <option>2019</option>
                        <option>2020</option>
                    </select>
                </div>
                <table id="example" class="table table-striped table-bordered data-table" cellspacing="0"
                    style="width:100%;">
                    <thead>
                        <tr>
                            <th> Id </th>
                            <th> Name </th>
                            <th> Desc </th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- End Master Table -->

        <!-- Start Property Table  -->
        <div class="box">
            <div class="box-header">
                <i class="fa fa-hashtag"></i>
                <h3 class="box-title"><?= gettext("Properties") ?></h3>
                <div class="box-tools pull-right">
                    <?php if (AuthenticationManager::GetCurrentUser()->isEditRecordsEnabled()) { ?>
                    <button id="add-family-property" type="button" class="btn btn-box-tool hidden"><i
                            class="fa fa-plus-circle text-blue"></i></button>
                    <?php } ?>

                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">

                <div id="family-property-loading" class="col-xs-12 text-center">
                    <i class="btn btn-default btn-lrg ajax">
                        <i class="fa fa-spin fa-refresh"></i>&nbsp; <?= gettext("Loading") ?>
                    </i>
                </div>

                <div id="family-property-no-data" class="alert alert-warning hidden">
                    <i class="fa fa-question-circle fa-fw fa-lg"></i>
                    <span><?= gettext("No property assignments.") ?></span>
                </div>

                <table id="family-property-table" class="table table-striped table-bordered data-table hidden"
                    cellspacing="0" width="80%">
                    <thead>
                        <tr>
                            <th width="50"></th>
                            <th width="250" class="text-center"><?= gettext("Name") ?></th>
                            <th class="text-center"><?= gettext("Value") ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
        <!-- End Property Table  -->

        <!-- Start Timeline Table -->
        <div class="box">
            <div class="box-header">
                <i class="fa fa-history"></i>
                <h3 class="box-title"><?= gettext("Timeline") ?></h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <ul class="timeline">
                    <!-- timeline time label -->
                    <li class="time-label"><span class="bg-teal"><?= $curYear ?></span></li>
                    <!-- /.timeline-label -->

                    <!-- timeline item -->
                    <?php foreach ($familyTimeline as $item) {
                        if ($curYear != $item['year']) {
                            $curYear = $item['year']; ?>
                    <li class="time-label"><span class="bg-green"><?= $curYear ?></span></li>
                    <?php
                        } ?>
                    <li>
                        <!-- timeline icon -->
                        <i class="fa <?= $item['style'] ?>"></i>
                        <div class="timeline-item">
                            <span class="time">
                                <?php if (AuthenticationManager::GetCurrentUser()->isNotesEnabled() && (isset($item["editLink"]) || isset($item["deleteLink"]))) {
                                    ?>
                                <?php if (isset($item["editLink"])) { ?>
                                <a href="<?= $item["editLink"] ?>"><button type="button"
                                        class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></button></a>
                                <?php }
                                        if (isset($item["deleteLink"])) { ?>
                                <a href="<?= $item["deleteLink"] ?>"><button type="button"
                                        class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></button></a>
                                <?php } ?>
                                &nbsp;
                                <?php
                                } ?>
                                <i class="fa fa-clock-o"></i> <?= $item['datetime'] ?></span>
                            <?php if ($item['slim']) { ?>
                            <h4 class="timeline-header">
                                <?= $item['text'] ?> <?= gettext($item['header']) ?>
                            </h4>
                            <?php } else { ?>
                            <h3 class="timeline-header">
                                <?php if (in_array('headerlink', $item)) {
                                            ?>
                                <a href="<?= $item['headerlink'] ?>"><?= $item['header'] ?></a>
                                <?php
                                        } else {
                                            ?>
                                <?= gettext($item['header']) ?>
                                <?php
                                        } ?>
                            </h3>

                            <div class="timeline-body">
                                <pre><?= $item['text'] ?></pre>
                            </div>



                            <?php } ?>
                        </div>
                    </li>
                    <?php
                    } ?>
                    <!-- END timeline item -->
                </ul>
            </div>
        </div>



    </div>
</div>



<?php if (AuthenticationManager::GetCurrentUser()->isFinanceEnabled()) {
?>
<div class="row">
    <div class="col-lg-12">
        <div class="row">
            <div class="col-lg-12">
                <div class="box">
                    <div class="box-header">
                        <i class="fa fa-map"></i>
                        <h3 class="box-title"><?= gettext("Pledges and Payments") ?></h3>
                        <div class="box-tools pull-right">
                            <input type="checkbox" id="ShowPledges"
                                <?= AuthenticationManager::GetCurrentUser()->isShowPledges() ? "checked" : "" ?>>
                            <?= gettext("Show Pledges") ?>
                            <input type="checkbox" id="ShowPayments"
                                <?= AuthenticationManager::GetCurrentUser()->isShowPayments() ? "checked" : "" ?>>
                            <?= gettext("Show Payments") ?>
                            <label for="ShowSinceDate"><?= gettext("Since") ?>:</label>
                            <input type="text" class="date-picker" id="ShowSinceDate"
                                value="<?= AuthenticationManager::GetCurrentUser()->getShowSince() ?>" maxlength="10"
                                id="ShowSinceDate" size="15">
                        </div>
                    </div>
                    <div class="box-body">
                        <table id="pledge-payment-v2-table"
                            class="table table-striped table-bordered table-responsive data-table">
                            <tbody></tbody>
                        </table>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<script src="<?= SystemURLs::getRootPath() ?>/skin/js/MemberView.js"></script>
<script src="<?= SystemURLs::getRootPath() ?>/skin/js/FamilyView.js"></script>


<!-- Photos start -->
<div id="photoUploader"></div>
<script src="<?= SystemURLs::getRootPath() ?>/skin/external/jquery-photo-uploader/PhotoUploader.js"></script>

<div class="modal fade" id="confirm-delete-image" tabindex="-1" role="dialog" aria-labelledby="delete-Image-label"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="delete-Image-label"><?= gettext("Confirm Delete") ?></h4>
            </div>

            <div class="modal-body">
                <p><?= gettext("You are about to delete the profile photo, this procedure is irreversible.") ?></p>

                <p><?= gettext("Do you want to proceed?") ?></p>
            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= gettext("Cancel") ?></button>
                <button class="btn btn-danger danger" id="deletePhoto"><?= gettext("Delete") ?></button>

            </div>
        </div>
    </div>
</div>
<script nonce="<?= SystemURLs::getCSPNonce() ?>">
$(document).ready(function() {
    window.CRM.photoUploader = $("#photoUploader").PhotoUploader({
        url: window.CRM.root + "/api/family/" + window.CRM.currentFamily + "/photo",
        maxPhotoSize: window.CRM.maxUploadSize,
        photoHeight: <?= SystemConfig::getValue("iPhotoHeight") ?>,
        photoWidth: <?= SystemConfig::getValue("iPhotoWidth") ?>,
        done: function(e) {
            location.reload();
        }
    });

    $(".edit-family").click(function() {
        window.location.href = window.CRM.root + '/FamilyEditor.php?FamilyID=' + window.CRM
            .currentFamily;
    });
});
</script>
<!-- Photos end -->
<div class="modal fade" id="confirm-verify" tabindex="-1" role="dialog" aria-labelledby="confirm-verify-label"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="confirm-verify-label"><?= gettext("Request Family Info Verification") ?>
                </h4>
            </div>
            <div class="modal-body">
                <b><?= gettext("Select how do you want to request the family information to be verified") ?></b>
                <p>
                    <?php if (count($family->getEmails()) > 0) {
                    ?>
                <p><?= gettext("You are about to email copy of the family information to the following emails") ?>
                <ul>
                    <?php foreach ($family->getEmails() as $tmpEmail) { ?>
                    <li><?= $tmpEmail ?></li>
                    <?php } ?>
                </ul>
                </p>
            </div>
            <?php
            } ?>
            <div class="modal-footer text-center">
                <?php if (count($family->getEmails()) > 0 && !empty(SystemConfig::getValue('sSMTPHost'))) {
                    ?>
                <button type="button" id="onlineVerify" class="btn btn-warning warning"><i class="fa fa-envelope"></i>
                    <?= gettext("Online Verification") ?>
                </button>
                <?php
                } ?>
                <button type="button" id="verifyURL" class="btn btn-default"><i class="fa fa-chain"></i>
                    <?= gettext("URL") ?></button>
                <button type="button" id="verifyDownloadPDF" class="btn btn-info"><i class="fa fa-download"></i>
                    <?= gettext("PDF") ?></button>
                <button type="button" id="verifyNow" class="btn btn-success"><i class="fa fa-check"></i>
                    <?= gettext("Verified In Person") ?>
                </button>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {

    $("#year_status").change(function() {
        var value = $("#year_status").val();
        $.ajax({
            // url:  "/churchcrm/v2/templates/people/ajax.php",
            url: "/churchcrm/PostRedirect.php",
            type: "POST",
            // datatype: "text",
            data: {
                val: value
            },
            success: function(obj) {
                var json = JSON.parse(obj);
                var local_master = new Array();
                var i, j = 0;

                local_master.push(json[0]);

                for (i = 1; i <= 12; i++) {
                    if (json[j].year_id == i && j < json.length) {
                        local_master.push(json[j]);
                        j++;
                    } else {
                        local_master.push({
                            year_id: "" + i,
                            year_name: "" + i,
                            year_desc: "" + i
                        });
                    }
                }

                var table = $('#example').DataTable({
                    destroy: true,
                    responsive: true,
                    data: local_master,
                    //  dataType: 'json',    
                    columns: [{
                            data: "year_id"
                        },
                        {
                            data: "year_name"
                        },
                        {
                            data: "year_desc"
                        }
                    ]
                });


                // $("#example tbody").on('dblclick', 'tr', function() {
                //     var row = table.row(this).data();
                //     alert(row.year_name);
                //     // RoweditMode($(this).parent());
                // });

                var table = $('#example').DataTable();
                table.MakeCellsEditable({
                    "onUpdate": myCallbackFunction,
                    "inputCss": 'my-input-class',
                    "columns": [0, 1, 2],
                    "confirmationButton": { // could also be true
                        "confirmCss": 'my-confirm-class',
                        "cancelCss": 'my-cancel-class'
                    },
                    "inputTypes": 
                    [
                        {
                            "column": 0,
                            "type": "text",
                            "options": null
                        },
                        {
                            "column": 1,
                            "type": "list",
                            "options": [{
                                    "value": "1",
                                    "display": "Beaty"
                                },
                                {
                                    "value": "2",
                                    "display": "Doe"
                                },
                                {
                                    "value": "3",
                                    "display": "Dirt"
                                }
                            ]
                        },
                        {
                            "column": 2,
                            "type": "text",
                            "options": null
                        }
                        // Nothing specified for column 3 so it will default to text

                    ]
                });


            }

        }).done(function(returneddata) {
            console.log(returneddata);
        })

    });

});


function myCallbackFunction (updatedCell, updatedRow, oldValue) {
    alert(""+updatedCell.data())
    // console.log("The new value for the cell is: " + );
    // console.log("The old value for that cell was: " + oldValue);
    // console.log("The values for each cell in that row are: " + updatedRow.data());
}

function destroyTable() {
    if ($.fn.DataTable.isDataTable('#example')) {
        table.destroy();
        table.MakeCellsEditable("destroy");
    }
}

</script>
<?php include SystemURLs::getDocumentRoot() . '/Include/Footer.php'; ?>