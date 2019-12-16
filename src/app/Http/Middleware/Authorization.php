<?php

namespace App\Http\Middleware;

use HamidAtyabi\OAuth2Client\AccessToken\AccessToken;
use HamidAtyabi\OAuth2Client\Models\TokenInfoResponse;
use HamidAtyabi\OAuth2Client\Entities\TokenInfo;
use Closure;

class Authorization
{
    private $tokenInfo;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $config = array(
            "oauth2_host" => env('OAUTH_HOST', "localhost"),
            "oauth2_port" => env('OAUTH_PORT', 80),
            "oauth2_client_id" => env('OAUTH_CLIENT_ID', "client_id"),
            "oauth2_client_secret" => env('OAUTH_CLIENT_SECRET', "client_secret"),
            "oauth2_resource_id" => env('OAUTH_RESOURCE_ID', "resource_id")
        );
        $client = new \HamidAtyabi\OAuth2Client\AccessToken\AccessToken($config);
        $this->tokenInfo = $client->validity();
        if($this->tokenInfo->getCode() != 200){
            return response()->json(array(
            "error_code" => $this->tokenInfo->getCode(),
            "error" => $this->tokenInfo->getMessage()
            ), $this->tokenInfo->getCode());
        }
        $request->request->add(['user_id' => $this->tokenInfo->getResult()->getUserId()]);
        
        return $next($request);
    }
}
