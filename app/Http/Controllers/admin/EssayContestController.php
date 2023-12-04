<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\EssayContest;
use App\Models\EssayContestCategory;
use App\Models\User;
use Illuminate\Http\Request;

class EssayContestController extends Controller
{
    public function index(){
        $query=EssayContest::query();

        if(!empty($_GET['year']) && !empty($_GET['month'])){
            $query->whereYear('created_at',$_GET['year'])->whereMonth('created_at',$_GET['month']);
        }else{
            $query->whereYear('created_at',date('Y'))->whereMonth('created_at',date('m'));
        }

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

        $essays=$query->withCount('getRatings')->with('getUser','getCategory')->paginate(12);
        $categories=EssayContestCategory::orderBy('order')->get();
        $users=User::orderByDesc('id')->get();

        return view('admin/essays/index',compact('essays','users','categories'));
    }

    public function makeWinner($id){
        $contest=EssayContest::where('id',$id)->firstOrFail();

        EssayContest::where('id','!=',$id)->whereMonth('created_at',date('m',strtotime($contest->created_at)))
            ->whereYear('created_at',date('Y',strtotime($contest->created_at)))->update(['is_winner'=>'no']);
        $contest->is_winner='yes';
        $contest->update();

        return back()->with('success','winner selected');
    }


    
}
