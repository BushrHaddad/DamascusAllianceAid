<?php
/*******************************************************************************
 *
 *  filename    : Include/Footer.php
 *  last change : 2002-04-22
 *  description : footer that appear on the bottom of all pages
 *
 *  http://www.churchcrm.io/
 *  Copyright 2001-2002 Phillip Hullquist, Deane Barker, Philippe Logel
  *
 ******************************************************************************/

use ChurchCRM\Authentication\AuthenticationManager;
use ChurchCRM\Bootstrapper;
use ChurchCRM\dto\SystemURLs;
use ChurchCRM\Service\SystemService;

$isAdmin = AuthenticationManager::GetCurrentUser()->isAdmin();
?>
</section><!-- /.content -->

</div>
<!-- /.content-wrapper -->
<footer class="main-footer">
    <div class="pull-right">
        <b><?= gettext('Version') ?></b> <?= $_SESSION['sSoftwareInstalledVersion'] ?>
    </div>
    <strong><?= gettext('Copyright') ?> &copy; <?= SystemService::getCopyrightDate() ?> <a href="http://www.churchcrm.io" target="_blank"><b>Church</b>CRM</a>.</strong> <?= gettext('All rights reserved') ?>.
    <!-- | <a href="https://twitter.com/church_crm" target="_blank"><i class="fa fa-twitter"></i> <?= gettext("Follow us on Twitter") ?></a> -->
    <!-- | <span class="flag-icon flag-icon-squared"></span> -->
</footer>

<!-- The Right Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <div class="tab-content">
        <div class="tab-pane active" id="control-sidebar-tasks-tab">
            <?= gettext('You have') ?> &nbsp; <span class="label label-danger"><?= $taskSize ?></span>
            &nbsp; <?= gettext('task(s)') ?>
            <br/><br/>
            <ul class="control-sidebar-menu">
                <?php foreach ($tasks as $task) {
    $taskIcon = 'fa-info bg-green';
    if ($task['admin']) {
        $taskIcon = 'fa-lock bg-yellow-gradient';
    } ?>
                    <!-- Task item -->
                    <li>
                        <a target="blank" href="<?= $task['link'] ?>">
                            <i class="menu-icon fa fa-fw <?= $taskIcon ?>"></i>
                            <div class="menu-info">
                                <h4 class="control-sidebar-subheading"
                                    title="<?= $task['desc'] ?>"><?= $task['title'] ?></h4>
                            </div>
                        </a>

                    </li>
                    <!-- end task item -->
                    <?php
} ?>
            </ul>
            <!-- /.control-sidebar-menu -->

        </div>
        <!-- /.tab-pane -->
    </div>
</aside>
<!-- The sidebar's background -->
<!-- This div must placed right after the sidebar for it to work-->
<div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
</div><!-- ./wrapper -->

<!-- Bootstrap 3.3.5 -->
 
<!-- <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script> 
 <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script> -->


<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-colvis-1.7.0/b-html5-1.7.0/b-print-1.7.0/kt-2.6.1/r-2.2.7/sl-1.3.3/datatables.min.css"/> 
 <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-colvis-1.7.0/b-html5-1.7.0/b-print-1.7.0/kt-2.6.1/r-2.2.7/sl-1.3.3/datatables.min.js"></script>

<!--  Before removing jquery -->
 <!-- <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-3.3.1/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-colvis-1.7.0/b-html5-1.7.0/b-print-1.7.0/kt-2.6.1/r-2.2.7/sl-1.3.3/datatables.min.css"/>  -->
 <!-- <script type="text/javascript" src="https://cdn.datatables.net/v/dt/jq-3.3.1/jszip-2.5.0/dt-1.10.24/b-1.7.0/b-colvis-1.7.0/b-html5-1.7.0/b-print-1.7.0/kt-2.6.1/r-2.2.7/sl-1.3.3/datatables.min.js"></script> -->


<script src="<?= SystemURLs::getRootPath() ?>/skin/external/bootstrap/bootstrap.min.js"></script>
<!-- AdminLTE App -->
<script src="<?= SystemURLs::getRootPath() ?>/skin/external/adminlte/adminlte.min.js"></script>

<!-- InputMask -->
<script src="<?= SystemURLs::getRootPath() ?>/skin/external/inputmask/jquery.inputmask.min.js"></script>
<script src="<?= SystemURLs::getRootPath() ?>/skin/external/inputmask/inputmask.binding.js"></script>

<!-- <script src="<?= SystemURLs::getRootPath() ?>/skin/external/bootstrap-datepicker/bootstrap-datepicker.min.js"></script> -->
<!-- <script src="<?= SystemURLs::getRootPath() ?>/skin/external/bootstrap-daterangepicker/daterangepicker.js"></script> -->

<script src="<?= SystemURLs::getRootPath() ?>/skin/external/datatables/pdfmake.min.js"></script>
<script src="<?= SystemURLs::getRootPath() ?>/skin/external/datatables/vfs_fonts.js"></script>
<!-- <script src="<?= SystemURLs::getRootPath() ?>/skin/external/datatables/dataTables.fixedColumns.min.js"></script> -->
<!-- Here is a script the conflict with pdf make and other confiqurations above -->
<!--  /skin/external/datatables/datatables.min.js-->
<script src="<?= SystemURLs::getRootPath() ?>/skin/external/datatables/dataTables.cellEdit.js"></script>
<!-- <script src="<?= SystemURLs::getRootPath() ?>/skin/external/datatables/dataTables.keyTable.min.js"></script> -->


