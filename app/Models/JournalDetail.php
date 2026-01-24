<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'journal_id',
        'kebiasaan',
        'nilai',
        'actual_value',
        'note',
    ];

    public function journal()
    {
        return $this->belongsTo(Journal::class);
    }

    public function habit()
    {
        return $this->belongsTo(Habit::class, 'kebiasaan', 'id');
    }
}
