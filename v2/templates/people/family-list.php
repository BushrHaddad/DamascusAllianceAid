<?php


use ChurchCRM\dto\SystemConfig;
use ChurchCRM\dto\SystemURLs;

use ChurchCRM\Authentication\AuthenticationManager;
use ChurchCRM\dto\Classification;
use ChurchCRM\Service\MailChimpService;



//Set the page title
$sPageTitle =ucfirst($sMode). ' ' . 'Family List';
include SystemURLs::getDocumentRoot() . '/Include/Header.php';
?>
<div class="container-fluid">
    <div class="row">
        <input id="ClearFilters" type="button" class="btn btn-default" value="Reset Filters"><BR><BR>
        <a class="pull-right btn btn-success" role="button" href="<?= SystemURLs::getRootPath()?>/FamilyEditor.php">
            <span class="fa fa-plus"></span> Add Family</a>
    </div>
</div>

<div class="box">
    <div class="box-body">
        <table id="example" class="display table table-striped table-bordered data-table" cellspacing="0"
            style="width:100%;">
            <thead>
                <tr>
                    <?php foreach ($familyAttributes as $attribute) { ?>
                    <th><?= $attribute ?></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
            </tbody>
            <tfoot>
                <tr>
                    <?php foreach ($familyAttributes as $attribute) { ?>
                    <th><?= $attribute ?></th>
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

    var sMode = '<?php echo $sMode; ?>';
    var columns = [{
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
        }
    ];

    // get filteration columns description 
    function get_filtering_options(response, total_num) {
        filtering_options = []
        var json = JSON.parse(response);
        for (var i = 1; i <= total_num; i++) {
            if (json[String(i)]) {
                console.log(json[String(i)]);
                filtering_options.push({
                    column_number: i,
                    filter_type: 'multi_select',
                    filter_match_mode: 'regex',
                    data: get_option(json[String(i)]),
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
    var filtering_options = [];

    // get options
    $.ajax({

        url: "/churchcrm/PostRedirect_Filteration.php",
        type: "POST",
        data: {
            post_name: "get_filtering_options",
        },
        success: function(response) {
            filtering_options = get_filtering_options(response, 26);

            var table = $('#example').DataTable({
                // "bJQueryUI": true,
                "bStateSave": true,
                colReorder: true,
                select: true,
                destroy: true,
                "serverSide": true,
                "pageLength": 5,
                processing: true,
                keys: true,
                scrollX: true,
                "ajax": {
                    type: "POST",
                    url: '/churchcrm/PostRedirect_Filteration.php',
                    data: function(d) {
                        d.post_name = "all_families",
                            d.sMode = sMode
                    }
                },
                "columns": columns,
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

            $("#ClearFilters").click(function() {
                yadcf.exResetAllFilters(table);
            });

            yadcf.init(table, filtering_options);

        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log("Error on get Ajax request ");
        }
    });

});
</script>

<?php
require SystemURLs::getDocumentRoot() .  '/Include/Footer.php';
?>