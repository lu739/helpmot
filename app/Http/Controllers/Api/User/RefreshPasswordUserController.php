<?php

namespace App\Http\Controllers\Api\User;

use App\Enum\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\RefreshPasswordRequest;
use App\Http\Resources\User\UserMinifiedResource;
use App\Models\User;
use App\UseCases\User\ChangePassword\Dto\RefreshPasswordUserDto;
use App\UseCases\User\ChangePassword\RefreshPasswordUserUseCase;
use Illuminate\Support\Facades\DB;


/**
 * @OA\Post (
 *     path="/api/v1/refresh-password",
 *     summary="Смена пароля юзера",
 *     tags={"User"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *              mediaType="application/json",
 *              @OA\Schema(
 *                  @OA\Property(property="phone", type="string", example="79161234567", description="Телефон юзера", nullable=false),
 *                  @OA\Property(property="phone_code", type="integer", example=123456),
 *                  @OA\Property(property="role", type="string", example="driver", enum={"client", "driver"}, description="Роль юзера, которому нужна смена пароля", nullable=false),
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
 *                  @OA\Property(property="role", type="string", example="driver"),
 *              ),
 *         )
 *     )
 * )
 */
class RefreshPasswordUserController extends Controller
{
    public function __construct(
        private readonly RefreshPasswordUserUseCase $refreshPasswordUserUseCase,
    )
    {
    }

    public function __invoke(RefreshPasswordRequest $request)
    {
        $data = $request->validated();

        $user = User::query()
            ->where('phone', $data['phone'])
            ->where('role', $data['role'])
            ->first();

        if (!$user) {
            return responseFailed(404, __('exceptions.user_not_found'));
        }

        if (!$user->new_password || !$user->phone_code || !$user->phone_code_datetime) {
            return responseFailed(404, __('exceptions.user_has_not_data'));
        }

        try {
            DB::beginTransaction();

            $refreshPasswordUserDto = (new RefreshPasswordUserDto())
                ->setPhone($data['phone'])
                ->setPhoneCode(random_int(100000, 999999))
                ->setNewPassword($user->new_password)
                ->setRole(UserRole::from($data['role']))
                ->setId($user->id);
            $user = $this->refreshPasswordUserUseCase->handle($refreshPasswordUserDto);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();

            return response()->json([
                'message' => $exception->getMessage()
            ], 500);
        }

        return response()->json([
            'user' => UserMinifiedResource::make($user)->resolve(),
        ]);
    }
}