<!-- <script src="<?= SystemURLs::getRootPath() ?>/skin/external/chartjs/Chart.js"></script> -->
<script src="<?= SystemURLs::getRootPath() ?>/skin/external/select2/select2.full.min.js"></script>

<script src="<?= SystemURLs::getRootPath() ?>/skin/external/bootstrap-notify/bootstrap-notify.min.js"></script>
<script src="<?= SystemURLs::getRootPath() ?>/skin/external/fullcalendar/fullcalendar.min.js"></script>
<script src="<?= SystemURLs::getRootPath() ?>/skin/external/bootbox/bootbox.min.js"></script> <!-- Need it -->
<!-- <script src="<?= SystemURLs::getRootPath() ?>/skin/external/fastclick/fastclick.js"></script> -->
<!-- <script src="<?= SystemURLs::getRootPath() ?>/skin/external/bootstrap-toggle/bootstrap-toggle.js"></script> -->

<script src="<?= SystemURLs::getRootPath() ?>/skin/external/i18next/i18next.min.js"></script> <!-- Need it -->
<!-- <script src="<?= SystemURLs::getRootPath() ?>/locale/js/<?= Bootstrapper::GetCurrentLocale()->getLocale() ?>.js"></script> -->
<script src="<?= SystemURLs::getRootPath() ?>/skin/external/bootstrap-validator/validator.min.js"></script>

<!-- <script src="<?= SystemURLs::getRootPath() ?>/skin/js/IssueReporter.js"></script> -->
<script src="<?= SystemURLs::getRootPath() ?>/skin/js/DataTables.js"></script>
<!-- <script src="<?= SystemURLs::getRootPath() ?>/skin/js/Tooltips.js"></script>
<script src="<?= SystemURLs::getRootPath() ?>/skin/js/Events.js"></script>
<script src="<?= SystemURLs::getRootPath() ?>/skin/js/Footer.js"></script> -->

<!-- <script src="<?= SystemURLs::getRootPath() ?>/skin/external/datatables/dataTables.colVis.js"></script>
<script src="<?= SystemURLs::getRootPath() ?>/skin/external/datatables/jquery.dataTables.js"></script>
<script src="<?= SystemURLs::getRootPath() ?>/skin/dataTables.scroller.min.js"></script> -->


<!-- Multi Select Filteration -->
<!-- ================================================== -->
<!-- <link href="<?= SystemURLs::getRootPath() ?>/skin/filter2/shCore.css" rel="stylesheet" type="text/css" /> -->
<!-- <link href="<?= SystemURLs::getRootPath() ?>/skin/filter2/shThemeDefault.css" rel="stylesheet" type="text/css" /> -->
<link href="<?= SystemURLs::getRootPath() ?>/skin/filter2/main.css" rel="stylesheet" type="text/css" />
<link href="<?= SystemURLs::getRootPath() ?>/skin/filter2/chosen.min.css" rel="stylesheet" type="text/css" />
<!-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> -->
<script type="text/javascript" src="<?= SystemURLs::getRootPath() ?>/skin/filter2/fnReloadAjax.js"></script>
<script type="text/javascript" src="<?= SystemURLs::getRootPath() ?>/skin/filter2/jquery.dataTables.yadcf.0.9.2.js"></script>
<!--  <script type="text/javascript" src="<?= SystemURLs::getRootPath() ?>/skin/="filter2/server_side_example.js"></script> -->
<!-- <script type="text/javascript" src="<?= SystemURLs::getRootPath() ?>/skin/filter2/shCore.js"></script> -->
<!-- <script type="text/javascript" src="<?= SystemURLs::getRootPath() ?>/skin/filter2/shBrushJScript.js"></script> -->
<!-- <script type="text/javascript" src="<?= SystemURLs::getRootPath() ?>/skin/filter2/shBrushJava.js"></script> -->
<!-- <script type="text/javascript" charset="utf-8" language="javascript"
    src="<?= SystemURLs::getRootPath() ?>/skin/filter2/chosen.jquery.min.js"></script> -->
<!-- <link href="<?= SystemURLs::getRootPath() ?>/skin/filter2/jquery.dataTables.yadcf.0.9.2.css" rel="stylesheet"
        type="text/css">
    </link> -->
<!-- End of Multi Select Filteration -->



<script src="<?= SystemURLs::getRootPath() ?>/skin/external/datatables/print.button.js"></script>


<?php if (isset($sGlobalMessage)) {
        ?>
    <script nonce="<?= SystemURLs::getCSPNonce() ?>">
        $("document").ready(function () {
            showGlobalMessage("<?= $sGlobalMessage ?>", "<?=$sGlobalMessageClass?>");
        });
    </script>
    <?php
    } ?>

<?php  
// include_once('analyticstracking.php'); 
?>
</body>
</html>
<?php

// Turn OFF output buffering
ob_end_flush();

// Reset the Global Message
$_SESSION['sGlobalMessage'] = '';

?>
<style>
body {
	margin-bottom: 0px;
}

</style>