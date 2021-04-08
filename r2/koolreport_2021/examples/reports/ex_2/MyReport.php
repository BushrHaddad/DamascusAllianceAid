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

    protected function setup()
    {
        $qqq=$_GET['query']; //echo $qqq;
        $this->src('churchcrm')->query($qqq)//SELECT family_name,fam_add FROM master_view")
            ->pipe(new Filter(
                array(
                "family_name"=>"Name"
                )
            )
        )
        ->pipe(new Filter(array(
            "fam_add"=>"Address",
            //"sum"=>"famadd"
        )))
        ->pipe($this->dataStore('churchcrm2'));
    } 
}
