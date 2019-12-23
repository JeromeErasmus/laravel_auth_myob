<?php

namespace Creativecurtis\Laramyob\Request;

use GuzzleHttp\Client;

class MyobRequest {

    protected $httpConfig = [];
    protected $httpClient;

    public function __construct($httpConfig = []) 
    {
      $this->httpConfig = $httpConfig;
    }

    /**
     * Get a instance of the Guzzle HTTP client.
     *
     * @return \GuzzleHttp\Client
     */
    public function getHttpClient()
    {
        if (is_null($this->httpClient)) {
            $this->httpClient = new Client(['headers' => $this->httpConfig]);
        }

        return $this->httpClient;
    }

    /**
     * Set the Guzzle HTTP client instance.
     *
     * @param  \GuzzleHttp\Client  $client
     * @return $this
     */
    public function setHttpClient(Client $client)
    {
        $this->httpClient = $client;

        return $this;
    }

    /**
     * Send the POST request to MYOB endpoint
     *
     * @param  $endpoint
     * @param  $data
     * @return  \GuzzleHttp\Client  $client
     */
    public function sendPostRequest($endpoint, $data) 
    {
        return $this->getHttpClient()->request('POST', $endpoint, $data);
    }

    /**
     * Send the GET request to MYOB endpoint
     *
     * @param  $endpoint
     * @param  $data
     * @return  \GuzzleHttp\Client  $client
     */
    public function sendGetRequest($endpoint) 
    {
        return $this->getHttpClient()->request('GET', $endpoint);    
    }
    
}