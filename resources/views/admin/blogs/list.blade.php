@extends('admin/master')
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

      $('.show-product-in-home').click(function(){
              var product=$(this).attr('data');
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
                  url: '{{url("/admin/blog/show-on-home")}}',
                  type:"POST",
                  data:{product:product,status:status},
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
				<h2 class="d-inline-block">Blogs</h2>
				<div class="filter">
          <div class="btn-group">
            <a class="btn btn-primary" href="{{ url('/admin/blog/add') }}"><i class="fa fa-plus"></i> Add</a>
            <a class="btn btn-warning" href="{{ url('/admin/blog/trash') }}"><i class="fa fa-trash"></i> Trash</a>
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
              <th>Blog</th>
              <th>Show Homepage</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($blogs as $blog)
              <tr>
                <td>{{$blog->order}}</td>
                <td>
                  <div class="media">
                    <div class="media-left">
                      <img src="{{asset('public/storage/'.$blog->cover_image)}}" class="media-object" style="width:60px">
                    </div>
                    <div class="media-body">
                      <h4 class="media-heading">{{$blog->title }}(<small>{{$blog->slug }}</small>)</h4>
                      <p class="text-muted">{{$blog->meta_tags}}</p>
                      <p class="text-muted">{{$blog->sub_title}}</p>
                    </div>
                  </div>
                  
                  
                </td>
                <td>
                  <label class="switch">
                    <input type="checkbox" class="show-product-in-home" data="{{$blog->id}}"{{$blog->show_homepage==1?'checked':''}}>
                    <span class="slider round"></span>
                  </label>
                </td>
                <td>
                    {{-- <li><a href="{{ url('/product/'.$blog->id) }}" target="_blank" class="btn_1 bg-info"><i class="fa fa-fw fa-eye"></i> View</a></li>--}}
                    <a href="{{ url('/admin/blog/edit/'.$blog->id) }}" class="btn_1 bg-info"><i class="fa fa-fw fa-edit"></i></a>
                    <a href="{{ url('/admin/blog/trash/'.$blog->id) }}" class="btn_1 yellow delete wishlist_close"><i class="fa fa-fw fa-trash"></i></a>
                  
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="4"><h4 class='text-danger'>No blog is added</h4></td>
              </tr>
            @endforelse

          </tbody>
        </table>
			</div>
		</div>
    
@endsection
