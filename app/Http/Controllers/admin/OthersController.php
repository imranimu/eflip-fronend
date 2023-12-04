<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class OthersController extends Controller
{
  public function aboutUs(){
    return view('frontEnd/others/about');
  }
  public function privacyPolicy(){
    return view('frontEnd/others/privacy');
  } 
  public function cancelPolicy(){
    return view('frontEnd/others/cancel');
  }
  public function contactUs(){
    return view('frontEnd/others/contact');
  }

}
