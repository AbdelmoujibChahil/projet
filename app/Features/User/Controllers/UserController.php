<?php
namespace App\Features\User\Controllers;

use App\Http\Controllers\Controller;
use App\Features\User\Services\UserService;
use App\Features\User\Requests\StoreUserRequest;
use App\Features\User\Requests\UpdateUserRequest;
use App\Features\User\Resources\UserResource;
use Illuminate\Http\Request;
/**
 * @OA\Tag(
 *     name="Users",
 *     description="User management endpoints"
 * )
 */
class UserController extends Controller
{
        /**
     * @OA\Get(
     *     path="/api/v1/users",
     *     summary="Get all users",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="List of users"
     *     )
     * )
     */
    public function allUsers(UserService $service)
    {
        return response()->json($service->getAllUsersWithStats());
    }
 /**
     * @OA\Post(
     *     path="/api/v1/users",
     *     summary="Create a new user",
     *     tags={"Users"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", example="Salim"),
     *             @OA\Property(property="email", type="string", example="salim@gmail.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User created successfully"
     *     )
     * )
     */
    public function store(StoreUserRequest $request, UserService $service)
    {
         $user = $service->create($request->validated());
        return response()->json(new UserResource($user), 201);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/profile/update",
     *     summary="Update authenticated user profile",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(
     *                     property="image",
     *                     type="string",
     *                     format="binary"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profile updated successfully"
     *     )
     * )
     */
    public function updateProfile(UpdateUserRequest $request, UserService $service)
    {
        
        return response()->json($service->update(auth()->user(),$request->validated(),$request->file('image')));
    }
     /**
     * @OA\Put(
     *     path="/api/v1/profile/password",
     *     summary="Update user password",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"password"},
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 example="NewPassword123!"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Password updated successfully"
     *     )
     * )
     */
    public function updatePassword(Request $request, UserService $service)
    {
        return response()->json($service->updatePassword(auth()->user(), $request->password));
    }
    /**
     * @OA\Put(
     *     path="/api/v1/profile/phone",
     *     summary="Update user phone number",
     *     tags={"Users"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"phone"},
     *             @OA\Property(
     *                 property="phone",
     *                 type="string",
     *                 example="0662172930"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Phone updated successfully"
     *     )
     * )
     */
    public function updatePhone(Request $request, UserService $service)
    {
        return response()->json($service->updatePhone(auth()->user(), $request->phone));
    }
}