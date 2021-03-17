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
$sPageTitle = "Master Table";
$year_id=1;
$month_id=1;
$prev_month=1;
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $year_id = $request->year_id;
    $month_id = $request->month_id;
    $prev_month = $request->prev_month;    
}

include SystemURLs::getDocumentRoot() . '/Include/Header.php';
/* @var $families ObjectCollection */

?>

<div class="row">
    <form method="post" action="/churchcrm/v2/family/master">
        <div class="col-lg-5">
            <div class="form-group">
                <label>Choose a Year:</label>
                <select id="year_option_id" class="form-control" name="year_id" onchange="this.form.submit()">
                    <?php
                         foreach ($all_years as $year){
                            if($year['id']==$year_id){
                    ?>
                    <option value=<?= $year['id'] ?> selected=""><?= $year['name'] ?></option>
                    <?php
                             }
                             else if($year['id']!=0){
                    ?>
                    <option value=<?= $year['id'] ?>><?= $year['name'] ?></option>
                    <?php
                             }
                        }
                    ?>
                </select>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="form-group">
                <label>Choose a month:</label>
                <select id="month_option_id" class="form-control" name="month_id" onchange="this.form.submit()">
                    <?php
                         foreach ($all_months as $month){
                            if($month['id']==$month_id){
                    ?>
                    <option value=<?= $month['id'] ?> selected=""><?= $month['name'] ?></option>
                    <?php
                             }
                             else if($month['id']!=0){
                    ?>
                    <option value=<?= $month['id'] ?>><?= $month['name'] ?></option>
                    <?php
                             }
                        }
                    ?>
                </select>
            </div>
        </div>

        <div class="col-lg-3">
            <input id="prev_month_count_id" name="prev_month" value=<?= $prev_month ?>></input>
            <input id="prev_month_button_id" type="submit" value="Previous Month" class="btn btn-primary"> </input>
        </div>

    </form>

</div>


<p><br /><br /></p>

