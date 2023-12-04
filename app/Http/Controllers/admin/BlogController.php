<?php
namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Blog; 
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\ProductGallery;

class BlogController extends Controller
{
    public function lists(){
      $blogs=Blog::orderBy('order')->get();
      return view('admin/blogs/list',compact('blogs'));
    }

    public function details($id){
      $blogs = Blog::find($id);
      return view('admin/blogs/details',compact('blogs'));
    }

    /*public function viewshow($slug)
{
    $blog = Blog::where('slug','=', $slug)->firstOrFail();
        return view('admin/blogs/slug',compact('product'));
    }
*/

    
    public function trashed(){
      $blogs=Blog::onlyTrashed()->get();
      return view('admin/blogs/trash_list',compact('blogs'));
    }


    public function add(){
        $categories=Category::all();
      return view('admin/blogs/add',compact('categories'));

    }
    public function store(Request $request){
      $request->validate([
        'title'=>'required',
        'sub_title'=>'required',
        'slug'=>'required|alpha_dash|unique:blogs',
        'cover_image'=>'required|image',//|dimensions:width=355,height=200,
        'description'=>'required',
        'order'=>'required',
      ]);

      $coverImagePath=$request->file('cover_image')->store('productCoverImage');
      $blog=Blog::create([
        'title'=>$request->title,
        'sub_title'=>$request->sub_title,
        'meta_tags'=>$request->meta_tags,
        'slug'=>strtolower($request->slug),
        'cover_image'=>$coverImagePath,
        'description'=>$request->description,
        'meta_title'=>$request->meta_title,
        'meta_description'=>$request->meta_description,
        'order'=>$request->order,
      ]);


      return redirect('/admin/blogs')->with('success','Blog Added successfully');
      //return $request->all();


    }

    public function edit($id){
        $blog=Blog::where('id',$id)->first();
        return view('admin/blogs/edit',compact('blog'));
    }

    public function update(Request $request){
      $request->validate([
        'title'=>'required',
        'sub_title'=>'required',
        'slug'=>'required|alpha_dash|unique:blogs,id,'.$request->id,
        'description'=>'required',
        'order'=>'required',
      ]);

      $blog=Blog::where('id',$request->id)->first();
      $blog->update([
        'title'=>$request->title,
        'sub_title'=>$request->sub_title,
        'slug'=>strtolower($request->slug),
        'description'=>$request->description,
        'meta_title'=>$request->meta_title,
        'meta_description'=>$request->meta_description,
        'order'=>$request->order,
      ]);

      if($request->hasFile('cover_image')){
        $coverImagePath=$request->file('cover_image')->store('productCoverImage');
        $blog->update([
          'cover_image'=>$coverImagePath,
        ]);
      }


      return redirect('/admin/blogs')->with('success','The blog Updated successfully');
      //return $request->all();   
       
    }
    
    public function trash($id){
      Blog::where('id',$id)->delete();
      return back()->with('success','The blog is moved to trash successfully');
    }
    public function restore($id){
      Blog::where('id',$id)->restore();
      return back()->with('success','The blog is restored to active blogs successfully');
    }

    public function delete($id){
      Blog::where('id',$id)->forceDelete();
      return back()->with('success','The blog is deleted permanently');
    }
    public function showOnHome(Request $request){
      Blog::where('id',$request->product)->update(['show_homepage'=>$request->status]);
      return response()->json(['status'=>'done']);
    }
}
