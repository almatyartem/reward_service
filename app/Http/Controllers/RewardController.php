<?php

namespace App\Http\Controllers;

use App\Contracts\CacheServiceContract;
use App\Contracts\RewardRepositoryContract;
use App\Contracts\UserServiceContract;
use App\Http\Requests\AttachRewardToUserRequest;
use App\Http\Requests\StoreRewardRequest;
use App\Http\Requests\UpdateRewardRequest;
use App\Http\Resources\RewardResource;
use App\Models\Reward;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(
 *    title="Reward Service Swagger",
 *    version="1.0.0",
 * )
 * @OA\SecurityScheme(
 *      type="apiKey",
 *      in="header",
 *      securityScheme="api_key_security",
 *      name="Authorization",
 *  )
 */

class RewardController extends Controller
{
    public function __construct(
        readonly RewardRepositoryContract $repository,
        readonly UserServiceContract $userService,
        readonly CacheServiceContract $cacheService
    ){}

    /**
     * @OA\Post(
     *     path="/api/reward",
     *     operationId="createReward",
     *     tags={"Create reward"},
     *     summary="Create reward",
     *     description="Reward Creation Endpoint",
     *     security = {{"api_key_security": {}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"details"},
     *                 @OA\Property(property="details",type="string",example="{""title"":""test reward""}"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *       response="200",
     *       description="Reward created Successfully",
     *       @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function store(StoreRewardRequest $request) : JsonResponse
    {
        return response()->json(new RewardResource($this->repository->create($request->validated())));
    }

    /**
     * @OA\Get(
     *     path="/api/reward/{id}",
     *     operationId="showReward",
     *     tags={"Retrieve reward"},
     *     summary="Retrieve reward",
     *     description="Reward Retrieve Endpoint",
     *     security = {{"api_key_security": {}}},
     *     @OA\Parameter(
     *          name="id",
     *          description="Reward id",
     *           in = "path",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )),
     *     @OA\Response(
     *       response="200",
     *       description="Reward retrieved",
     *       @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Record not found",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function show(string $id) : JsonResponse
    {
        $id = (int)$id;
        if(!$resource = $this->cacheService->get(Reward::cacheKey($id))){
            $resource = new RewardResource($this->repository->getById($id));
            $this->cacheService->set(Reward::cacheKey($id), $resource);
        }

        return response()->json($resource);
    }

    /**
     * @OA\Put(
     *     path="/api/reward/{id}",
     *     operationId="updateReward",
     *     tags={"Update reward"},
     *     summary="Update reward",
     *     description="Reward Update Endpoint",
     *     security = {{"api_key_security": {}}},
     *     @OA\Parameter(
     *           name="id",
     *           description="Reward id",
     *            in = "path",
     *           required=true,
     *           @OA\Schema(
     *               type="integer"
     *           )),
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"details"},
     *                 @OA\Property(property="details",type="string",example="{""title"":""test reward""}"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *       response="200",
     *       description="Reward updated Successfully",
     *       @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="Record not found",
     *          @OA\JsonContent()
     *      ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function update(UpdateRewardRequest $request, string $id) : JsonResponse
    {
        return response()->json(new RewardResource($this->repository->update((int)$id, $request->validated())));
    }

    /**
     * @OA\Delete(
     *     path="/api/reward/{id}",
     *     operationId="deleteReward",
     *     tags={"Delete reward"},
     *     summary="Delete reward",
     *     description="Reward Delete Endpoint",
     *     security = {{"api_key_security": {}}},
     *          @OA\Parameter(
     *            name="id",
     *            description="Reward id",
     *             in = "path",
     *            required=true,
     *            @OA\Schema(
     *                type="integer"
     *            )),
     *     @OA\Response(
     *       response="200",
     *       description="Reward deleted",
     *       @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *         response="404",
     *         description="Record not found",
     *         @OA\JsonContent()
     *     ),
     * )
     */
    public function destroy(string $id) : JsonResponse
    {
        $this->repository->delete((int)$id);

        return response()->json(null, 204);
    }

    /**
     * @OA\Post(
     *     path="/api/reward/{id}/attach_to_user",
     *     operationId="attachReward",
     *     tags={"Attach user to reward"},
     *     summary="Attach user to reward",
     *     description="Attach user to reward Endpoint",
     *     security = {{"api_key_security": {}}},
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"uid"},
     *                 @OA\Property(property="uid",type="string",example="123"),
     *             ),
     *         ),
     *     ),
     *     @OA\Response(
     *       response="200",
     *       description="Reward attached Successfully",
     *       @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *          response="404",
     *          description="Record not found",
     *          @OA\JsonContent()
     *      ),
     *     @OA\Response(
     *         response="422",
     *         description="Validation error",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(
     *          response="500",
     *          description="Server error",
     *          @OA\JsonContent()
     *      ),
     * )
     */
    public function attachToUser(string $id, AttachRewardToUserRequest $request) : JsonResponse
    {
        if(!$this->userService->checkUserExistence($request->uid)){
            return response()->json([
                'success' => false,
                'message' => "User with uid ".$request->uid." not found"
            ], 404);
        }

        $uid = $request->uid;
        $reward = $this->repository->getById((int)$id);

        if($result = $this->repository->updateWithLock((int)$id, $request->validated(),
            function() use ($uid, $reward){
                return $this->userService->syncRewardWithUserService($uid, new RewardResource($reward));
            })
        ) {
            return response()->json(new RewardResource($result));
        }

        return response()->json([
            'success' => false,
            'message' => "Try later"
        ], 500);
    }
}
