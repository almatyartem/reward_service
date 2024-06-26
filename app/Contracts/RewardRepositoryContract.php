<?php

namespace App\Contracts;

use App\Exceptions\RewardNotFound;
use App\Models\Reward;

interface RewardRepositoryContract
{
    /**
     * @param int $id
     * @return Reward|null
     * @throws RewardNotFound
     */
    public function getById(int $id) : ?Reward;

    /**
     * @param array $data
     * @return Reward
     */
    public function create(array $data) : Reward;

    /**
     * @param int $id
     * @param array $data
     * @return Reward
     * @throws RewardNotFound
     */
    public function update(int $id, array $data) : Reward;

    /**
     * @param int $id
     * @return bool
     * @throws RewardNotFound
     */
    public function delete(int $id) : bool;
}
