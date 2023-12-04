@extends('admin/master')
@section('title')Add Service @endsection
@section('breadcrumbs')

    <li class="breadcrumb-item">
        <a href="{{url('/admin/services')}}">Services</a>
    </li>
    <li class="breadcrumb-item active">Add Service</li>
@endsection
@section('content')


    <form action="{{ url('/admin/service/store') }}" method="post" enctype="multipart/form-data">
        @csrf

		<div class="box_general padding_bottom">
			<div class="header_box version_2">
				<h2><i class="fa fa-file"></i>Basic info</h2>
			</div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Service Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" value='{{old('title')}}' class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="service Title" required>
                        @if ($errors->has('title'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('title') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Cover Image<span class="text-danger">*</span></label>
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
                        <label>Service Sub-Title <span class="text-danger">*</span></label>
                        <input type="text" name="sub_title" value='{{old('sub_title')}}' class="form-control {{ $errors->has('sub_title') ? ' is-invalid' : '' }}" placeholder="service Sub-Title" required>
                        
                        @if ($errors->has('sub_title'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('sub_title') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>


                <div class="col-md-9">
                    <div class="form-group">
                        <label>Service Slug <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-addon">{{ url('/service') }}/</div>
                            <input type="text" name="slug" value='{{old('slug')}}' class="form-control {{ $errors->has('slug') ? ' is-invalid' : '' }}" placeholder="service Slug" required>
                        </div>
                        @if ($errors->has('slug'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('slug') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="form-group">
                        <label>Sort Order <span class="text-danger">*</span></label>
                        <input type="number" name="order" value='{{count(App\Models\Service::all())+1}}' class="form-control {{ $errors->has('Sort order of service') ? ' is-invalid' : '' }}" placeholder="Sorting order of service" required>
                        @if ($errors->has('order'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('order') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

        
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Description<span class="text-danger">*</span></label>
                        <textarea rows="5" name="description" id="editor" class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}" style="height:100px;" placeholder="Describe the service with stylish document editor. this design will be same to same in the public page.">{{old('description')}}</textarea>
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
                <h2><i class="fa fa-sitemap"></i>SEO Fields</h2>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Meta Title</label>
                        <input type="text" name="meta_title" value="{{old('meta_title')}}" class="form-control {{ $errors->has('meta_title') ? ' is-invalid' : '' }}" placeholder="Meta Title">
                        @if ($errors->has('meta_title'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('meta_title') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label>Meta Description</label>
                        <textarea rows="5" name="meta_description" class="form-control {{ $errors->has('meta_description') ? ' is-invalid' : '' }}" style="height:100px;" placeholder="">{{old('meta_description')}}</textarea>
                        @if ($errors->has('meta_description'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('meta_description') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>

        </div>
        <div class="text-right">
            <button type="submit" class="btn_1 medium">Add service</button>
        </div>
    </form>
        
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

        </script>
    @endsection
