@extends('admin/master')
@section('content')


<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{url('/admin/dashboard')}}">Admin</a>
        </li>
        <li class="breadcrumb-item active"><a href="{{url('admin/inbox')}}">Inbox</a></li>
        <li class="breadcrumb-item active">Recycle Bin</li>
      </ol>
      @if(session()->has('message'))
        <h3 class="text-success">{{session()->get('message')}}</h3>
      @endif
		<div class="box_general">
      <div class="row">
          <h4 class="col-lg-8">Recycle Bin</h4>
          @if(count($trashedMessages)>0)
            <span class="col-lg-2"><a href="{{url('/admin/inbox/trashed/clear')}}" onclick="return confirm('are you sure to Delete all messages?')" class="text-danger">Clear Recycle Bin</a></span>
            <span class="col-lg-2"><a href="{{url('/admin/inbox/trashed/restore')}}" class="">restore all</a></span>
          @endif
      </div>

			<div class="list_general">
				<ul>
            @forelse($trashedMessages as $message)
    					<li>
    						<span>{{\Carbon\Carbon::parse($message->created_at)->diffForHumans()}}

                  <a href="{{url('/admin/inbox/restore/'.$message->id)}}" class="btn btn-success btn-sm">Restore</a>

                  <a href="{{url('/admin/inbox/parmanent-delete/'.$message->id)}}"  onclick="return confirm('are you sure to Delete the message?')" class="btn btn-danger btn-sm">delete parmanently</a>
                </span>
    						<figure><img src="{{asset('public/admin/img/avatar.png')}}" alt=""></figure>
    						<h4>{{$message->name}}</h4>
                <p class="text-secondary"><i class="fa fa-envelope"> </i>{{$message->email}} | <i class="fa fa-phone"> </i> {{$message->phone}}</p>
    						<p><i>"{{$message->message}}"</i></p>
    					</li>
            @empty
              <li><h3 class="text-warning">No messages in Recycle Bin</h3></li>
            @endforelse
				</ul>
			</div>
		</div>
		<!-- /box_general-->
		{{ $trashedMessages->links() }}
		<!-- /pagination-->
	  </div>
	  <!-- /container-fluid-->
   	</div>

@endsection
