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
$sPageTitle = gettext(ucfirst($sMode)) . ' ' . gettext('Family List');
include SystemURLs::getDocumentRoot() . '/Include/Header.php';
/* @var $families ObjectCollection */
?>

<div class="pull-right">
    <a class="btn btn-success" role="button" href="<?= SystemURLs::getRootPath()?>/FamilyEditor.php">
        <span class="fa fa-plus" aria-hidden="true"></span><?= gettext('Add Family') ?>
    </a>
</div>
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

    // table = $('#example').DataTable();
    alert(table.rows().count());
    table.MakeCellsEditable({
        "onUpdate": myCallbackFunction,
        "inputCss": 'my-input-class',
        "columns": [0, 1, 2],
        "confirmationButton": { // could also be true
            "confirmCss": 'my-confirm-class',
            "cancelCss": 'my-cancel-class'
        },
        "inputTypes": [{
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


});

function myCallbackFunction(updatedCell, updatedRow, oldValue) {
    // $.ajax({

    //     url: "/churchcrm/PostRedirect.php",
    //     type: "POST",
    //     // datatype: "text",
    //     data: {
    //         post_name: "edit_local_master",
    //         family_id: window.CRM.currentFamily,
    //         year_id: updatedRow.data().year_id,
    //         year_name: updatedRow.data().year_name,
    //         year_desc: updatedRow.data().year_desc
    //     },

    //     success: function(response) {
    //         // You will get response from your PHP page (what you echo or print)
    //     },
    //     error: function(jqXHR, textStatus, errorThrown) {
    //         console.log(textStatus, errorThrown);
    //     }
    // });

    console.log("The old value for that cell was: " + oldValue);
    console.log("The values for each cell in that row are: " + updatedRow
        .data()
        .year_id);
}

function destroyTable() {
    if ($.fn.DataTable.isDataTable('#example')) {
        table.destroy();
        table.MakeCellsEditable("destroy");
    }
}
</script>

<?php
require SystemURLs::getDocumentRoot() .  '/Include/Footer.php';
?>