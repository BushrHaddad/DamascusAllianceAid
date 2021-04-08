<?php
require_once "SalesByCustomer.php";

$salesByCustomer = new SalesByCustomer;
$salesByCustomer->run()->render();