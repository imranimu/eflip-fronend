@extends('admin/master')


@section('content')


<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{url('/admin/dashboard')}}">Admin</a>
        </li>
        <li class="breadcrumb-item active">Categories</li>
      </ol>
      <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#myModal">+ Add New</button>
        <h4>Categories</h4>

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
            <th>Sub-Categories</th>
            {{-- <th>Status</th> --}}
            {{-- <th>Action</th> --}}
          </tr>
        </thead>
        <tbody>
          @forelse($categories as $category)
          <tr>
            <td>{{$category->id}}</td>
            <td>{{$category->category_name}}
              (<small>{{$category->slug}}</small>)
              <a href="{{url('admin/category/edit/'.$category->id)}}" class="badge badge-primary"><i class="fa fa-edit"></i></a>
              <a href="{{url('admin/category/delete/'.$category->id)}}" class="badge badge-danger" onclick="return confirm('are you sure to delete the category permanently?')"></i><i class="fa fa-trash"></i></a>
            
            </td>
              <td>
                  <ul>
                      @forelse($category->getSubCategories as $subCategory)
                          <li> {{ $subCategory->name }}
                            (<small>{{$subCategory->slug}}</small>) 
                            <a href="{{url('admin/sub-category/edit/'.$subCategory->id)}}" class="badge badge-primary"><i class="fa fa-edit"></i></a>
                            <a href="{{url('admin/sub-category/delete/'.$subCategory->id)}}" class="badge badge-danger" onclick="return confirm('are you sure to delete the subcategory permanently?')"></i><i class="fa fa-trash"></i></a>
            
                          </li>
                      @empty
                          <li class="text-danger"> No SubCategory added</li>
                      @endforelse
                      <li>
                        <form action="{{url('/admin/sub-categories/add')}}" method="POST">
                          @csrf 
                          <input type="hidden" name="category_id" value="{{$category->id}}">
                          <div class="input-group  mb-3">
                            <input type="text" class="form-control" placeholder="SubCategory Name" name="title">
                            <input class="form-control" name="slug" type="text" value="" placeholder="{{__('SubCategory slug')}}">
                            <div class="input-group-append">
                              <button type="submit" class="btn btn-info" >Add</button>
                            </div>
                          </div>
                        </form> 
                      </li>
                  </ul>
              </td>
            {{-- <td>{{$category->category_status}}</td> --}}
            {{-- <td>

                @if($category->category_status == 'inactive')
                  <a href="{{url('admin/category/activate/'.$category->id)}}" class="btn btn-primary bg-success btn-sm">Activate</a>
                @else
                  <a href="{{url('admin/category/deactivate/'.$category->id)}}" class="btn btn-primary btn-sm">Deactivate</a>
                @endif
              <a href="{{url('admin/category/delete/'.$category->id)}}" class="btn btn-danger btn-sm" onclick="return confirm('are you sure to delete the category permanently?')"></i><i class="fa fa-trash"></i></a>
            </td> --}}
          </tr>
          @empty
          <tr>
            <td collspan="6"> No categories Found</td>
          </tr>
          @endforelse
        </tbody>
      </table>
      </div>
      </div>
      {{$categories->links()}}
	  </div>
	  <!-- /container-fluid-->
</div>

<!-- The Modal -->
<div class="modal" id="myModal">
  <div class="modal-dialog modal-md">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Add New Category</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
        <form action="{{url('/admin/categories/add')}}" method="POST"enctype="multipart/form-data">
      <!-- Modal body -->
      <div class="modal-body row">

          @csrf
           <div class="form-group col-lg-12">
             <label for="title">Title of Category:</label>
             <input type="text" name="title" value="" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="title">
           </div> 
           <div class="form-group col-lg-12">
            <label for="title">Slug of Category:</label>
            <input type="text" name="slug" value="" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}" id="slug">
          </div>
            {{-- <hr>
            <div  id="subCategory-fields">
              <div class="input-group col-lg-12"  id="subCategory-field">
                  <input class="form-control mt-1" name="subCategories[]" type="text" value="" placeholder="{{__('SubCategory name')}}">
                  <div class="input-group-append">
                    <input class="form-control mt-1" name="subCategorySlugs[]" type="text" value="" placeholder="{{__('SubCategory slug')}}">
                  </div>
              </div>
            </div>
            <button type="button" id="add-subCategory" class="btn btn-icon btn-info btn-sm ml-5"> <i class="mdi mdi-plus"></i>{{__('New SubCategory')}}</button> --}}

      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
          <button type="submit" class="btn btn-success btn-lg offset-lg-5">Add Category</button>
      </div>

        </form>
    </div>
  </div>
</div>

@endsection

@section('custom-js')
    <script>
        $('#add-subCategory').click(function(){
            $("#subCategory-field").clone().appendTo("#subCategory-fields").val('');
        });
    </script>
@endsection
