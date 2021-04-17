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


    <!-- Multi select filtering -->
    <!-- ================================================== -->
    <link href="<?= SystemURLs::getRootPath() ?>/skin/filter2/jquery.dataTables.yadcf.0.9.2.css" rel="stylesheet"
        type="text/css">
    </link>
    <link href="<?= SystemURLs::getRootPath() ?>/skin/filter2/shCore.css" rel="stylesheet" type="text/css" />
    <link href="<?= SystemURLs::getRootPath() ?>/skin/filter2/shThemeDefault.css" rel="stylesheet" type="text/css" />
    <link href="<?= SystemURLs::getRootPath() ?>/skin/filter2/main.css" rel="stylesheet" type="text/css" />
    <link href="<?= SystemURLs::getRootPath() ?>/skin/filter2/select2.css" rel="stylesheet" type="text/css" />
    <link href="<?= SystemURLs::getRootPath() ?>/skin/filter2/chosen.min.css" rel="stylesheet" type="text/css" />
    <!-- <script src="<?= SystemURLs::getRootPath() ?>/skin/="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
    <!-- <script type="text/javascript" src="<?= SystemURLs::getRootPath() ?>/skin/filter2/fnReloadAjax.js"></script>
    <script src="<?= SystemURLs::getRootPath() ?>/skin/filter2/select2.js"></script> -->
    <script type="text/javascript" src="<?= SystemURLs::getRootPath() ?>/skin/filter2/jquery.dataTables.yadcf.0.9.2.js">
    </script>
    <!--  <script type="text/javascript" src="<?= SystemURLs::getRootPath() ?>/skin/="filter2/server_side_example.js"></script> -->
    <script type="text/javascript" src="<?= SystemURLs::getRootPath() ?>/skin/filter2/shCore.js"></script>
    <script type="text/javascript" src="<?= SystemURLs::getRootPath() ?>/skin/filter2/shBrushJScript.js"></script>
    <script type="text/javascript" src="<?= SystemURLs::getRootPath() ?>/skin/filter2/shBrushJava.js"></script>
    <script type="text/javascript" charset="utf-8" language="javascript"
        src="<?= SystemURLs::getRootPath() ?>/skin/filter2/chosen.jquery.min.js"></script>
    <!-- -------------------------------------- -->


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
    </div>

    <style>
div.dataTables_wrapper {
    direction: rtl;
}

/* 1: استلام */
/* 2: استفسار */
/* 3: الاسم */
/* 4: الرقم الوطني */
/* 5: الهاتف */
/* 6: العنوان */
/* 7: التقييم */
/* 8: الحالة */
/* 9: عدد أفراد العائلة */
/* 10: ملاحظات عامة */
/* 11: ملاحظات الفريق */
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
    /* word-break: break-all; */
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
    /* margin: 0; */
    /* word-break: break-all; */
    /* font-size: 14px; */
    /* white-space: pre-line; */
}

table.dataTable td:nth-child(4),
td:nth-child(5) {
    width: 125px;
    max-width: 125px;
    word-break: break-all;
    /* font-size: 14px; */
    white-space: pre-line;
}

table.dataTable td:nth-child(6),
td:nth-child(10),
td:nth-child(11) {
    width: 150px;
    max-width: 150px;
    font-size: 12px;
    /* word-break: break-all; */
    white-space: pre-line;
}

table.dataTable td:nth-child(9) {
    width: 80px;
    max-width: 80px;
    word-break: break-all;
    font-size: 12px;
    /* white-space: pre-line; */
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
        "</p></div><div class='col-md-6'><p>" + month_name + " - " + year_name + "</p></div></div>"
    // var my_title = team_name+ " "+year_name+ " - "+ month_name;
    $('#example tfoot th').each(function() {
        var title = $(this).text();
        $(this).html('<input type="text" placeholder="' + title + '" />');
    });


    $('#example').DataTable({

    });

    $('#example').DataTable({
            scrollX: true,
            scrollY: 350,
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'copyHtml5',
                    exportOptions: {
                        columns: [0, ':visible']
                    },
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
                    }
                },
                {
                    extend: 'excelHtml5',
                    exportOptions: {
                        columns: ':visible'
                    },
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
                    // messageTop: 'This print was produced using the Print button for DataTables',
                    repeatingHead: {
                        // logo: 'https://www.google.co.in/logos/doodles/2018/world-cup-2018-day-22-5384495837478912-s.png',
                        // logoStyle: '',
                        title: my_title,
                    },
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
                            // Only Searching 
                            // if (e.keyCode == 13) that.draw();
                        });
                });
            }
        })

        .yadcf([{
                column_number: 6,
                filter_type: "multi_select",
                select_type: 'select2'
            },
            // {
            //     column_number: 1,
            //     filter_type: "range_number_slider"
            // }, {
            //     column_number: 6,
            //     select_type: 'select2',
            //     select_type_options: {
            //         width: '110px',
            //         minimumResultsForSearch: -1 // remove search box
            //     }
            /*, {
                column_number: 3,
                filter_type: "text",
                text_data_delimiter: ",",
                exclude: true
            }*/
            /*, {
                    column_number: 4,
                    select_type: 'select2',
                    select_type_options: {
                        width: '150px',
                        placeholder: 'Select tag',
                        allowClear: true  // show 'x' (remove) next to selection inside the select itself
                    },
                    column_data_type: "html",
                    html_data_type: "text",
                    filter_reset_button_text: false // hide yadcf reset button
                }*/
        ]);

    yadcf.initMultipleColumns(oTable, [{
        column_number: [0, 1],
        filter_container_id: 'multi-column-filter-01',
        filter_default_label: 'First table columns 1 and 2!'
    }]);

    SyntaxHighlighter.all();

});
    </script>

    <?php
require SystemURLs::getDocumentRoot() .  '/Include/Footer.php';
?>