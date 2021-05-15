<?php

use ChurchCRM\dto\SystemConfig;
use ChurchCRM\dto\SystemURLs;

use ChurchCRM\Authentication\AuthenticationManager;
use ChurchCRM\dto\Classification;

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
?>


<!--Select Year and Month  -->
<div class="row">
    <form method="post" action="/churchcrm/v2/family/master">
        <div class="col-lg-5">
            <div class="form-group">
                <label>Choose a Year:</label>
                <select id="year_option_id" class="select2-master form-control" name="year_id"
                    onchange="this.form.submit()">
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
                <select id="month_option_id" class="select2-master form-control" name="month_id"
                    onchange="this.form.submit()">
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

<!-- Reset Filter -->
<div class="container-fluid">
    <input id="ClearFilters" type="button" class="btn btn-default" value="Reset Filters"><BR><BR>
</div>

<!-- Master Table -->
<div class="box">
    <div class="box-body">
        <table id="example" class="display table table-striped table-bordered data-table" cellspacing="0"
            style="width:100%;">
            <thead>
                <tr>
                    <!-- Family Attributes  -->
                    <?php foreach ($familyAttributes as $attribute) { ?>
                    <th><?= $attribute ?></th>
                    <?php } ?>
                    <!-- Aid Attributes -->
                    <?php 
                    for($i=0; $i < $prev_month; $i++){
                        $m_id = $month_id;
                        $y_id = $year_id;

                        $x_m = $m_id - $i;

                        if($x_m<=0){
                                $x_y = intval(($x_m*-1)/12) + 1;
                                $y_id = $y_id - $x_y;
                                $m_id = 12 + ($x_m%12);
                        }
                        else{
                            $m_id = $x_m;
                        }

                        $month_name = $all_months[$m_id]['name'];
                        $year_name = $all_years[$y_id]['name'];
                    ?>
                    <th><?= $month_name ?>-<?= $year_name ?> (Team)</th>
                    <th><?= $month_name ?>-<?= $year_name ?> (Cash)</th>
                    <!-- <th><?= $month_name ?>-<?= $year_name ?> (Bag)</th>
                    <th><?= $month_name ?>-<?= $year_name ?> (Sup)</th> -->
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <!-- Family Attributes -->
                    <?php foreach ($familyAttributes as $attribute) { ?>
                    <th><?= $attribute ?></th>
                    <?php } ?>
                    <!-- Aid Attributes -->
                    <?php 
                    for($i=0;$i<$prev_month;$i++){
                        $m_id = $month_id;
                        $y_id = $year_id;

                        $x_m = $m_id - $i;

                        if($x_m<=0){
                                $x_y = intval(($x_m*-1)/12) + 1;
                                $y_id = $y_id - $x_y;
                                
                                $m_id = 12 + ($x_m%12);
                        }
                        else{
                            $m_id = $x_m;
                        }
                        $month_name = $all_months[$m_id]['name'];
                        $year_name = $all_years[$y_id]['name'];
                    ?>
                    <th><?= $month_name ?>-<?= $year_name ?> (Team)</th>
                    <th><?= $month_name ?>-<?= $year_name ?> (Cash)</th>
                    <!-- <th><?= $month_name ?>-<?= $year_name ?> (Bag)</th>
                    <th><?= $month_name ?>-<?= $year_name ?> (Sup)</th> -->
                    <?php } ?>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script src="<?= SystemURLs::getRootPath() ?>/skin/js/alliance_aid.js"></script>

<script nonce="<?= SystemURLs::getCSPNonce() ?>">
$(document).ready(function() {

    var table = $('#example').DataTable({});
    'use strict';

    var x = 0; // the number of months that should be back
    var additional_fields = 26; // number of family attributes
    var aid_fields = 2; // number of aid fileds (team_name, cash_name)
    
    var team_options, cash_options;
    var team_dic, bag_dic;
    var month_ = $("#month_option_id").val(); //  our current month
    var year_ = $("#year_option_id").val(); // our current year
    var prev_ = Number($("#prev_month_count_id").val()); // how much we should go back

    var filtering_options = [];
    // should be moved to a shared code 


    function get_filtering_options(json, total_fields_num) {
        filtering_options = []
        for (var i = 1; i <= total_fields_num; i++) {
            if (json[String(i)]) {
                filtering_options.push({
                    column_number: i,
                    filter_type: 'multi_select',
                    filter_match_mode: 'regex',
                    data: parse_multi_select(json[String(i)]),
                    select_type: 'select2',
                    select_type_options: {
                        width: '200px'
                    }
                });
                // after the additional Fields which are 26 fileds... ther's gonna be team, cash, team, cash 
                // do team is gonna take odd index and cash even indexes
            } else if (i > additional_fields) {
                var name = "";
                if (i % 2 == 1) { // 27, 29, 31 
                    name = "teams";
                } else { // 28, 30, 32
                    name = "cash";
                }
                filtering_options.push({
                    column_number: i,
                    filter_type: 'multi_select',
                    filter_match_mode: 'regex',
                    data: parse_multi_select(json[name]),
                    select_type: 'select2',
                    select_type_options: {
                        width: '200px'
                    }
                });
            } else {
                filtering_options.push({
                    column_number: i,
                    filter_type: "text",
                    select_type_options: {
                        width: '150px'
                    }
                });
            }
        }
        return filtering_options;
    }

    function getVarsCallBack(response) {
        var json = JSON.parse(response);
        team_options = _parse(json['all_teams']);
        cash_options = _parse(json['all_cash']);

        team_dic = _dic(json['all_teams']);
        cash_dic = _dic(json['all_cash']);
        // now get multi select filtering options 
        filtering_options = get_filtering_options(json, additional_fields + (prev_ * aid_fields));

    }
    //***************************** Columns for Master Datatable ************************** */
    var columns = [
        {
            name: "id",
            data: "1",
            wrap: true,
            "render": function(item) {
                var path_view = window.CRM.root + '/v2/family/' + item;
                var path_edit = window.CRM.root + '/FamilyEditor.php?FamilyID=' +
                    item + '';
                return '<div> <a href=' + path_view +
                    '><span class="fa-stack"> <i class="fa fa-square fa-stack-2x"></i><i class="fa fa-search-plus fa-stack-1x fa-inverse"></i></span></a> <a href=' +
                    path_edit +
                    '><span class="fa-stack"><i class="fa fa-square fa-stack-2x"></i><i class="fa fa-pencil fa-stack-1x fa-inverse"></i></span></a> </div>';
            },
        },
        {
            name: "id",
            data: "1"
        },
        {
            name: "old_id",
            data: "2"
        },
        {
            name: "p",
            data: "3"
        },
        {
            name: "main_name",
            data: "4"
        },
        {
            name: "main_id",
            data: "5"
        },
        {
            name: "partner_name",
            data: "6"
        },
        {
            name: "partner_id",
            data: "7"
        },
        {
            name: "poverty_rate",
            data: "8"
        },
        {
            name: "state",
            data: "12"
        },
        {
            name: "city",
            data: "11"
        },
        {
            name: "address1",
            data: "9"
        },
        {
            name: "address2",
            data: "10"
        },
        {
            name: "home_phone",
            data: "13"
        },
        {
            name: "aid_phone",
            data: "14"
        },
        {
            name: "mobile_phone",
            data: "15"
        },
        {
            name: "status",
            data: "16"
        },
        {
            name: "aid_note",
            data: "17"
        },
        {
            name: "general_note",
            data: "18"
        },
        {
            name: "team_note",
            data: "19"
        },
        {
            name: "ref",
            data: "20"
        },
        {
            name: "membership_status",
            data: "21"
        },
        {
            name: "members_num",
            data: "22"
        },
        {
            name: "children",
            data: "23"
        },
        {
            name: "no_money",
            data: "24"
        },
        {
            name: "other_notes",
            data: "25"
        },
        {
            name: "verifying_question",
            data: "26"
        },
    ];

    for (var i = 1; i <= prev_; i++) {
        columns.push({
            data: 'team_name' + i,
        });
        columns.push({
            data: 'cash_name' + i,
        });
    }

    //******************* Ajax call for get global vars, filtering options, and cell options ******************** */ 

    // get options
    $.ajax({

        url: "/churchcrm/PostRedirect_Filteration.php",
        type: "POST",
        data: {
            post_name: "get_global_vars",
        },
        success: function(response) {

            getVarsCallBack(response);
            destroyTable();
            table = $('#example').DataTable({
                "bStateSave": true,
                select: true,
                destroy: true,
                "serverSide": true,
                "pageLength": 5,
                processing: true,
                keys: true,
                scrollX: true,
                'ajax': {
                    "type": "POST",
                    'url': '/churchcrm/PostRedirect_Filteration.php',
                    'data': function(d) {
                        d.post_name = "global_master",
                            d.month_id = month_,
                            d.year_id = year_,
                            d.prev = prev_
                    }
                },
                'columns': columns,
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'excelHtml5',
                        exportOptions: {
                            columns: ':visible',
                            format: {
                                header: function(data, row, column, node) {
                                    var newdata = data;

                                    newdata = newdata.replace(/<.*?<\/*?>/gi, '');
                                    newdata = newdata.replace(/<div.*?<\/div>/gi,
                                        '');
                                    newdata = newdata.replace(/<\/div.*?<\/div>/gi,
                                        '');
                                    return newdata;
                                }
                            }
                        },
                        action: newExportAction
                    },
                    'colvis'
                ]
            });

            // Reset all filters  
            $("#ClearFilters").click(function() {
                yadcf.exResetAllFilters(table);
            });

            yadcf.init(table, filtering_options);

            var edits = [];
            var nums = [];
            var current_idx = 0;

            for (var i = 1; i <= (prev_ * aid_fields); i++) {
                current_idx = i + additional_fields;
                nums.push(current_idx);
                if (i % aid_fields == 1) {
                    edits.push({
                        "column": current_idx,
                        "type": "list",
                        "options": team_options,
                    });
                } else if (i % aid_fields == 0) {
                    edits.push({
                        "column": current_idx,
                        "type": "list",
                        "options": cash_options,
                    });
                }
                // else if (i % aid_fields == 2) {
                //     edits.push({
                //         "column": current_idx,
                //         "type": "list",
                //         "options": bag_options,
                //     });
                // } else if (i % aid_fields == 3) {
                //     edits.push({
                //         "column": current_idx,
                //         "type": "list",
                //         "options": sup_options,
                //     });
                // }
            }

            // inline editing
            table.MakeCellsEditable({
                "onUpdate": myCallbackFunction,
                "inputCss": 'js-example-basic-single',
                "columns": nums,
                "confirmationButton": {
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
        // console.log(updatedRow.data());
        var row = updatedCell[0][0]['row'];
        var col = updatedCell[0][0]['column'];
        console.log(col);
        
        col = col - (additional_fields); // the number of added field for family (should be subtracted)
        col--;
        var p = parseInt((col / aid_fields));
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

        var team_col_idx = p * aid_fields + (additional_fields+1);
        var cash_col_idx = p * aid_fields + (additional_fields+2);

        var team_name = table.cell(row, team_col_idx).data();
        var cash_name = table.cell(row, cash_col_idx).data();

        fam_id = table.cell(row, 0).data();
        $.ajax({

            url: "/churchcrm/PostRedirect_Filteration.php",
            type: "POST",
            data: {
                post_name: "edit_global_master",
                family_id: fam_id,
                month_id: month_,
                year_id: year_,
                team_id: team_dic[team_name],
                cash_id: cash_dic[cash_name]
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

    $('.select2-master').select2();

});
</script>

<?php
require SystemURLs::getDocumentRoot() .  '/Include/Footer.php';
?>