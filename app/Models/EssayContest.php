<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EssayContest extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'category_id',
        'title',
        'slug',
        'source',
        'attachment',
        'average_rating',
        'is_winner',
    ];

    public function getUser(){
        return $this->hasOne('App\Models\User','id','user_id');
    }

    public function getRatings(){
        return $this->hasOne('App\Models\EssayContestRating','essay_id','id');
    }

    public function getCategory(){
        return $this->hasOne('App\Models\EssayContestCategory','id','category_id');
    }

}
