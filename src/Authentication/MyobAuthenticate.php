<?php

namespace Creativecurtis\Laramyob\Authentication;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Creativecurtis\Laramyob\Request\MyobRequest;

class MyobAuthenticate {

    protected $client_id;
    protected $client_secret;
    protected $scope_type;
    protected $redirect_uri;
    protected $myobRequest;

    public function __construct(MyobRequest $myobRequest) 
    {
        $this->client_id     = config('laramyob.client_id');
        $this->client_secret = config('laramyob.client_secret');
        $this->scope_type    = config('laramyob.scope_type');
        $this->redirect_uri  = config('app.url').'/'.config('laramyob.redirect_uri');
        $this->myobRequest   = $myobRequest;
    }

    /**
     * Redirect the user to the authentication point for MYOB
     *
     * @return  redirect
     */
    public function getCode()
    {
        return 'https://secure.myob.com/oauth2/account/authorize?client_id='.$this->client_id.'&redirect_uri='.urlencode($this->redirect_uri).'&response_type=code&scope='.$this->scope_type;
    }

    /**
     * On redirect from either a refresh or a authorization, store the config
     *
     * @param Illuminate\Http\Request $reqest
     * @param String $grant_type 
     * @param String $refresh_token
     * @return Response
     */
    public function getToken(Request $request = null, $grant_type = 'authorization_code', $refresh_token = null)
    {
        $http_attributes = [
            'headers'     => [
                'Content-Type' => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'code'          => $request ? $request['code'] : null,
                'grant_type'    => $grant_type, 
                'refresh_token' => $refresh_token,
                'redirect_uri'  => $this->redirect_uri, 
                'scope'         => $this->scope_type, 
                'client_id'     => $this->client_id, 
                'client_secret' => $this->client_secret
            ],
        ];
        
        $response = $this->myobRequest->sendPostRequest('https://secure.myob.com/oauth2/v1/authorize', $http_attributes);
        
        $data = json_decode($response->getBody()->getContents(), true);
        
        if(!$data) {
            return false;
        }

        return [
            'access_token'  => $data['access_token'],
            'refresh_token' => $data['refresh_token'],
            'scope'         => $data['scope'],
            'expires_at'    => Carbon::now()->addSeconds($data['expires_in']),
        ];
    }

    /**
     * Refresh the token for the MYOB bearer
     *
     * @param String $refreshToken 
     * @return Response
     */
    public function getRefreshToken(String $refreshToken)
    {
        if (!$refreshToken)
            return false;
        return $this->getToken(null, 'refresh_token', $refreshToken);
    }
}
