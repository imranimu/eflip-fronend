<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;

use App\Models\Page;
use App\Models\Setting;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages=Page::all();
        return view('admin/pages/index',compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       return view('admin/pages/add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'=>'required',
            'description'=>'required',
        ]);

        Page::create([
            'title'=>$request->title,
            'slug'=>str_replace(' ','-',trim(strtolower($request->title))),
            'description'=>$request->description,
        ]);

        return redirect(route('admin.pages.index'))->with('success','Page created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function show(Page $page)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit($page)
    {
        $page=Page::where('id',$page)->first();
        return view('admin/pages/edit',compact('page'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        Page::where('id',$request->page_id)->update([
            'title'=>$request->title,
            'description'=>$request->description
        ]);

        return redirect(route('admin.pages.index'))->with('success','The page has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Page::where('id',$id)->delete();
        return redirect(route('admin.pages.index'))->with('success','The page has been deleted');
    }

    public function settings(){
        $settings=Setting::all();
        return view('admin.pages.settings',compact('settings'));
    }

    public function updateSettings(Request $request){
        foreach($request->settings as $key=>$value){
            $setting=Setting::where('slug',$key)->first();
            if(!empty($setting)){
                if($setting->type=='file'){
                    $setting->value=$value->store('settings');
                }else if($setting->type=='json'){
                    $setting->value=json_encode($value);
                }else{
                    $setting->value=$value;
                }
                $setting->update();
            }
        }
        return redirect(route('admin.dashboard'))->with('success','settings are updated');
    }



}
