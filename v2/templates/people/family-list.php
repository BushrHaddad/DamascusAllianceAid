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

    var columns = [{
            data: "id",
            title: 'Action',
            wrap: true,
            "render": function(item) {
                // console.log(item);
                // console.log(item.old_id);
                // window.href = item;
                var path_view = window.CRM.root+ '/v2/family/'+ item;
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
            data: "chose"
        },
        {
            data: "shared_housing"
        },
        {
            data: "household_member_shared_housing"
        }
    ];
    // var table;
    // table = $('#example').DataTable();
    // destroyTable();
    $('#example tfoot th').each(function() {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="' + title + '" />');
    });

    $('#example').DataTable({
        // destroy: true,
        "serverSide": true,
        // processing: true,
        // responsive: true,
        "scrollY": 450,
        keys: true,
        "scrollX": true,
        "ajax": {
            type: "POST",
            url: '/churchcrm/PostRedirect.php',
            data: function(d) {
                d.post_name = "all_families"
            }
        },
        "columns": columns,
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

    // var table = $('#example').DataTable({
    //     orderCellsTop: true,
    //     // fixedHeader: true,
    //     "scrollX": true,
    //     keys: true
    // });

});
</script>

<?php
require SystemURLs::getDocumentRoot() .  '/Include/Footer.php';
?>