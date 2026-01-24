<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'class_id',
        'nis',
        'nisn',
        'nama',
        'gender',
        'tempat_lahir',
        'tanggal_lahir',
        'alamat',
        'nama_ayah',
        'pekerjaan_ayah',
        'nama_ibu',
        'pekerjaan_ibu',
        'no_hp_ortu',
        'no_urut',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function parents()
    {
        return $this->hasMany(ParentModel::class, 'student_id');
    }

    public function journals()
    {
        return $this->hasMany(Journal::class);
    }
}
