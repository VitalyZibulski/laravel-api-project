<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateInfoRequest;
use App\Http\Requests\UserUpdatePasswordRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index()
    {
        $this->authorize('view', 'users'); // ability, model
        return UserResource::collection(User::with('role')->paginate(10));
    }

    public function store(UserCreateRequest $request)
    {
        $this->authorize('edit', 'users');
        $user = User::create(
            $request->only('first_name', 'last_name', 'email', 'role_id')
            + ['password' => Hash::make('password')]
        );

        return \response(new UserResource($user), Response::HTTP_CREATED);
    }

    public function show($id)
    {
        $this->authorize('view', 'users');
        return new UserResource(User::with('role')->find($id));
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $this->authorize('edit', 'users');
        $user = User::find($id);
        $user->update($request->only('first_name', 'last_name', 'email', 'role_id'));

        return \response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    public function destroy($id)
    {
        $this->authorize('edit', 'users');
        User::destroy($id);

        return \response(null, Response::HTTP_NO_CONTENT);
    }

    public function updateInfo(UserUpdateInfoRequest $request)
    {
        $user = $request->user();
        $user->update($request->only('first_name', 'last_name', 'email'));

        return \response(new UserResource($user), Response::HTTP_ACCEPTED);
    }

    public function updatePassword(UserUpdatePasswordRequest $request)
    {
        $user = $request->user();
        $user->update([
            'password' => Hash::make($request->input('password'))
        ]);

        return \response(new UserResource($user), Response::HTTP_ACCEPTED);
    }
}
