<?php


use ChurchCRM\dto\SystemConfig;
use ChurchCRM\dto\SystemURLs;

use ChurchCRM\Authentication\AuthenticationManager;
use ChurchCRM\dto\Classification;
use ChurchCRM\Service\MailChimpService;



//Set the page title
$sPageTitle =ucfirst($sMode). ' ' . 'Family List';
include SystemURLs::getDocumentRoot() . '/Include/Header.php';
/* @var $families ObjectCollection */
?>
<div class="pull-right">
    <a class="btn btn-success" role="button" href="<?= SystemURLs::getRootPath()?>/FamilyEditor.php">
        <span class="fa fa-plus" aria-hidden="true"></span> Add Family</a>
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

<script nonce="<?= SystemURLs::getCSPNonce() ?>">
$(document).ready(function() {

    var table = $('#example').DataTable({});
    'use strict';

    var sMode = '<?php echo $sMode; ?>';
    var columns = [{
            data: "id",
            title: 'Action',
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
            data: "id"
        },
        {
            data: "old_id"
        },
        {
            data: "p"
        },
        {
            data: "main_name"
        },
        {
            data: "main_id"
        },
        {
            data: "partner_name"
        },
        {
            data: "partner_id"
        },
        {
            data: "poverty_rate"
        },
        {
            data: "address1"
        },
        {
            data: "address2"
        },
        {
            data: "city"
        },
        {
            data: "state"
        },
        {
            data: "home_phone"
        },
        {
            data: "aid_phone"
        },
        {
            data: "mobile_phone"
        },
        {
            data: "status"
        },
        {
            data: "aid_note"
        },
        {
            data: "general_note"
        },
        {
            data: "team_note"
        },
        {
            data: "ref"
        },
        {
            data: "membership_status"
        },
        {
            data: "members_num"
        },
        {
            data: "children"
        },
        {
            data: "no_money"
        },
        {
            data: "other_notes"
        },
        {
            data: "question"
        }
    ];

    // get filteration columns description 
    function get_filtering_options(total_num, multi_select_list) {
        filtering_options = []
        for (var i = 1; i <=total_num; i++) {
            if (multi_select_list.includes(i) ) {
                filtering_options.push({
                    column_number: i,
                    filter_type: 'multi_select',
                    append_data_to_table_data: 'before',
                    data: [{
                        value: '^$',
                        label: 'Empty'
                    }, {
                        value: '(.)+',
                        label: 'Not Empty'
                    }],
                    filter_match_mode: 'regex',
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
    var filtering_options = get_filtering_options(26, [3, 8, 10, 11, 15, 19, 20, 23]);


    var table = $('#example').DataTable({
        "bJQueryUI": true,
        "bStateSave": true,
        destroy: true,
        "pageLength": 5,
        keys: true,
        scrollX: true,
        "ajax": {
            type: "POST",
            url: '/churchcrm/PostRedirectLocal.php',
            data: function(d) {
                d.post_name = "all_families",
                    d.sMode = sMode
            }
        },
        dom: 'Bfrtip',
        buttons: [{
                extend: 'copyHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible',
                    format: {
                        header: function(data, row, column, node) {
                            var newdata = data;

                            newdata = newdata.replace(/<.*?<\/*?>/gi, '');
                            newdata = newdata.replace(/<div.*?<\/div>/gi, '');
                            newdata = newdata.replace(/<\/div.*?<\/div>/gi, '');
                            return newdata;
                        }
                    }
                }
            },
            {
                extend: 'print',
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
                orientation: 'landscape',
            },
            'colvis'
        ],
        "columns": columns
    });

    // Multi select Filteration
    yadcf.init(table, filtering_options);

});
</script>

<?php
require SystemURLs::getDocumentRoot() .  '/Include/Footer.php';
?>