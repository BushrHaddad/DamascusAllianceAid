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

    // Get the master view table
    // for month_id, year_id
    // for all user 

    

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
            <select id="year_option_id" class="form-control" name="c5">
                <option selected="" value=1>>--------------------</option>
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
            <select id="month_option_id" class="form-control" name="c5">
                <option selected="" value=1>--------------------</option>
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
        <a id="prev_month_id" class="btn btn-success" role="button">
            <span class="fa fa-plus" aria-hidden="true"></span><?= gettext(' Previous Month') ?>
        </a>
    </div>

</div>


<p><br /><br /></p>

<div class="box">
    <div class="box-body">
        <table id="example" class="table table-striped table-bordered data-table" cellspacing="0" style="width:100%;"
            data-page-length='100'>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Family ID</th>
                    <th>Bag Name</th>
                    <th>Cash Name</th>
                    <th>Sup Name</th>
                    <th>team Name</th>
                    <th>visiting Name</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script nonce="<?= SystemURLs::getCSPNonce() ?>">
$(document).ready(function() {


    var x = 0; // the number of months that should be back
    var table; // our datatable
    var team_options, bag_options, sup_options, visiting_options, cash_options;
    var team_dic, bag_dic, sup_dic, visiting_dic, cash_dic;
    
    var month_= $("#month_option_id").val();
    var year_ = $("#year_option_id").val();
   
    function _parse(obj) {
        parsed = [];
        for (index = 0; index < obj.length; index++) {
            parsed.push({
                "value": obj[index]['name'],
                "display": obj[index]['name']
                // "value_id": obj[index]['id']
            })

        }
        return parsed;
    }

    function _dic(obj) {
        parsed = {};
        for (index = 0; index < obj.length; index++) {
            parsed[obj[index]['name']] = obj[index]['id'];
        }
        return parsed;
    }

    function getVarsCallBack(response) {
        var json = JSON.parse(response);
        team_options = _parse(json['all_teams']);
        bag_options = _parse(json['all_bags']);
        sup_options = _parse(json['all_suppliments']);
        visiting_options = _parse(json['all_visitings']);
        cash_options = _parse(json['all_cash']);

        team_dic = _dic(json['all_teams']);
        bag_dic = _dic(json['all_bags']);
        sup_dic = _dic(json['all_suppliments']);
        visiting_dic = _dic(json['all_visitings']);
        cash_dic = _dic(json['all_cash']);
    }

    // get options
    $.ajax({

        url: "/churchcrm/PostRedirect.php",
        type: "POST",
        data: {
            post_name: "get_vars",
        },
        success: function(response) {

            getVarsCallBack(response);

            table = $('#example').DataTable();
            destroyTable();
            $('#example thead th').each(function() {
                var title = $(this).text();
                $(this).html('<input type="text" placeholder="Search ' + title + '" />');
            });

            // console.log('Bag options');
            // console.log(bag_options);

            table = $('#example').DataTable({
                destroy: true,
                // "bSort": false,
                // responsive: true,
                // data: json,
                orderCellsTop: true,
                "scrollX": true,
                'processing': true,
                'serverSide': true,
                'serverMethod': 'post',
                'ajax': {
                    'url': '/churchcrm/PostRedirect.php',
                    'data': function(d) {
                        d.post_name = "global_master",
                        d.month_id = month_,
                        d.year_id = year_
                    }
                },
                'columns': [{
                        data: 'master_id',
                        visible: false,
                    },
                    {
                        data: 'fam_id',
                    },
                    {
                        data: 'bag_name',
                    },
                    {
                        data: 'cash_name',
                    },
                    {
                        data: 'sup_name',
                    },
                    {
                        data: 'team_name',
                    },
                    {
                        data: 'visiting_name',
                    },
                ],

                // apply the search
                initComplete: function() {
                    this.api().columns().every(function() {
                        var that = this;
                        $('input', this.header()).on('keyup change clear',
                            function() {
                                if (that.search() !== this.value) {
                                    that.search(this.value)
                                        .draw(); // search on adding new character

                                }
                                // Only Searching 
                                // if (e.keyCode == 13) that.draw();
                            });
                    });
                }
            });

            table.MakeCellsEditable({
                "onUpdate": myCallbackFunction,
                "inputCss": 'my-input-class',
                "columns": [0, 1, 2, 3, 4, 5, 6],
                "confirmationButton": { // could also be true
                    "confirmCss": 'my-confirm-class',
                    "cancelCss": 'my-cancel-class'
                },
                "inputTypes": [{
                        "column": 1,
                        "type": "text",
                        "options": null,
                    },
                    {
                        "column": 2,
                        "type": "list",
                        "options": bag_options
                    },
                    {
                        "column": 3,
                        "type": "list",
                        "options": cash_options
                    },
                    {
                        "column": 4,
                        "type": "list",
                        "options": sup_options
                    },
                    {
                        "column": 5,
                        "type": "list",
                        "options": team_options
                    },
                    {
                        "column": 6,
                        "type": "list",
                        "options": visiting_options
                    },
                    // Nothing specified for column 3 so it will default to text
                ]
            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("Error on get Ajax request ");
        }
    });

    $("#year_option_id").change(function() {
        year_ = $("#year_option_id").val();
        table.ajax.reload(null, false);

    });

    
    $("#month_option_id").change(function() {
        month_ = $("#month_option_id").val();
        table.ajax.reload(null, false);
    });


    $('#prev_month_id').click(function() {
        if(month_==1){
            month_=12;
            year_--;
        }
        else{
            month_--;
        }
        
        table.ajax.reload(null, false);

    });


    function myCallbackFunction(updatedCell, updatedRow, oldValue) {
        console.log(updatedCell);
        console.dir(updatedRow.data());
    }

    function destroyTable() {
        if ($.fn.DataTable.isDataTable('#example')) {
            table.destroy();
            table.MakeCellsEditable("destroy");
        }
    }
});
</script>

<?php
require SystemURLs::getDocumentRoot() .  '/Include/Footer.php';
?>