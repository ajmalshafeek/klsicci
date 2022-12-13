<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Jobtransaction extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    protected $table = 'jobtransaction';
}
