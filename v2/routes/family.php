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

// Server-Side
$app->group('/family', function () {
    
    $this->get('','listFamilies');
    $this->get('/','listFamilies');
    $this->get('/master','getGlobalMaster');
    $this->post('/master','postGlobalMaster');
    $this->get('/not-found', 'viewFamilyNotFound');
    $this->get('/{id}', 'viewFamily');
});

// Client-Side
$app->group('/local_family', function () {
    $this->get('','listFamiliesLocally');
    $this->get('/','listFamiliesLocally');
    $this->get('/master','getGlobalMasterLocally');
    $this->post('/master','postGlobalMasterLocally');
    $this->get('/not-found', 'viewFamilyNotFound');
    $this->get('/{id}', 'viewFamily');
});


// get id, name from $table
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

$family_attributes = ['Action','Id','Old Id','p','Main Name', 'Main Id', 'Partner Name', 'Partner Id', "Rate",
'Address','Address2', 'Region', 'State', 'Home Phone', 'Aid Phone', 'Mobile Phone','Status', 'Aid Notes', 'General Note', 
"Team Note", "Ref", "Membership status", "Members Number", "Children", "Financial Support", "Other Notes", "Question"];

// Server-Side: get master-list
function getGlobalMaster(Request $request, Response $response, array $args){
    $renderer = new PhpRenderer('templates/people/');

    $_years = _get('master_dates_year');
    $_months = _get('master_dates_months');

    $pageArgs = [
        'sMode' => $sMode,
        'sRootPath' => SystemURLs::getRootPath(),
        'all_months' => $_months,
        'all_years' => $_years,
        'familyAttributes' =>  ['Action','Id','Old Id','p','Main Name', 'Main Id', 'Partner Name', 'Partner Id', "Rate", "State", "Region",
        'Address1','Address2', 'Home Phone', 'Aid Phone', 'Mobile Phone','Status', 'Aid Notes', 'General Note', 
        "Team Note", "Ref", "Membership status", "Members Number", "Children", "Financial Support", "Other Notes", "Question"],
 
      ];

    return $renderer->render($response, 'master-list.php', $pageArgs);
}

// Local-Side: get master-list
function getGlobalMasterLocally(Request $request, Response $response, array $args){
    $renderer = new PhpRenderer('templates/people/');

    $_years = _get('master_dates_year');
    $_months = _get('master_dates_months');

    $pageArgs = [
        'sMode' => $sMode,
        'sRootPath' => SystemURLs::getRootPath(),
        'all_months' => $_months,
        'all_years' => $_years,
        'familyAttributes' =>  ['Action','Id','Old Id','p','Main Name', 'Main Id', 'Partner Name', 'Partner Id', "Rate", "State", "Region",
        'Address1','Address2', 'Home Phone', 'Aid Phone', 'Mobile Phone','Status', 'Aid Notes', 'General Note', 
        "Team Note", "Ref", "Membership status", "Members Number", "Children", "Financial Support", "Other Notes", "Question"],
      ];

    return $renderer->render($response, 'master-list_local.php', $pageArgs);
}

// Server-Side: post master-list
function postGlobalMaster(Request $request, Response $response, array $args){
    $renderer = new PhpRenderer('templates/people/');

    $_years = _get('master_dates_year');
    $_months = _get('master_dates_months');

    $pageArgs = [
        'sMode' => $sMode,
        'sRootPath' => SystemURLs::getRootPath(),
        'all_months' => $_months,
        'all_years' => $_years,
        'familyAttributes' =>  ['Action','Id','Old Id','p','Main Name', 'Main Id', 'Partner Name', 'Partner Id', "Rate", "State", "Region",
        'Address1','Address2', 'Home Phone', 'Aid Phone', 'Mobile Phone','Status', 'Aid Notes', 'General Note', 
        "Team Note", "Ref", "Membership status", "Members Number", "Children", "Financial Support", "Other Notes", "Question"],
        'request' => (object)$request->getParsedBody()
      ];

    return $renderer->render($response, 'master-list.php', $pageArgs);
}

// Local-Side: post master-list
function postGlobalMasterLocally(Request $request, Response $response, array $args){
    $renderer = new PhpRenderer('templates/people/');
    $_years = _get('master_dates_year');
    $_months = _get('master_dates_months');

    $pageArgs = [
        'sMode' => $sMode,
        'sRootPath' => SystemURLs::getRootPath(),
        'all_months' => $_months,
        'all_years' => $_years,
        'request' => (object)$request->getParsedBody(),
        'familyAttributes' =>  ['Action','Id','Old Id','p','Main Name', 'Main Id', 'Partner Name', 'Partner Id', "Rate", "State", "Region",
        'Address1','Address2', 'Home Phone', 'Aid Phone', 'Mobile Phone','Status', 'Aid Notes', 'General Note', 
        "Team Note", "Ref", "Membership status", "Members Number", "Children", "Financial Support", "Other Notes", "Question"],
      ];

    return $renderer->render($response, 'master-list_local.php', $pageArgs);
}

// Server-Side: family List
function listFamilies(Request $request, Response $response, array $args)
{
    
  $renderer = new PhpRenderer('templates/people/');

  if (isset($_GET['mode'])) {
      $sMode = InputUtils::LegacyFilterInput($_GET['mode']);
  }

  $pageArgs = [
    'sMode' => $sMode,
    'sRootPath' => SystemURLs::getRootPath(),
    'familyAttributes' =>  ['Action','Id','Old Id','p','Main Name', 'Main Id', 'Partner Name', 'Partner Id', "Rate", "State", "Region",
        'Address1','Address2', 'Home Phone', 'Aid Phone', 'Mobile Phone','Status', 'Aid Notes', 'General Note', 
        "Team Note", "Ref", "Membership status", "Members Number", "Children", "Financial Support", "Other Notes", "Question"],
    ];

  return $renderer->render($response, 'family-list.php', $pageArgs);
}

// Local-Side: family list
function listFamiliesLocally(Request $request, Response $response, array $args)
{
  $renderer = new PhpRenderer('templates/people/');

  if (isset($_GET['mode'])) {
      $sMode = InputUtils::LegacyFilterInput($_GET['mode']);
  }

  $pageArgs = [
    'sMode' => $sMode,
    'sRootPath' => SystemURLs::getRootPath(),
    'familyAttributes' =>  ['Action','Id','Old Id','p','Main Name', 'Main Id', 'Partner Name', 'Partner Id', "Rate", "State", "Region",
    'Address1','Address2', 'Home Phone', 'Aid Phone', 'Mobile Phone','Status', 'Aid Notes', 'General Note', 
    "Team Note", "Ref", "Membership status", "Members Number", "Children", "Financial Support", "Other Notes", "Question"],

    ];

  return $renderer->render($response, 'family-list_local.php', $pageArgs);
}

function viewFamily(Request $request, Response $response, array $args)
{
    // return "Hello world";
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

    $_years = _get('master_dates_year');
  
    $pageArgs = [
        'sRootPath' => SystemURLs::getRootPath(),
        'family' => $family,
        'familyTimeline' => $timelineService->getForFamily($family->getId()),
        'allFamilyProperties' => $allFamilyProperties,
        'familyCustom' => $familyCustom,
        'all_years' => $_years,
    ];
    // echo "Hello wolrd";

    return $renderer->render($response, 'family-view.php', $pageArgs);
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

