<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
class Client extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
}
