<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailUser extends Model
{
    use HasFactory;

    protected $table = 'detail_users';

    protected $fillable = [
        'user_id',
        'image',
        'name',
        'no_id',
        'birth_date',
        'gender',
        'religion',
        'citizenship',
        'fathers_name',
        'fathers_occupation',
        'mothers_name',
        'mothers_occupation',
        'level',
        'school',
        'gpa',
        'phone_number',
        'province',
        'city',
        'regency',
        'district',
        'address',
    ];
}

