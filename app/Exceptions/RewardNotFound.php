<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RewardNotFound extends Exception
{
    public function __construct(
        readonly int $id)
    {}

    public function getId() : int
    {
        return $this->id;
    }

    public function render(): JsonResponse
    {
        return response()->json([
                'success' => false,
                'message' => "Reward with id ".$this->getId()." not found"
            ],404
        );
    }
}
