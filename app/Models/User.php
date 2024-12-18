<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enum\OrderStatus;
use App\Enum\OrderType;
use App\Enum\UserRole;
use App\Services\ConfirmSms\Interfaces\SmsUserInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements SmsUserInterface
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
        'role',
        'phone',
        'email',
        'password',
        'new_password',
        'phone_code',
        'phone_code_datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'new_password' => 'hashed',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = self::generateUniqueNumericId();
        });
    }

    private static function generateUniqueNumericId()
    {
        $randomNumber = mt_rand(1000000, 99999999);

        // Проверка уникальности (рекурсивный вызов, если не уникально)
        while (self::where('id', $randomNumber)->exists()) {
            $randomNumber = mt_rand(1000000, 99999999);
        }

        return $randomNumber;
    }

    public function onboardingUser()
    {
        return $this->hasOne(OnboardingUser::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'client_id');
    }

    public function inProgressOrder(): HasOne
    {
        return $this
            ->hasOne(Order::class, 'client_id')
            ->where('status', OrderStatus::IN_PROGRESS->value)
            // ToDo как только будут добавлены другие типы заказов:
            //   тут надо будет отдавать несколько заказов
            //   и связь менять на HasMany
            //   и делать несколько вкладок активных/в процессе заказов на фронте
            ->where('type', OrderType::TOW_TRUCK->value);
    }

    public function driver()
    {
        return $this->hasOne(Driver::class);
    }
    public function getId(): string
    {
        return $this->id;
    }
    public function getPhone(): string
    {
        return $this->phone;
    }
    public function getPhoneCode(): int
    {
        return $this->phone_code;
    }

    public function isDriver(): bool
    {
        return $this->role === UserRole::DRIVER->value;
    }
    public function isCodeExpired(): bool
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->phone_code_datetime)->addMinutes(3) < now();
    }
}
