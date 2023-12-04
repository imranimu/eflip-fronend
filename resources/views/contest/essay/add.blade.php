@extends('layouts.app')



@section('scripts')

  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
      
  <script>

    @if(session('success'))

      toastr.success("{{session('success')}}");

    @endif

    @if(session('error'))

      toastr.warning("{{session('error')}}");

    @endif
    @if ($errors->any())
      @foreach ($errors->all() as $error)

        toastr.warning("{{$error}}");

      @endforeach
    @endif
  </script>

  {{-- <script>

    $('#input-essay-title').keyup(function(){
      let data=$(this).val();
      let slug=data.toLowerCase().trim().replace(/[^\w ]+/g, '').replace(/ +/g, '-');
      $('#input-essay-slug').val(slug);
    });

    $('#input-category-title').keyup(function(){
      let data=$(this).val();
      let slug=data.toLowerCase().trim().replace(/[^\w ]+/g, '').replace(/ +/g, '-');
      $('#input-category-slug').val(slug);
    });

  </script> --}}
@endsection

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')

  <div>
    <h1 class="text-center mt-1">
      <span class="border-bottom border-danger border-3 fw-bold" style="color:#072E7C">
        <img src="{{ asset('/assets/') }}/website_images/logo2.jpg" alt="Eflip" class="float-left" /> 
        Essay Contest
      </span>
    </h1>
    <h2 class="text-center" style="color:#072E7C">Add an essay</h2>
      
  </div>
  
  <hr class="border border-2 border-primary">
  <div class="center main_div">
    <div class="col-md-10 col-md-offset-2">
    
      <form action="{{ route('contest.essay.store') }}" class="panel panel-default" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="panel-body">
          <div class="form-group text-left">
            <label for="title">Title:</label>
            <input type="text" name="title" class="form-control @error('title') border border border-danger @enderror" value="{{ old('title') }}" required id="input-essay-title">
                @error('title')
                    <span class="color-red" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
          </div>
          {{-- <div class="form-group text-left">
            <label for="title">Slug:</label>
            <div class="input-group">
              <span class="input-group-addon">{{ url('/') }}/contest/</span>
              <input type="text" name="slug" class="form-control @error('slug') border border-danger @enderror" value="{{ old('slug') }}" id="input-essay-slug" required>
            </div>
                @error('slug')
                    <span class="color-red" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
          </div> --}}

          <div class="form-group text-left">
            <label for="source">Source URL:</label>
            <input type="url" name="source" class="form-control @error('source') border border-danger @enderror" value="{{ old('source') }}" id="source">
                @error('source')
                    <span class="color-red" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
          </div>

          <div class="row">

            <div class="col-md-5">
              <div class="form-group text-left">
                <label >Category: 
                  <a href="#" class="float-right" data-toggle="modal" data-target="#myModal">+ Add</a>
                </label>
                <select name="category" class="form-control @error('category') border border-danger @enderror" required>
                  <option value="">Select Category</option>
                  @forelse ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category')==$category->id?'selected':'' }}>{{ $category->name }}</option>
                  @empty
                    
                  @endforelse
                </select>
                @error('category')
                  <span class="color-red" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>
            <div class="col-md-7">
              <div class="form-group text-left">
                <label for="attachment">Attachment (txt, doc, docx):</label>
                <input type="file" name="attachment" accept=".txt, .doc, .docx" class="form-control-file @error('attachment') border border-danger @enderror" value="{{ old('attachment') }}" id="attachment" required>
                @error('attachment')
                    <span class="color-red" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
            </div>


          </div>
        </div>

        <div class="panel-footer text-right">
          <button type="submit" class="btn btn-success btn-lg">Submit</button>
        </div>

        
      </form>
      
    </div>
  </div>

  <!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <form action="{{ route('contest.category.store') }}" method="POST" class="modal-content">
      @csrf
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Category</h4>
      </div>
      <div class="modal-body">
        <div class="form-group text-left">
          <label for="name">Name:</label>
          <input type="text" name="name" class="form-control @error('name') border border-danger @enderror" value="{{ old('name') }}" id="input-category-title" required>
          @error('name')
              <span class="color-red" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>

        {{-- <div class="form-group text-left">
          <label for="title">Slug:</label>
          <div class="input-group">
            <span class="input-group-addon">{{ url('/') }}/contest/</span>
            <input type="text" name="category_slug" class="form-control @error('slug') border border-danger @enderror" value="{{ old('category_slug') }}" id="input-category-slug">
          </div>
              @error('category_slug')
                  <span class="color-red" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
        </div> --}}

        <div class="form-group text-left">
          <label for="order">Order:</label>
          <input type="number" name="order" class="form-control @error('order') border border-danger @enderror" id="order" value="{{ count($categories)+1 }}" required>
          @error('order')
              <span class="color-red" role="alert">
                  <strong>{{ $message }}</strong>
              </span>
          @enderror
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-success" >Add</button>
      </div>
    </form>

  </div>
</div>

@endsection