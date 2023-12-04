@extends('admin/master')
@section('content') 


<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{url('/admin/dashboard')}}">Admin</a>
        </li>
        <li class="breadcrumb-item active">Clicks</li>
      </ol>
      @if(session()->has('message'))
        <h3 class="text-success">{{session()->get('message')}}</h3>
      @endif
		<div class="box_general">
			<h4>Clicks  </h4>
			
      
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                  <th>Clicked Product</th>
                  <th>Affiliater</th>
                  {{-- <th>IP Address</th> --}}
                  <th>Clicked At</th>
                  {{-- <th>Action</th> --}}
                </tr>
              </thead>
              <tbody>
                @forelse ($clicks as $click)
                <tr>
                  <td>
                    <a href="{{ route('product.view',$click->getProduct->slug) }}" target="_blank" class="media">
                      <div class="media-left">
                        <img src="{{asset('public/storage/'.$click->getProduct->cover_image)}}" class="media-object border mr-2" style="width:90px">
                      </div>
                      <div class="media-body">
                        <h4 class="media-heading">{{$click->getProduct->title }}</h4>
                        <p class="text-muted mb-0">{{$click->getProduct->sub_title}}</p>
                      </div>
                    </a>
                    
                    
                  </td>
                  <td>
                    {{-- <a href="{{ route('product.view',$quotation->getProduct->slug) }}" target="_blank" class="media"> --}}
                      @if(!empty($click->getAffiliater))
                        <div class="media-left">
                          <img src="{{asset('public/storage/'.$click->getAffiliater->profile_picture)}}" class="media-object border mr-2" style="width:90px">
                        </div>
                        <div class="media-body">
                          <h4 class="media-heading">{{$click->getAffiliater->name }}</h4>
                          <p class="text-muted mb-0">{{$click->getAffiliater->job_title}}</p>
                        </div>
                      @endif
                    {{-- </a> --}}
                    
                    
                  </td>
                  {{-- <td>{{ $click->ip_address }}</td> --}}
                  <td>{{ $click->created_at->diffForHumans() }}</td>
                  {{-- <td>
                    <a href="#" data="{{ $click->details }}" class="btn btn-info">View Details</a>
                  </td> --}}
                </tr>
    
              @empty
                <tr>
                  <td colspan="4"><h4 class='text-danger'>No clicks found</h4></td>
                </tr>
                
              @endforelse
    
              </tbody>

        </table>
      </div>
      
      
		</div>
		<!-- /box_general-->
      {{ $clicks->links() }}
		<!-- /pagination-->
	  </div>
	  <!-- /container-fluid-->
   	</div>

@endsection
