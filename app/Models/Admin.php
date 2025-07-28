<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

class Admin extends Authenticatable
{
    use HasFactory;
     
    protected $table = 'admin';
    protected $primaryKey = 'NRP_admin';
    public $incrementing = false;
    protected $keyType = 'integer';

    protected $fillable = [
        'NRP_admin',
        'username',
        'password',
        'nama_admin'
    ];

    protected $hidden = [
        'password',
    ];

    // Override default remember token column since it's not in your table
    public function getRememberTokenName()
    {
        return null;
    }
    
    // Override the default username field
    public function getAuthIdentifierName()
    {
        return 'username';
    }
    
    // Override password field
    public function getAuthPassword()
    {
        return $this->password;
    }
    
    // Mutator untuk hash password otomatis
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }
    
    // Relasi ke Sertijab (verifikator)
    public function sertijab()
    {
        return $this->hasMany(Sertijab::class, 'verified_by_admin_nrp');
    }
}