<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Service;
use App\Models\ServicePrice;
use App\Models\ServiceType;
use App\Models\SquareFootage;

class ServiceController extends Controller
{
    public function lists(){
      $services=Service::orderBy('order')->get();
      return view('admin/services/list',compact('services'));
    }

    public function details($slug){
      $service = Service::where('slug',$slug)->firstOrFail();
      $serviceTypes = ServiceType::all();
      $squareFootages = SquareFootage::all();
      return view('admin/services/details',compact('service','serviceTypes','squareFootages'));
    }

    public function storePrice(Request $request){
      $request->validate([
        'service'=>'required|numeric',
        'service_type'=>'required|numeric',
        'square_footage'=>'required|numeric',
        'amount'=>'required|numeric',
      ]);

      if(empty($price=ServicePrice::where('service_id',$request->service)->where('service_type_id',$request->service_type)->where('square_footage_id',$request->square_footage)->first())){
        $price=new ServicePrice();
        $price->service_id=$request->service;
        $price->service_type_id=$request->service_type;
        $price->square_footage_id=$request->square_footage;
      }
      $price->amount=$request->amount;
      $price->save();

      return back()->with('success','The price is updated successfully');
    }

    
    public function trashed(){
      $services=Service::onlyTrashed()->get();
      return view('admin/services/trash_list',compact('services'));
    }


    public function add(){
      return view('admin/services/add');

    }
    public function store(Request $request){
      $request->validate([
        'title'=>'required',
        'sub_title'=>'required',
        'slug'=>'required|alpha_dash|unique:services',
        'cover_image'=>'required|image',//|dimensions:width=355,height=200
        'description'=>'required',
        'order'=>'required',
        'promo_video'=>'nullable|mimes:mp4,3gp',
        'product_image.*' => 'mimes:jpeg,jpg,png,JPG,PNG,JPEG|dimensions:width=1366,height=768',
      ]);

      $coverImagePath=$request->file('cover_image')->store('services');

      $product=Service::create([
        'title'=>$request->title,
        'sub_title'=>$request->sub_title,
        'meta_tags'=>$request->meta_tags,
        'slug'=>strtolower($request->slug),
        'cover_image'=>$coverImagePath,
        'description'=>$request->description,
        'order'=>$request->order,
        'service_types'=>json_encode($request->service_types),
        'meta_title'=>$request->meta_title,
        'meta_description'=>$request->meta_description,
      ]);

      if($request->hasFile('promo_video')){
        $videoPath=$request->file('promo_video')->store('videos');
        $product->update([
          'promo_video'=>$videoPath,
        ]);
      }

      // foreach($request->file('product_image') as $galleryImage){
      //   $productImagePath=$galleryImage->store('productImage');
      //   ProductGallery::create([
      //    'product_id'=>$product->id,
      //    'product_image'=>$productImagePath,
      //   ]);
      // }

      return redirect('/admin/services')->with('success','Service Added successfully');
      //return $request->all();


    }

    public function edit($id){
        $service=Service::where('id',$id)->first();
        return view('admin/services/edit',compact('service'));
    }

    public function update(Request $request){
      $request->validate([
        'title'=>'required',
        'slug'=>'required|unique:services,id,'.$request->id,
        'sub_title'=>'required',
        'order'=>'required',
        // 'cover_image'=>'dimensions:width=355,height=200',
        'promo_video'=>'nullable|mimes:mp4,3gp',
        'product_image.*' => 'mimes:jpeg,jpg,png,JPG,PNG,JPEG|dimensions:width=1366,height=768',
        'description'=>'required',
      ]);

          $product=Service::where('id',$request->id)->firstOrFail();
          $product->update([
            'title'=>$request->title,
            'sub_title'=>$request->sub_title,
            'slug'=>strtolower($request->slug),
            'order'=>$request->order,
            'description'=>$request->description,
            'service_types'=>json_encode($request->service_types),
            'meta_title'=>$request->meta_title,
            'meta_description'=>$request->meta_description,
          ]);

          if($request->hasFile('cover_image')){
            $coverImagePath=$request->file('cover_image')->store('services');
            $product->update([
              'cover_image'=>$coverImagePath,
            ]);
          }
          if($request->hasFile('promo_video')){
            $videoPath=$request->file('promo_video')->store('videos');
            $product->update([
              'promo_video'=>$videoPath,
            ]);
          }


          // if($request->file('product_image')){
          //   foreach($request->file('product_image') as $galleryImage){
          //   $productImagePath=$galleryImage->store('productImage');
          //     ProductGallery::create([
          //       'product_id'=>$product->id,
          //       'product_image'=>$productImagePath,
          //     ]);
          //   }
          // }


          return redirect('/admin/services')->with('success','The service is Updated successfully');
        
    }
  
    
    public function trash($id){
      Service::where('id',$id)->delete();
      return back()->with('success','The service is moved to trash successfully');
    }
    public function restore($id){
      Service::where('id',$id)->restore();
      return back()->with('success','The service is restored to active services successfully');
    }

    public function delete($id){
      Service::where('id',$id)->forceDelete();
      return back()->with('success','The service is deleted permanently');
    }
    public function showOnHome(Request $request){
      Service::where('id',$request->service)->update(['show_homepage'=>$request->status]);
      return response()->json(['status'=>'done']);
    }
}
