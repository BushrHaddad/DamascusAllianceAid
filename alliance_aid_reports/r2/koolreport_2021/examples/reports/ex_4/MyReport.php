<?php
//print_r($_GET); echo 'ffffffffffffffffffff';

require_once "./../../load.koolreport.php";

use \koolreport\KoolReport;
use \koolreport\processes\Filter;
//use \koolreport\processes\TimeBucket;
use \koolreport\processes\ColumnsSort;
use \koolreport\processes\Group;
use \koolreport\processes\Limit;
//use \koolreport\widgets\koolphp\Card;

class MyReport extends \koolreport\KoolReport
//class SakilaRental extends KoolReport
{
    public function settings()
    {
        //Get default connection from config.php
        $config = include "./../../config.php";

        return array(
            "dataSources"=>array(
                "churchcrm"=>$config["churchcrm"]
            )
        );
    }
    
    protected function setup()
    {
        $qqq=$_GET['query']; echo $qqq;
        $this->src('churchcrm')->query($qqq)//SELECT family_name,fam_add FROM master_view")
            ->pipe(new Group(
                array(
                "family_name"=>"Name"
                )
            )
        )
        ->pipe(new Group(array(
            "fam_add"=>"Address",
            //"sum"=>"famadd"
        )))
        ->pipe($this->dataStore('churchcrm'));
    } 
}