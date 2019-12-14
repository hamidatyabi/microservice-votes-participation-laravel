<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Model\VoteService;

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
        });
        if ($validator->fails()) 
            return response()->json(array(
                "error_code" => 406,
                "error" => $validator->errors()
                ), 406);
        
        ///////////////////////////////////////////////////////////
        
        ///////////////////////////////////////////////////////////
        return "OK";
    }
}
