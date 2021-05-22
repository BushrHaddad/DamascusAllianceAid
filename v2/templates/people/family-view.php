<?php


use ChurchCRM\Authentication\AuthenticationManager;
use ChurchCRM\dto\Classification;
use ChurchCRM\dto\SystemConfig;
use ChurchCRM\dto\SystemURLs;
use ChurchCRM\Service\MailChimpService;


use ChurchCRM\dto\PeopleCustomField;
use ChurchCRM\FamilyCustomMasterQuery;
use ChurchCRM\FamilyCustomQuery;



$sPageTitle =  "عائلة: ". $family->getName();
include SystemURLs::getDocumentRoot() . '/Include/Header.php';

$curYear = (new DateTime)->format("Y");
$mailchimp = new MailChimpService();
?>

<script nonce="<?= SystemURLs::getCSPNonce() ?>">
window.CRM.currentFamily = <?= $family->getId() ?>;
window.CRM.currentFamilyName = "<?= $family->getName() ?>";
window.CRM.currentActive = <?= $family->isActive() ? "true" : "false" ?>;
window.CRM.currentFamilyView = 2;
</script>


<div id="family-deactivated" class="alert alert-danger hide">
    <strong>This Family is Deactivated</strong>
</div>

<div class="row family-info">
    <div class="col-lg-5">
        <div class="row">
            <div>
                <div class="box box-primary">
                    <div class="box-header">
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
                                    <a id="view-larger-image-btn" href="#" title="View Photo">
                                        <i class="fa fa-search-plus"></i>
                                    </a>
                                    <?php if (AuthenticationManager::GetCurrentUser()->isEditRecordsEnabled()): ?>
                                    &nbsp;
                                    <a href="#" data-toggle="modal" data-target="#upload-image"
                                        title="Upload Photo">
                                        <i class="fa fa-camera"></i>
                                    </a>&nbsp;
                                    <a href="#" data-toggle="modal" data-target="#confirm-delete-image"
                                        title="Delete Photo">
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
                                class="fa fa-hand-o-left"></i>Previous Family</a>

                        <a class="btn btn-app btn-danger" role="button"
                            href="<?= SystemURLs::getRootPath()?>/v2/family"><i
                                class="fa fa-list-ul"></i>Family List</a>

                        <a class="btn btn-app" role="button" id="nextFamily"><i
                                class="fa fa-hand-o-right"></i>Next Family</a>
                    </div>
                    <hr />
                    <a class="btn btn-app bg-olive"
                        href="<?= SystemURLs::getRootPath() ?>/PersonEditor.php?FamilyID=<?=$family->getId()?>"><i
                            class="fa fa-plus-square"></i> Add Member</a>

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
                            class="fa fa-trash-o"></i>Delete Family</a>
                    <?php
                        }
                        if (AuthenticationManager::GetCurrentUser()->isNotesEnabled()) {
                            ?>
                    <a class="btn btn-app"
                        href="<?= SystemURLs::getRootPath() ?>/NoteEditor.php?FamilyID=<?= $family->getId()?>"><i
                            class="fa fa-sticky-note"></i>Add a Note</a>
                    <?php
                        } ?>

                </div>
            </div>
        </div>

        <div class="row">
            <div>
                <div class="box box-primary">
                    <div class="box-header">
                        <i class="fa fa-id-badge"></i>
                        <h3 class="box-title">Family Info</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool edit-family"><i class="fa fa-edit"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <ul class="fa-ul">
                            <?php 
                            foreach ($familyCustom as $customField) {
                                echo '<li><i class="fa-li ' . $customField->getIcon() . '"></i>'. $customField->getDisplayValue().': <span>';
                                echo getCustomListOptionField($customField->getDisplayValue(),$customField->getFormattedValue());
                                echo '</span></li>';
                            }  
                            ?>
                            </br>
                            </br>
                            <?php                       
                            if (!empty($family->getAddress())) { ?>
                            <li> <i class="fa-li fa fa-map"></i>Address: <span>
                                    <a href="http://maps.google.com/?q=<?= $family->getAddress() ?>"
                                        target="_blank"><?= $family->getAddress() ?></a></span>
                            </li>
                            <?php
                            }
                            if (!empty($family->getHomePhone())) { ?>
                            <li><i class="fa-li fa fa-building"></i>ِAid Phone: <span><a
                                        href="tel:Home phone"><?= $family->getHomePhone()?></a></span>
                            </li>
                            <?php
                            }
                            // exit;
                            if (!empty($family->getWorkPhone())) {
                                
                                ?>

                            <li><i class="fa-li fa fa-mobile"></i>Mobile Phone: <span><a
                                        href="tel:<?= $family->getWorkPhone() ?>"><?= $family->getWorkPhone() ?></a></span>
                            </li>
                            <?php
                            }
                            if (!empty($family->getCellPhone())) {
                                ?>
                            <li><i class="fa-li fa fa-phone"></i>Home Phone: <span><a
                                        href="tel:<?= $family->getCellPhone() ?>"><?= $family->getCellPhone() ?></a></span>
                            </li>
                            <?php
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
                        <h3 class="box-title">Family Members</h3>
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
                                                title="Work Phone"></i>(W)
                                            <?= $person->getWorkPhone() ?>
                                            <br />
                                            <?php }
                                                if (!empty($person->getCellPhone())) { ?>
                                            <i class="fa fa-fw fa-mobile" title="Mobile Phone"></i>(M)
                                            <?= $person->getCellPhone() ?>
                                            <br />
                                            <?php }
                                                if (!empty($person->getEmail())) { ?>
                                            <i class="fa fa-fw fa-envelope" title="Email"></i>(H)
                                            <?= $person->getEmail() ?>
                                            <br />
                                            <?php }
                                                if (!empty($person->getWorkEmail())) { ?>
                                            <i class="fa fa-fw fa-envelope-o"
                                                title="Work Email"></i>(W)
                                            <?= $person->getWorkEmail() ?>
                                            <br />
                                            <?php }
                                                $formatedBirthday = $person->getFormattedBirthDate();
                                                if ($formatedBirthday) {?>
                                            <i class="fa fa-fw fa-birthday-cake" title="Birthday"></i>
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
    <!--  Local Master  -->
    <div class="col-lg-7">

        <!-- Master Table -->
        <div class="box">

            <div class="box-header">
                <i class="fa fa-history"></i>
                <h3 class="box-title">Master Table</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="form-group">
                    <label>Choose a Year:</label>
                    <select id="year_status" class="form-control" name="c5">
                        <?php foreach ($all_years as $year){?>
                        <?php if($year['name'] == "2014"){  ?>
                        <option selected = "" value=<?= $year['id'] ?>><?= $year['name'] ?></option>
                        <?php  }  else{  ?>
                        <option value=<?= $year['id'] ?>><?= $year['name'] ?></option>
                        
                        <?php } }?>
                    </select>
                </div>
                <table id="example" class="table table-striped table-bordered data-table" cellspacing="0"
                    style="width:100%;" data-page-length='12'>
                    <thead>
                        <tr>
                            <th>Found</th>
                            <th>Month ID</th>
                            <th>Month</th>
                            <th>Team</th>
                            <th>Cash</th>
                            <th>Bag</th>
                            <th>Suppliments</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- End Master Table -->

        <!-- Start Timeline Table -->
        <div class="box">
            <div class="box-header">
                <i class="fa fa-history"></i>
                <h3 class="box-title">Timeline</h3>
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
                                <?= $item['text'] ?> <?=$item['header'] ?>
                            </h4>
                            <?php } else { ?>
                            <h3 class="timeline-header">
                                <?php if (in_array('headerlink', $item)) {
                                            ?>
                                <a href="<?= $item['headerlink'] ?>"><?= $item['header'] ?></a>
                                <?php
                                        } else {
                                            ?>
                                <?= $item['header'] ?>
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
<style>
.family-info ul li {
    /* direction: rtl; */
    /* float: left; */
    padding: 7px 5px;
    margin: 0;
    font-size: 15px;
    font-weight: bold;
    /* direction: rtl; */
}

.family-info span {
    float: right;
    text-align: right;
    font-weight: 500;
    font-size: 14px;
}
</style>


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
                <h4 class="modal-title" id="delete-Image-label">Confirm Delete</h4>
            </div>

            <div class="modal-body">
                <p>You are about to delete the profile photo, this procedure is irreversible.</p>

                <p>Do you want to proceed?</p>
            </div>


            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                <button class="btn btn-danger danger" id="deletePhoto">Delete</button>

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


<?php include SystemURLs::getDocumentRoot() . '/Include/Footer.php'; ?>