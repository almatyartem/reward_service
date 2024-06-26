<?php

namespace App\Services;

use App\Contracts\UserServiceContract;
use App\Http\Resources\RewardResource;

class UserServiceAdapter implements UserServiceContract
{
    public function checkUserExistence(string $uid) : bool
    {
        //TODO implement
        return true;
    }

    public function syncRewardWithUserService(string $uid, RewardResource $reward) : bool
    {
        //TODO implement
        return true;
    }
}
