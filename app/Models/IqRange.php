<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IqRange extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_type',
        'raw_min',
        'raw_max',
        'iq_score',
        'category'
    ];

    protected $casts = [
        'raw_min' => 'integer',
        'raw_max' => 'integer',
        'iq_score' => 'integer'
    ];

    public function scopeForTestType($query, $testType)
    {
        return $query->where('test_type', $testType);
    }

    public function scopeForRawScore($query, $testType, $rawScore)
    {
        return $query->where('test_type', $testType)
                    ->where('raw_min', '<=', $rawScore)
                    ->where('raw_max', '>=', $rawScore);
    }
}