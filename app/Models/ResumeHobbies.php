<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResumeHobbies extends Model
{
    use HasFactory;
    protected $fillable = [
        'hobby','resume_id'
    ];

    // public function resume()
    // {
    //     return $this->belongsTo(ResumeHobbies::class, 'resume_id');
    // }
}
