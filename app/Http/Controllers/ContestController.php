<?php

namespace App\Http\Controllers;

use App\Models\EssayContest;
use App\Models\EssayContestCategory;
use App\Models\User;
use Illuminate\Http\Request;

class ContestController extends Controller
{
    public function index(){
        return view('contest.index');
    }
    public function rules(){
        return view('contest.rules');
    }

    public function essays(){

        $query=EssayContest::query();

        if(!empty($_GET['category'])){
            $query->where('category_id',$_GET['category']);
        }
        
        if(!empty($_GET['search'])){
            $query->where('title','LIKE','%'.$_GET['category'].'%');
        }
        
        if(!empty($_GET['score'])){
            $query->where('average_rating','>',$_GET['score']);
        }
        
        if(!empty($_GET['author'])){
            $query->where('user_id',$_GET['author']);
        }
        
        if(!empty($_GET['year']) && !empty($_GET['month'])){
            $query->whereYear('created_at',$_GET['year'])->whereMonth('created_at',$_GET['month']); //->whereDate('created_at','<',date('Y-m').'-1');
        }else{
            // $query->whereMonth('created_at',date('m')-1)->whereYear('created_at',date('Y'));
            if(date('d')>10){
                $query->whereDate('created_at','>',date('Y-m-').'1');
            }else{
                $query->whereDate('created_at','>',date('Y').'-'.(date('m')-1).'-1');
            }


        }

        if(!empty($_GET['sort'])){
            if($_GET['sort']=='date_high_to_low'){
                $query->orderbyDesc('id');
            }else if($_GET['sort']=='date_low_to_high'){
                $query->orderby('id');
            }else if($_GET['sort']=='rating_high_to_low'){
                $query->orderbyDesc('average_rating');
            }else if($_GET['sort']=='rating_low_to_high'){
                $query->orderby('average_rating');
            }else{
                $query->orderbyDesc('id');
            }
        }else{
            $query->orderbyDesc('id');
        }

        $essays=$query->withCount('getRatings')->paginate(12);


        $categories=EssayContestCategory::orderBy('order')->get();
        $users=User::orderByDesc('id')->get();
        return view('contest.essays',compact('essays','categories','users'));
    }
    
    public function view($slug){
        $essayContest=EssayContest::where('slug',$slug)->withCount('getRatings')->firstOrFail();

        
        return view('contest.view',compact('essayContest'));
    }



}
