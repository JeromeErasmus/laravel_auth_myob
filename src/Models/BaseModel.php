<?php

namespace Creativecurtis\Laramyob\Models;

use Creativecurtis\Laramyob\Request\MyobRequest;
use Creativecurtis\Laramyob\Exceptions\MyobConfigurationException;
use Creativecurtis\Laramyob\Models\Configuration\MyobConfiguration;

abstract class BaseModel {

    public $endpoint;
    public $myobRequest;
    public $baseurl;
    public $paginated = false;
    public $paginationStep = 400;

    public function __construct() 
    {
        if(MyobConfiguration::first()) {
            $myobConfiguration = MyobConfiguration::first();
            $this->myobRequest = new MyobRequest([
                'Authorization' => 'Bearer '.$myobConfiguration->access_token,
                'x-myobapi-version' => 'v2',
                'x-myobapi-key' => config('laramyob.client_id'),
                'x-myobapi-cftoken' => $myobConfiguration->company_file_token,
                'Accept' => 'application/json',
                'Content-Type' =>'application/json',
            ]);
            $this->baseurl = MyobConfiguration::first()->company_file_uri;
        } else {
            throw MyobConfigurationException::myobConfigurationNotFoundException();
        }
        
    }

    public function load($page = 1)
    {
        //
        if(!$this->paginated) {
            $response = $this->myobRequest->sendGetRequest($this->baseurl.$this->endpoint);
            return json_decode($response->getBody()->getContents(), true);
        } else {
            return $this->page($page);
        }
        
    }

    public function page($page = 1)
    {
        //offset because MYOB paginate doesn't start at 1 -.-
        $skip = $this->paginationStep * ($page - 1);
        $response = $this->myobRequest
                         ->sendGetRequest($this->baseurl.$this->endpoint.'?$top=400&$skip='.$skip);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function loadByUid($uid)
    {
        $response = $this->myobRequest
                         ->sendGetRequest($this->baseurl.$this->endpoint.'/'.$uid);
        return json_decode($response->getBody()->getContents(), true);
    }

    public function whereEmail($value) 
    {
        $this->endpoint = $this->endpoint."/?\$filter=Addresses/any(x: x/Email eq '".$value."')";
        return $this;
    }

    public function get() 
    {
        $response = $this->myobRequest
                         ->sendGetRequest($this->baseurl.$this->endpoint);

        return json_decode($response->getBody()->getContents(), true);
    }
}