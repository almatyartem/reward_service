<?php

namespace App\Services;

use App\Contracts\RewardRepositoryContract;
use App\Exceptions\RewardNotFound;
use App\Models\Reward;
use Illuminate\Support\Facades\DB;

class RewardRepository implements RewardRepositoryContract
{
    /**
     * @param int $id
     * @return Reward|null
     * @throws RewardNotFound
     */
    public function getById(int $id) : ?Reward
    {
        if($record = Reward::query()->find($id)){
            return $record;
        }

        throw new RewardNotFound($id);
    }

    /**
     * @param array $data
     * @return Reward
     */
    public function create(array $data) : Reward
    {
        return Reward::query()->create($data);
    }

    /**
     * @param int $id
     * @param array $data
     * @return Reward
     * @throws RewardNotFound
     */
    public function update(int $id, array $data) : Reward
    {
        if($record = $this->getById($id)){
            $record->update($data);

            return $record;
        }

        throw new RewardNotFound($id);
    }

    public function updateWithLock(int $id, array $data, callable $callbackForTransaction) : ?Reward
    {
        DB::beginTransaction();

        if($reward = $this->update($id, $data)){
            if(call_user_func($callbackForTransaction)){
                DB::commit();
                return $reward;
            }
        }

        DB::rollBack();

        return null;
    }

    /**
     * @param int $id
     * @return bool
     * @throws RewardNotFound
     */
    public function delete(int $id) : bool
    {
        if($record = $this->getById($id)){
            return $record->delete();
        }

        throw new RewardNotFound($id);
    }
}
