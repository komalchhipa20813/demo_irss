<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Agent extends Authenticatable
{
    use HasFactory,Notifiable;
    protected $guard = 'agent';
    protected $guarded = [];
    public function branch()
    {
        return $this->hasOne(IrssBranch::class, 'id', 'home_irss_branch_id')->where('status',1);
    }
    public function documents()
    {
        return $this->hasMany(AgentDocuments::class, 'agent_id', 'id')->with('documents_type')->where('status',1);
    }
    public function fdo()
    {
        return $this->hasOne(Fdo::class, 'id', 'fdo_id')->where('status',1);
    }
    public function businessCategory(){
        return $this->hasOne(Fdo::class, 'id', 'fdo_id')->where('status',1);
    }
}
