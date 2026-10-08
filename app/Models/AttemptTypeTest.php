<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttemptTypeTest extends Model
{
    use HasFactory;
    protected $table = 'attempt_type_tests';
    protected $fillable = [
        'attempt_id',
        'type_test_id',
        'correct',
        'total',
        'score'
    ];

    public function attempt()
    {
        return $this->belongsTo(Attempt::class);
    }

    public function typeTest()
    {
        return $this->belongsTo(TypeTests::class);
    }

    public function subAnswers()
    {
        return $this->hasMany(AttemptSubTest::class);
    }
}
