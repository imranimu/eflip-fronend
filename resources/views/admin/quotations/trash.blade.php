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
			<h4>Quotations <small>
        <a href="{{url('/admin/quotations')}}" class=" btn btn-success float-right">
        <i class="fa fa-check"></i> Active Quotations</a></small>
      </h4>
			
      
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                  <th>Quoted Product</th>
                  <th>Affiliater</th>
                  <th>name</th>
                  <th>phone_number</th>
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
                    <td>{{ $quotation->status }}</td>
                    <td>৳ {{ $quotation->earned }}</td>
                    {{-- <td>{{ $quotation->budget }}</td>
                    <td>{{ $quotation->spendings }}</td> --}}
                    <td>{{ $quotation->is_confirmed }}</td>
                    <td>{{ $quotation->created_at->diffForHumans() }}</td>
                    <td>   
                      <a href="{{url('/admin/quotations/restore/'.$quotation->id)}}" class="btn btn-success btn-sm">Restore</a>

                      <a href="{{url('/admin/quotations/parmanent-delete/'.$quotation->id)}}"  onclick="return confirm('are you sure to Delete the quotation?')" class="btn btn-danger btn-sm">delete parmanently</a>
                    
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

@endsection
