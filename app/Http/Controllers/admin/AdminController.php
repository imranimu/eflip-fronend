<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;

use App\Models\AdminInbox;
use App\Models\Product;
use App\Models\ServiceClick;
use App\Models\User;
use App\Models\SubCategory;
use Carbon\Carbon;
use App\Charts\UsersChart;
use App\Models\QuotationRequest;
use App\Models\Service;
use App\Models\Website;
use App\Models\EssayContest;
use App\Models\EssayContestRating;
use Illuminate\Http\Request;
use Auth;
use Hash;
use Helper;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth')->except('storeClick');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

     
        $totalWebsites=Website::count();
        $totalContest=EssayContest::count();
        $totalContestRating=EssayContestRating::count();
        // $todaysClicks=ServiceClick::whereDay('created_at',Carbon::now()->day)->count();
        // $totalClicks=ServiceClick::count();
        // $todaysClicks=0;
        // $totalClicks=0;


        // $thisMonthsClicks=ServiceClick::whereMonth('created_at',Carbon::now()->month)->count();
        // $prevOneMonthsClicks=ServiceClick::whereMonth('created_at',Carbon::now()->month-1)->count();
        // $prevTwoMonthsClicks=ServiceClick::whereMonth('created_at',Carbon::now()->month-2)->count();
        // $prevThreeMonthsClicks=ServiceClick::whereMonth('created_at',Carbon::now()->month-3)->count();
        // $prevFourMonthsClicks=ServiceClick::whereMonth('created_at',Carbon::now()->month-4)->count();
        // $prevFiveMonthsClicks=ServiceClick::whereMonth('created_at',Carbon::now()->month-5)->count();
        // $clicks=[
        //     $prevFiveMonthsClicks,
        //     $prevFourMonthsClicks, 
        //     $prevThreeMonthsClicks, 
        //     $prevTwoMonthsClicks, 
        //     $prevOneMonthsClicks, 
        //     $thisMonthsClicks, 
        // ];
         $clicks=[
            0,
            0, 
            0, 
            0, 
            0, 
            0, 
        ];
        

        return view('admin.home.index',compact('totalContest','totalContestRating','clicks','totalWebsites'));
    
    }

    public function fetchSubCategory(Request $request){
        $subCategories = SubCategory::where("category_id",$request->catID)->pluck("name","id");
        return json_encode($subCategories);
    }

    public function storeClick(Request $request){
        $click = ServiceClick::create(["product_id"=>$request->product]);
        return json_encode($click);
    }

    public function adminProfile(){
        $admin=User::where('id',Auth::id())->firstOrFail();
        return view('admin/adminProfile',compact('admin'));
    }

    public function updateProfile(Request $request){
        $request->validate([
            'first_name'=>'required',
            'last_name'=>'required',
            'email'=>'required|email',
            //'oldPassword'=>'required|same:admins.password',
            'password'=>'confirmed',
        ]);
        $admin=User::where('id',Auth::id())->firstOrFail();

        if(Hash::check($request->oldPassword,$admin->password)){
            $admin->update([
                'first_name'=>$request->first_name,
                'last_name'=>$request->last_name,
                'email'=>$request->email,
            ]);
            if($request->password != ''){
                $admin->update([
                    'password'=>bcrypt($request->password),
                ]);
                Session::flush();
                return redirect('/admin/login')->with('errorMessage','Password changed, login with new info now');
            }
        }else{
            return back()->with('errorMessage','Old password not matched');

        }
        return back()->with('success','Profile updated');
    }

}
