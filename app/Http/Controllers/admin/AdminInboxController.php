<?php

namespace App\Http\Controllers\admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\AdminInbox;
use Session;

class AdminInboxController extends Controller
{

  //public function __construct(){Admin::where('id',Session::get('adminId'))->Where('email',Session::get('email'))->firstOrFail();}

  public function viewMessage(){
        $messages=AdminInbox::orderByDesc('id')->simplePaginate(5);
        $newMessages=AdminInbox::where('status','unread')->orderby('id','desc')->get();
    return view('admin/message/viewMessages',['messages'=>$messages,'newMessages'=>$newMessages]);
  }
  public function readMessage($id){
        $messageById=AdminInbox::where('id',$id)->update([
          'status'=>'read'
        ]);
    return back()->with('success','The message marked as read');
  }
  public function unreadMessage($id){
        $messageById=AdminInbox::where('id',$id)->update([
          'status'=>'unread'
        ]);
    return back()->with('success','The message marked as read');
  }
  public function deleteMessage($id){
        $messageById=AdminInbox::where('id',$id)->delete();
        return back()->with('success','The message is deleted successfully');
    }
    public function trashedMessage(){
          $newMessages=AdminInbox::where('status','1')->orderby('id','desc')->get();
          $trashedMessages=AdminInbox::onlyTrashed()->paginate(5);
      return view('admin/message/trashedMessages',['trashedMessages'=>$trashedMessages,'newMessages'=>$newMessages]);
    }


  public function parmanentDeleteMessage($id){
      $messageById=AdminInbox::where('id',$id)->forceDelete();
      return back()->with('success','The message is deleted permanently');
  }
  public function restoreMessage($id){
      $messageById=AdminInbox::where('id',$id)->restore();
      return back()->with('success','The message is Restored successfully');
  }

  public function clearAllMessage(){
      $messageById=AdminInbox::onlyTrashed()->forceDelete();
      return back()->with('success','All messages are deleted permanently');
  }
  public function restoreAllMessage(){
      $messageById=AdminInbox::onlyTrashed()->restore();
      return back()->with('success','All messages are Restored successfully');
  }
}
