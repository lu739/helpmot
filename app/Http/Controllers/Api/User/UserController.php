<?php

namespace App\Http\Controllers\Api\User;

use App\Enum\TokenAbility;
use App\Enum\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\LoginRequest;
use App\Http\Requests\Api\User\RegisterRequest;
use App\UseCases\User\Create\CreateUserUseCase;
use App\UseCases\User\Create\Dto\CreateUserDto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
            'phone' => $data['phone'],
            'password' => $data['password'],
        ]);

        if (!$auth) {
            return response()->json([
                'message' => 'Неверный телефон или пароль'
            ], 401);
        }

        $user = Auth::guard('web')->user();
        $tokens = $this->generateTokens($user);

        return $this->sendResponseWithTokens($tokens);
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        try {
            DB::beginTransaction();
                $createUserDto = (new CreateUserDto())
                    ->setId(Str::uuid()->toString())
                    ->setName($data['name'])
                    ->setPhone($data['phone'])
                    ->setPassword(bcrypt($data['password']))
                    ->setRole(UserRole::CLIENT);
                $user = $this->createUserUseCase->handle($createUserDto);

            DB::commit();

            Auth::guard()->login($user);

            $tokens = $this->generateTokens($user);

            return $this->sendResponseWithTokens($tokens);
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }
    }

    public function refresh(Request $request): JsonResponse
    {
        $user = Auth::user();
        $request->user()->tokens()->delete();
        $tokens = $this->generateTokens($user);

        return $this->sendResponseWithTokens($tokens);
    }

    public function generateTokens($user): array
    {
        $atExpireTime = now()->addMinutes(config('sanctum.expiration'));
        $rtExpireTime = now()->addMinutes(config('sanctum.rt_expiration'));

        $accessToken = $user->createToken('access_token', [TokenAbility::ACCESS_API], $atExpireTime);
        $refreshToken = $user->createToken('refresh_token', [TokenAbility::ISSUE_ACCESS_TOKEN], $rtExpireTime);

        return [
            'accessToken' => $accessToken->plainTextToken,
            'refreshToken' => $refreshToken->plainTextToken,
        ];
    }

    private function sendResponseWithTokens(array $tokens): JsonResponse
    {
        // $rtExpireTime = config('sanctum.rt_expiration');
        // $cookie = cookie('refreshToken', $tokens['refreshToken'], $rtExpireTime, secure: true);

        return response()->json([
            'accessToken' => $tokens['accessToken'],
            'refreshToken' => $tokens['refreshToken'],
        ]);
    }
}
