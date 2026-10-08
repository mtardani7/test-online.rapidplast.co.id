<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Tests extends Model
{
    use HasFactory;

    protected $table = 'tests';

    protected $fillable = [
        'name',
        'slug',
        'type',
        'description',
        'rules',
        'is_active',
        'is_shuffle'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getRulesFormattedAttribute()
    {
        if (!$this->rules) return [];
        
        return array_filter(
            array_map('trim', explode("\n", $this->rules))
        );
    }
    
    public function getInstructionsFormattedAttribute()
    {
        if (!$this->instructions) return [];
        
        return array_filter(
            array_map('trim', explode("\n", $this->instructions))
        );
    }

    public function typeTests()
    {
        return $this->hasMany(TypeTests::class);
    }

    public function subtests()
    {
        return $this->hasManyThrough(SubTests::class, TypeTests::class, 'tests_id','type_tests_id','id','id');
    }


    public function getTotalDurationAttribute()
    {
    return $this->subtests()->sum('duration');
    }

    protected static function booted()
    {
        static::creating(function ($test) {
            $test->slug = Str::slug($test->name);
        });

        static::updating(function ($test) {
            $test->slug = Str::slug($test->name);
        });
    }
}
