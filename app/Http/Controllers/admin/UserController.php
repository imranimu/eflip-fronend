<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    
    public function index(){
        $users=User::orderByDesc('id')->paginate(10);
        return view('admin.user.index',compact('users'));
    }

    public function delete($id){
        $messageById=User::where('id',$id)->delete();
        return back()->with('success','The user is deleted successfully');
    }
}
