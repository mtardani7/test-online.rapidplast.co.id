<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class QuestionChoices extends Model {
    protected $fillable = [
        'questions_id', 
        'option_text', 
        'option_label'
    ];
    protected $table = 'question_choices';

    public static function questionForeignKey(): string
    {
        static $foreignKey = null;

        return $foreignKey ??= Schema::hasColumn('question_choices', 'questions_id')
            ? 'questions_id'
            : 'question_id';
    }

    public function question() {
        return $this->belongsTo(Questions::class, self::questionForeignKey());
    }
}

