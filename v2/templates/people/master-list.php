
<?php

/****************************************************************
    // Update 
    
    UPDATE family_fam SET fam_Name='family name test1',fam_Address1='jhjk',fam_Address2='hjk',fam_City='hjkhkjh',
    fam_State='',fam_Zip='',fam_Latitude='0',fam_Longitude='0',fam_Country='AF',fam_HomePhone='(345) 678-3456',
    fam_WorkPhone='(456) 745-6756 x76767',fam_CellPhone='(345) 678-9345',fam_Email='',fam_WeddingDate=NULL,
    fam_Envelope='0',fam_DateLastEdited='20210303234156',fam_EditedBy = 1,fam_SendNewsLetter = 'FALSE',
    fam_OkToCanvass = 'TRUE', fam_Canvasser = '0' WHERE fam_ID = 7

    REPLACE INTO family_custom SET c9 = 'Main-Name', c11 = 'Main National Id', c10 = 'Partner Name',
    c12 = 'Partner Id', c2 = 'Additional Info', c1 = 'Address Additional Info', c3 = 'Team Info', c4 = '1',
    c7 = 'Children', c6 = '9', c8 = 'Poverty Rate hjhkhkhk', c5 = '1', fam_ID = 7
***************************************************************************
    // Insert New

    INSERT INTO family_fam ( fam_Name, fam_Address1, fam_Address2, fam_City, fam_State, fam_Zip, fam_Country,
    fam_HomePhone, fam_WorkPhone, fam_CellPhone, fam_Email, fam_WeddingDate, fam_DateEntered, fam_EnteredBy,
    fam_SendNewsLetter, fam_OkToCanvass, fam_Canvasser, fam_Latitude, fam_Longitude, fam_Envelope) 
    VALUES ('Name','test','test','test','','','AF','','','','',NULL,'20210303234740',1,'FALSE','TRUE','0','0','0','0')
      
    REPLACE INTO family_custom SET c9 = 'test', c11 = 'test', c10 = 'test', c12 = 'test', c2 = 'test', c1 = 'test',
     c3 = 'test', c4 = '1', c7 = 'test', c6 = '7', c8 = 'test', c5 = NULL, fam_ID = 52

******************************************************************/
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

<div class="row">
    <div class="col-lg-5">
        <div class="form-group">
            <label>Choose a Year:</label>
            <select id="years_option" class="form-control" name="c5">
                <option selected="">--------------------</option>
                <?php
                         foreach ($all_years as $year){
                        ?>
                <option value=<?= $year['id'] ?>><?= $year['name'] ?></option>
                <?php
                        }
                        ?>
            </select>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="form-group">
            <label>Choose a month:</label>
            <select id="months_option" class="form-control" name="c5">
                <option selected="">--------------------</option>
                <?php
                         foreach ($all_months as $month){
                        ?>
                <option value=<?= $month['id'] ?>><?= $month['name'] ?></option>
                <?php
                        }
                        ?>
            </select>
        </div>
    </div>

    <div class="col-lg-3">
        <div class="form-group">
            <label>Back</label>
            <select id="months_back_option" class="form-control" name="c5">
                <option selected="">--------------------</option>
                <?php
                         for ($i=1; $i<=12; $i++){
                        ?>
                <option value=<?= $i ?>><?= $i ?></option>
                <?php
                        }
                        ?>
            </select>
        </div>
    </div>


</div>


<p><br /><br /></p>

<div class="box">
    <div class="box-body">
        <table id="example" class="table table-striped table-bordered data-table" cellspacing="0" style="width:100%;"
            data-page-length='10'>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Name</th>
                    <th>Note1</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script nonce="<?= SystemURLs::getCSPNonce() ?>">
$(document).ready(function() {

    // Bushr: Adding the ability to scroll horizaontally 
    $('#example thead th').each(function() {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="Search ' + title + '" />');
    });
    

    $('#example').DataTable({
        orderCellsTop: true,
        "scrollX": true,
        'processing': true,
        'serverSide': true,
        'serverMethod': 'post',
        'ajax': {
            'url': '/churchcrm/PostRedirect.php',
            'data': {
                "post_name": "global_master",
                "month_id": $("#months_option").val(),
                "year_id": $("#years_option").val(),
                "months_back": $("#months_back_option").val(),
            }
        },
        'columns': [{
                data: 'id',
            },
            {
                data: 'name',
            },
            {
                data: 'note1',
            },
        ],

        initComplete: function() {
            // Apply the search
            this.api().columns().every(function() {
                var that = this;
                $('input', this.header()).on('keyup change clear', function() {
                    if (that.search() !== this.value) {
                        that
                            .search(this.value)
                            .draw();
                    }
                });
            });
        }

    });




});
</script>

<?php
require SystemURLs::getDocumentRoot() .  '/Include/Footer.php';
?>