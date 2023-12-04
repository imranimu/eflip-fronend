<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;

use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Models\EssayContestCategory;
use Str;

class ContestCategoryController extends Controller
{

  public function categories(){
    $categories=EssayContestCategory::orderby('order')->paginate(12);
    return view('admin/contest_categories/viewCategories',compact('categories'));
  }

  public function addCategory(Request $request){
    $request->validate([
      'title'=>'required|string',
      'slug'=>'required|alpha_dash|unique:categories',
    ]);
    $category=EssayContestCategory::create([
      'category_name'=>$request->title,
      'slug'=>strtolower($request->slug),
    ]);
  // foreach($request->subCategories as $subCategory)
  //     if($subCategory != ''){
  //         SubEssayContestCategory::create([
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
    $category=SubEssayContestCategory::create([
      'name'=>$request->title,
      'slug'=>strtolower($request->slug),
      'category_id'=>$request->category_id,
    ]);
    return back()->with('success','New SubCategory Added successfully');
  }


  public function editCategory($id){
    $categoryById=EssayContestCategory::where('id',$id)->first();
    return view('admin/contest_categories/editCategory',['categoryById'=>$categoryById]);
  }
  public function editSubCategory($id){
    $subCategoryById=SubEssayContestCategory::where('id',$id)->first();
    return view('admin/contest_categories/editSubCategory',['subCategoryById'=>$subCategoryById]);
  }

  public function updateCategory(Request $request){
    $request->validate([
      'name'=>'required|string',  
      'order'=>'required|numeric',
    ]);
    
    $category=EssayContestCategory::where('id',$request->id)->first();
        $category->update([
          'name'=>$request->name,
          'order'=>$request->order,
          'slug'=>Str::slug($request->name),
        ]);

    return redirect('/admin/contest/categories')->with('success','The Category and descriptions are updated successfully');
   
  }

  public function updateSubCategory(Request $request){
    $request->validate([
      'title'=>'required|string',      
      'meta_tags'=>'required|string',      
      'slug'=>'required|alpha_dash',
    ]);
    $isExistSlug=SubEssayContestCategory::where('id','!=',$request->id)->where('slug',$request->slug)->first();
    if(empty($isExistSlug)){

      $category=SubEssayContestCategory::where('id',$request->id)->first();
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
    EssayContestCategory::where('id',$id)->update([
      'category_status'=>'active'
    ]);
    return back()->with('success','The Category Section is Activated');
  }
//deactivate Category
  public function deactivateCategory($id){

    EssayContestCategory::where('id',$id)->update([
      'category_status'=>'inactive'
    ]);
    return back()->with('success','The Category Section is De-Activated');
  }

  public function deleteCategory($id){
    EssayContestCategory::where('id',$id)->delete();
    return back()->with('success','The Category is Deleted');
  }
  public function deleteSubCategory($id){
    SubEssayContestCategory::where('id',$id)->delete();
    return back()->with('success','The SubCategory is Deleted');
  }
}
