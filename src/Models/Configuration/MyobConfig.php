<?php

namespace Creativecurtis\Laramyob\Models\Configuration;

class MyobConfig {

    public $access_token;
    
    public $company_file_token;
    
    public $company_file_uri;

    public $company_file_name;

    public $company_file_guid;

    public $expires_at;

    public $refresh_token;

    public $scope;

    public $version;

    public function  __construct(String $access_token=null, 
                                 String $company_file_token=null,
                                 String $company_file_uri=null,
                                 String $company_file_name=null,
                                 String $company_file_guid=null,
                                 String $refresh_token=null,
                                 String $expires_at=null,
                                 String $scope=null,
                                 String $version=null)
    {
        $this->access_token = $access_token;
        $this->company_file_token = $company_file_token;
        $this->company_file_uri = $company_file_uri;
        $this->company_file_name = $company_file_name;
        $this->company_file_guid = $company_file_guid;
        $this->refresh_token = $refresh_token;
        $this->expires_at = $expires_at;
        $this->scope = $scope;
        $this->version = $version;
    }

    public function update(array $data)
    {
        foreach($data as $item => $value) {
            if(property_exists($this, $item)) {
                $this->{$item} = $value;
            }
        }

        return true;
    }

}