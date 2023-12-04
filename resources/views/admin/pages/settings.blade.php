@extends('admin.master')

@section('styles')
  
  
@endsection

@section('scripts')
  @include('components.html_editor')
<script>
   
   
  </script>
@endsection

@section('content')
	<div class="box_general">
			<div class="header_box">
				<h2 class="d-inline-block">Settings</h2>
			</div>

          <form action="{{ route('admin.settings.update') }}" method="POST" class="modal-content mb-3" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
        
              @forelse ($settings as $setting)
              
                <div class="form-group">
                  <label for="" class="">
                    {{ $setting->name }}
                    
                  </label>
                   @if($setting->type=='file')
                      <img src="{{ asset('storage/'.$setting->value) }}" width="60px" class="float-right">
                    @endif
                  @if($setting->type=='text')
                    <input name="settings[{{ $setting->slug}}]" placeholder="" type="text" class="form-control @error($setting->slug) is-invalid @enderror" value="{{ $setting->value }}">
                  @elseif($setting->type=='email')
                    <input name="settings[{{ $setting->slug}}]" placeholder="" type="email" class="form-control @error($setting->slug) is-invalid @enderror" value="{{ $setting->value }}">
                  @elseif($setting->type=='longtext')
                    <textarea name="settings[{{ $setting->slug }}]" placeholder="" class="form-control @error($setting->slug) is-invalid @enderror">{{ $setting->value }}</textarea>
                  @elseif($setting->type=='html_editor')
                    <textarea name="settings[{{ $setting->slug }}]" placeholder="" class="form-control editor @error($setting->slug) is-invalid @enderror">{{ $setting->value }}</textarea>
                  @elseif($setting->type=='file')
                    <input name="settings[{{ $setting->slug }}]" placeholder="" type="file" class="form-control-file @error($setting->slug) is-invalid @enderror">
                  @endif
                 
    
                  @error($setting->slug)
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div>
              @empty
                
              @endforelse
              
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary btn-lg">{{ __('Save') }}</button>
            </div>
        
          
          </form>
        
  
		</div>


@endsection