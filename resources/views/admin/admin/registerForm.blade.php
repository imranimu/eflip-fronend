@extends('admin/master')

@section('title')
    Register New admin
@endsection

@section('content')
<div class="content-wrapper">
<div class="container-fluid">
        <div class="col-lg-12">
          @if(session()->has('message'))
            <h3 class="text-success">{{session()->get('message')}}</h3>
          @endif
          <!-- /Navigation-->

            <div class="card card-default">
                <div class="card-header">Add New Admin</div>
                <div class="card-body justify-content-center">
                    <form class="form-horizontal row" method="POST" action="{{ url('/admin/admin/store') }}">
                        {{ csrf_field() }}
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                            <label for="name" class=" control-label">Name</label>
                            <div class="">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
                                @if ($errors->has('name'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class=" control-label">E-Mail Address</label>
                            <div class="">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class=" control-label">Password</label>

                            <div class="">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 form-group ">
                            <label for="password-confirm" class=" control-label">Confirm Password</label>
                            <div class="">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>
                        <div class=" col-lg-6 lg-offset-4 form-group">
                            <div class=" ">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Register New Admin
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
