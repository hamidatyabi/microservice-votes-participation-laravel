<?php
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class VoteToUser extends Model
{
    protected $connection = 'mysql';
    protected $table = 'votes_to_users';

    const ORDERS = ['id'];
    const DIRECTIONS = ['asc', 'desc'];

    public $timestamps = false;
    
    public static function getVoteIdAndUserId($userId, $voteId)
    {
        $key = "votes_to_users:$userId:$voteId";
        $value = Cache::store('redis')->get($key);
        if($value == null){
            $vote = self::where([['user_id', $userId],['vote_id', $voteId]])->first();
            if($vote == null) return $vote;
            Cache::store('redis')->forever($key, $vote->original);
            return $vote->original;
        }
        return $value;
    }
    
    public static function saveVote($vote)
    {
        $key = "votes_to_users:".$vote->user_id.":".$vote->vote_id;
        $voteInserted = $vote->save();
        if($voteInserted){
            Cache::store('redis')->forever($key, $vote->original);
            return $vote;
        }
        return false;
    }
}

