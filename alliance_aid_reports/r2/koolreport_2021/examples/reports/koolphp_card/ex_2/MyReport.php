<?php
//require_once "../../../koolreport/core/autoload.php";
 
require_once "../../../load.koolreport.php";

class MyReport extends \koolreport\KoolReport
{
    public function settings()
    {
        $config = include "../../../config.php";

        return array(
            "dataSources"=>array(
                "churchcrm"=>$config["churchcrm"]
            )
        );
    }
}
