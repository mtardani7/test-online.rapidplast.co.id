<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttemptSubTest extends Model
{
    use HasFactory;
    protected $table = 'attempt_sub_tests';
    protected $fillable = [
        'attempt_type_test_id',
        'sub_test_id',
        'correct_count',
        'wrong_count',
        'score',
        'is_finished',
        'started_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'immutable_datetime',
            'expires_at' => 'immutable_datetime',
        ];
    }

    public function attemptTypeTest()
    {
        return $this->belongsTo(AttemptTypeTest::class);
    }

    public function subTest()
    {
        return $this->belongsTo(SubTests::class, 'sub_test_id');
    }

}
