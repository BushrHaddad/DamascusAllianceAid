<?php

use ChurchCRM\Authentication\AuthenticationManager;
use ChurchCRM\dto\PeopleCustomField;
use ChurchCRM\dto\SystemURLs;
use ChurchCRM\FamilyCustomMasterQuery;
use ChurchCRM\FamilyCustomQuery;
use ChurchCRM\FamilyQuery;
use ChurchCRM\PropertyQuery;
use ChurchCRM\Service\TimelineService;
use ChurchCRM\Utils\InputUtils;
use Propel\Runtime\ActiveQuery\Criteria;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\PhpRenderer;
use ChurchCRM\Utils\RedirectUtils;


$app->group('/family', function () {
    $this->get('','listFamilies');
    $this->get('/','listFamilies');
    $this->get('/master','getGlobalMaster');
    $this->post('/master','postGlobalMaster');
    $this->get('/not-found', 'viewFamilyNotFound');
    $this->get('/{id}', 'viewFamily');
});


function _get($table){
 
    $sSQL = "SELECT  `id`, `name` FROM $table ";
    $rsOpps = RunQuery($sSQL);

    $data= array();
    while($row = mysqli_fetch_array($rsOpps))
    {
        $row1 = array('id' => $row[0], 'name' => $row[1]);
        $data[] = $row1;
    }
    
    return $data;
    
}

function getGlobalMaster(Request $request, Response $response, array $args){

    $renderer = new PhpRenderer('templates/people/');
    $sMode = 'Active';
    // Filter received user input as needed
    if (isset($_GET['mode'])) {
        $sMode = InputUtils::LegacyFilterInput($_GET['mode']);
    }

    if (strtolower($sMode) == 'inactive') {
        $families = FamilyQuery::create()
            ->filterByDateDeactivated(null, Criteria::ISNOTNULL)
                ->orderByName()
                ->find();
    } else {
        $sMode = 'Active';
        $families = FamilyQuery::create()
            ->filterByDateDeactivated(null)
                ->orderByName()
                ->find();
    }

    $_years = _get('master_dates_year');
    $_months = _get('master_dates_months');
    $_bags = _get('master_bags');
    $_cash = _get('master_cash');
    $_suppliments = _get('master_suppliments');
    $_teams = _get('master_teams');
    $_visiting = _get('master_visiting');

    $data = Array('all_years' => $_years,'all_months' => $_months, 'all_bags' => $_bags, 'all_cash' => $_cash, 'all_suppliments' =>  $_suppliments, 
    'all_teams' => $_teams, 'all_visitings' => $_visiting);

    $pageArgs = [
        'sMode' => $sMode,
        'sRootPath' => SystemURLs::getRootPath(),
        'families' => $families,
        'all_months' => $_months,
        'all_years' => $_years,
        // 'vars' => $data,
          // Bushr-todo: get family attributes from admin panel 
        'familyAttributes' => ['Actions','Name','Address','Home Phone', 'Cell Phone',
                             'Address Additional Info', 'Additional Info', 'Team Info',
                              'Ref', 'Membership Status','Home Phone', 'Cell Phone',
                              'Address Additional Info', 'Additional Info', 'Team Info',
                               'Ref', 'Membership Status']     
    
      ];

    return $renderer->render($response, 'master-list.php', $pageArgs);
}

function postGlobalMaster(Request $request, Response $response, array $args){
    // echo $request;
    $renderer = new PhpRenderer('templates/people/');
    $sMode = 'Active';
    // Filter received user input as needed
    if (isset($_GET['mode'])) {
        $sMode = InputUtils::LegacyFilterInput($_GET['mode']);
    }

    if (strtolower($sMode) == 'inactive') {
        $families = FamilyQuery::create()
            ->filterByDateDeactivated(null, Criteria::ISNOTNULL)
                ->orderByName()
                ->find();
    } else {
        $sMode = 'Active';
        $families = FamilyQuery::create()
            ->filterByDateDeactivated(null)
                ->orderByName()
                ->find();
    }

    $_years = _get('master_dates_year');
    $_months = _get('master_dates_months');
    $_bags = _get('master_bags');
    $_cash = _get('master_cash');
    $_suppliments = _get('master_suppliments');
    $_teams = _get('master_teams');
    $_visiting = _get('master_visiting');

    $data = Array('all_years' => $_years,'all_months' => $_months, 'all_bags' => $_bags, 'all_cash' => $_cash, 'all_suppliments' =>  $_suppliments, 
    'all_teams' => $_teams, 'all_visitings' => $_visiting);

    $pageArgs = [
        'sMode' => $sMode,
        'sRootPath' => SystemURLs::getRootPath(),
        'families' => $families,
        'all_months' => $_months,
        'all_years' => $_years,
        'request' => (object)$request->getParsedBody(),
        // 'vars' => $data,
          // Bushr-todo: get family attributes from admin panel 
        'familyAttributes' => ['Actions','Name','Address','Home Phone', 'Cell Phone',
                             'Address Additional Info', 'Additional Info', 'Team Info',
                              'Ref', 'Membership Status','Home Phone', 'Cell Phone',
                              'Address Additional Info', 'Additional Info', 'Team Info',
                               'Ref', 'Membership Status']     
    
      ];

    return $renderer->render($response, 'master-list.php', $pageArgs);
}

