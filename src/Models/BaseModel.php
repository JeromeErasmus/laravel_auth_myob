<?php

namespace Creativecurtis\Laramyob\Models;

use ArrayAccess;
use JsonSerializable;
use Creativecurtis\Laramyob\Request\MyobRequest;
use Creativecurtis\Laramyob\Exceptions\MyobConfigurationException;
use Creativecurtis\Laramyob\Models\Configuration\MyobConfiguration;

abstract class BaseModel implements JsonSerializable, ArrayAccess {

    public $endpoint;
    public $myobRequest;
    public $baseurl;
    public $paginated = false;
    public $paginationStep = 400;
    public $data;
    
    public function __construct() 
    {
        if(MyobConfiguration::first()) {
            $myobConfiguration = MyobConfiguration::first();
            $this->myobRequest = new MyobRequest([
                'Authorization'     => 'Bearer '.$myobConfiguration->access_token,
                'x-myobapi-version' => 'v2',
                'x-myobapi-key'     => config('laramyob.client_id'),
                'x-myobapi-cftoken' => $myobConfiguration->company_file_token,
                'Accept'            => 'application/json',
                'Content-Type'      =>'application/json',
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

    public function get() 
    {
        $response = $this->myobRequest
                         ->sendGetRequest($this->baseurl.$this->endpoint);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function first() 
    {
        $response = $this->myobRequest
                         ->sendGetRequest($this->baseurl.$this->endpoint);

        return json_decode($response->getBody()->getContents(), true)['Items'][0];
    }

    public function post() 
    {
        $response = $this->myobRequest
                         ->sendPostRequest($this->baseurl.$this->endpoint, $this->data);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function create($data) {
        $this->data = $data;
        return $this;
    }

    /**
     * JSON Encode overload to pull out hidden properties
     *
     * @return string
     */
    public function jsonSerialize()
    {
        return json_encode($this);
    }

    /**
     * @param mixed $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->data);
    }

    /**
     * @param mixed $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->data[$offset];
    }

    /**
     * @param mixed $offset
     * @param mixed $value
     * @return mixed
     */
    public function offsetSet($offset, $value)
    {
        return $this->data[$offset] = $value;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->data[$offset]);
    }
}