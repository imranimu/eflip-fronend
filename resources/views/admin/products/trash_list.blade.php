@extends('admin/master')
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

  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{url('/admin/dashboard')}}">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Trashed Products</li>
      </ol>
      @if(session()->has('message'))
        <h3 class="text-success">{{session()->get('message')}}</h3>
      @endif
		<div class="box_general">
			<div class="header_box">
				<h2 class="d-inline-block">Trashed Products</h2>
				<div class="filter">
          <div class="btn-group">
            <a class="btn btn-primary" href="{{ url('/admin/product/add') }}"><i class="fa fa-plus"></i> Add</a>
            <a class="btn btn-success" href="{{ url('/admin/products') }}"><i class="fa fa-file"></i> Products</a>
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
              <th>Product</th>
              <th>Category</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($products as $product)
            <tr>
              <td>{{$product->order}}</td>
              <td>
                <div class="media">
                  <div class="media-left">
                    <img src="{{asset('public/uploads/'.$product->cover_image)}}" class="media-object" style="width:60px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading">{{$product->title }} (<small>{{$product->slug }}</small>)</h4>
                    <p class="text-muted">{{$product->meta_tags}}</p>
                    <p class="text-muted">{{$product->sub_title}}</p>
                  </div>
                </div>
                
                
              </td>
              <td>
                @if(!empty($product->getCategory))
                  {{$product->getCategory->category_name}}
                @endif
                @if(!empty($product->getSubCategory))
                    > {{$product->getSubCategory->name}}
                @endif
              </td>
     
              <td>
                  {{-- <li><a href="{{ url('/product/'.$product->id) }}" target="_blank" class="btn_1 bg-info"><i class="fa fa-fw fa-eye"></i> View</a></li>--}}
                  <a href="{{ url('/admin/product/delete/'.$product->id) }}" onclick="return confirm('are you sure to delete it permanently?')" class="btn_1 bg-danger delete wishlist_close"><i class="fa fa-fw fa-times-circle-o"></i></a>
                  <a href="{{ url('/admin/product/restore/'.$product->id) }}" class="btn_1 bg-success delete wishlist_close"><i class="fa fa-fw fa-retweet"></i></a>
             
              </td>
            </tr>

          @empty
            <tr>
              <td colspan="4"><h4 class='text-danger'>No product in trash</h4></td>
            </tr>
            
          @endforelse

          </tbody>
        </table>
			</div>
		</div>
		<!-- /box_general-->
		<!-- /pagination-->
	  </div>
	  <!-- /container-fluid-->
   	</div>
    <!-- /container-wrapper-->
@endsection
