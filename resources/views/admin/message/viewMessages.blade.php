@extends('admin/master')
@section('content')


<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{url('/admin/dashboard')}}">Admin</a>
        </li>
        <li class="breadcrumb-item active">Inbox</li>
      </ol>
      @if(session()->has('message'))
        <h3 class="text-success">{{session()->get('message')}}</h3>
      @endif
		<div class="box_general">
			<h4>Inbox <small><a href="{{url('/admin/inbox/trashed')}}" class=" btn btn-danger float-right"><i class="fa fa-trash"></i> Recycle Bin</a></small></h4>
			<div class="list_general">
				<ul>
            @forelse($messages as $message)
    					<li class="m-2 {{$message->status == 'unread'? 'bg-info' : 'bg-secondary'}}">
    						<span class="text-white">{{\Carbon\Carbon::parse($message->created_at)->diffForHumans()}}</span>
    						<figure><img src="{{asset('public/admin/img/avatar.png')}}" class="float-left" height="40px" alt=""></figure>

    						<h4>{{$message->name}}</h4>
                <p class="text-white"><i class="fa fa-envelope"> </i> {{$message->email}} | <i class="fa fa-phone"> </i> {{$message->phone_number}}</p>
                @if($message->status == 'unread')
                  <a href="{{url('/admin/inbox/read/'.$message->id)}}" class="btn btn-sm float-right btn-warning"><i class="fa fa-eye"></i> read</a>
                @else
                  <a href="{{url('/admin/inbox/unread/'.$message->id)}}" class="btn btn-sm float-right btn-success"><i class="fa fa-eye"></i> unread</a>
                  <a href="{{url('/admin/inbox/delete/'.$message->id)}}" class="btn btn-sm float-right btn-danger"><i class="fa fa-trash"></i> delete</a>
                @endif

                <p class="text-white">{{$message->message}}</p>

    					</li>
            @empty
              <li><h3 class="text-warning">No messages</h3></li>
            @endforelse
				</ul>
			</div>
		</div>
		<!-- /box_general-->
		{{ $messages->links() }}
		<!-- /pagination-->
	  </div>
	  <!-- /container-fluid-->
   	</div>

@endsection
