<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttemptAnswer extends Model
{
    use HasFactory;
    protected $table = 'attempt_answers';
    protected $fillable = [
        'attempt_id',
        'question_id',
        'answer',
        'is_correct'
    ];

    public function attempt()
    {
        return $this->belongsTo(Attempt::class);
    }

    public function question()
    {
        return $this->belongsTo(Questions::class);
    }
}
