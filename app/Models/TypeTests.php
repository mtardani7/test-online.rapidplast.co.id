<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TypeTests extends Model
{
    protected $fillable = [
        'name', 
        'slug', 
        'tests_id', 
        'description'
    ];
    
    protected $table = 'type_tests';

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function subTests()
    {
        return $this->hasMany(SubTests::class);
    }

    public function test()
    {
        return $this->belongsTo(Tests::class, 'tests_id');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($typeTest) {
            $typeTest->slug = Str::slug($typeTest->name);
        });
    }
}
