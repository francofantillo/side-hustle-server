<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Resume extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'resume';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'user_id',
        'profile_image',
        'actual_name',
        'nick_name',
        'profession',
        'family_ties',
        'professional_background',
        'favourite_quote',
        'description',
        'filename',
        'file_size',
        'file'
    ];

    public function hobbies()
    {
        return $this->hasMany(ResumeHobbies::class, 'resume_id');
    }

    public function user()
    {
        return $this->hasOne(ResumeHobbies::class, 'user_id');
    }
}
