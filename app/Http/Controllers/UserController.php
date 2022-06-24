<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateInfoRequest;
use App\Http\Requests\UserUpdatePasswordRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
{
    public function index()
    {
        return User::paginate(10);
    }

    public function store(UserCreateRequest $request)
    {
        $user = User::create(
            $request->only('first_name', 'last_name', 'email')
            + ['password' => Hash::make('password')]
        );

        return \response($user, Response::HTTP_CREATED);
    }

    public function show($id)
    {
        return User::find($id);
    }

    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::find($id);
        $user->update($request->only('first_name', 'last_name', 'email'));

        return \response($user, Response::HTTP_ACCEPTED);
    }

    public function destroy($id)
    {
        User::destroy($id);

        return \response(null, Response::HTTP_NO_CONTENT);
    }

    public function updateInfo(UserUpdateInfoRequest $request)
    {
        $user = $request->user();
        $user->update($request->only('first_name', 'last_name', 'email'));

        return \response($user, Response::HTTP_ACCEPTED);
    }

    public function updatePassword(UserUpdatePasswordRequest $request)
    {
        $user = $request->user();
        $user->update([
            'password' => Hash::make($request->input('password'))
        ]);

        return \response($user, Response::HTTP_ACCEPTED);
    }
}
