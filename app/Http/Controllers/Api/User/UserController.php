<?php

namespace App\Http\Controllers\Api\User;

use App\Enum\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\LoginRequest;
use App\Http\Requests\Api\User\RegisterRequest;
use App\Models\User;
use App\UseCases\User\Create\CreateUserUseCase;
use App\UseCases\User\Create\Dto\CreateUserDto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct(
        private readonly CreateUserUseCase $createUserUseCase,
    )
    {
    }

    public function login(LoginRequest $request)
    {
        $data = $request->validated();

        $auth = Auth::guard('web')->attempt([
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        if (!$auth) {
            return response()->json([
                'message' => 'Не верный логин или пароль'
            ], 401);
        }

        $user = Auth::guard('web')->user();
        $token = $user->createToken('auth_token');

        return response()->json([
            'token' => $token->plainTextToken,
        ], 200);
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $createUserDto = (new CreateUserDto())
            ->setId(Str::uuid()->toString())
            ->setName($data['name'])
            ->setPhone($data['phone'])
            ->setPassword(bcrypt($data['password']))
            ->setRole(UserRole::CLIENT);
        $user = $this->createUserUseCase->handle($createUserDto);

        Auth::guard()->login($user);

        $token = $user->createToken('auth_token');

        return response()->json([
            'token' => $token->plainTextToken,
        ], 200);
    }
}
