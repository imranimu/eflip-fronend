@extends('layouts.app')



@section('scripts')



@endsection

@section('styles')
  <style>
    #linklist {
      list-style: none; /* Remove default bullets */
    }

    #linklist li::before {
      content: "\2022";  /* Add content: \2022 is the CSS Code/unicode for a bullet */
      color: blue; /* Change the color */
      font-weight: bold; /* If you want it to be bold */
      display: inline-block; /* Needed to add space between the bullet and the text */
      width: 1em; /* Also needed for space (tweak if needed) */
      margin-left: -1em; /* Also needed for space (tweak if needed) */
      font-size: 20px;
    }
    .fw-550{font-weight: 550!important}
  </style>

@endsection

@section('content')

  <div class="border-bottom border-3" style="border-color: #072E7C!important">
      <h1 class="text-center mt-1">
        <span class="border-bottom border-danger border-3 fw-bold" style="color:#072E7C">
          <img src="{{ asset('/assets/') }}/website_images/logo2.jpg" alt="Eflip" class="float-left" /> 
          Essay Contest
        </span>
      </h1>
      <h2 class="text-center mt-1" style="color:#072E7C">Rules</h2>
      
  </div>
  
  <div class="center main_div">
    {!! Helper::getSetting('contest-rules') !!}
    
  </div>
@endsection