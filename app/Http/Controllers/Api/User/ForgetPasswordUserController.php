<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\OnboardingRequest;
use App\Http\Requests\Api\User\RefreshPasswordRequest;
use App\Http\Resources\OnboardingUser\OnboardingClientResource;
use App\Models\User;
use App\Services\ConfirmSms\ConfirmSmsService;
use App\UseCases\User\ForgetPassword\Dto\ForgetPasswordUserDto;
use App\UseCases\User\ForgetPassword\ForgetPasswordUserUseCase;
use Illuminate\Support\Facades\DB;


/**
 * @OA\Post (
 *     path="/api/v1/forget-password",
 *     summary="Аутентификация юзера для смены пароля",
 *     tags={"User"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(property="phone", type="string", example="79161234567", description="Телефон юзера, которому нужна смена пароля", nullable=false),
 *                  @OA\Property(property="password", type="string", example="1234567s", description="Новый пароль юзера", nullable=false),
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="OK",
 *         @OA\JsonContent(
 *              @OA\Property(property="user", type="object",
 *                  @OA\Property(property="id", type="string", example="15735744919122398"),
 *                  @OA\Property(property="phone", type="string", example="79161234567"),
 *              ),
 *         )
 *     )
 * )
 */
class ForgetPasswordUserController extends Controller
{
    public function __construct(
        private readonly ForgetPasswordUserUseCase $forgetPasswordUserUseCase,
        private readonly ConfirmSmsService         $confirmSmsService,
    )
    {
    }

    public function __invoke(RefreshPasswordRequest $request)
    {
        $data = $request->validated();

        $user = User::query()
            ->where('phone', $data['phone'])
            ->first();

        if ($user) {
            try {
                DB::beginTransaction();

                $forgetPasswordUserDto = (new ForgetPasswordUserDto())
                    ->setPhone($data['phone'])
                    ->setPhoneCode(random_int(100000, 999999))
                    ->setNewPassword($data['new_password'])
                    ->setPhoneCodeDatetime(now()->format('Y-m-d H:i:s'));
                $user = $this->forgetPasswordUserUseCase->handle($forgetPasswordUserDto);

                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();

                return response()->json([
                    'message' => $exception->getMessage()
                ], 500);
            }
        }

        $response = $this->confirmSmsService->setSmsUser($user)->sendSmsToUser();

        if ($response->status() === 200 && strtolower($response->json()['status']) === 'ok') {
            return response()->json([
                'user' => OnboardingClientResource::make($user)->resolve(),
            ]);
        } else {
            $message = __('exceptions.sms_server_error') .
                ': ' . ($response->json()['description'] ?? 'Unknown error') .
                ' (status: ' . ($response->json()['status'] ?? 'Unknown status') . ')';

            return response()->json([
                'message' => $message,
            ], 500);
        }
    }
}
