<?php
require_once "load.koolreport.php";
class MyReport extends \koolreport\KoolReport
{
    function settings()
    {
        return array(
            "dataSources"=>array(
                "automaker"=>array(
                    "connectionString"=>"mysql:host=localhost;dbname=alliance_aid",
                    "username"=>"root",
                    "password"=>"",
                    "charset"=>"utf8"
                ),
            )
        ); 
    } 
    protected function setup()
    {
        $this->src('automaker')
        ->query("SELECT fam_ID, old_id,imported_p from family_fam")
        ->pipe($this->dataStore("employees"));
    } 

}