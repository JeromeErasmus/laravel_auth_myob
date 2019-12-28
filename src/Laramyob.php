<?php

namespace Creativecurtis\Laramyob;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Creativecurtis\Laramyob\Request\MyobRequest;
use Creativecurtis\Laramyob\Authentication\MyobAuthenticate;
use Creativecurtis\Laramyob\Models\Configuration\MyobConfig;

class Laramyob
{
    public $authenticate; 
    public $myobRequest;
    public $myobConfig;

    public function __construct(MyobConfig $myobConfig)
    {
        $this->myobRequest = new MyobRequest;
        $this->authenticate = new MyobAuthenticate($this->myobRequest);
        $this->myobConfig = $myobConfig;
    }
    
    /**
     * Return the MYOB authentication class for usage
     *
     * @return MyobAuthenticate
     */
    public function authenticate() 
    {
        return $this->authenticate;
    }

    /**
     * Gets the Refresh Token and updates the config
     *
     * @return array || bool
     */
    public function getRefreshToken()
    {
        $result = $this->authenticate->getRefreshToken($this->myobConfig->refresh_token);

        if(!$result) {
            return false;
        } else {
            $this->myobConfig->update($result);
            return $result;
        }
    }

    /**
     * Take a model that extends the base model.
     * Create that model, and then load the defaults
     *
     * @return bool,
     */
    public function of($model) 
    {
        if($this->preflight()) {
            return App::makeWith($model, ['myobConfiguration' => $this->myobConfig]);
        }
    }

    /**
     * Preflight check for any request to see if we need to refresh the token
     *
     * @return MyobConfig || bool
     */
    private function preflight()
    {
        if($this->myobConfig && Carbon::now() > $this->myobConfig->expires_at) {
            return $this->getRefreshToken($this->myobConfig->refresh_token);
        } else {
            return true;
        }
    }
}
