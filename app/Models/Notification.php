<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'icon',
        'title',
        'message',
        'link',
        'read',
        'user_id',
        'mutasi_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function mutasi()
    {
        return $this->belongsTo(Mutasi::class, 'mutasi_id');
    }
}
