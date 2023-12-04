@extends('admin/master')
@section('content')

  <div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{url('/dashboard')}}">Dashboard</a>
        </li>
        <li class="breadcrumb-item active">Add Product</li>
      </ol>

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


		<div class="box_general padding_bottom">
			<div class="header_box version_2">
				<h2><i class="fa fa-file"></i>Basic info</h2>
			</div>
      <form action="{{ url('/admin/product/store') }}" method="post" enctype="multipart/form-data">
        @csrf
			<div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Product Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" value='{{old('title')}}' class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="Product Title" required>
                        @if ($errors->has('title'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('title') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Cover Image(355px*200px)<span class="text-danger">*</span></label>
                        <input type="file" name="cover_image" value='{{old('cover_image')}}' class="form-control {{ $errors->has('cover_image') ? ' is-invalid' : '' }}" required>
                        @if ($errors->has('cover_image'))
                            <span class="help-block text-danger">
                        <strong>{{ $errors->first('cover_image') }}</strong>
                    </span>
                        @endif
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Promo Video</label>
                        <input type="file" name="promo_video" value='{{old('promo_video')}}' class="form-control {{ $errors->has('promo_video') ? ' is-invalid' : '' }}">
                        @if ($errors->has('promo_video'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('promo_video') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>Product Sub-Title <span class="text-danger">*</span></label>
                        <input type="text" name="sub_title" value='{{old('sub_title')}}' class="form-control {{ $errors->has('sub_title') ? ' is-invalid' : '' }}" placeholder="Product Sub-Title" required>
                        @if ($errors->has('sub_title'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('sub_title') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="form-group">
                        <label>Product Meta Tags <span class="text-danger">*</span></label>
                        <input type="text" name="meta_tags" value='{{old('meta_tags')}}' class="form-control {{ $errors->has('meta_tags') ? ' is-invalid' : '' }}" placeholder="write seperating by comma(,)" required>
                        @if ($errors->has('meta_tags'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('meta_tags') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Product Slug <span class="text-danger">*</span></label>
                        <input type="text" name="slug" value='{{old('slug')}}' class="form-control {{ $errors->has('slug') ? ' is-invalid' : '' }}" placeholder="Product Slug" required>
                        @if ($errors->has('slug'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('slug') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Category<span class="text-danger">*</span></label>
                        <select name="category_id" id="course-category" class="form-control {{ $errors->has('category_id') ? ' is-invalid' : '' }}" required>
                            <option value="">Select Category</option>
                            @forelse($categories as $category)
                                <option value="{{$category->id}}">{{$category->category_name}}</option>
                            @empty
                            @endforelse
                        </select>
                        @if ($errors->has('category_id'))
                            <span class="help-block text-danger">
                        <strong>{{ $errors->first('category_id') }}</strong>
                    </span>brand_id
                        @endif
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>SubCategory</label>
                        <select  class="form-control {{ $errors->has('sub_category_id') ? ' is-invalid' : '' }}" id="course-sub_category" name="sub_category_id" title="sub_category">
                            <option value="">Select SubCategory</option>
                        </select>
                        @if($errors->has('sub_category_id'))
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $errors->first('sub_category_id') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>



                <div class="col-md-4">
                    <div class="form-group">
                        <label>Sort Order <span class="text-danger">*</span></label>
                        <input type="number" name="order" value='{{count(App\Models\Product::all())+1}}' class="form-control {{ $errors->has('Sort order of Product') ? ' is-invalid' : '' }}" placeholder="Sorting order of Product" required>
                        @if ($errors->has('order'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('order') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>Product Link</label>
                        <input type="url" name="link" value='{{old('link')}}' class="form-control {{ $errors->has('link') ? ' is-invalid' : '' }}" placeholder="https://......">
                        @if ($errors->has('link'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('link') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Product Tools</label>
                        <input type="text" name="tools" value='{{old('tools')}}' class="form-control {{ $errors->has('tools') ? ' is-invalid' : '' }}" placeholder="html,css,php, laravel...........">
                        @if ($errors->has('tools'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('tools') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>

			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label>Description<span class="text-danger">*</span></label>
						<textarea rows="5" name="description" id="editor" class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}" style="height:100px;" placeholder="Describe the product with stylish document editor. this design will be same to same in the public page.">{{old('description')}}</textarea>
                        @if ($errors->has('description'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                        @endif
					</div>
				</div>
			</div>

           
	    </div>

         <!-- /box_general-->
         <div class="box_general padding_bottom">
            <div class="header_box version_2">
                <h2><i class="fa fa-image"></i>Images Gallery</h2>
            </div>
            <!-- /row-->
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Images<span class="text-danger">*</span>(1366px*768px Multiple Images Support)</label>
                        <input name="product_image[]" type="file" onchange="previewImg(this)" multiple class="form-control {{ $errors->has('product_image') ? ' is-invalid' : '' }}" required>
                        @if ($errors->has('product_image'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('product_image') }}</strong>
                            </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <div class="table-responsive">
                        <table class="">
                            <tr>
                            <td id="previewImages"></td>
                            </td>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
		

		<button type="submit" class="btn_1 medium">Add Product</button>
  </form>
	  </div>
	  <!-- /.container-fluid-->
   	</div>
    <!-- /.container-wrapper-->
  @endsection
  @section('custom-css')
  
  @endsection
  @section('custom-js')

    @include('components/tinymce')
    <script>
    function previewImg(input){
			if (input.files){
				$('#previewImages').empty('');
				var totalFiles=input.files.length;

				//preview multiple file
				for(i=0;i < totalFiles; i++){
					var reader = new FileReader();
					reader.onload = function (e) {
						$('#previewImages').append("<img src='"+e.target.result+"'height='45px' style='border:1px solid gray' alt='' />");
					};
					reader.readAsDataURL(input.files[i]);
				}

				//ajax multiple upload

			}
		}
    // program add
    function newProgramItem() {
      $('table#program-list-container').append('<tr class="program-list-item"><td><div class="row"><div class="col-md-3"><div class="form-group"><input name="program_title[]" type="text" class="form-control " placeholder="Title"/></div></div><div class="col-md-4"><div class="form-group"><input name="program_description[]" type="text" class="form-control " placeholder="Description"/></div></div><div class="col-md-2"><div class="form-group"><input name="program_starting_time[]"type="text" class="form-control" placeholder="Starting Time"/></div></div><div class="col-md-2"><div class="form-group"><input name="program_duration[]" type="text" class="form-control" placeholder="Duration hours"/></div></div><div class="col-md-1"><div class="form-group"><a class="delete" href="index.htm#"><i class="fa fa-fw fa-remove"></i></a></div></div></div></td></tr>');
    }
      $(document).on("click", "#program-list-container .delete", function (e) {
        e.preventDefault();
        $(this).parent().parent().parent().remove();
      });

    // $('.datetimepicker').datetimepicker({
    //     format:'Y-m-d H:i:s',
    // });

    $('#course-category').on('change', function(){
        var catID = $(this).val();

        if(catID) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: '{{url("/fetch-sub_category")}}',
                type:"POST",
                data:{catID:catID},
                dataType:"json",
                // beforeSend: function(){
                //     $('#loader').css("visibility", "visible");
                // },
                success:function(data) {
                    $('#course-sub_category').empty();
                    $.each(data, function(key, value){
                        $('#course-sub_category').append('<option value="'+ key +'">' + value + '</option>');
                    });
                }//,
                // complete: function(){
                //     $('#loader').css("visibility", "hidden");
                // }
            });
        } else {
            $('#course-sub_category').empty();
        }
    });
    </script>
  @endsection
