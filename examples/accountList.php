<?php

require '../WGAPI.php';


$wgapi = new WGAPI('demo','na'); //demo should never be used in production, much slower!

$accounts = json_decode($wgapi->account_list(API_WOT,'jayz536',1,array('account_id')),true);

print_r($accounts);

echo $accounts['data'][0]['account_id']; //to get account_id only - [0] is for closest matching result