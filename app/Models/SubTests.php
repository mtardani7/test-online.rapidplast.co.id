<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SubTests extends Model
{
    protected $table = 'sub_tests';
    protected $primaryKey = 'id';

    protected $fillable = [
        'type_tests_id',
        'name',
        'slug',
        'description',
        'duration',
    ];

    public function instructionRanges()
    {
        return $this->hasMany(SubtestInstructionRange::class, 'sub_test_id');
    }

    public function activeInstruction()
    {
        return $this->hasOne(SubtestInstructionRange::class, 'sub_test_id')->where('is_active', true)->orderBy('order_number');
    }

    public function questions()
    {
        return $this->hasMany(Questions::class);
    }

    public function getQuestionCountAttribute()
    {
        if (!$this->relationLoaded('questions')) {
            return $this->questions()->count();
        }
        return $this->questions->count();
    }

    public function typeTest()
    {
        return $this->belongsTo(TypeTests::class, 'type_tests_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($listTest) {
            $listTest->slug = Str::slug($listTest->name);
        });
    }
}
