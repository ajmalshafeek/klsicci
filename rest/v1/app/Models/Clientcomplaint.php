<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class Clientcomplaint extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'issueName',
        'issueDetail',
        'companyId',
        'status',
        'requireDate',
        'createdBy',
        'fileAttach',
        'messageStatus',
        'orgId',
        'timeFrame',
    ];
    protected $table = 'clientcomplaint';
}
