<?php
//require_once "../../../koolreport/core/autoload.php";
 
require_once "./../../load.koolreport.php";
use \koolreport\processes\Filter;
//use \koolreport\processes\TimeBucket;
use \koolreport\processes\ColumnsSort;
use \koolreport\processes\Group;
use \koolreport\processes\Limit;

class MyReport extends \koolreport\KoolReport
{
    public function settings()
    {
        $config = include "./../../config.php";

        return array(
            "dataSources"=>array(
                "churchcrm"=>$config["churchcrm"]
            )
        );
    }

    // protected function setup()
    // {
    //     $year = $_GET['year']; //echo $qqq;
    //     $month = $_GET['month']; //echo $qqq;
    //     $team = $_GET['team']; //echo $qqq;
    //     $qqq = "SELECT * from master_family_master";
        
    //     $this->src('churchcrm')//SELECT family_name,fam_add FROM master_view")
    //     ->pipe($this->dataStore('churchcrm2'));
    // } 
}
