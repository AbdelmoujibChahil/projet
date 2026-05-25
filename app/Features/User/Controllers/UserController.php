<?php
namespace App\Features\User\Controllers;

use App\Http\Controllers\Controller;
use App\Features\User\Services\UserService;
use App\Features\User\Requests\StoreUserRequest;
use App\Features\User\Requests\UpdateUserRequest;
use App\Features\User\Resources\UserResource;
use Illuminate\Http\Request;
class UserController extends Controller
{
    public function allUsers(UserService $service)
    {
        return response()->json($service->getAllUsersWithStats());
    }

    public function store(StoreUserRequest $request, UserService $service)
    {
         $user = $service->create($request->validated());
        return response()->json(new UserResource($user), 201);
    }

    public function updateProfile(UpdateUserRequest $request, UserService $service)
    {
        
        return response()->json($service->update(auth()->user(),$request->validated(),$request->file('image')));
    }
    public function updatePassword(Request $request, UserService $service)
    {
        return response()->json($service->updatePassword(auth()->user(), $request->password));
    }

    public function updatePhone(Request $request, UserService $service)
    {
        return response()->json($service->updatePhone(auth()->user(), $request->phone));
    }
}