<?php

require '../WGAPI.php';


$wgapi = new WGAPI('demo','na'); //demo should never be used in production, much slower!

$account_id = '1000645432';

///////////////////
/* BASIC EXAMPLE */
///////////////////

$account = json_decode($wgapi->account_info(API_WOT,$account_id),true);

echo "<pre>";
print_r($account);
echo "</pre>";



echo $account['data'][$account_id]['clan_id']; //to get clan_id only



////////////////////
/* FIELDS EXAMPLE */
////////////////////


$account = json_decode($wgapi->account_info(API_WOT,$account_id,array('clan_id','statistics.all.battles')),true);


echo "<pre>";
print_r($account);
echo "</pre>";


////////////////////////
/* EXTRA ONLY EXAMPLE */
////////////////////////

$account = json_decode($wgapi->account_info(API_WOT,$account_id,array('statistics.globalmap_absolute'),array('statistics.globalmap_absolute')),true);

echo "<pre>";
print_r($account);
echo "</pre>";