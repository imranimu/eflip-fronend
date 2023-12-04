@extends('layouts.app')

@section('content')
   
  <div class="center main_div">
    <div class="col-md-12">
      <ol class="mt-5 text-left">
        <li>Choose a category &amp; subcategory from the menu.</li>
        <li>The number after a subcategory says how many sites there are.</li>
        <li>"Random" category presents sites in random order, and that is the default</li>
        <li>The "Random" Hourly subcategory shows just the sites whose images are updated every hour.</li>
        <li>Click Next &amp; Prev to step through images.</li>
        <li>The red numbers on top of image says what you are up to.</li>
        <li>Click on image to go to site.</li>
        <li>The red button under the image lets you see the articles for the site (from RSS feed)</li>
        <li>Click 'myEflip' box to add site to your favorite sites, and myEflip category to see only these sites.</li>
      </ol>
    </div>
  </div>

@endsection