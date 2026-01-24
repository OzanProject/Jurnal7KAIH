<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'tanggal',
        'status',
        'catatan_guru',
        'catatan_siswa',
        'catatan_orang_tua',
        'parent_confirmed_at',
    ];

    protected $casts = [
        'parent_confirmed_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function details()
    {
        return $this->hasMany(JournalDetail::class);
    }
}
