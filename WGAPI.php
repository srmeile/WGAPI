<?php
define('API_WOT',['wot','worldoftanks']);
define('API_BLITZ',['wotb','wotblitz']);
define('API_WOWP',['wowp','worldofwarplanes']);
define('API_WOWS',['wows','worldofwarships']);
define('API_WGN',['wgn','worldoftanks']);
define('API_SERVERS',['na', 'eu', 'ru', 'asia']);

/**
 * Wargaming API Implementation
 * @Author S.R.
 * Date: 12/25/2015
 *
 * Simple class to communicate with Wargaming's API
 * Requirement: PHP7.0 or greater
 */
class WGAPI{

    //Required
    private $application_id     = NULL;
    private $server             = NULL;

    //Optional
    private $language       = 'en';
    private $use_https      = false;
    private $method         = 'GET';
    private $access_token   = NULL;


    //Other
    private $tld    = NULL;
    private $api_format  = 'api.%s.%s/%s/%s/%s/';


    /**
     * WGAPI constructor.
     * @param string $application_id - You application's API Key [You can use 'demo' as a placeholder but be aware that demo has a slow request/second]
     * @param string $server - Server to communicate with [Valid Servers NA, EU, RU, ASIA]
     * @throws Exception - WGAPI Class Requires cURL library to function
     */
    public function __construct(string $application_id, string $server){
        if(!function_exists('curl_version'))
            throw new Exception('WGAPI Class requires cURL library to function.');

        $this->server = strtolower($server);

        switch($this->server) {
            case 'na';
                $this->tld = 'com';
                break;
            case 'eu';
                $this->tld = $this->server;
                break;
            case 'ru';
                $this->tld = $this->server;
                break;
            case 'sea';
                $this->tld = $this->server;
                break;
            default:
                throw new InvalidArgumentException('invalid server specified');
        }

        $this->application_id = $application_id;

    }

    /**
     * Set the desired response language
     * @param string $language - The language to use [ Valid Languages EN, RU, PL, DE, FR, ES, ZH-CH, TR, CS, TH, VI, KO ]
     */
    public function setLang(string $language){
        $this->language = strtolower($language);
    }

    /**
     * Set the method for using the API
     * @param string $method - Method to use [ Valid Methods POST, GET]
     */
    public function setMethod(string $method){
        $this->method = strtoupper($method);
    }

    /**
     * Should Secured Connection be used?
     * @param bool $use_https - Whether or not to use HTTPS
     */
    public function setHttps(bool $use_https){
        $this->use_https = $use_https;
    }

    /**
     * Set access token to access private data
     * @param string $access_token - Access token is obtained from authentication
     */
    public function setAccessToken(string $access_token){
        $this->access_token = $access_token;
    }

    /**
     * account/list method
     * @param array $api - API to use [VALID Constants:  API_WOT, API_BLITZ, API_WOWP, API_WOWS, API_WGN]
     * @param string $search - Player name to search
     * @param int $limit - Number of results to return [Max is 100]
     * @param array $fields - List of response fields to use
     * @return string  - Returns a Json response from the API
     * @throws Exception - unidentified constant used
     */
    public function account_list(array $api, string $search, int $limit = 100, array $fields = array()): string{
        $request_data = array('search' => $search);
        if($limit <100)
            $request_data['limit'] = $limit;

        if(count($fields) > 0 )
            $request_data['fields'] = $fields;

        if(count($api) < 2 )
            throw new InvalidArgumentException('Game API is invalid, please check valid constants to use');

        return $this->getRequest(sprintf($this->api_format,$api[1],$this->tld,$api[0],'account','list'),$request_data);
    }

    /**
     * Function that handles web requests
     * @param string $url - Api request url
     * @param array $data - Data to send to the api
     * @return string - Returns json response from the api
     * @throws Exception - Querying API Error
     */
    private function getRequest(string $url, array $data): string{
        $this->use_https == true ? $prefix = "https://" : $prefix = "http://";

        $data['application_id'] = $this->application_id;
        $data['language'] = $this->language;

        if(isset($data['fields'])){
            $data['fields'] = implode(',', $data['fields']);    //Converts array of fields into one line string
        }

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);  //we don't need headers to be passed

        $parameters = http_build_query($data);

        if($this->method == "GET") {
            curl_setopt($curl, CURLOPT_URL, "{$prefix}{$url}?{$parameters}");
        } elseif($this->method == "POST") {
            curl_setopt($curl, CURLOPT_URL, "{$prefix}{$url}");
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $parameters);
        }

        if($this->use_https){
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  //accept any server certificate
        }

        $response = curl_exec($curl);

        if(!$response){
            throw new Exception('Error querying API. Error: '.curl_error($curl).' - Error Code: '.curl_errno($curl));
        }

        return $response;
    }


}