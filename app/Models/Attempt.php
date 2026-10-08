<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attempt extends Model
{
    use HasFactory;

    protected $table = 'attempts';

    protected $fillable = [
        'user_id',
        'test_id',
        'total_correct',
        'total_question',
        'score',
        'iq_score',
        'iq_category',
        'started_at',
        'finished_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function typeTests()
    {
        return $this->hasMany(AttemptTypeTest::class);
    }

    public function tests()
    {
        return $this->belongsTo(Tests::class, 'test_id');
    }

    public function typeAttempts()
    {
        return $this->hasMany(AttemptTypeTest::class);
    }

    public function answers()
    {
        return $this->hasMany(AttemptAnswer::class);
    }
    
}
