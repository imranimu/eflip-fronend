@extends('admin/master')

@section('breadcrumbs')

  <li class="breadcrumb-item">
    <a href="{{url('/admin/services')}}">Services</a>
  </li>
  <li class="breadcrumb-item active">Trash</li>
@endsection

@section('custom-css')
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
@endsection
@section('custom-js')
  <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
  <script>

    $(document).ready( function () {
      $('#dataTable').DataTable();
    });

  </script>
@endsection

@section('content')

		<div class="box_general">
			<div class="header_box">
				<h2 class="d-inline-block">Trashed services</h2>
				<div class="filter">
          <div class="btn-group">
            <a class="btn btn-primary" href="{{ url('/admin/service/add') }}"><i class="fa fa-plus"></i> Add</a>
            <a class="btn btn-success" href="{{ url('/admin/services') }}"><i class="fa fa-file"></i> services</a>
          </div>
        <!--	<select name="orderby" class="selectbox">
						<option value="Any time">Any time</option>
						<option value="Latest">Latest</option>
						<option value="Oldest">Oldest</option>
					</select> -->
				</div>
			</div>
			<div class="list_general">
				<table class="table table-bordered table-striped" id="dataTable">
          <thead>
            <tr>
              <th>Sort</th>
              <th>service</th>
              <th>Category</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($services as $service)
            <tr>
              <td>{{$service->order}}</td>
              <td>
                <div class="media">
                  <div class="media-left">
                    <img src="{{asset('public/uploads/'.$service->cover_image)}}" class="media-object" style="width:60px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading">{{$service->title }} (<small>{{$service->slug }}</small>)</h4>
                    <p class="text-muted">{{$service->meta_tags}}</p>
                    <p class="text-muted">{{$service->sub_title}}</p>
                  </div>
                </div>
                
                
              </td>
              <td>
                @if(!empty($service->getCategory))
                  {{$service->getCategory->category_name}}
                @endif
                @if(!empty($service->getSubCategory))
                    > {{$service->getSubCategory->name}}
                @endif
              </td>
     
              <td>
                  {{-- <li><a href="{{ url('/service/'.$service->id) }}" target="_blank" class="btn_1 bg-info"><i class="fa fa-fw fa-eye"></i> View</a></li>--}}
                  <a href="{{ url('/admin/service/delete/'.$service->id) }}" onclick="return confirm('are you sure to delete it permanently?')" class="btn_1 bg-danger delete wishlist_close"><i class="fa fa-fw fa-times-circle-o"></i></a>
                  <a href="{{ url('/admin/service/restore/'.$service->id) }}" class="btn_1 bg-success delete wishlist_close"><i class="fa fa-fw fa-retweet"></i></a>
             
              </td>
            </tr>

          @empty
            <tr>
              <td colspan="4"><h4 class='text-danger'>No service in trash</h4></td>
            </tr>
            
          @endforelse

          </tbody>
        </table>
			</div>
		</div>

@endsection