function listFamilies(Request $request, Response $response, array $args)
{
  $renderer = new PhpRenderer('templates/people/');
  $sMode = 'Active';
  // Filter received user input as needed
  if (isset($_GET['mode'])) {
      $sMode = InputUtils::LegacyFilterInput($_GET['mode']);
  }
  if (strtolower($sMode) == 'inactive') {
      $families = FamilyQuery::create()
          ->filterByDateDeactivated(null, Criteria::ISNOTNULL)
              ->orderByName()
              ->find();
  } else {
      $sMode = 'Active';
      $families = FamilyQuery::create()
          ->filterByDateDeactivated(null)
              ->orderByName()
              ->find();
  }

  $pageArgs = [
      'sMode' => $sMode,
      'sRootPath' => SystemURLs::getRootPath(),
      'families' => $families,
        //  todo: get family attributes from admin panel 
        'familyAttributes' => ['Actions','Name','Address','Home Phone', 'Cell Phone',
                             'Address Additional Info', 'Additional Info', 'Team Info',
                              'Ref', 'Membership Status','Family Members', 'Children',
                              'Poverty Rate', 'Main Name', 'Partner Name',
                               'Main ID', 'Partner ID']       
    ];

  return $renderer->render($response, 'family-list.php', $pageArgs);
}

function viewFamilyNotFound(Request $request, Response $response, array $args)
{
  $renderer = new PhpRenderer('templates/common/');

  $pageArgs = [
        'sRootPath' => SystemURLs::getRootPath(),
        'memberType' => "Family",
        'id' => $request->getParam("id")
    ];

    return $renderer->render($response, 'not-found-view.php', $pageArgs);
}

function viewFamily(Request $request, Response $response, array $args)
{
    $renderer = new PhpRenderer('templates/people/');

    $familyId = $args["id"];
    $family = FamilyQuery::create()->findPk($familyId);

    if (empty($family)) {
        return $response->withRedirect(SystemURLs::getRootPath() . "/v2/family/not-found?id=".$args["id"]);
    }

    $timelineService = new TimelineService();

    $allFamilyProperties = PropertyQuery::create()->findByProClass("f");

    $allFamilyCustomFields = FamilyCustomMasterQuery::create()->find();

    // get family with all the extra columns created
    $rawQry =  FamilyCustomQuery::create();
    foreach ($allFamilyCustomFields as $customfield ) {
        $rawQry->withColumn($customfield->getField());
    }
    $thisFamilyCustomFields = $rawQry->findOneByFamId($familyId);

    if ($thisFamilyCustomFields) {
        $familyCustom = [];
        foreach ($allFamilyCustomFields as $customfield ) {
            if (AuthenticationManager::GetCurrentUser()->isEnabledSecurity($customfield->getFieldSecurity())) {
                $value = $thisFamilyCustomFields->getVirtualColumn($customfield->getField());
                if (!empty($value)) {
                    $item = new PeopleCustomField($customfield, $value);
                    array_push($familyCustom, $item);
                }
            }
        }
    }
    
    // $_bags = _get('master_bags');
    // $_cash = _get('master_cash');
    $_years = _get('master_dates_year');
    // $_months = _get('master_dates_months');
    // $_teams = _get('master_teams');
    // $_visiting = _get('master_visiting');


    $pageArgs = [
        'sRootPath' => SystemURLs::getRootPath(),
        'family' => $family,
        'familyTimeline' => $timelineService->getForFamily($family->getId()),
        'allFamilyProperties' => $allFamilyProperties,
        'familyCustom' => $familyCustom,
        'all_years' => $_years,
    ];

    return $renderer->render($response, 'family-view.php', $pageArgs);

}