<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $guarded = [];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function branch()
    {
        return $this->hasOne(IrssBranch::class, 'id', 'irss_branch_id')->where('status',1);
    }
	public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id')->where('status',1);
    }
	public function department()
    {
        return $this->hasOne(Department::class, 'id', 'department_id')->where('status',1);
    }
    public function designation()
    {
        return $this->hasOne(Designation::class, 'id', 'designation_id')->where('status',1);
    }
}
