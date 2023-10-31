<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $append = ['full_name'];

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $this->first_name . ' '.$this->middle_name . ' ' . $this->last_name,
        );
    }
}
