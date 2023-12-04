<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table ='category';

    public function getWebsites(){
        return $this->hasMany('App\Models\Website','category_id','id')->where('status',0);
    }
    public function subCategories(){
        return $this->hasMany('App\Models\Subcategory','category_id','id')->orderBy('priority');
    }
}
