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

@endsection

@section('styles')
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection

@section('content')

  <div class="border-bottom border-3 border-color-blue">
      <h1 class="text-center mt-1">
        <span class="border-bottom border-danger border-3 fw-bold color-blue">
          <img src="{{ asset('/assets/') }}/website_images/logo2.jpg" alt="Eflip" class="float-left" /> 
          Essay Contest
        </span>
      </h1>
      
      

        @if(
          strtotime($essayContest->created_at)>strtotime(date('Y').'-'.(date('m')-1).'-1 00:00:00')  && 
          strtotime($essayContest->created_at)<strtotime(date('Y').'-'.(date('m')+1).'-10 00:00:00')
        )
        
          <h2 class="text-center mt-1 color-blue">
            Read and Rate Essays 
            &nbsp;&nbsp;<strong class="color-red">Rate Essay</strong>

            <form action="{{ route('contest.rate',$essayContest->id) }}" method="POST" class="input-group float-end me-5" style="width:200px">
              @csrf
              <input class="form-control" type="number" placeholder="1 to 10" name="rating" min="0" max="10" required>
              <span class="input-group-addon p-0"><button class="btn btn-sm">Rate</button></span>
            </form><br>
            <small class="float-end pe-5"><small>choose number & hit enter</small></small><br>
          </h2>
        @else
          <h2 class="text-center mt-1 color-blue">
            <p class="float-end text-warning me-5" style="width:200px"></p>
              {{-- @if(strtotime($essayContest->created_at)>strtotime(date('Y').'-'.(date('m')-1).'-1'))
                you can rate soon
              @else    --}}
                rating time expired
              {{-- @endif --}}
            </p>
          </h2>
        @endif

        
        

      
  </div>
  
  <div class="center main_div">
    
    <div class="text-left px-3">
      <h3>
        <span class="color-maroon">Title:</span>
        {{ $essayContest->title }}
        @if($essayContest->is_winner=='yes') 
          <span class="text-success"><i class="fa fa-trophy fa-2x"></i> Winner</span>
        @endif
      </h3>
      <h3>
        <span class="color-maroon">Author:</span>
        {{ $essayContest->getUser->first_name.' '.$essayContest->getUser->last_name }}
      </h3>
      
      <div class="row">
        <div class="col-md-4">
          <h3>
            <span class="color-maroon">Date:</span>
            {{ date('d M Y H:m',strtotime($essayContest->created_at)) }}
          </h3>
        </div>
        <div class="col-md-4">
          <h3><span class="color-maroon">Score:</span>{{ $essayContest->average_rating }}</h3>
        </div>
        <div class="col-md-4"><h3><span class="color-maroon">No. Ratings:</span>{{ $essayContest->get_ratings_count }}</h3></div>
      </div>
      
      @if(!empty($essayContest->source))
        <h3>
          <span class="color-maroon">Eflip article:</span>
          {{ $essayContest->source }}
        </h3>
      @endif
  
      <h3>
        <span class="color-maroon">Attachment:</span>
        <a href="{{ asset('/public/storage/'.$essayContest->attachment) }}" target="_blank">View Attachment</a>
      </h3>
    </div>

  </div>
@endsection