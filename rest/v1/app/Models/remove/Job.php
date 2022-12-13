<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class Job extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;
    protected $table = 'job';
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
    public $timestamps = false;
}
