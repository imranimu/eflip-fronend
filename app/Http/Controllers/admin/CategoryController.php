<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;

use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
  public function categories(){
    $categories=Category::orderby('id','desc')->paginate(12);
    return view('admin/categories/viewCategories',compact('categories'));
  }

  public function addCategory(Request $request){
    $request->validate([
      'title'=>'required|string',
      'slug'=>'required|alpha_dash|unique:categories',
    ]);
    $category=Category::create([
      'category_name'=>$request->title,
      'slug'=>strtolower($request->slug),
    ]);
  // foreach($request->subCategories as $subCategory)
  //     if($subCategory != ''){
  //         SubCategory::create([
  //             'name'=>$subCategory,
  //             'category_id'=>$category->id,
  //         ]);
  //     }
    return back()->with('success','New Category and SubCategories Added successfully');
  }

  public function addSubCategory(Request $request){
    $request->validate([
      'title'=>'required|string',
      'category_id'=>'required|numeric',
      'slug'=>'required|alpha_dash|unique:sub_categories',
    ]);
    $category=SubCategory::create([
      'name'=>$request->title,
      'slug'=>strtolower($request->slug),
      'category_id'=>$request->category_id,
    ]);
    return back()->with('success','New SubCategory Added successfully');
  }


  public function editCategory($id){
    $categoryById=Category::where('id',$id)->first();
    return view('admin/categories/editCategory',['categoryById'=>$categoryById]);
  }
  public function editSubCategory($id){
    $subCategoryById=SubCategory::where('id',$id)->first();
    return view('admin/categories/editSubCategory',['subCategoryById'=>$subCategoryById]);
  }

  public function updateCategory(Request $request){
    $request->validate([
      'title'=>'required|string',  
      'meta_tags'=>'required|string', 
      'slug'=>'required|alpha_dash',
    ]);
    $isExistSlug=Category::where('id','!=',$request->id)->where('slug',$request->slug)->first();
    if(empty($isExistSlug)){
      $category=Category::where('id',$request->id)->first();
          $category->update([
            'category_name'=>$request->title,
            'meta_tags'=>$request->meta_tags,
            'slug'=>strtolower($request->slug),
            'header_description'=>$request->header_description,
            'footer_description'=>$request->footer_description,
          ]);

      return redirect('/admin/categories')->with('success','The Category and descriptions are updated successfully');
    }else{
      return back()->with('error','The Category slug already exist');
    }
  }

  public function updateSubCategory(Request $request){
    $request->validate([
      'title'=>'required|string',      
      'meta_tags'=>'required|string',      
      'slug'=>'required|alpha_dash',
    ]);
    $isExistSlug=SubCategory::where('id','!=',$request->id)->where('slug',$request->slug)->first();
    if(empty($isExistSlug)){

      $category=SubCategory::where('id',$request->id)->first();
          $category->update([
            'name'=>$request->title,
            'slug'=>strtolower($request->slug),
            'meta_tags'=>$request->meta_tags,
            'header_description'=>$request->header_description,
            'footer_description'=>$request->footer_description,
          ]);
      return redirect('/admin/categories')->with('success','The SubCategory and descriptions are updated successfully');
    }else{
      return back()->with('error','The SubCategory slug already exist');
    }
  }

//activate Category
  public function activateCategory($id){
    Category::where('id',$id)->update([
      'category_status'=>'active'
    ]);
    return back()->with('success','The Category Section is Activated');
  }
//deactivate Category
  public function deactivateCategory($id){

    Category::where('id',$id)->update([
      'category_status'=>'inactive'
    ]);
    return back()->with('success','The Category Section is De-Activated');
  }

  public function deleteCategory($id){
    Category::where('id',$id)->delete();
    return back()->with('success','The Category is Deleted');
  }
  public function deleteSubCategory($id){
    SubCategory::where('id',$id)->delete();
    return back()->with('success','The SubCategory is Deleted');
  }
}
