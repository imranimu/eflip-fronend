<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EssayContestRating extends Model
{
    use HasFactory;

    protected $fillable=[
        'user_id',
        'essay_id',
        'rating',
    ];

    public function getUser(){
        return $this->hasOne('App\Models\User','id','user_id');
    }

}
