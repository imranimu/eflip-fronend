<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Feed;
use App\Models\MyEflip;
use App\Models\Subcategory;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use DB;
use Helper;
use Auth;

class IndexController extends Controller
{
    
    public function index($categoryName='random',$subcategoryName='hourlydaily'){
        
        $hourlySites=Website::where('status',0)->where('get_image_hourly',"H")->count();
        $dailySites=Website::where('status',0)->where('get_image_hourly', "D")->count();
        $weeklymonthlySites=Website::where('status',0)->where(function($query){
            $query->where('get_image_hourly', "W")
            ->orWhere('get_image_hourly', "M")
            ->orWhere('get_image_hourly', "N")
            ->orWhereNull('get_image_hourly');
        })->count();
        $articlespicturesSites=Website::where('status',0)->where('feed_has_image', '1')->count();
        $articlesSites = Feed::select(DB::raw('count(distinct website_id) as website_count'))->first()->website_count;

        $myFlips=[];
        if(Auth::check()){
            $myFlips=MyEflip::where('user_id',Auth::id())->pluck('website_id')->toArray();
        }
        
        $query=Website::where('status',0);
        
        $selectedCategory='';

        if($categoryName!='random'){
            $selectedCategory=Category::where('name',$categoryName)->with('subCategories','subCategories.getWebsites')->firstOrFail();
            $categoryName==$selectedCategory->name;
            $query->where('category_id',$selectedCategory->id)->orderBy('alexa');
        }
        
        if($categoryName=='random'){
            if($subcategoryName == 'hourlydaily'){
                $query->whereIn('get_image_hourly',['H','D']);
            } else if ($subcategoryName == 'hourly') {
                $query->where('get_image_hourly',"H");
            } else if ($subcategoryName == 'daily') {
                $query->where('get_image_hourly', "D");
            } else if ($subcategoryName == 'weeklymonthly') {
                $query->where(function ($query) {
                    $query->Where('get_image_hourly', "W")
                        ->orWhere('get_image_hourly', "M")
                        ->orWhere('get_image_hourly', "N")
                        ->orWhereNull('get_image_hourly');
                });
            } else if ($subcategoryName == 'articlespictures') {// website that ha articles in it
                $query->where('feed_has_image', '1');
            }

            if(empty(Session::get('randomsort')) || !isset($_GET['page'])){
                $randomNumber=mt_rand(1000,9999);
                Session::put('randomsort',$randomNumber);
            }
            $query->inRandomOrder(Session::get('randomsort'));
            
            
        }else if($subcategoryName!='random'){
            $selectedSubCategory=Subcategory::where('name',$subcategoryName)->where('category_id',$selectedCategory->id)->firstOrFail();
            $subcategoryName=$selectedSubCategory->name;
            $query->where('subcategory_id',$selectedSubCategory->id)->orderBy('alexa');
        }

        if(!empty($_GET['search'])){
            $query->where('name','LIKE','%'.$_GET['search'].'%');
        }


        $totalWebsites=count($query->get());
            
        
        $perpage=40;
        $websites=$query->with('getCategory','getSubCategory')->paginate($perpage);
        
        return view('index',compact(
            'websites',
            'selectedCategory',
            'perpage',
            'totalWebsites',
            'categoryName',
            'hourlySites',
            'dailySites',
            'weeklymonthlySites',
            'articlespicturesSites',
            'subcategoryName',
            'articlesSites',
            'myFlips',
        ));
        
    }

    public function new($categoryName='random',$subcategoryName='hourlydaily'){
        
        
        $hourlydailySites=Website::where('status',0)->whereIn('get_image_hourly',['H','D'])->count();
        $hourlySites=Website::where('status',0)->where('get_image_hourly',"H")->count();
        $dailySites=Website::where('status',0)->where('get_image_hourly', "D")->count();
        $weeklymonthlySites=Website::where('status',0)->where(function($query){
            $query->where('get_image_hourly', "W")
            ->orWhere('get_image_hourly', "M")
            ->orWhere('get_image_hourly', "N")
            ->orWhereNull('get_image_hourly');
        })->count();
        $articlespicturesSites=Website::where('status',0)->where('feed_has_image', '1')->count();
        $articlesSites = Feed::select(DB::raw('count(distinct website_id) as website_count'))->first()->website_count;
        
        $query=Website::where('status',0);
        
        $selectedCategory='';

        if($categoryName!='random'){
            $selectedCategory=Category::where('name',$categoryName)->with('subCategories','subCategories.getWebsites')->firstOrFail();
            $categoryName==$selectedCategory->name;
            $query->where('category_id',$selectedCategory->id)->orderBy('alexa');
        }
        

        if($categoryName=='random'){
            if($subcategoryName == 'hourlydaily'){
                $query->whereIn('get_image_hourly',['H','D']);
            } else if ($subcategoryName == 'hourly') {
                $query->where('get_image_hourly',"H");
            } else if ($subcategoryName == 'daily') {
                $query->where('get_image_hourly', "D");
            } else if ($subcategoryName == 'weeklymonthly') {
                $query->where(function ($query) {
                    $query->Where('get_image_hourly', "W")
                        ->orWhere('get_image_hourly', "M")
                        ->orWhere('get_image_hourly', "N")
                        ->orWhereNull('get_image_hourly');
                });
            } else if ($subcategoryName == 'articlespictures') {// website that ha articles in it
                $query->where('feed_has_image', '1');
            }
            $query->inRandomOrder();
            
        }else if($subcategoryName!='random'){
            $selectedSubCategory=Subcategory::where('name',$subcategoryName)->firstOrFail();
            $subcategoryName=$selectedSubCategory->name;
            $query->where('subcategory_id',$selectedSubCategory->id)->orderBy('alexa');
        }

        if(!empty($_GET['search'])){
            $query->where('name','LIKE','%'.$_GET['search'].'%');
        }


        $totalWebsites=$query->get()->count();
            
        
        $perpage=40;
        $websites=$query->with('getCategory','getSubCategory')->paginate($perpage);
        
        return view('layouts.master',compact(
            'websites',
            'selectedCategory',
            'perpage',
            'totalWebsites',
            'categoryName',
            'hourlydailySites',
            'hourlySites',
            'dailySites',
            'weeklymonthlySites',
            'articlespicturesSites',
            'subcategoryName',
            'articlesSites',
        ));
        
    }

    public function intro(){
        $categoryName='';
        return view('intro',compact('categoryName'));
    }

    public function help(){
        $categoryName='';
        return view('help',compact('categoryName'));
    }
    public function contact(){
        $categoryName='';
        return view('contact',compact('categoryName'));
    }

    public function storeContact(Request $request){
        $request->validate([
            'name'=>'required',
            'email'=>'required',
            'message'=>'required',
        ]);

        DB::table('contact_us')->insert([
            'name'=>$request->name,
            'email'=>$request->email,
            'message'=>$request->message,
        ]);

        return back()->with('success','your information is sent to authority successfully');


    }

}
