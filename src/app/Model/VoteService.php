<?php
namespace App\Model;

use Ixudra\Curl\Facades\Curl;
use Cache;
use Log;

class VoteService extends Curl
{
    /**
     * @var string
     */
    private $serviceUrl;
    
    function __construct() {
        $this->serviceUrl = env('VOTE_SERVICE_ROUTE', "localhost");
    }

    /**
     * @param $voteId
     * @param $token
     * @return mixed
     */
    public function get($voteId, $token)
    {
        $response = self::to($this->serviceUrl."/api/votes/get?voteId=".$voteId)
                ->withHeader('Authorization: Bearer '.$token)
                ->returnResponseObject()
                ->get();

        return $response;
    }
}

