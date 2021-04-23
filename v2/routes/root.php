<?php

use ChurchCRM\Authentication\AuthenticationManager;
use ChurchCRM\dto\ChurchMetaData;
use ChurchCRM\dto\SystemConfig;
use ChurchCRM\dto\SystemURLs;
use ChurchCRM\EventAttendQuery;
use ChurchCRM\FamilyQuery;
use ChurchCRM\GroupQuery;
use ChurchCRM\PersonQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\PhpRenderer;

$app->group('', function () {
    $this->get('/dashboard', 'viewDashboard');
    $this->get('/reports/cash_report', 'cashReport');
    $this->get('/reports/team_report', 'teamReport');
});

function getTable($table){ 

    $sSQL = "SELECT `id`, `name` FROM $table ;";
    if($table == "master_dates_months"){
        $sSQL = "SELECT `id`, `note1` as 'name' FROM $table ;";
    }

    $rsOpps = RunQuery($sSQL);
    $data= array();

    while($row = mysqli_fetch_array($rsOpps))
    {
        $row1 = array('id' => $row[0], 'name' => $row[1]);
        $data[] = $row1;
    }
    return $data;  
}

function viewDashboard(Request $request, Response $response, array $args)
{
    $renderer = new PhpRenderer('templates/root/');
    $dashboardCounts = [];

    $dashboardCounts["families"] = FamilyQuery::Create()->filterByDateDeactivated()->count();
    $dashboardCounts["People"] =  PersonQuery::create()->leftJoinWithFamily()->where('Family.DateDeactivated is null')->count();

    $months = getTable('master_dates_months');
    $years = getTable('master_dates_year');
    $teams = getTable('master_teams');

    $pageArgs = [
        'sRootPath' => SystemURLs::getRootPath(),
        'sPageTitle' => gettext('Welcome to').' '. ChurchMetaData::getChurchName(),
        'dashboardCounts' => $dashboardCounts,
        'report_months' => $months,
        'report_years' => $years,
        'report_teams' => $teams,
    ];

    return $renderer->render($response, 'dashboard.php', $pageArgs);
}

function cashReport(Request $request, Response $response, array $args){
    $renderer = new PhpRenderer('templates/root/');

    $month_name = $request->getParams()['month'];
    $year_name = $request->getParams()['year'];

    $query = "SELECT family_id, cash_name, team_name FROM master_general_view where month_name = '$month_name' 
        AND year_name = '$year_name'
        AND cash_name is not NULL ; ";

    $result = RunQuery($query);
    $data= array();

    while ($row = mysqli_fetch_array($result))
    {

        $family_id = $row['family_id']; // this family id 
        $fam_detail_q = "SELECT main_name, partner_name, main_id, partner_id,
            members_num, children, home_phone, aid_phone, mobile_phone,
            address1, address2, city, state, poverty_rate, ref, general_note, team_note FROM families_view WHERE id = $family_id;";
        
        $fam_result = RunQuery($fam_detail_q);
        $fam_row = mysqli_fetch_array($fam_result);

        $row1 = array(
            $family_id,
            $row['cash_name'],
            $row['team_name'],
            $fam_row['main_name'] . " <br />". $fam_row['partner_name'],
            $fam_row['main_id'] . " <br />". $fam_row['partner_id'],
            $fam_row['home_phone']. " <br />". $fam_row['aid_phone']. " <br />". $fam_row['mobile_phone'],
            $fam_row['address2']." <br />".$fam_row['address1']." <br />".$fam_row['city']." <br />".$fam_row['state'],
            $fam_row['poverty_rate'],
            $fam_row['ref'],
            $fam_row['members_num']. "<hr style=' margin:0 !important;'>" .$fam_row['children'],
            $fam_row['team_note'],
        );

        $data[] = $row1;
    }

    $pageArgs = [
        'sRootPath' => SystemURLs::getRootPath(),
        'sPageTitle' => "Cash Report",
        'attributes' => ['تسلسل','مالية','فريق الزيارة','الاسم','الرقم الوطني','أرقام الهواتف', 'العنوان', 'التقييم','الحالة', 'عدد الأفراد',
                        'ملاحظات الفريق'],
        'results' => $data,
        'team_name' => "تقرير مالية",
        'year_name' => $year_name,
        'month_name' => $month_name,
    ];

    return $renderer->render($response, 'report.php', $pageArgs);

}

function teamReport(Request $request, Response $response, array $args){
    $renderer = new PhpRenderer('templates/root/');

    $month_name = $request->getParams()['month'];
    $year_name = $request->getParams()['year'];
    $team_name = (String)$request->getParams()['team'];
    
    $query = "SELECT family_id, cash_name FROM master_general_view where
             month_name = '$month_name' AND year_name = '$year_name' AND team_name = '$team_name';";
    
    // echo $query;
    // exit;
    $result = RunQuery($query);
    
    $data= array();
    
    while ($row = mysqli_fetch_array($result))
    {
        $family_id = $row['family_id']; // this family id 
        $fam_detail_q = "SELECT main_name, verifying_question, partner_name, main_id, partner_id,
                            members_num, children, home_phone, aid_phone, mobile_phone,
                            address1, address2, city, state, poverty_rate, ref, general_note, team_note FROM families_view
                            WHERE id = $family_id;";
        $fam_result = RunQuery($fam_detail_q);
        $fam_row=mysqli_fetch_array($fam_result);

        $children = $fam_row['children'];
        $address1 = $fam_row['address1'];
        $address2 = $fam_row['address2'];

        // children
        if($children != NULL){
            $children = "<hr style=' margin:0 !important;'>" .$fam_row['children'];
        }
        // address1
        if($address1 != NULL){
            $address1 = " <br />".$fam_row['address1'];
        }
        // city
        if($address2 != NULL){
            $address2 = " <br />".$fam_row['address2'];
        }

        $row1 = array(
            $family_id. "<br /><br />". "مع.م <input type='checkbox'><br />بدون.م <input type='checkbox'>",
            $fam_row['verifying_question'],
            $fam_row['main_name']. " <br />". $fam_row['partner_name'],
            $fam_row['main_id']. " <br />". $fam_row['partner_id'],
            $fam_row['home_phone']. " <br />". $fam_row['aid_phone']. " <br />". $fam_row['mobile_phone'],
            $fam_row['city']. $address1 .$address2,
            $fam_row['poverty_rate'],
            $fam_row['ref'],
            $fam_row['members_num']. $children,
            $fam_row['general_note'],
            $fam_row['team_note'],
        );

        $data[] = $row1;

    }

    $pageArgs = [
        'sRootPath' => SystemURLs::getRootPath(),
        'sPageTitle' => "Team Report",
        'attributes' => ['استلام','استفسار','الاسم','الرقم الوطني','أرقام الهواتف', 'العنوان', 'التقييم','الحالة', 'عدد الأفراد',
                        'ملاحظات عامة', 'ملاحظات الفريق'],
        'results' => $data,
        'team_name' => $team_name,
        'year_name' => $year_name,
        'month_name' => $month_name,

    ];
    return $renderer->render($response, 'report.php', $pageArgs);

}