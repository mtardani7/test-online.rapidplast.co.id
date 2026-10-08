<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubtestInstructionRange extends Model
{
    use HasFactory;

    protected $table = 'subtest_instruction_ranges';

    protected $fillable = [
        'sub_test_id',
        'start_number',
        'end_number',
        'order_number',
        'is_active',
        'description'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'start_number' => 'integer',
        'end_number' => 'integer',
        'order_number' => 'integer'
    ];

    public function subTest()
    {
        return $this->belongsTo(SubTests::class);
    }

    public function getRangeAttribute()
    {
        return "Soal {$this->start_number} - {$this->end_number}";
    }

    public function getTotalQuestionsAttribute()
    {
        return ($this->end_number - $this->start_number) + 1;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_number')->orderBy('start_number');
    }

    public function containsQuestion($questionNumber)
    {
        return $questionNumber >= $this->start_number && $questionNumber <= $this->end_number;
    }

    public function overlapsWith($start, $end)
    {
        return !($end < $this->start_number || $start > $this->end_number);
    }
}