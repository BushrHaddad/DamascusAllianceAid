<?php

use ChurchCRM\Authentication\AuthenticationManager;
use ChurchCRM\dto\SystemConfig;
use ChurchCRM\dto\SystemURLs;
use Propel\Runtime\ActiveQuery\Criteria;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Views\PhpRenderer;


$app->group('/newcomponent', function () {
    $this->get('/', 'newComp');
    $this->get('', 'newComp');
    $this->get('/{id}', 'newComp');
});

function newComp(Request $request, Response $response, array $args) {
    
    $added_comp = $args["id"];
    if($added_comp == NULL){
        $added_comp = "new_year";
    }
    $pageArgs = [
        'sRootPath' => SystemURLs::getRootPath(),
        'sPageTitle' => "Adding New Components",
        'added_comp' => $added_comp,
    ];

    $renderer = new PhpRenderer('templates/root/');

    return $renderer->render($response, 'new-comp.php', $pageArgs);
}
