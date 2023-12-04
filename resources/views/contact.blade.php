@extends('layouts.app')

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" integrity="sha512-vKMx8UnXk60zUwyUnUPM3HbQo8QfmNx7+ltw8Pm5zLusl1XIfwcxo8DbWCqMGKaWeNxWA8yrx5v3SaVpMvR3CA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
  .invalid-feedback{
    color: red;
  }
  .has-error{
    border-color: red;
  }
</style>
@endsection
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js" integrity="sha512-VEd+nq25CkR676O+pLBnDW09R7VQX9Mdiij052gVCp5yVH3jGtH70Ho/UUv4mJDsEdTvqRCFZg0NKGiojGnUCw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
 
  @if(session('success'))
    toastr.success("{{session('success')}}");
  @endif

  @if(session('error'))
    toastr.error("{{session('error')}}");
  @endif

  @if($errors->any())
    @foreach ($errors->all() as $error)
      toastr.error("{{$error}}");
    @endforeach
  @endif
</script>   
@endsection
@section('content')
   
  <div class="center main_div">
    <div class="col-md-6 col-md-offset-3">
      <div class="panel panel-default mt-5">
        <div class="panel-heading">
          <h3>Contact Us</h3>
        </div>
        <div class="panel-body">
          <form action="{{ route('contact.store') }}" method="POST">                
            @csrf
            <div class="notification"></div>
            <div class="form-group text-left">
                <label>Name</label>
                <input name="name" class="form-control focused @error('name') has-error @enderror" type="text" placeholder="Name…" required>
                
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>                
            <div class="form-group text-left">
                <label>Email</label>
                <input name="email" class="form-control @error('email') has-error @enderror" type="email" placeholder="Email…" required>
                
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>                
            <div class="form-group text-left">
                <label>Message</label>
                <textarea name="message" class="form-control @error('message') has-error @enderror" placeholder="Message..." required></textarea>
                
                @error('message')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>
            <button class="btn btn-primary btn-block btn-sm" id="send_btn"><i class="fa fa-share"></i>&nbsp;Send</button>
          </form>
        </div>
      </div>
    </div>
  </div>

@endsection