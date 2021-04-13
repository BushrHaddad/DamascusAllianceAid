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

<div class="btn-group">
    <a href="<?= SystemURLs::getRootPath() ?>/r2/koolreport_2021/examples/reports/ex_2/index.php?month=<?=$_GET['month']?>&year=<?=$_GET['year']?>&team=<?=$_GET['team']?>">
        <button type="button" class="btn btn-success">Make Report</button>
    </a>
</div>
<div class="box">
    <div class="box-body">
        <table id="example" class="display table table-striped table-bordered data-table" cellspacing="0"
            style="width:100%;">
            <!-- id="example" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%"> -->
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

    /* 1: استلام */
    /* 2: تسلسل */
    /* 3: مالية أو فريق الزيارة */
    /* 4: الاسم */
    /* 5: الرقم الوطني */
    /* 6: الهاتف */
    /* 7: العنوان */
    /* 8: التقييم */
    /* 9: عدد أفراد العائلة */
    /* 10: ملاحظات عامة */
    /* 11: ملاحظات الفريق */


    table.dataTable th:nth-child(1),
    th:nth-child(2) {
        width: 10px;
        max-width: 10px;
        white-space: pre-line;
    }

    table.dataTable th:nth-child(3) {
        width: 30px;
        max-width: 30px;
        word-break: break-all;
        white-space: pre-line;
    }

    table.dataTable td:nth-child(4),
    th:nth-child(4),
    td:nth-child(5),
    th:nth-child(5),
    td:nth-child(6),
    th:nth-child(6) {
        width: 100px;
        max-width: 100px;
        word-break: break-all;
        white-space: pre-line;
    }

    table.dataTable td:nth-child(4),
    td:nth-child(5),
    td:nth-child(6),
    td:nth-child(7),
    td:nth-child(8),
    td:nth-child(9) {
        font-size: 13px;
    }

    table.dataTable td:nth-child(7),
    th:nth-child(7) {
        width: 110px;
        max-width: 110px;
        word-break: break-all;
        white-space: pre-line;
    }

    table.dataTable td:nth-child(8),
    th:nth-child(8) {
        width: 110px;
        max-width: 110px;
        word-break: break-all;
        white-space: pre-line;
    }

    table.dataTable td:nth-child(9),
    th:nth-child(9) {
        width: 90px;
        max-width: 90px;
        word-break: break-all;
        white-space: pre-line;
    }

    table.dataTable th:nth-child(10),
    th:nth-child(11) {
        width: 140px;
        max-width: 140px;
        word-break: break-all;
        white-space: pre-line;
    }

    table.dataTable td:nth-child(10),
    td:nth-child(11) {
        width: 140px;
        max-width: 140px;
        font-size: 12px;
        word-break: break-all;
        white-space: pre-line;
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