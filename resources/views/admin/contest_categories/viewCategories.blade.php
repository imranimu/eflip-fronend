@extends('admin/master')


@section('content')


    <div class="box_general">


      <div class="table-responsive">
      <table class="table table-striped table-hover">
        <thead>
          <tr>
            <th>Order</th>
            <th>Name</th>
            {{-- <th>Status</th> --}}
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @forelse($categories as $category)
          <tr>
            <td>{{$category->order}}</td>
            <td>{{$category->name}}
              (<small>{{$category->slug}}</small>)
              
            </td>
            {{-- <td>{{$category->category_status}}</td> --}}
            <td>

              <a href="{{url('admin/contest/category/edit/'.$category->id)}}" class="btn btn-info btn-sm" ></i><i class="fa fa-edit"></i></a>
              <a href="{{url('admin/contest/category/delete/'.$category->id)}}" class="btn btn-danger btn-sm" onclick="return confirm('are you sure to delete the category permanently?')"></i><i class="fa fa-trash"></i></a>
            </td>
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
