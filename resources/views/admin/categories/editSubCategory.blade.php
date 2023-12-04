@extends('admin/master')
@section('content')


<div class="content-wrapper">
    <div class="container-fluid">
      <!-- Breadcrumbs-->
      <ol class="breadcrumb">
        <li class="breadcrumb-item">
          <a href="{{url('/admin/dashboard')}}">Admin</a>
        </li>
        <li class="breadcrumb-item active">  <a href="{{url('/admin/categories')}}">Categories</a></li>
        <li class="breadcrumb-item active">Edit SubCategory</li>
      </ol>
  			<h4>Edit SubCategory</h4>
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
    @if(session()->has('error'))
      <h3 class="text-danger">{{session()->get('error')}}</h3>
    @endif
  		<div class="box_general col-lg-12">
        <form action="{{url('/admin/sub-category/update')}}" method="POST" class="">
          @csrf
          <input type="hidden" name="id" value="{{$subCategoryById->id}}" class="form-control">

           <div class="form-group">
             <label for="title">Title of SubCategory:</label>
             <input type="text" name="title" value="{{$subCategoryById->name}}" class="form-control" id="title" required>
           </div>
           <div class="form-group">
            <label for="title">Slug of SubCategory:</label>
            <input type="text" name="slug" value="{{$subCategoryById->slug}}" class="form-control {{ $errors->has('slug') ? ' is-invalid' : '' }}" id="slug" required>
          </div>

               
          <div class="form-group">
            <label>Meta Tags of SubCategory<span class="text-danger">*</span></label>
            <input type="text" name="meta_tags" value='{{$subCategoryById->meta_tags}}' class="form-control {{ $errors->has('meta_tags') ? ' is-invalid' : '' }}" placeholder="write seperating by comma(,)" required>
            @if ($errors->has('meta_tags'))
                <span class="help-block text-danger">
                    <strong>{{ $errors->first('meta_tags') }}</strong>
                </span>
            @endif
        </div>

           <div class="form-group">
            <label for="title">Header Description:</label>
            <textarea name="header_description" class="form-control editor">{{$subCategoryById->header_description}}</textarea>
          </div>

          <div class="form-group">
            <label for="title">Footer Description:</label>
            <textarea name="footer_description" class="form-control editor">{{$subCategoryById->footer_description}}</textarea>
          </div>

            <div class="row">
                <div class="col-6">
                   </div>
                <div class="col-6">
                    <button type="submit" class="btn btn-success">Update SubCategory</button>
                </div>
            </div>

         </form><br>
  		</div>
		<!-- /box_general-->
	  </div>
	  <!-- /container-fluid-->
</div>

@endsection

@section('custom-js')
<script src="https://cdn.tiny.cloud/1/cxdzswsxk6toyfpdqsb8o1qw0gymft77yxorevw20xaprmsn/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '.editor',
        plugins: 'print preview paste importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern noneditable help charmap quickbars emoticons',
        imagetools_cors_hosts: ['picsum.photos'],
        menubar: 'file edit view insert format tools table help',
        toolbar: 'undo redo | bold italic underline strikethrough | fontselect fontsizeselect formatselect | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
        toolbar_sticky: true,
        autosave_ask_before_unload: true,
        autosave_interval: "30s",
        autosave_prefix: "{path}{query}-{id}-",
        autosave_restore_when_empty: false,
        autosave_retention: "2m",
        image_advtab: true,
        content_css: '//www.tiny.cloud/css/codepen.min.css',
        link_list: [
            { title: 'My page 1', value: 'http://www.tinymce.com' },
            { title: 'My page 2', value: 'http://www.moxiecode.com' }
        ],
        image_list: [
            { title: 'My page 1', value: 'http://www.tinymce.com' },
            { title: 'My page 2', value: 'http://www.moxiecode.com' }
        ],
        image_class_list: [
            { title: 'None', value: '' },
            { title: 'Some class', value: 'class-name' }
        ],
        importcss_append: true,
        height: 400,
        file_picker_callback: function (callback, value, meta) {
            /* Provide file and text for the link dialog */
            if (meta.filetype === 'file') {
            callback('https://www.google.com/logos/google.jpg', { text: 'My text' });
            }

            /* Provide image and alt text for the image dialog */
            if (meta.filetype === 'image') {
            callback('https://www.google.com/logos/google.jpg', { alt: 'My alt text' });
            }

            /* Provide alternative source and posted for the media dialog */
            if (meta.filetype === 'media') {
            callback('movie.mp4', { source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg' });
            }
        },
        templates: [
                { title: 'New Table', description: 'creates a new table', content: '<div class="mceTmpl"><table width="98%%"  border="0" cellspacing="0" cellpadding="0"><tr><th scope="col"> </th><th scope="col"> </th></tr><tr><td> </td><td> </td></tr></table></div>' },
            { title: 'Starting my story', description: 'A cure for writers block', content: 'Once upon a time...' },
            { title: 'New list with dates', description: 'New List with dates', content: '<div class="mceTmpl"><span class="cdate">cdate</span><br /><span class="mdate">mdate</span><h2>My List</h2><ul><li></li><li></li></ul></div>' }
        ],
        template_cdate_format: '[Date Created (CDATE): %m/%d/%Y : %H:%M:%S]',
        template_mdate_format: '[Date Modified (MDATE): %m/%d/%Y : %H:%M:%S]',
        height: 600,
        image_caption: true,
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
        noneditable_noneditable_class: "mceNonEditable",
        toolbar_mode: 'sliding',
        contextmenu: "link image imagetools table",
    });

    </script>
@endsection
