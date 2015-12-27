# WGAPI
Basic PHP Library for handling Wargaming's Public API.


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


//basic example
$accounts = json_decode($wgapi->account_list(API_WOT,'jayz536',2),true);

print_r($accounts);
```


###Current progress

```
->WoT Functions
        ->Account
                ->Player List
                ->Player Personal data
->WOWP Functions
        ->Account
                ->Player List
                ->Player Personal data
->WOWS Functions
        ->Account
                ->Player List
                ->Player Personal data
->BLITZ Functions
        ->Account 
                ->Player List
                ->Player Personal data
->WGN Functions
        ->Account
                ->Player List
                ->Player Personal data
                
```