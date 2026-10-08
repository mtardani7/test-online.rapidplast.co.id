<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScoringComponent extends Model
{
    protected $fillable = [
        'scoring_rule_id',
        'key', 
        'description'
    ];
    protected $table = 'scoring_component';
    public function rule()
    {
        return $this->belongsTo(ScoringRule::class);
    }
}

