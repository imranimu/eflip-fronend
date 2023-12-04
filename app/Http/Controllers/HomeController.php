<?php

namespace App\Http\Controllers;

use App\Models\EssayContest;
use App\Models\EssayContestCategory;
use App\Models\EssayContestRating;
use App\Models\MyEflip;
use Illuminate\Http\Request;
use Auth;
use Str;
use Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(Auth::user()->permissions=='admin'){
            return redirect(route('admin.dashboard'));
        }
        Session::flush();

        return redirect(url('/'));
    }

    public function addEssay(){
        $categories=EssayContestCategory::orderBy('order')->get();
        return view('contest.essay.add',compact('categories'));
    }

    public function rateContest(Request $request,$id){
        
        $request->validate([
            'rating'=>'required|min:0|max:10|numeric',
        ]);

        if(empty(EssayContestRating::where('user_id',Auth::id())->where('essay_id',$id)->first())){
            $essayContest=EssayContest::where('id',$id)->whereMonth('created_at',date('m')-1)->whereYear('created_at',date('Y'))->withCount('getRatings')->withSum('getRatings','rating')->firstOrFail();

            EssayContestRating::create([
                'user_id'=>Auth::id(),
                'essay_id'=>$id,
                'rating'=>$request->rating,
            ]);

            $essayContest->average_rating=round(($essayContest->get_ratings_sum_rating/$essayContest->get_ratings_count),2);
            $essayContest->update();
    
            return back()->with('success','essay rated successfully');
        }
        return back()->with('error','you already rated the essay');
    }


    public function storeEssay(Request $request){

        $request->validate([
            'category'=>'required',
            'title'=>'required',
            'attachment'=>'required|mimes:txt,doc,docx',
            // 'slug'=>'required|alpha_dash|unique:essay_contests',
        ]);

        

        EssayContest::create([
            'user_id'=>Auth::id(),
            'category_id'=>$request->category,
            'title'=>$request->title,
            'slug'=>Str::slug($request->title),
            'attachment'=>$request->file('attachment')->store('contests'),
        ]);

        return back()->with('success','essay added successfully');

    }


    public function storeCategory(Request $request){

        $request->validate([
            'name'=>'required',
            // 'category_slug'=>'required|alpha_dash|unique:essay_contest_categories,slug',
            'order'=>'required',
        ]);

        EssayContestCategory::create([
            'user_id'=>Auth::id(),
            'name'=>$request->name,
            'slug'=>Str::slug($request->name),
            'order'=>$request->order,
        ]);

        return back()->with('success','category added successfully');

    }

    
    public function favourite($id){
        $isExist=MyEflip::where('website_id',$id)->where('user_id',Auth::id())->first();

        if(empty($isExist)){
            MyEflip::create([
                'website_id'=>$id,
                'user_id'=>Auth::id()
            ]);
            $msg='added';
        }else{
            $isExist->delete();
            $msg='deleted';
        }

        return ['status'=>'success','message'=>$msg];

    }

}
