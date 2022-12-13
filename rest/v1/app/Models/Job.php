<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class Job extends Authenticatable
{
    use HasFactory;
    protected $fillable = [
        'refNo',
        'jobName',
        'clientCompanyId',
        'vendorId',
        'vendorUserId',
        'jobLocation',
        'picName',
        'picContactNo',
        'remarks',
        'createdBy',
        'cName',
        'orgId',
        'complaintId',
        'updated_at',
        'created_at'
    ];


    protected $table = 'job';


}
