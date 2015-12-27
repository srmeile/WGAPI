<?php

require '../WGAPI.php';


$wgapi = new WGAPI('demo','na'); //demo should never be used in production, much slower!

$wgapi->setAPI(API_WOT);
$account_id = '1000645432';

///////////////////
/* BASIC EXAMPLE */
///////////////////

$account = json_decode($wgapi->account_vehicles($account_id),true);

echo "<pre>";
print_r($account);
echo "</pre>";


///////////////////////////
/* SPECIFIC TANK EXAMPLE */
///////////////////////////


$account = json_decode($wgapi->account_vehicles($account_id,2817),true);


echo "<pre>";
print_r($account);
echo "</pre>";

