<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;

    protected $table ='subcategory';

    public function getWebsites(){
        return $this->hasMany('App\Models\Website','subcategory_id','id')->where('status',0);
    }

}
