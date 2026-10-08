<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionChoices extends Model {
    protected $fillable = [
        'questions_id', 
        'option_text', 
        'option_label'
    ];
    protected $table = 'question_choices';

    public function question() {
        return $this->belongsTo(Questions::class, 'questions_id');
    }
}

