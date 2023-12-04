@extends('admin/master')
@section('breadcrumbs')
  <li class="breadcrumb-item active">Edit City</li>
@endsection
@section('content')

  
		<div class="box_general">
			<div class="header_box">
          <h2 class="d-inline-block">Edit City</h2>
        
        <div class="list_general">

            <div class="card">
          
                <div class="card-body">
              

                  <form action="{{ route('admin.area.city.update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{ $city->id }}">

                      <div class="form-group">
                        <label>State</label>
                        <select type="text" class="form-control {{ $errors->has('state') ? ' is-invalid' : '' }}" name="state" required>
                          @forelse ($states as $state)
                            <option value="{{ $state->id }}" {{ $state->id==$city->state_id?'acitve':'' }}>{{ $state->state }}</option>
                          @empty
                            
                          @endforelse
                        </select>
                        @if($errors->has('state'))
                          <span class="help-block text-danger">
                              <strong>{{ $errors->first('state') }}</strong>
                          </span>
                        @endif
                      </div>
                      <div class="form-group">
                        <label>City</label>
                        <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $city->name }}" placeholder="city name" required>
                        @if($errors->has('name'))
                          <span class="help-block text-danger">
                              <strong>{{ $errors->first('name') }}</strong>
                          </span>
                        @endif
                      </div>

                      <div class="form-group">
                        <label>Slug</label>
                        <input type="text" class="form-control {{ $errors->has('slug') ? ' is-invalid' : '' }}" name="slug" value="{{ $city->slug }}" placeholder="slug" required>
                        @if($errors->has('slug'))
                          <span class="help-block text-danger">
                              <strong>{{ $errors->first('slug') }}</strong>
                          </span>
                        @endif
                      </div>
                      <div class="form-group text-right">
                        <button class="btn btn-primary btn-lg" type="submit">Update City</button>
                      </div>
                     
                     
                  </form>

                </div>

            </div>
      
        </div>
      </div>
	  </div>
@endsection
