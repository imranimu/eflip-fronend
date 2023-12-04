@extends('admin/master')
@section('title')Add Service @endsection

@section('breadcrumbs')

    <li class="breadcrumb-item">
        <a href="{{url('/admin/services')}}">Services</a>
    </li>
    <li class="breadcrumb-item">
        <a href="{{url('/service/'.$service->slug)}}" target="_blank">{{ $service->title }}</a>
    </li>
    <li class="breadcrumb-item active">Edit service</li>
@endsection

@section('content')
    <form action="{{ url('/admin/service/update') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{$service->id}}">
        
        <div class="box_general padding_bottom">
            <div class="header_box version_2">
                <h2><i class="fa fa-file"></i>Basic info</h2>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Service Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" value='{{$service->title}}' class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="service Title" required>
                        @if ($errors->has('title'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('title') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label>Cover Image</label>
                        <input type="file" name="cover_image" value='' class="form-control {{ $errors->has('cover_image') ? ' is-invalid' : '' }}" >
                        @if ($errors->has('cover_image'))
                            <span class="help-block text-danger">
                        <strong>{{ $errors->first('cover_image') }}</strong>
                    </span>
                        @endif
                    </div>
                </div>
                
                <div class="col-md-1 pl-0 pr-0">
                    <img src="{{asset('storage/'.$service->cover_image)}}" width="100%">
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Promo Video</label>
                        <input type="file" name="promo_video" value='{{$service->promo_video}}' class="form-control {{ $errors->has('promo_video') ? ' is-invalid' : '' }}">
                        @if ($errors->has('promo_video'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('promo_video') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                
                @if(!empty($service->promo_video))
                    <div class="col-md-1 pl-0 pr-0">
                        <video src="{{asset('storage/'.$service->promo_video)}}" width="100%" controls></video>
                    </div>
                @endif

                <div class="col-md-12">
                    <div class="form-group">
                        <label>Service Sub-Title <span class="text-danger">*</span></label>
                        <input type="text" name="sub_title" value='{{$service->sub_title}}' class="form-control {{ $errors->has('sub_title') ? ' is-invalid' : '' }}" placeholder="service Sub-Title" required>
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
                            <input type="text" name="slug" value='{{$service->slug}}' class="form-control {{ $errors->has('slug') ? ' is-invalid' : '' }}" placeholder="service Slug" required>
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
                        <input type="number" name="order" value='{{ $service->order }}' class="form-control {{ $errors->has('Sort order of service') ? ' is-invalid' : '' }}" placeholder="Sorting order of service" required>
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
                        <textarea rows="5" name="description" id="editor" class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}" style="height:100px;" placeholder="Describe the service with stylish document editor. this design will be same to same in the public page.">{!! $service->description !!}</textarea>
                        @if ($errors->has('description'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                        @endif
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
                            <input type="text" name="meta_title" value='{{$service->meta_title}}' class="form-control {{ $errors->has('meta_title') ? ' is-invalid' : '' }}" placeholder="Meta Title">
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
                            <textarea rows="5" name="meta_description" class="form-control {{ $errors->has('meta_description') ? ' is-invalid' : '' }}" style="height:100px;" placeholder="">{{$service->meta_description}}</textarea>
                            @if ($errors->has('meta_description'))
                                <span class="help-block text-danger">
                                    <strong>{{ $errors->first('meta_description') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        
        </div>
        <div class="text-right">
            <button type="submit" class="btn_1 medium">Update service</button>
        </div>
    </form>
@endsection
@section('custom-css')

@endsection
@section('custom-js')
@include('components/tinymce')
<script>
  

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
