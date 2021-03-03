<?php


use ChurchCRM\dto\SystemConfig;
use ChurchCRM\dto\SystemURLs;

use ChurchCRM\Authentication\AuthenticationManager;
use ChurchCRM\dto\Classification;
use ChurchCRM\Service\MailChimpService;

use ChurchCRM\dto\PeopleCustomField;
use ChurchCRM\FamilyCustomMasterQuery;
use ChurchCRM\FamilyCustomQuery;


//Set the page title
$sPageTitle = gettext(ucfirst($sMode)) . ' ' . gettext('Master List');
include SystemURLs::getDocumentRoot() . '/Include/Header.php';
/* @var $families ObjectCollection */
?>


<p><br /><br /></p>

<div class="box">
    <div class="box-body">
        <table id="example" class="table table-striped table-bordered data-table" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <?php 
                    foreach ($familyAttributes as $attribute) {
                    /* @var $familyAttributes ChurchCRM\family.php */
                ?>
                    <th><?= gettext($attribute) ?></th>
                    <?php
                }
                ?>
                </tr>
            </thead>
            <tbody>

                <!--Populate the table with family details -->
                <?php 
            foreach ($families as $family) {
              /* @var $family ChurchCRM\Family */
    ?>
                <tr>
                    <td><a href='<?= SystemURLs::getRootPath()?>/v2/family/<?= $family->getId() ?>'>
                            <span class="fa-stack">
                                <i class="fa fa-square fa-stack-2x"></i>
                                <i class="fa fa-search-plus fa-stack-1x fa-inverse"></i>
                            </span>
                        </a>
                        <a href='<?= SystemURLs::getRootPath()?>/FamilyEditor.php?FamilyID=<?= $family->getId() ?>'>
                            <span class="fa-stack">
                                <i class="fa fa-square fa-stack-2x"></i>
                                <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                            </span>
                        </a>
                    </td>
                    <td> <?= $family->getName() ?></td>
                    <td> <?= $family->getAddress() ?></td>
                    <td> <?= $family->getHomePhone() ?></td>
                    <td> <?= $family->getCellPhone() ?></td>
                    <!-- todo: Select custom list options from an array -->
                    <!-- todo: Select custom list for Ref -->
                    <!-- todo: Select custom list for Membership Status -->
                    <?php
                    // New code Added
                    $allFamilyCustomFields = FamilyCustomMasterQuery::create()->find();

                  $rawQry =  FamilyCustomQuery::create();
                  foreach ($allFamilyCustomFields as $customfield ) {
                    $rawQry->withColumn($customfield->getField());
                }

                $thisFamilyCustomFields = $rawQry->findOneByFamId($family->getId());
                if ($thisFamilyCustomFields) {
                  $familyCustom = [];
 
                  foreach ($allFamilyCustomFields as $customfield ) {

                        $value = $thisFamilyCustomFields->getVirtualColumn($customfield->getField());
            
                        if (!empty($value)) {

                            $item = new PeopleCustomField($customfield, $value);
                            ?>
                    <!-- Call the function responsible of showing corresponding values of indexes -->
                    <td> <?= getCustomListOptionField($item->getDisplayValue(),$item->getFormattedValue()) ?></td>
                    <?php
                          }
                        else{
                          ?>
                    <td> </td>
                    <?php
                        }
                  }
              }

            ?>

                    <?php
               
}
                ?>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script nonce="<?= SystemURLs::getCSPNonce() ?>">
$(document).ready(function() {
    // Setup - add a text input to each footer cell

    $('#example thead tr').clone(true).appendTo('#example thead');
    $('#example thead tr:eq(1) th').each(function(i) {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="Filter ' + title + '" />');
        $('input', this).on('keyup change', function() {
            if (table.column(i).search() !== this.value) {
                table
                    .column(i)
                    .search(this.value)
                    .draw();
            }
        });
    });

    var table = $('#example').DataTable({
        orderCellsTop: true,
        // fixedHeader: true,
        "scrollX": true

    });

});

</script>

<?php
require SystemURLs::getDocumentRoot() .  '/Include/Footer.php';
?>