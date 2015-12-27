<?php
define('API_WOT',['wot','worldoftanks','tanks']);
define('API_BLITZ',['wotb','wotblitz','tanks']);
define('API_WOWP',['wowp','worldofwarplanes','planes']);
define('API_WOWS',['wows','worldofwarships','ships']);
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
    private $apiMethod          = NULL;

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
     * Set the method for querying the API
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
     * @param array $api - API to use [VALID Constants:  API_WOT, API_BLITZ, API_WOWP, API_WOWS, API_WGN]
     */
    public function setAPI(array $api){
        if(count($api) < 2 )
            throw new InvalidArgumentException('Game API is invalid, please check valid constants to use');

        $this->apiMethod = $api;
    }

    /**
     * account/list method
     * @param string $search - Player name to search
     * @param int $limit - Number of results to return [Max is 100]
     * @param array $fields - List of response fields to use
     * @param string $type - Search type [VALID: startswith, exact]
     * @return string  - Returns a Json response from the API
     */
    public function account_list(string $search, int $limit = null, array $fields = null,string $type = null): string{
        $request_data = array('search' => $search);
        if($limit <100)
            $request_data['limit'] = $limit;

        if(count($fields) > 0 )
            $request_data['fields'] = $fields;

        if($type == 'exact'){
            $request_data['type'] = $type;
        }

        return $this->getRequest(sprintf($this->api_format,$this->apiMethod[1],$this->tld,$this->apiMethod[0],'account','list'),$request_data);
    }

    /**
     * Get Player(s) Personal data
     * @param $account_id - A single player or list players
     * @param array|null $fields - List of response fields to use
     * @param array|null $extra - Extra fields to query
     * @return string - Returns a Json response from the API
     */
    public function account_info($account_id, array $fields = null, array $extra = null): string{
        $request_data = array('account_id' => $account_id);

        if(count($fields) > 0)
            $request_data['fields'] = $fields;
        if(count($extra) > 0)
            $request_data['extra'] = $extra;
        if($this->access_token !=null)
            $request_data['access_token'] = $this->access_token;

        return $this->getRequest(sprintf($this->api_format,$this->apiMethod[1],$this->tld,$this->apiMethod[0],'account','info'),$request_data);
    }

    /**
     * Get Player(s) Vehicles [Not Detailed Stats! Only works for API_WOT and API_WOWP ]
     * @param mixed $account_id - A single player or list players
     * @param mixed $tank_id - A single tank id or list of tank ids
     * @param array|null $fields - List of response fields to use
     * @return string - Returns a Json response from the API
     */
    public function account_vehicles($account_id, $tank_id = null, array $fields = null){
        $request_data = array('account_id' => $account_id);

        if(count($fields) > 0)
            $request_data['fields'] = $fields;
        if($tank_id !=null)
            $request_data['tank_id'] = $tank_id;
        if($this->access_token !=null)
            $request_data['access_token'] = $this->access_token;

        return $this->getRequest(sprintf($this->api_format,$this->apiMethod[1],$this->tld,$this->apiMethod[0],'account',$this->apiMethod[2]),$request_data);
    }

    /**
     * Get Player(s) Achievements
     * @param mixed $account_id - A single player or list players
     * @param array|null $fields - List of response fields to use
     * @return string - Returns a Json response from the API
     */
    public function account_achievements($account_id, array $fields = null){
        $request_data = array('account_id' => $account_id);

        if(count($fields) > 0)
            $request_data['fields'] = $fields;

        return $this->getRequest(sprintf($this->api_format,$this->apiMethod[1],$this->tld,$this->apiMethod[0],'account','achievements'),$request_data);
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

        if(isset($data['fields']))
            $data['fields'] = implode(',', $data['fields']);    //Converts array of fields into one line string
        if(isset($data['extra']))
            $data['extra'] = implode(',', $data['extra']);

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