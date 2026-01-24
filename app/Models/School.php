<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function classes()
    {
        return $this->hasMany(ClassRoom::class, 'school_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
