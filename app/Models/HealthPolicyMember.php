<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthPolicyMember extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = true;
    protected $table = 'health_policy_member';
    protected $PrimaryKey = 'id';

}
