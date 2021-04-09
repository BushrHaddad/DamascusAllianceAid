<?php
//Step 1: Load KoolReport
require_once "./../../load.koolreport.php";

//Step 2: Creating Report class
class MyReport extends \koolreport\KoolReport
{
   /* public function settings()
    {
        $config = include "./../../config.php";

        return array(
            "dataSources"=>array(
                "churchcrm"=>$config["churchcrm"]
            )
        );
    }*/


    protected function settings()
    {
        $config = include "./../../config.php";
        return array(
            "dataSources"=>array(
                "churchcrm"=>$config["churchcrm"],
               /* "data"=>array(
                   // "churchcrm"=>$config["churchcrm"],
                    //"class"=>'\koolreport\datasources\ArrayDataSource',
                    "dataFormat"=>"table",
                    "data"=>array
                    (
                        array("name","age","income"),
                       // array("John",26,50000),
                        //$config["churchcrm"]
                    )
                    /*"data"=>array
                    (
                        array("name","age","income"),
                        array("John",26,50000),
                        array("Marry",29,60000),
                        array("Peter",34,100000),
                        array("Donald",28,80000),
                    )
                )*/
            )
        );
    }
     protected function setup()
    {
        $this->src('churchcrm')
        ->query("SELECT * FROM master_view")
        ->pipe(new TimeBucket(array(
            "payment_date"=>"month"
        )))
        ->pipe(new Group(array(
            "by"=>"payment_date",
            "sum"=>"amount"
        )))
        ->pipe($this->dataStore('sale_by_month'));
    } 
    /*protected function setup()
    {
        //$this->src("data")->pipe($this->dataStore("data"));
    }    */
}