<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ProductGallery;

class ProductController extends Controller
{
    public function productLists(){
      $products=Product::orderBy('order')->get();
      return view('admin/products/product_list',compact('products'));
    }

    public function details($id){
      $products = Product::find($id);
      return view('admin/products/product_details',compact('products'));
    }

    /*public function viewshow($slug)
{
    $product = Product::where('slug','=', $slug)->firstOrFail();
        return view('admin/products/product_slug',compact('product'));
    }
*/

    
    public function trashedProduct(){
      $products=Product::onlyTrashed()->get();
      return view('admin/products/trash_list',compact('products'));
    }


    public function productAdd(){
        $categories=Category::all();
      return view('admin/products/product_add',compact('categories'));

    }
    public function productStore(Request $request){
      $request->validate([
        'title'=>'required',
        'sub_title'=>'required',
        'meta_tags'=>'required',
        'slug'=>'required|alpha_dash|unique:products',
        'cover_image'=>'required|image|dimensions:width=355,height=200',
        'description'=>'required',
        'category_id'=>'required',
        'tools'=>'required',
        'order'=>'required',
        'product_image'=>'required',
        'promo_video'=>'nullable|mimes:mp4,3gp',
        'product_image.*' => 'mimes:jpeg,jpg,png,JPG,PNG,JPEG|dimensions:width=1366,height=768',
      ]);

      $coverImagePath=$request->file('cover_image')->store('productCoverImage');
      $product=Product::create([
        'title'=>$request->title,
        'sub_title'=>$request->sub_title,
        'meta_tags'=>$request->meta_tags,
        'slug'=>strtolower($request->slug),
        'cover_image'=>$coverImagePath,
        'description'=>$request->description,
        'category_id'=>$request->category_id,
        'sub_category_id'=>$request->sub_category_id,
        'link'=>$request->link,
        'tools'=>$request->tools,
        'order'=>$request->order,
        'meta_keywords'=>$request->meta_keywords,
      ]);

      if($request->hasFile('promo_video')){
        $videoPath=$request->file('promo_video')->store('videos');
        $product->update([
          'promo_video'=>$videoPath,
        ]);
      }

      foreach($request->file('product_image') as $galleryImage){
        $productImagePath=$galleryImage->store('productImage');
        ProductGallery::create([
         'product_id'=>$product->id,
         'product_image'=>$productImagePath,
        ]);
      }

      return redirect('/admin/products')->with('success','Product Added successfully');
      //return $request->all();


    }

    public function productEdit($id){
        $product=Product::where('id',$id)->first();
        $categories=Category::all();
        $productImages=ProductGallery::where('product_id',$id)->get();
        $subCategories=SubCategory::where('category_id',$product->category_id)->get();
        return view('admin/products/product_edit',compact('product','productImages','categories','subCategories'));
    }

    public function productUpdate(Request $request){
      $request->validate([
        'title'=>'required',
        'sub_title'=>'required',
        'order'=>'required',
        'cover_image'=>'dimensions:width=355,height=200',
        'promo_video'=>'nullable|mimes:mp4,3gp',
        'product_image.*' => 'mimes:jpeg,jpg,png,JPG,PNG,JPEG|dimensions:width=1366,height=768',
        'category_id'=>'required',
        'description'=>'required',
      ]);

      $isExistSlug=Product::where('id','!=',$request->id)->where('slug',$request->slug)->first();
      if(empty($isExistSlug)){
          $product=Product::where('id',$request->id)->first();
          $product->update([
            'title'=>$request->title,
            'sub_title'=>$request->sub_title,
            'meta_tags'=>$request->meta_tags,
            'slug'=>strtolower($request->slug),
            'order'=>$request->order,
            'category_id'=>$request->category_id,
            'sub_category_id'=>$request->sub_category_id,
            'description'=>$request->description,
            'link'=>$request->link,
          ]);

          if($request->hasFile('cover_image')){
            $coverImagePath=$request->file('cover_image')->store('productCoverImage');
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


          if($request->file('product_image')){
            foreach($request->file('product_image') as $galleryImage){
            $productImagePath=$galleryImage->store('productImage');
              ProductGallery::create([
                'product_id'=>$product->id,
                'product_image'=>$productImagePath,
              ]);
            }
          }


          return redirect('/admin/products')->with('success','The product Updated successfully');
          //return $request->all();   
        }else{
          return back()->with('error','The product slug already exist');
        }
    }
    public function deleteProductImage($id){
      ProductGallery::where('id',$id)->delete();
      return back()->with('success','The product gallery image is deleted successfully');
    }
    public function productTrash($id){
      Product::where('id',$id)->delete();
      return back()->with('success','The product is moved to trash successfully');
    }
    public function restoreProduct($id){
      Product::where('id',$id)->restore();
      return back()->with('success','The product is restored to active products successfully');
    }

    public function deleteProduct($id){
      Product::where('id',$id)->forceDelete();
      return back()->with('success','The product is deleted permanently');
    }
    public function showOnHome(Request $request){
      Product::where('id',$request->product)->update(['show_homepage'=>$request->status]);
      return response()->json(['status'=>'done']);
    }
}
