<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Slider;
use Illuminate\Http\Request;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sliders=Slider::orderBy('order')->get();
        return view('admin/sliders/index',compact('sliders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            'sub_title'=>'required',
            'cover_image'=>'required|image',//|dimensions:width=355,height=200
            'order'=>'required',
          ]);
    
        $coverImagePath=$request->file('cover_image')->store('sliders');

        $product=Slider::create([
            'title'=>$request->title,
            'sub_title'=>$request->sub_title,
            'cover_image'=>$coverImagePath,
            'order'=>$request->order,
        ]);
        return back()->with('success','slider Added successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $slider=Slider::where('id',$id)->first();
        return view('admin/sliders/edit',compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'=>'required',
            'sub_title'=>'required',
            'order'=>'required',
            // 'cover_image'=>'dimensions:width=355,height=200',
        ]);
    
        $slider=Slider::where('id',$id)->firstOrFail();
        $slider->update([
            'title'=>$request->title,
            'sub_title'=>$request->sub_title,
            'order'=>$request->order,
        ]);

        if($request->hasFile('cover_image')){
            $coverImagePath=$request->file('cover_image')->store('sliders');
            $slider->update([
                'cover_image'=>$coverImagePath,
            ]);
        }
              
        return redirect('/admin/sliders')->with('success','The slider is Updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Slider::where('id',$id)->delete();
        return back()->with('success','The slider is deleted');
    }
}
