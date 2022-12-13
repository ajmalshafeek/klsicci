<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
class Clientupdateimage extends Authenticatable
{
    use HasFactory;
    protected $fillable = [
        'complaintid',
        'path',
        'taken',
        'updated_at',
        'created_at',
    ];
    protected $table = 'clientupdateimage';
}
