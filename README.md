# WGAPI
Basic PHP Library for handling Wargaming's Public API.

##Requirements
* PHP7 or greater
* cURL

###Compatible Games

* World of Tanks `[API_WOT]`
* World of Warplanes `[API_WOWP]`
* World of Warships `[API_WOWS]`
* World of Tanks Blitz `[API_BLITZ]`
* Wargaming.NET `[API_WGN]`

###Compatible Servers

* North America `[NA]`
* Europe `[EU]`
* Russia `[RU]`
* Asia `[ASIA]`

##Sample usage

``` php
<?php


require '../WGAPI.php';


$wgapi = new WGAPI('demo','na');
$wgapi->setAPI(API_WOT);

//basic example
$accounts = json_decode($wgapi->account_list('jayz536',2),true);

print_r($accounts);
```


###Current progress

```
->WoT Functions
        ->Account
                ->Player List
                ->Player Personal data
                ->Player's vehicles
                ->Player's achievements
->WOWP Functions
        ->Account
                ->Player List
                ->Player Personal data
                ->Player's vehicles
->WOWS Functions
        ->Account
                ->Player List
                ->Player Personal data
                ->Player's achievements
->BLITZ Functions
        ->Account 
                ->Player List
                ->Player Personal data
                ->Player's achievements
->WGN Functions
        ->Account
                ->Player List
                ->Player Personal data
                
```