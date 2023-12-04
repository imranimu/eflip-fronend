@extends('admin/master')
@section('content')


<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{url('/admin/dashboard')}}">Admin</a>
        </li>
        <li class="breadcrumb-item active">Quotations</li>
      </ol>
      @if(session()->has('message'))
        <h3 class="text-success">{{session()->get('message')}}</h3>
      @endif
		<div class="box_general">
			<h4>Quotations 
        <small>
          <form action="" class="d-inline" method="GET">
            <select name="status" onchange="this.form.submit()">
              <option value="">All Status</option>
              <option value="unseen"@isset($_GET['status']) {{ $_GET['status']=='unseen'?'selected':'' }} @endisset>Unseen</option>
              <option value="seen"@isset($_GET['status']) {{ $_GET['status']=='seen'?'selected':'' }} @endisset>Seen</option>
            </select>
            <select name="confirmed" onchange="this.form.submit()">
              <option value="">All Deals</option>
              <option value="yes"@isset($_GET['confirmed']) {{ $_GET['confirmed']=='yes'?'selected':'' }} @endisset>Confirmed</option>
              <option value="no"@isset($_GET['confirmed']) {{ $_GET['confirmed']=='no'?'selected':'' }} @endisset>Not Confirmed</option>
            </select>
          </form>
          <a href="{{url('/admin/quotations/trashed')}}" class=" btn btn-danger float-right">
          <i class="fa fa-trash"></i> Recycle Bin</a>
        </small>
      </h4>
			
      
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                  <th>Quoted Product</th>
                  <th>Affiliater</th>
                  <th>name</th>
                  <th>phone number</th>
                  <th>email</th>
                  <th>message</th>
                  <th>attachment</th>
                  <th>status</th>
                  <th>earned</th>
                  {{-- <th>budget</th>
                  <th>spendings</th> --}}
                  <th>Deal Confirmation</th>
                  <th>Quoted At</th>
                  <th>Action</th>
                  
                </tr>
              </thead>
              <tbody>
                @forelse ($quotations as $quotation)

                  <tr>
                    <td>
                      <a href="{{ route('product.view',$quotation->getProduct->slug) }}" target="_blank" class="media">
                        <div class="media-left">
                          <img src="{{asset('public/storage/'.$quotation->getProduct->cover_image)}}" class="media-object border mr-2" style="width:90px">
                        </div>
                        <div class="media-body">
                          <h4 class="media-heading">{{$quotation->getProduct->title }}</h4>
                          <p class="text-muted mb-0">{{$quotation->getProduct->sub_title}}</p>
                        </div>
                      </a>
                      
                      
                    </td>
                    <td>
                      {{-- <a href="{{ route('product.view',$quotation->getProduct->slug) }}" target="_blank" class="media"> --}}
                        @if(!empty($quotation->getAffiliater))
                          <div class="media-left">
                            <img src="{{asset('public/storage/'.$quotation->getAffiliater->profile_picture)}}" class="media-object border mr-2" style="width:90px">
                          </div>
                          <div class="media-body">
                            <h4 class="media-heading">{{$quotation->getAffiliater->name }}</h4>
                            <p class="text-muted mb-0">{{$quotation->getAffiliater->job_title}}</p>
                          </div>
                        @endif
                      {{-- </a> --}}
                      
                    </td>
                    <td>{{ $quotation->name }}</td>
                    <td>{{ $quotation->phone_number }}</td>
                    <td>{{ $quotation->email }}</td>
                    <td>{{ $quotation->message }}</td>
                    <td>
                      @if(!empty($quotation->attachment))
                        <a href="{{ asset('public/storage/'.$quotation->attachment) }}" target="_blank">View </a>
                      @endif
                    </td>
                    <td><span class="badge text-white {{ $quotation->status=='unseen'?'bg-danger':'bg-success' }}">{{ $quotation->status }}</span></td>
                    <td>
                      ৳ {{ $quotation->earned }} 
                      
                      @if($quotation->is_confirmed == 'yes')
                        <a href="#" class="change-earning" data-id="{{ $quotation->id }}" data-amount="{{ $quotation->earned }}" data-toggle="modal" data-target="#myModal"><i class="fa fa-edit"></i></a>
                      @endif
                    </td>
                    {{-- <td>{{ $quotation->budget }}</td>
                    <td>{{ $quotation->spendings }}</td> --}}
                    <td><span class="badge text-white {{ $quotation->is_confirmed=='no'?'bg-danger':'bg-success' }}">{{ $quotation->is_confirmed }}</span></td>
                    <td>{{ $quotation->created_at->diffForHumans() }}</td>
                    <td>   
                      @if($quotation->is_confirmed == 'yes')
                        <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#myModal">Earned</button>
                        <a href="{{url('/admin/quotations/confirmation/'.$quotation->id)}}" class="btn btn-sm float-right btn-warning">&times; Not Confirmed</a>
                      @else
                        <a href="{{url('/admin/quotations/confirmation/'.$quotation->id)}}" class="btn btn-sm float-right btn-primary"><i class="fa fa-check"></i> confirmed</a>
                      @endif

                      @if($quotation->status == 'unseen')
                        <a href="{{url('/admin/quotations/seen/'.$quotation->id)}}" class="btn btn-sm float-right btn-warning"><i class="fa fa-eye"></i> seen</a>
                      @else
                        <a href="{{url('/admin/quotations/unseen/'.$quotation->id)}}" class="btn btn-sm float-right btn-success"><i class="fa fa-eye"></i> unseen</a>
                        <a href="{{url('/admin/quotations/delete/'.$quotation->id)}}" class="btn btn-sm float-right btn-danger"><i class="fa fa-trash"></i> delete</a>
                      @endif
                    </td>
                  </tr>
      
                @empty
                  <tr>
                    <td colspan="4"><h4 class='text-danger'>No quotations found</h4></td>
                  </tr>
                  
                @endforelse
    
              </tbody>

        </table>
      </div>
      
		</div>
		<!-- /box_general-->
      {{ $quotations->links() }}
		<!-- /pagination-->
	  </div>
	  <!-- /container-fluid-->
   	</div>
<!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog modal-md">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Change Earned Amount for affiliater</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
        <form action="{{url('/admin/quotation/earned')}}" method="POST">
      <!-- Modal body -->
      <div class="modal-body row">
          <input type="hidden" id="quotation-id" name="id" value="">
          @csrf
           <div class="form-group col-lg-12">
             <label for="earned">Affiliater's Earning:</label>
             <input type="text" name="earned" value="" class="form-control{{ $errors->has('earned') ? ' is-invalid' : '' }}" id="earned">
           </div> 
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
          <button type="submit" class="btn btn-success btn-lg offset-lg-5">Update</button>
      </div>

        </form>
    </div>
  </div>
</div>
@endsection

@section('custom-js')
    <script>
        $('.change-earning').click(function(){
            var id=$(this).attr('data-id');
            var amount=$(this).attr('data-amount');
            $("#quotation-id").val(id);
            $("#earned").val(amount);
        });
    </script>
@endsection