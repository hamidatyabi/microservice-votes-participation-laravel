<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

use App\Model\VoteService;
use App\Model\VoteToUser;

class VoteController extends Controller
{
    private $serviceApi;
    private $voteInfo;
    private $params;
    public function __construct(Request $request)
    {
        $this->serviceApi = new VoteService();
        $this->token = $request->bearerToken();
        $this->params = json_decode($request->getContent(), true);
        if($this->params == null) $this->params = $request->all();
    }
    public function push(Request $request)
    {
        $this->voteInfo = $this->serviceApi->get($this->params['voteId'], $request->bearerToken());
        $validator = Validator::make($this->params, [
            'voteId' => 'required|integer',
            'optionId' => 'required|integer'
        ]);
        $validator->after(function ($validator) {
            $result = json_decode($this->voteInfo->content, true);
            if($this->voteInfo->status != 200){
                $validator->errors()->add('voteId', $result['error']);
            }
            $find = false;
            foreach($result['options'] as $option){
                if($option['id'] == $this->params['optionId']){
                    $find = true;
                    break;
                }
            }
            if(!$find) $validator->errors()->add('optionId', "OptionId is incorrect");
            $this->voteInfo = $result;
        });
        if ($validator->fails()) 
            return response()->json(array(
                "error_code" => 406,
                "error" => $validator->errors()
                ), 406);
        
        ///////////////////////////////////////////////////////////
        $userVote = VoteToUser::getVoteIdAndUserId($request->get('user_id'), $this->voteInfo['id']);
        if($userVote != null){
            return response()->json(array(
                "error_code" => 406,
                "error" => "You voted this option"
                ), 406);
        }
        $vote = new VoteToUser;
        $vote->user_id = (int)$request->get('user_id');
        $vote->vote_id = (int)$this->voteInfo['id'];
        $vote->option_id = (int)$this->params['optionId'];
        $vote->assign_time = date("Y-m-d H:i:s");
        if(!VoteToUser::saveVote($vote)){
            return response()->json(array(
                "error_code" => 406,
                "error" => "Your voting has exception.maybe you voted before"
                ), 406);
        }
        ///////////////////////////////////////////////////////////
        return $vote;
    }
    
    public function check(Request $request)
    {
        $userVote = VoteToUser::getVoteIdAndUserId($request->get('user_id'), $this->params['voteId']);
        return response()->json(array("voted" => ($userVote != null)), 200);
    }
    
}
