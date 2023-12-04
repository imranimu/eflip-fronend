@extends('admin/master')

@section('breadcrumbs')
  <li class="breadcrumb-item active">services</li>
@endsection

@section('custom-css')

 <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
 <style>
    /* The switch - the box around the slider */
    .switch {
      position: relative;
      display: inline-block;
      width: 60px;
      height: 34px;
    }

    /* Hide default HTML checkbox */
    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }

    /* The slider */
    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      -webkit-transition: .4s;
      transition: .4s;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 26px;
      width: 26px;
      left: 4px;
      bottom: 4px;
      background-color: white;
      -webkit-transition: .4s;
      transition: .4s;
    }

    input:checked + .slider {
      background-color: #2196F3;
    }

    input:focus + .slider {
      box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
      -webkit-transform: translateX(26px);
      -ms-transform: translateX(26px);
      transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
      border-radius: 34px;
    }

    .slider.round:before {
      border-radius: 50%;
    }
 </style>

@endsection
@section('custom-js')
  <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
  <script>
    $(document).ready( function () {
      $('#dataTable').DataTable();

      $('.show-service-in-home').click(function(){
            var service=$(this).attr('data');
            if($(this).prop('checked')){
                  var status=1;
            }else{
                  var status=0;
            }
              $.ajaxSetup({
                  headers: {
                      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                  }
              });

              $.ajax({
                  url: '{{url("/admin/service/show-on-home")}}',
                  type:"POST",
                  data:{service:service,status:status},
                  success:function(data) {
                    toastr.success("Done");
                  }

              });
          });
      });
  </script>
@endsection

@section('content')

  
		<div class="box_general">
			<div class="header_box">
				<h2 class="d-inline-block">services</h2>
				<div class="filter">
          <div class="btn-group">
            <a class="btn btn-primary" href="{{ url('/admin/service/add') }}"><i class="fa fa-plus"></i> Add</a>
            <a class="btn btn-warning" href="{{ url('/admin/service/trash') }}"><i class="fa fa-trash"></i> Trash</a>
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
              <th>Service</th>
              <th>In Homepage</th>
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
                    <img src="{{asset('/storage/'.$service->cover_image)}}" class="media-object mr-1" style="width:60px">
                  </div>
                  <div class="media-body">
                    <h4 class="media-heading">{{$service->title }}(<small>{{$service->slug }}</small>)</h4>
                    <p class="text-muted">{{$service->sub_title}}</p>
                  </div>
                </div>
                
                
              </td>
              <td>
                <label class="switch">
                  <input type="checkbox" class="show-service-in-home" data="{{$service->id}}"{{$service->show_homepage==1?'checked':''}}>
                  <span class="slider round"></span>
                </label>
              </td>
              <td>
                  <a href="{{ url('/service/'.$service->slug) }}" target="_blank" class="btn_1 bg-primary"><i class="fa fa-fw fa-eye"></i></a>
                  <a href="{{ url('/admin/service/edit/'.$service->id) }}" class="btn_1 bg-info"><i class="fa fa-fw fa-edit"></i></a>
                  <a href="{{ url('/admin/service/trash/'.$service->id) }}" class="btn_1 yellow delete wishlist_close"><i class="fa fa-fw fa-trash"></i></a>
                
              </td>
            </tr>

          @empty
            <tr>
              <td colspan="4"><h4 class='text-danger'>No service is added</h4></td>
            </tr>
            
          @endforelse

          </tbody>
        </table>
			</div>
		</div>
@endsection
