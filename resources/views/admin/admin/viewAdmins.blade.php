@extends('admin/master')
@section('content')


<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{url('/admin/dashboard')}}">Admin</a>
        </li>
        <li class="breadcrumb-item active">View Admins</li>
      </ol>
      <a href="{{ url('/admin/admins/add') }}" class="btn btn-success float-right text-white">+ Add New</a>
        <h4>Admins</h4>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session()->has('message'))
          <h3 class="text-success">{{session()->get('message')}}</h3>
        @endif
      <div class="box_general">


      <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($admins as $admin)
          <tr>
            <td>{{$admin->id}}</td>
            <td>{{$admin->name}}</td>
            <td>{{$admin->email}}</td>
            <td>{{$admin->role}}</td>
            <td>

                @if($admin->role == 'owner')
                  <a href="{{url('admin/profile')}}" class="btn btn-primary bg-success btn-sm"><i class="fa fa-edit"></i> Edit Profile</a>
                @else
                  <a href="{{url('admin/admin/delete/'.$admin->id)}}" class="btn btn-danger btn-sm" onclick="return confirm('are you sure to dlete the service permanently?')"><i class="fa fa-trash"></i> Delete</a>
                @endif
              </td>
          </tr>
          @empty
          <tr>
            <td collspan="6"> No Secondary Admins Found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
      </div>
      </div>
      {{$admins->links()}}
	  </div>
	  <!-- /container-fluid-->
</div>


@endsection
