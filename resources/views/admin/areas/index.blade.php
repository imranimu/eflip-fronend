@extends('admin/master')
@section('breadcrumbs')
  <li class="breadcrumb-item active">Areas</li>
@endsection
@section('content')

  
		<div class="box_general">
			<div class="header_box">
				<h2 class="d-inline-block">Areas</h2>
			
			<div class="list_general">

        <div id="accordion">

          @forelse ($states as $key=>$state)
            
          <div class="card">
            <div class="card-header">
              <a class="card-link text-lg" data-toggle="collapse" href="#collapse-{{ $state->state_code }}">
                {{ $state->state }} 
                ({{ count($state->getCities) }} cities)
                <span class="badge text-white bg-{{ $state->status=='active'?'success':'danger' }}">{{ $state->status }}</span>
              </a>

              <a href="{{ route('admin.area.status',$state->state_code) }}" class="float-right btn btn-{{ $state->status=='active'?'danger':'success' }}">
                {{ $state->status=='active'?'Disable':'Activate' }}
              </a>

            </div>
            <div id="collapse-{{ $state->state_code }}" class="collapse {{ $key==0?'show':'' }}" data-parent="#accordion">
              <div class="card-body">
                <ul class="list-group mb-3">
                  @forelse ($state->getCities as $city)
                    <li class="list-group-item">{{ $city->name }}
                      ({{ $city->slug }})
                      <span class="badge text-white bg-{{ $city->status=='active'?'success':'danger' }}">{{ $city->status }}</span>
                      <a href="{{ route('admin.area.city.edit',$city->id) }}" class="float-right btn btn-info btn-sm"><i class="fa fa-edit"></i></a>
                      <a href="{{ route('admin.area.city.status',$city->id) }}" class="float-right btn btn-{{ $city->status=='active'?'warning':'success' }} mr-1 btn-sm"><i class="fa fa-{{ $city->status=='active'?'eye-slash':'eye' }}"></i></a>
                      <a href="{{ route('admin.area.city.delete',$city->id) }}" onclick="return confirm('Are you sure to delete?')" class="float-right btn btn-danger btn-sm mr-1"><i class="fa fa-trash"></i></a>
                    </li>
                  @empty
                      <li class="list-group-item text-danger">No city added</li>
                  @endforelse
                </ul>

                <form action="{{ route('admin.area.city.store') }}" method="POST">
                  @csrf
                  <input type="hidden" name="state_id" value="{{ $state->id }}">
                    <div class="input-group">
                      <input type="text" class="form-control {{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name') }}" placeholder="city name" required>
                      <input type="text" class="form-control {{ $errors->has('slug') ? ' is-invalid' : '' }}" name="slug" value="{{ old('slug') }}" placeholder="slug" required>
                      <div class="input-group-addon">
                        <button class="btn btn-primary">Add City</button>
                      </div>
                    </div>
                    @if ($errors->has('name'))
                        <span class="help-block text-danger">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                    @endif
                    @if ($errors->has('slug'))
                        <span class="help-block text-danger">
                            <strong>{{ $errors->first('slug') }}</strong>
                        </span>
                    @endif
                </form>

              </div>
            </div>
          </div>
          @empty 

          @endforelse

        </div>
		
			</div>
		</div>
    {{--		    {{$pages->links()}}--}}
	  </div>
@endsection
