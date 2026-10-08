<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScoringRule extends Model
{
    protected $fillable = [
        'test_id', 
        'formula', 
        'description'
    ];
    protected $table = 'scoring_rules';
    public function components()
    {
        return $this->hasMany(ScoringComponent::class);
    }

    public function test()
    {
        return $this->belongsTo(Tests::class);
    }
}

