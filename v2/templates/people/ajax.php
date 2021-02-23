<?php


use ChurchCRM\Authentication\AuthenticationManager;
use ChurchCRM\dto\Classification;
use ChurchCRM\dto\SystemConfig;
use ChurchCRM\dto\SystemURLs;
use ChurchCRM\Service\MailChimpService;
if(isset($_POST['val'])){
    $val = $_POST['val'];
    echo "yup, that's true";
}else{
    echo "I don't have anything here";
}

?>