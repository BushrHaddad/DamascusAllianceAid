<?php
require_once "../vendor/koolreport/core/autoload.php";

// require_once "../../../";
require_once "MyReport.php";

$report = new MyReport;
$report->run()->render();