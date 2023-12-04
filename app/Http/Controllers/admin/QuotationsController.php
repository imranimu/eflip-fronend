<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\ProductClick;
use App\Models\QuotationRequest;
use App\Models\ServiceClick;
use Illuminate\Http\Request;

class QuotationsController extends Controller
{
    public function index(){
        $query=QuotationRequest::query();
        
        if(!empty($_GET['status'])){
          $query->where('status',$_GET['status']);
        }
        
        if(!empty($_GET['service'])){
          $query->where('service_type',$_GET['service']);
        }

       
        $quotations=$query->orderByDesc('id')->paginate(10);

        return view('admin.quotations.index',compact('quotations'));
    }

    
  public function viewMessage(){
    $messages=QuotationRequest::orderByDesc('id')->simplePaginate(5);
    $newMessages=QuotationRequest::where('status','unread')->orderby('id','desc')->get();
    return view('admin/message/viewMessages',['messages'=>$messages,'newMessages'=>$newMessages]);
  }
 

  public function statusQuotation($status,$id){
      QuotationRequest::where('id',$id)->update([
        'status'=>$status
      ]);
      return back()->with('success','The quotation marked as '.$status);
  }
  public function deleteMessage($id){
      $messageById=QuotationRequest::where('id',$id)->delete();
      return back()->with('success','The quotation is deleted successfully');
  }
  public function trashedMessage(){
      $newMessages=QuotationRequest::where('status','1')->orderby('id','desc')->get();
      $quotations=QuotationRequest::onlyTrashed()->paginate(5);
      return view('admin/quotations/trash',['quotations'=>$quotations,'newMessages'=>$newMessages]);
  }

  public function parmanentDeleteMessage($id){
    $messageById=QuotationRequest::where('id',$id)->forceDelete();
    return back()->with('success','The message is deleted permanently');
  }

  public function restoreMessage($id){
    $messageById=QuotationRequest::where('id',$id)->restore();
    return back()->with('success','The message is Restored successfully');
  }

  public function clearAllMessage(){
    $messageById=QuotationRequest::onlyTrashed()->forceDelete();
    return back()->with('success','All quotations are deleted permanently');
  }
  public function restoreAllMessage(){
    $messageById=QuotationRequest::onlyTrashed()->restore();
    return back()->with('success','All quotations are Restored successfully');
  }

  public function clicks(){
      $clicks=ServiceClick::orderByDesc('id')->paginate(10);
      return view('admin.clicks.index',compact('clicks'));
  }

}
