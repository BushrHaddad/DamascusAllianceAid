<?php



use ChurchCRM\dto\SystemURLs;

//Set the page title
include SystemURLs::getDocumentRoot() . '/Include/Header.php';

?>

<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-3">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3 id="familyCountDashboard">
                    <?= $dashboardCounts["families"] ?>
                </h3>
                <p>
                    Active Families
                </p>
            </div>
            <div class="icon">
                <i class="fa fa-users"></i>
            </div>
            <a href="<?= SystemURLs::getRootPath() ?>/v2/family" class="small-box-footer">
                See all Families <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-3">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3 id="peopleStatsDashboard">
                    <?= $dashboardCounts["People"] ?>
                </h3>
                <p>People</p>
            </div>
            <div class="icon">
                <i class="fa fa-user"></i>
            </div>
            <a href="<?= SystemURLs::getRootPath() ?>/v2/people" class="small-box-footer">See All People <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div><!-- ./col -->

    <!-- Global Master Table -->
    <div class="col-lg-5">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>
                    Master Table
                </h3>
                <p>
                    فلترة القوائم
                </p>
            </div>
            <div class="icon">
                <i class="fa fa-gg"></i>
            </div>
            <a href="<?= SystemURLs::getRootPath() ?>/v2/family/master" class="small-box-footer">More Info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>


</div><!-- /.row -->

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Reports</h3>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-lg-5">
                <div class="small-box bg-teal">
                    <div class="inner">
                        <h4> Cash Reports </h4>
                        <div>
                            <label>Choose a Year:</label>
                            <select id="cash_year_option" class="js-example-basic-single" style="width: 50%">
                            <?php foreach($report_years as $year){ ?>
                            <option value='<?= $year['name'] ?>'><?= $year['name'] ?></option>
                            <?php } ?>
                            </select>
                        </div>
                        </br>
                        <div>
                            <label>Choose a Month:</label>
                            <select id="cash_month_option"  class="js-example-basic-single" style="width: 50%">
                            <?php foreach($report_months as $month){ ?>
                          
                            <option value='<?= $month['name'] ?>' ><?= $month['name'] ?></option>
                          
                            <?php } ?>
                            </select>
                        </div>
                       
                    </div>
                    <a id= "cash_reports_link" href="" class="small-box-footer">
                    Generate Cash Report  <i class="fa fa-arrow-circle-right"></i>
            </a>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="small-box bg-maroon">
                    <div class="inner">
                        <h4> Teams Reports </h4>
                        <div>
                            <label>Choose a Year:</label>
                            <select id="team_year_option" class="js-example-basic-single" style="width: 25%">
                            <?php
                            foreach($report_years as $year){
                            ?>
                            <option value= '<?= $year['name'] ?>' ><?= $year['name'] ?></option>
                            <?php
                            }
                            ?>
                            </select>
                            
                            <label>Choose a Month:</label>
                            <select id="team_month_option" class="js-example-basic-single" style="width: 25%">
                            <?php
                            foreach($report_months as $month){
                            ?>
                            <option value= '<?= $month['name'] ?>' ><?= $month['name'] ?></option>
                            <?php
                            }
                            ?>
                            </select>
                        </div>
                        </br>
                        <div>
                            <label>Choose a Team:</label>
                            <select id="team_team_option" class="js-example-basic-single" style="width: 50%">
                            <?php
                            foreach($report_teams as $team){
                            ?>
                            <option value= '<?= $team['name'] ?>' ><?= $team['name'] ?></option>
                            <?php
                            }
                            ?>
                            </select>
                        </div>
                    </div>
                    <a id= "team_reports_link" href="" class="small-box-footer">
                    Generate Team Report  <i class="fa fa-arrow-circle-right"></i>                </div>
            </div>
            <div class="col-md-6">
            </div>
        </div>
    </div>
</div>

<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">People</h3>
        <div class="pull-right">
            <div class="btn-group">
                <a href="<?= SystemURLs::getRootPath() ?>/PersonEditor.php">
                    <button type="button" class="btn btn-success">Add New Person</button>
                </a>
                <a href="<?= SystemURLs::getRootPath() ?>/FamilyEditor.php" <button type="button"
                    class="btn btn-success">Add New Family</button>
                </a>
            </div>
        </div>
    </div>
    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <!-- Custom Tabs -->
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#ppl-tab_1" data-toggle="tab"><?= gettext('Latest Families') ?></a>
                        </li>
                        <li><a href="#ppl-tab_2" data-toggle="tab"><?= gettext('Updated Families') ?></a></li>
                        <li><a href="#ppl-tab_3" data-toggle="tab"><?= gettext('Latest Persons') ?></a></li>
                        <li><a href="#ppl-tab_4" data-toggle="tab"><?= gettext('Updated Persons') ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="ppl-tab_1">
                            <table class="table table-striped" width="100%" id="latestFamiliesDashboardItem"></table>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="ppl-tab_2">
                            <table class="table table-striped" width="100%" id="updatedFamiliesDashboardItem"></table>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="ppl-tab_3">
                            <table class="table table-striped" width="100%" id="latestPersonDashboardItem"></table>
                        </div>
                        <!-- /.tab-pane -->
                        <div class="tab-pane" id="ppl-tab_4">
                            <table class="table table-striped" width="100%" id="updatedPersonDashboardItem"></table>
                        </div>
                        <!-- /.tab-pane -->
                    </div>
                    <!-- /.tab-content -->
                </div>
                <!-- nav-tabs-custom -->
            </div>
        </div>
    </div>
</div>

<script src="<?= SystemURLs::getRootPath() ?>/skin/js/MainDashboard.js"></script>
<script nonce="<?= SystemURLs::getCSPNonce() ?>">
$(document).ready(function() {
    $('.js-example-basic-single').select2({    allowClear: true,
    placeholder: "Search..",});
    var cash_month = $("#cash_month_option").val();
    var cash_year = $("#cash_year_option").val();
    var team_month = $("#team_month_option").val();
    var team_year = $("#team_year_option").val();
    var team_team =  $("#team_team_option").val();
    change_link();

    // Cash Reports
    $('#cash_month_option').change(function() {
        cash_month = $("#cash_month_option").val();
        change_link();
    });
    $('#cash_year_option').change(function() {
        cash_year = $("#cash_year_option").val();
        change_link();
    });

    // Team Reports
    $('#team_month_option').change(function() {
        team_month = $("#team_month_option").val();
        change_link();
    });
    $('#team_year_option').change(function() {
        team_year = $("#team_year_option").val();
        change_link();
    });
    $('#team_team_option').change(function() {
        team_team = $("#team_team_option").val();
        change_link();
    });

    function change_link(){
        
        var link = document.getElementById("cash_reports_link");
        link.href = window.CRM.root+"/v2/reports/cash_report?month=" + cash_month + "&year="+cash_year;
        var link1 = document.getElementById("team_reports_link");
        link1.href = window.CRM.root+"/v2/reports/team_report?month=" + team_month + "&year="+team_year+ "&team="+team_team;

    }



});
</script>

<?php include SystemURLs::getDocumentRoot() . '/Include/Footer.php'; ?>