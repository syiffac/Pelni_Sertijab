<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'NRP_admin',
        'username',
        'nama_admin',
        'email',
        'password',
        'role',
        'nama_admin',
        'jabatan',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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
            'password' => 'hashed',
        ];
    }

    // Relasi dengan custom notifications table
    public function customNotifications()
    {
        return $this->hasMany(\App\Models\Notification::class);
    }

    // Method untuk mendapatkan unread notifications count dari custom table
    public function getUnreadNotificationsCountAttribute()
    {
        return $this->customNotifications()->where('read', false)->count();
    }

    // Override method routeNotificationForDatabase untuk menggunakan custom table
    public function routeNotificationForDatabase()
    {
        return $this->getKey();
    }
}
