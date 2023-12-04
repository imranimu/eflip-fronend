<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\State;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function index(){
        $states=State::orderBy('status')->with('getCities')->get();
        return view('admin.areas.index',compact('states'));
    }

    public function status($code){
        $state=State::where('state_code',$code)->firstOrFail();

        if($state->status=='active'){
            $state->status='disabled';
        }else{
            $state->status='active';
        }
        $state->update();

        return back()->with('success','The State is successfully marked as '.$state->status);

    }

    public function storeCity(Request $request){
        $request->validate([
            'state_id'=>'required',
            'name'=>'required',
            'slug'=>'required|unique:cities|alpha_dash',
        ]);

        City::create([
            'state_id'=>$request->state_id,
            'name'=>$request->name,
            'slug'=>strtolower($request->slug),
        ]);

        return back()->with('success','The city is added to the area');
    }

    public function editCity($id){
        $city=City::where('id',$id)->firstOrFail();
        $states=State::orderBy('status')->with('getCities')->get();
        return view('admin.areas.edit',compact('city','states'));

    }

    public function updateCity(Request $request){
        $request->validate([
            'state'=>'required',
            'name'=>'required',
            'slug'=>'required|alpha_dash|unique:cities,id,',$request->id,
        ]);

        City::where('id',$request->id)->update([
            'state_id'=>$request->state,
            'name'=>$request->name,
            'slug'=>strtolower($request->slug),
        ]);

        return redirect(route('admin.areas.index'))->with('success','The city is updated successfully');
    }

    public function deleteCity($id){
        $city=City::where('id',$id)->firstOrFail();
        $city->delete();
        return back()->with('success','The City is deleted successfully');

    }

    public function cityStatus($id){

        $city=City::where('id',$id)->firstOrFail();

        if($city->status=='active'){
            $city->status='disabled';
        }else{
            $city->status='active';
        }
        $city->update();

        return back()->with('success','The City is successfully marked as '.$city->status);
    }


}
