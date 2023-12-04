@extends('admin/master')
@section('title') Edit Page @endsection
@section('breadcrumbs')

    <li class="breadcrumb-item">
        <a href="{{url('/admin/pages')}}">Pages</a>
    </li>

    <li class="breadcrumb-item">
        <a href="{{url('/admin/pages')}}">Pages</a>
    </li>

    <li class="breadcrumb-item active">Add Page</li>

@endsection
@section('content')

    <div class="box_general padding_bottom">
        <div class="header_box version_2">
            <h2><i class="fa fa-file"></i>Basic info</h2>
        </div>
        <form action="{{ route('admin.pages.update',$page->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="page_id" value="{{$page->id}}">
            @method('PUT')
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Page Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" value='{{$page->title}}' class="form-control {{ $errors->has('title') ? ' is-invalid' : '' }}" placeholder="Product Title" required>
                        @if ($errors->has('title'))
                            <span class="help-block text-danger">
                        <strong>{{ $errors->first('title') }}</strong>
                    </span>
                        @endif
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>Description<span class="text-danger">*</span></label>
                        <textarea rows="5" name="description" id="editor" class="form-control {{ $errors->has('description') ? ' is-invalid' : '' }}" style="height:100px;" placeholder="Describe the page with stylish document editor. this design will be same to same in the public page."required>{{$page->description}}</textarea>
                        @if ($errors->has('description'))
                            <span class="help-block text-danger">
                        <strong>{{ $errors->first('description') }}</strong>
                    </span>
                        @endif
                    </div>
                </div>
            </div>
    </div>
    <button type="submit" class="btn_1 medium">Update Page</button>
    </form>
            
@endsection
@section('custom-css')

@endsection
@section('custom-js')
    @include('components/tinymce')
    <script>
        function newProgramItem() {
            $('table#program-list-container').append('<tr class="program-list-item"><td><div class="row"><div class="col-md-3"><div class="form-group"><input name="program_title[]" type="text" class="form-control " placeholder="Title"/></div></div><div class="col-md-4"><div class="form-group"><input name="program_description[]" type="text" class="form-control " placeholder="Description"/></div></div><div class="col-md-2"><div class="form-group"><input name="program_starting_time[]"type="text" class="form-control" placeholder="Starting Time"/></div></div><div class="col-md-2"><div class="form-group"><input name="program_duration[]" type="text" class="form-control" placeholder="Duration hours"/></div></div><div class="col-md-1"><div class="form-group"><a class="delete" href="index.htm#"><i class="fa fa-fw fa-remove"></i></a></div></div></div></td></tr>');
        }
        $(document).on("click", "#program-list-container .delete", function (e) {
            e.preventDefault();
            $(this).parent().parent().parent().remove();
        });

      

    </script>
@endsection
