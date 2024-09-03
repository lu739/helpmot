<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Services\ConfirmSms\Interfaces\SmsUserInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
}
