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

<!-- Team and Cash Report Adjustment -->
<link rel="stylesheet" type="text/css"
      href="<?= SystemURLs::getRootPath() ?>/skin/team-cash-report.css">


<!-- <div class="btn-group">
    <a
        href="<?= SystemURLs::getRootPath() ?>/r2/koolreport_2021/examples/reports/ex_2/index.php?month=<?=$_GET['month']?>&year=<?=$_GET['year']?>&team=<?=$_GET['team']?>">
        <button type="button" class="btn btn-success">Make Report</button>
    </a>

</div> -->

<input style="text-align: center;line-height: 35px;" size="80" type="search" id="repeating_text" placeholder="لتكرار نص معيَن، الرجاء كتابته ومن ثم الضغط على الزر الأيمن لحفظه">
<button type="button" id = "repeating_text_button" class="btn btn-success">Repeat this!</button>
<br/><br/>

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

    <script nonce="<?= SystemURLs::getCSPNonce() ?>">
    $(document).ready(function() {

        var year_name = '<?php echo $year_name; ?>';
        var month_name = '<?php echo $month_name; ?>';
        var team_name = '<?php echo $team_name; ?>';
        var repeating_text = '<?php echo $repeating_text; ?>';
        $("#repeating_text").val(repeating_text);
        var my_title = "  <div> <div style='clear:both'> <h2 style='float: left'>" + month_name + " - " + year_name + "</h2><h2 style='float: right'>" + team_name +"</h2> </div><p> "+ repeating_text+ "</p> </div>";

        $('#example tfoot th').each(function() {
            var title = $(this).text();
            $(this).html('<input type="text" placeholder="' + title + '" />');
        });

        $('#repeating_text_button').click(function() {
            var repeating_text = $("#repeating_text").val();
            window.location = window.location.href + "&text="+repeating_text;
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
                    autoPrint: true,
                    exportOptions: {
                        columns: ':visible',
                        stripHtml: false
                    },
                    title: 'كنيسة الاتحاد المسيحي الإنجيلية - توزيع قوائم الفرق',
                    // exportOptions: {
                    //     format: {
                    //         header: function(data, row, column, node) {
                    //             var newdata = data;
                    //             newdata = newdata.replace(/<.*?<\/*?>/gi, '');
                    //             newdata = newdata.replace(/<div.*?<\/div>/gi, '');
                    //             newdata = newdata.replace(/<\/div.*?<\/div>/gi, '');
                    //             return newdata;
                    //         }
                    //     }
                    // },
                    // messageTop: 'This print was produced using the Print button for DataTables',
                    repeatingHead: {
                        title: my_title,
                    },
                    customize: function(win) {
                        $(win.document.body).css('direction', 'rtl');
                        // $(win.document.body).find('tr:nth-child(odd) td').each(function(
                        //     index) {
                        //     $(this).css('background-color', '#D0D0D0');
                        // });
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
                                that.search(this.value).draw();
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