@extends('admin/master')

@section('title')
    Admin Profile
@endsection
@section('breadcrumbs')
    
<li class="breadcrumb-item active">Edit Admin Profile</li>
@endsection

@section('content')

        <div class="col-lg-12">
            <div class="card card-default">
                <div class="card-header">Change Admin Profile</div>
                <div class="card-body justify-content-center">
                    <form action="{{url('admin/profile/update')}}" class='row' method='POST'>
                      @csrf

                      <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                        <label for="">First Name:</label>
                        <input type="text" value="{{ $admin->first_name }}"  name="first_name" class="form-control" id="first_name" placeholder="Your first name" required />
                        @if ($errors->has('first_name'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('first_name') }}</strong>
                            </span>
                        @endif
                      </div>
                      <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
                        <label for="">LastName:</label>
                        <input type="text" value="{{ $admin->last_name }}"  name="last_name" class="form-control" id="last_name" placeholder="Your last name" required />
                        @if ($errors->has('last_name'))
                            <span class="help-block text-danger">
                                <strong>{{ $errors->first('last_name') }}</strong>
                            </span>
                        @endif
                      </div>

                      <div class="form-group col-lg-4 col-md-6 col-sm-6 col-xs-12">
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

                      <div class="col-md-12">
                        <div class="form-group justify-content-between">
                          <input type="submit" id="submit-button" value="Update Profile" class="btn btn-success btn-lg "/>
                        </div>
                      </div>

                    </form>
                  </div>

                </div>
            </div>
        </div>
        

@endsection

@section('custom-js')
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
