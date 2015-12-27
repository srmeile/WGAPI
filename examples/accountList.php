<?php

require '../WGAPI.php';


$wgapi = new WGAPI('demo','na'); //demo should never be used in production, much slower!


//basic example
$accounts = json_decode($wgapi->account_list(API_WOT,'jayz536',2),true);

print_r($accounts);

echo $accounts['data'][0]['account_id']; //to get account_id only - [0] is for closest matching result


//Exact match and only return account_id

$accounts = json_decode($wgapi->account_list(API_WOT,'jayz536',2,array('account_id'),'exact'),true);

print_r($accounts);