@extends('admin/master')
@section('content') 


<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{url('/admin/dashboard')}}">Admin</a>
        </li>
        <li class="breadcrumb-item active">Affiliaters</li>
      </ol>
      @if(session()->has('message'))
        <h3 class="text-success">{{session()->get('message')}}</h3>
      @endif
		<div class="box_general">
			<h4>Affiliaters  </h4>
			
      
      <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                  <th>User</th>
                  <th>Phone</th>
                  <th>Email</th>
                  <th>Clicks</th>
                  <th>Quotations</th>
                  <th>Confirmed quotations</th>
                  <th>Joined At</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                @forelse ($users as $user)
                <tr>
                  <td>
                    {{-- <a href="{{ route('product.view',$users->slug) }}" target="_blank" class="media"> --}}
                      <div class="media-left">
                        <img src="{{asset('public/storage/'.$user->profile_picture)}}" class="media-object border mr-2" style="width:90px">
                      </div>
                      <div class="media-body">
                        <h4 class="media-heading">{{$user->name }}</h4>
                        <p class="text-muted mb-0">{{$user->job_title}}</p>
                      </div>
                    {{-- </a> --}}
                    
                    
                  </td>
                  <td>{{ $user->phone_number }}</td>
                  <td>{{ $user->email }}</td>
                  <td>{{ count($user->getProductClicks) }}</td>
                  <td>{{ count($user->getProductQuotations) }}</td>
                  <td>{{ count($user->getConfirmedProductQuotations) }}</td>
                  <td>{{ $user->created_at->diffForHumans() }}</td>
                  <td>
                    <a href="{{url('/admin/users/delete/'.$user->id)}}" onclick="return confirm('Are you sure to delete?')" class="btn btn-sm float-right btn-danger">
                      <i class="fa fa-trash"></i> delete</a>
                      
                  </td>
                </tr>
    
              @empty
                <tr>
                  <td colspan="4"><h4 class='text-danger'>No users found</h4></td>
                </tr>
                
              @endforelse
    
              </tbody>

        </table>
      </div>
      
      
		</div>
		<!-- /box_general-->
      {{ $users->links() }}
		<!-- /pagination-->
	  </div>
	  <!-- /container-fluid-->
   	</div>

@endsection
