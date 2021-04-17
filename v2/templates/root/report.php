<?php


use ChurchCRM\dto\SystemConfig;
use ChurchCRM\dto\SystemURLs;

use ChurchCRM\Authentication\AuthenticationManager;
use ChurchCRM\dto\Classification;

include SystemURLs::getDocumentRoot() . '/Include/Header.php';

/* @var $families ObjectCollection */
?>


<link rel="stylesheet" type="text/css"
    href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-colvis-1.7.0/b-html5-1.7.0/b-print-1.7.0/datatables.min.css" />


<div class="btn-group">
    <a
        href="<?= SystemURLs::getRootPath() ?>/r2/koolreport_2021/examples/reports/ex_2/index.php?month=<?=$_GET['month']?>&year=<?=$_GET['year']?>&team=<?=$_GET['team']?>">
        <button type="button" class="btn btn-success">Make Report</button>
    </a>
</div>
<div class="box">
    <div class="box-body">
        <table id="example" class="display table table-striped table-bordered data-table" cellspacing="0"
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
                    <?php for($j=0; $j<=10; $j++){ ?>
                    <td><?= $result[$j] ?></td>
                    <?php } ?>
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

    table.dataTable td {
        font-size: 13px;
    }

    table.dataTable th {
        font-size: 16px;
        text-align: center;
    }


    table.dataTable th:nth-child(1),
    td:nth-child(1),
    th:nth-child(8),
    td:nth-child(8) {
        width: 35px;
        max-width: 35px;
        white-space: pre-line;
    }

    table.dataTable td:nth-child(7) {
        width: 20px;
        max-width: 20px;
        white-space: pre-line;
    }


    table.dataTable td:nth-child(2) {
        width: 80px;
        max-width: 80px;
        word-break: break-all;
        white-space: pre-line;
    }

    table.dataTable th:nth-child(3),
    td:nth-child(3) {
        width: 190px;
        max-width: 190px;

    }

    table.dataTable td:nth-child(4),
    td:nth-child(5) {
        width: 125px;
        max-width: 125px;
        word-break: break-all;
        white-space: pre-line;
    }

    table.dataTable td:nth-child(6),
    td:nth-child(10),
    td:nth-child(11) {
        width: 150px;
        max-width: 150px;
        font-size: 12px;
        white-space: pre-line;
    }

    table.dataTable td:nth-child(9) {
        width: 80px;
        max-width: 80px;
        word-break: break-all;
        font-size: 12px;
    }

    @media print {

        html,
        body {
            height: auto;
        }

        .dt-print-table,
        .dt-print-table thead,
        .dt-print-table th,
        .dt-print-table tr {
            border: 0 none !important;
        }
    }
    </style>

    <script nonce="<?= SystemURLs::getCSPNonce() ?>">
    $(document).ready(function() {

        var year_name = '<?php echo $year_name; ?>';
        var month_name = '<?php echo $month_name; ?>';
        var team_name = '<?php echo $team_name; ?>';
        var my_title = "<div class='row'><div class='col-md-6'><p>" + team_name +
            "</p></div><div class='col-md-6'><p>" + month_name + " - " + year_name + "</p></div></div>";

        $('#example tfoot th').each(function() {
            var title = $(this).text();
            $(this).html('<input type="text" placeholder="' + title + '" />');
        });

        $('#example').dataTable({
            scrollX: true,
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
                    // autoPrint: false,
                    exportOptions: {
                        columns: ':visible',
                        stripHtml: false
                    },
                    title: '',
                    exportOptions: {
                        format: {
                            header: function(data, row, column, node) {
                                var newdata = data;

                                newdata = newdata.replace(/<.*?<\/*?>/gi, '');
                                newdata = newdata.replace(/<div.*?<\/div>/gi, '');
                                newdata = newdata.replace(/<\/div.*?<\/div>/gi, '');
                                return newdata;
                            }
                        }
                    },
                    // messageTop: 'This print was produced using the Print button for DataTables',
                    repeatingHead: {
                        // logo: 'https://www.google.co.in/logos/doodles/2018/world-cup-2018-day-22-5384495837478912-s.png',
                        // logoStyle: '',
                        title: my_title,
                    },
                    customize: function(win) {
                        $(win.document.body).css('direction', 'rtl');
                        $(win.document.body).find('tr:nth-child(odd) td').each(function(
                            index) {
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
                        });
                });
            }
        });

    });
    </script>

    <?php
require SystemURLs::getDocumentRoot() .  '/Include/Footer.php';
?>