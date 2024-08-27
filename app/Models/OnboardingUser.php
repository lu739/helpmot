<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class OnboardingUser extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'id',
        'name',
        'role',
        'phone',
        'password',
        'phone_code',
        'phone_code_datetime',
        'user_id',
    ];

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
            'phone_code_datetime' => 'datetime',
            'password' => 'hashed',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