<div class="box">
    <div class="box-body">
        <table id="example" class="display table table-striped table-bordered data-table" cellspacing="0"
            style="width:100%;">
            <thead>
                <tr>
                    <th>Action</th>
                    <th>Id</th>
                    <th>Family ID</th>
                    <?php for($i=0; $i < $prev_month; $i++){
                        if ($month_id == 1) {
                            $month_id = 12;
                            $year_id = $year_id - 1;
                            if ($year_id == 0) {
                                $year_id = 1;
                                $month_id = 1;
                            }
                        } else {
                            $month_id = $month_id - 1;
                        }

                        $month_name = $all_months[$month_id]['name'];
                        $year_name = $all_years[$year_id]['name'];
                    ?>
                    <th><?= $month_name ?>-<?= $year_name ?> (Bag)</th>
                    <th><?= $month_name ?>-<?= $year_name ?> (Cash)</th>
                    <th><?= $month_name ?>-<?= $year_name ?> (Suppliments)</th>
                    <th><?= $month_name ?>-<?= $year_name ?> (Team)</th>
                    <th><?= $month_name ?>-<?= $year_name ?> (Visited)</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <th>Action</th>
                    <th>Id</th>
                    <th>Family ID</th>
                    <?php for($i=0;$i<$prev_month;$i++){
                        if ($month_id == 1) {
                            $month_id = 12;
                            $year_id = $year_id - 1;
                            if ($year_id == 0) {
                                $year_id = 1;
                                $month_id = 1;
                            }
                        } else {
                            $month_id = $month_id - 1;
                        }
                        $month_name = $all_months[$month_id]['name'];
                        $year_name = $all_years[$year_id]['name'];
                    ?>
                    <th><?= $month_name ?>-<?= $year_name ?> (Bag)</th>
                    <th><?= $month_name ?>-<?= $year_name ?> (Cash)</th>
                    <th><?= $month_name ?>-<?= $year_name ?> (Suppliments)</th>
                    <th><?= $month_name ?>-<?= $year_name ?> (Team)</th>
                    <th><?= $month_name ?>-<?= $year_name ?> (Visited)</th>
                    <?php } ?>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script nonce="<?= SystemURLs::getCSPNonce() ?>">
$(document).ready(function() {

    var x = 0; // the number of months that should be back
    var table;
    var additional_fields = 3;
    var team_options, bag_options, sup_options, visiting_options, cash_options;
    var team_dic, bag_dic, sup_dic, visiting_dic, cash_dic;

    var month_ = $("#month_option_id").val();
    var year_ = $("#year_option_id").val();

    var prev_ = Number($("#prev_month_count_id").val());

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

        bag_options = _parse(json['all_bags']);
        cash_options = _parse(json['all_cash']);

        sup_options = _parse(json['all_suppliments']);
        team_options = _parse(json['all_teams']);
        visiting_options = _parse(json['all_visitings']);


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

            var columns = [{
                    'data': null,
                    title: 'Action',
                    wrap: true,
                    "render": function(item) {
                        window.href = item.fam_id;
                        var path_view = window.href;
                        var path_edit = window.CRM.root + '/FamilyEditor.php?FamilyID=' +
                            item.fam_id + '';
                        return '<div> <a href=' + path_view +
                            '><span class="fa-stack"> <i class="fa fa-square fa-stack-2x"></i><i class="fa fa-search-plus fa-stack-1x fa-inverse"></i></span></a> <a href=' +
                            path_edit +
                            '><span class="fa-stack"><i class="fa fa-square fa-stack-2x"></i><i class="fa fa-pencil fa-stack-1x fa-inverse"></i></span></a> </div>';
                    },
                },
                {
                    data: 'master_id',
                    visible: false,
                },
                {
                    data: 'fam_id',
                }
            ];

            for (var i = 1; i <= prev_; i++) {
                columns.push({
                    data: 'bag_name' + i,
                });
                columns.push({
                    data: 'cash_name' + i,
                });
                columns.push({
                    data: 'sup_name' + i,
                });
                columns.push({
                    data: 'team_name' + i,
                });
                columns.push({
                    data: 'visiting_name' + i,
                });

            }


            table = $('#example').DataTable();
            destroyTable();
            $('#example tfoot th').each(function() {
                var title = $(this).text();
                $(this).html('<input type="text" placeholder="' + title + '" />');
            });


            table = $('#example').DataTable({

                destroy: true,
                // data: json,
                // "bSort": false,
                // responsive: true,
                // orderCellsTop: true,
                "scrollX": true,
                "scrollY": 400,
                keys: true,
                // paging:  false,
                //  dataType: 'json',  
                // "type": "POST",
                // 'processing': true,
                // 'serverSide': true,
                // 'serverMethod': 'post',
                // "bLengthChange": true,
                // "iDisplayLength": 10,
                'ajax': {
                    "type": "POST",
                    'url': '/churchcrm/PostRedirect.php',
                    'data': function(d) {
                        d.post_name = "global_master",
                            d.month_id = month_,
                            d.year_id = year_,
                            d.prev = prev_
                    }
                },
                'columns': columns,
                "dom": 'C<"clear">lfrtip',
                "colVis": {
                    "label": function(index, title, th) {
                        return (index + 1) + '. ' + title;
                    }
                },
                // apply the search
                initComplete: function() {
                    this.api().columns().every(function() {
                        var that = this;
                        $('input', this.footer()).on('keyup change clear',
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

            var edits = [];
            var nums = [];
            var current_idx = 0;
            for (var i = 0; i < (prev_ * 5); i++) {
                current_idx = i + additional_fields;
                nums.push(current_idx);
                if (i % 5 == 0) {
                    edits.push({
                        "column": current_idx,
                        "type": "list",
                        "options": bag_options,
                    });
                } else if (i % 5 == 1) {
                    edits.push({
                        "column": current_idx,
                        "type": "list",
                        "options": cash_options,
                    });
                } else if (i % 5 == 2) {
                    edits.push({
                        "column": current_idx,
                        "type": "list",
                        "options": sup_options,
                    });
                } else if (i % 5 == 3) {
                    edits.push({
                        "column": current_idx,
                        "type": "list",
                        "options": team_options,
                    });
                } else if ((i % 5 == 4)) {
                    edits.push({
                        "column": current_idx,
                        "type": "list",
                        "options": visiting_options,
                    });
                }
            }

            table.MakeCellsEditable({
                "onUpdate": myCallbackFunction,
                "inputCss": 'my-input-class',
                "columns": nums,
                "confirmationButton": { // could also be true
                    "confirmCss": 'my-confirm-class',
                    "cancelCss": 'my-cancel-class'
                },
                "inputTypes": edits

            });
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("Error on get Ajax request ");
        }
    });


    $('#prev_month_button_id').click(function() {
        var p = Number($("#prev_month_count_id").val());
        $("#prev_month_count_id").val(p + 1);
    });

    function myCallbackFunction(updatedCell, updatedRow, oldValue) {
        // todo: update individual cell instead of sending multiple value onUpdate() or onInsert() 
        var row = updatedCell[0][0]['row'];
        var col = updatedCell[0][0]['column'];
        col = col - (additional_fields); // the number of added field for family (should be subtracted)
        var p = parseInt((col / 5));

        for (var i = 0; i < p; i++) {
            if (month_ == 1) {
                month_ = 12;
                if (year_ == 1) {
                    year_ = 1;
                    month_ = 1;
                }
                year_--;
            } else {
                month_--;
            }
        }

        var bag_col_idx = p * 5 + (additional_fields);
        var cash_col_idx = p * 5 + (additional_fields + 1);
        var sup_col_idx = p * 5 + (additional_fields + 2);
        var team_col_idx = p * 5 + (additional_fields + 3);
        var visiting_col_idx = p * 5 + (additional_fields + 4);

        var bag_name = table.cell(row, bag_col_idx).data();
        var cash_name = table.cell(row, cash_col_idx).data();
        var sup_name = table.cell(row, sup_col_idx).data();
        var team_name = table.cell(row, team_col_idx).data();
        var visiting_name = table.cell(row, visiting_col_idx).data();

        $.ajax({

            url: "/churchcrm/PostRedirect.php",
            type: "POST",
            data: {
                post_name: "edit_local_master",
                family_id: updatedRow.data().fam_id,
                month_id: month_,
                year_id: year_,
                visited_id: visiting_dic[visiting_name],
                team_id: team_dic[team_name],
                bag_id: bag_dic[bag_name],
                cash_id: cash_dic[cash_name],
                sup_id: sup_dic[sup_name]
            },

            success: function(response) {
                console.log('Edited Correctly');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
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