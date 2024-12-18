<?php

namespace App\Actions\User\GenerateTokens;

use App\Enum\TokenAbility;
use Illuminate\Contracts\Auth\Authenticatable;

class GenerateTokensUserAction {
    public function handle(Authenticatable $user): array
    {
        $atExpireTime = now()->addMinutes(config('sanctum.expiration'));
        $rtExpireTime = now()->addMinutes(config('sanctum.rt_expiration'));

        $accessToken = $user->createToken('access_token', [TokenAbility::ACCESS_API], $atExpireTime);
        $refreshToken = $user->createToken('refresh_token', [TokenAbility::ISSUE_ACCESS_TOKEN], $rtExpireTime);

        return [
            'accessToken' => $accessToken->plainTextToken,
            'accessTokenExpires' => $atExpireTime->format('Y-m-d H:i:s'),
            'refreshToken' => $refreshToken->plainTextToken,
        ];
    }
}
