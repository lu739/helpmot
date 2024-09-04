<?php

namespace App\Providers;

use App\Enum\TokenAbility;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->overrideSanctumConfigurationToSupportRefreshToken();
    }

    private function overrideSanctumConfigurationToSupportRefreshToken(): void
    {
        Sanctum::$accessTokenAuthenticationCallback = function ($token, $isValid) {
            $abilities = collect($token->abilities);
            $currentRoute = Route::current();
            $routeName = $currentRoute ? $currentRoute->getName() : 'undefined';

            // Токен выдачи нового токена
            if ($abilities->first() === TokenAbility::ISSUE_ACCESS_TOKEN->value) {
                return $token->expires_at && $token->expires_at->isFuture() && $isValid && $routeName === 'refresh_tokens';
            }
            // Токен доступа к API
            if ($abilities->first() === TokenAbility::ACCESS_API->value) {
                return $token->expires_at && $token->expires_at->isFuture();
            }

            return false;
        };

        Sanctum::$accessTokenRetrievalCallback = function ($request) {
            if (!$request->routeIs('refresh_tokens')) {
                return str_replace('Bearer ', '', $request->headers->get('Authorization'));
            }

            return $request->refresh_token ?? '';
        };
    }
}
