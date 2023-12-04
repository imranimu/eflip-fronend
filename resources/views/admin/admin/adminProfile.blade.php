@extends('admin/master')

@section('title')
    Admin Profile
@endsection

@section('content')
<div class="content-wrapper">
<div class="container-fluid">
        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header">Change Admin Profile</div>
                <div class="card-body justify-content-center"><hr>
                  <h5 class="text-success">{{ Session::get('message') }}</h5>
                  <h5 class="text-danger">{{ Session::get('errorMessage') }}</h5>
                    {!! Form::open(['url'=>'admin/profile/update','class'=>'row','method'=>'POST']) !!}
                      {{csrf_field()}}
                      <input type="hidden" name="id" value="{{ Session::get('admnId') }}">

                      <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label for="oldPassword">Name:</label>
                        <input type="name" value="{{ $admin->name }}"  name="name" class="form-control" id="name" placeholder="Your name" required />
                        @if ($errors->has('name'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('name') }}</strong>
                            </span>
                        @endif
                      </div>

                      <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <label for="oldPassword">Email</label>
                        <input type="email" value="{{ $admin->email }}"  name="email" class="form-control" id="email" placeholder="Your email" required />
                        @if ($errors->has('email'))
                            <span class="help-block  text-danger">
                                <strong>{{ $errors->first('email') }}</strong>
                            </span>
                        @endif
                      </div>

                      <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label for="oldPassword">Old Password</label>
                        <input type="password"  name="oldPassword" class="form-control" id="oldPassword" placeholder="old Password(must provide to update profile)" required />
                        @if ($errors->has('oldPassword'))
                            <span class="help-block  text-danger">
                                <strong>{{ $errors->first('oldPassword') }}</strong>
                            </span>
                        @endif
                      </div>


                      <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label for="newPassword">New Password</label>
                        <input type="password" name="password" class="form-control" id="newPassword" placeholder="Keep blank for not changing password"/>
                        @if ($errors->has('password'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('password') }}</strong>
                            </span>
                        @endif
                      </div>
                      <div class="form-group col-lg-4 col-md-4 col-sm-4 col-xs-12">
                        <label for="confirmPassword">Re-type New Password</label>
                        <input type="password"  name="password_confirmation" class="form-control" id="confirmPassword" placeholder="Keep blank for not changing password"/>
                        <span id="show_error" class="text-danger"></span>
                        @if ($errors->has('password_confirmation'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('password_confirmation') }}</strong>
                            </span>
                        @endif
                      </div>


                      <div class="form-group ">
                        <input type="submit" id="submit-button" value="Update Password" class="btn btn-success btn-lg "/>
                      </div>


                     {!! Form::close() !!}
                  </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('public/admin/vendor/jquery/jquery.min.js')}}"></script>
<script>
  $('#confirmPassword').keyup(function(){
    var newPassword=$('#newPassword').val();
    var confirmPassword=$('#confirmPassword').val();
    if(confirmPassword != newPassword){
      $('#show_error').html("Typed new passwords doesn't matched");
      $('#submit-button').addClass("disabled");
    }else{
      $('#show_error').html("");
      $('#submit-button').removeClass("disabled");
    }
  });
  $('#confirmPassword').focusout(function(){
    var newPassword=$('#newPassword').val();
    var confirmPassword=$('#confirmPassword').val();
    if(confirmPassword != newPassword){
      $('#show_error').html("Typed new passwords doesn't matched");
      $('#confirmPassword').val("");
      $('#newPassword').val("");
    }else{
      $('#show_error').html("");
      $('#submit-button').removeClass("disabled");
    }
  });
</script>
@endsection
