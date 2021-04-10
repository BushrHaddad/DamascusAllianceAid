<?php


use ChurchCRM\dto\SystemConfig;
use ChurchCRM\dto\SystemURLs;

use ChurchCRM\Authentication\AuthenticationManager;
use ChurchCRM\dto\Classification;
use ChurchCRM\Service\MailChimpService;

include SystemURLs::getDocumentRoot() . '/Include/Header.php';
/* @var $families ObjectCollection */
?>
<link rel="stylesheet" type="text/css"
    href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-colvis-1.7.0/b-html5-1.7.0/b-print-1.7.0/datatables.min.css" />

<div class="box">
    <div class="box-body">
        <table id="example" class="my_table display table table-striped table-bordered data-table" cellspacing="0"
            style="width:100%;">
            <thead>
                <tr>
                    <?php foreach ($attributes as $attribute) { ?>
                    <th><?= $attribute ?></th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $result) { ?>
                <tr>
                    <td><?= $result[0] ?></td>
                    <td><?= $result[1] ?></td>
                    <td><?= $result[2] ?></td>
                    <td><?= $result[3] ?></td>
                    <td><?= $result[4] ?></td>
                    <td><?= $result[5] ?></td>
                    <td><?= $result[6] ?></td>
                    <td><?= $result[7] ?></td>
                    <td><?= $result[8] ?></td>
                    <td><?= $result[9] ?></td>
                    <td><?= $result[10] ?></td>
                </tr>
                <?php } ?>
            </tbody>
            <tfoot>
                <tr>
                    <?php foreach ($attributes as $attribute) { ?>
                    <th><?= $attribute ?></th>
                    <?php } ?>
                </tr>
            </tfoot>
        </table>
    </div>
    </div<>

    <style>
    div.dataTables_wrapper {
        direction: rtl;
    }

    </style>

    <script nonce="<?= SystemURLs::getCSPNonce() ?>">
    $(document).ready(function() {

        $('#example tfoot th').each(function() {
            var title = $(this).text();
            $(this).html('<input type="text" placeholder="' + title + '" />');
        });

        $('#example').DataTable({
            scrollX: true,
            scrollY: 350,
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: [0, ':visible']
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: ':visible'
                    }
                },
                {
                    extend: 'print',
                    // autoPrint: true,
                    exportOptions: {
                        columns: ':visible',
                        stripHtml: false
                    },
                    customize: function(win) {
                        $(win.document.body).css('direction', 'rtl');
                        $(win.document.body).find('th').addClass('display').css('text-align',
                            'right');
                        $(win.document.body).find('example').addClass('display').css(
                            'font-size',
                            '16px');
                        $(win.document.body).find('example').addClass('display').css(
                            'text-align',
                            'right');
                        $(win.document.body).find('tr:nth-child(odd) td').each(function(index) {
                            $(this).css('background-color', '#D0D0D0');
                        });
                        $(win.document.body).find('h1').css('text-align', 'center');
                    },
                    orientation: 'landscape',

                },
                'colvis'
            ],
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
    });
    </script>

    <?php
require SystemURLs::getDocumentRoot() .  '/Include/Footer.php';
?>