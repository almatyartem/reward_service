<?php

namespace App\Contracts;

use App\Http\Resources\RewardResource;

interface UserServiceContract
{
    public function checkUserExistence(string $uid) : bool;

    public function syncRewardWithUserService(string $uid, RewardResource $reward) : bool;
}
