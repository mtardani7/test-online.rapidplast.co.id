<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Questions extends Model {
    protected $fillable = [
        'sub_tests_id', 
        'question_text', 
        'correct_option_id', 
        'image', 
        'question_optional'
    ];

    protected $table = 'questions';

    public function subTest() {
        return $this->belongsTo(SubTests::class, 'sub_tests_id');
    }

    public function choices() {
        return $this->hasMany(QuestionChoices::class);
    }
}
