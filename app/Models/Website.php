<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    use HasFactory;

    public function getCategory(){
        return $this->hasOne('App\Models\Category','id','category_id');
    }
    
    public function getSubCategory(){
        return $this->hasOne('App\Models\Subcategory','id','subcategory_id');
    }
    
}
