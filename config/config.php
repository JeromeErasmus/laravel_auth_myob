<?php

/*
 * You can place your custom package configuration in here.
 */
return [
    'client_id'     => env('MYOB_CLIENT_ID', 'myob-client-id'),
    'client_secret' => env('MYOB_CLIENT_SECRET', 'myob-client-secret'),
    'grant_type'    => env('MYOB_GRANT_TYPE', 'authorization_code'),
    'scope_type'    => env('MYOB_SCOPE_TYPE', 'CompanyFile'),
    'redirect_uri'  => env('MYOB_REDIRECT_URI', '/login/myob')
];