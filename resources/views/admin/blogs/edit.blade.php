@extends('admin/master')
@section('breadcrumbs') Add Blog @endsection
@section('breadcrumbs')

    <li class="breadcrumb-item">
        <a href="{{url('/admin/blogs')}}">Blogs</a>
    </li>
    <li class="breadcrumb-item active">Add Blog</li>
    
@endsection

@section('content')

    <form action="{{ url('/admin/blog/update') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{$blog->id}}">

        <div class="box_general padding_bottom">
            <div class="header_box version_2">
                <h2><i class="fa fa-file"></i>Basic info</h2>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" value='{{$blog->title}}' class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="Blog Title" required>
                        @if ($errors->has('title'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('title') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Cover Image<span class="text-danger">*</span></label>
                        <input type="file" name="cover_image"  class="form-control {{ $errors->has('cover_image') ? ' is-invalid' : '' }}" >
                        @if ($errors->has('cover_image'))
                            <span class="help-block text-danger">
                        <strong>{{ $errors->first('cover_image') }}</strong>
                    </span>
                        @endif
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label> Sub-Title <span class="text-danger">*</span></label>
                        <input type="text" name="sub_title" value='{{$blog->sub_title}}' class="form-control {{ $errors->has('sub_title') ? ' is-invalid' : '' }}" placeholder="Blog Sub-Title" required>
                        @if ($errors->has('sub_title'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('sub_title') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="form-group">
                        <label> Slug <span class="text-danger">*</span></label>
                        <input type="text" name="slug" value='{{$blog->slug}}' class="form-control {{ $errors->has('slug') ? ' is-invalid' : '' }}" placeholder="Blog Slug" required>
                        @if ($errors->has('slug'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('slug') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>


                <div class="col-md-4">
                    <div class="form-group">
                        <label>Sort Order <span class="text-danger">*</span></label>
                        <input type="number" name="order" value='{{$blog->order}}' class="form-control {{ $errors->has('Sort order of Blog') ? ' is-invalid' : '' }}" placeholder="Sorting order of Blog" required>
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
                        <textarea rows="5" name="description" id="editor" class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}" style="height:100px;" placeholder="Describe the Blog with stylish document editor. this design will be same to same in the public page.">{{$blog->description}}</textarea>
                        @if ($errors->has('description'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('description') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="box_general padding_bottom">
            <div class="header_box version_2">
                <h2><i class="fa fa-sitemap"></i>SEO Fields</h2>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Meta Title</label>
                        <input type="text" name="meta_title" value="{{$blog->meta_title}}" class="form-control {{ $errors->has('meta_title') ? ' is-invalid' : '' }}" placeholder="Meta Title">
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
                        <textarea rows="5" name="meta_description" class="form-control {{ $errors->has('meta_description') ? ' is-invalid' : '' }}" style="height:100px;" placeholder="">{{ $blog->meta_description }}</textarea>
                        @if ($errors->has('meta_description'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('meta_description') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        <button type="submit" class="btn_1 medium">Update Blog</button>

    </form>
@endsection
  @section('custom-css')
  @endsection
  @section('custom-js')

    @include('components/tinymce')
   
  @endsection
