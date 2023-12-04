@if(!isset($categoryName))
  @php
    $categoryName='';
  @endphp
@endif

<div class="firstTimeLoad none hidden-xs"></div>

    @if(Route::currentRouteName()=='index')
    <div class="firstTimeLoadImg none hidden-xs">
      <div class="firstLoadClose">
        <a href="javascript:void(0)"></a>
      </div>
      <img class="img-responsive" id="intro_img" alt="Eflip Intro" src="{{ asset('/assets/') }}/website_images/poup_center_image.jpg">
    </div>
    @endif

    <nav class="navbar navbar-default design_nav explore_web_nav navbar-fixed-top hidden-sm hidden-md-sub hidden-xs" style="top: 0;padding:5px 0;min-height: 32px;max-height: 32px;overflow: hidden !important;background: #fff !important;text-align: center;margin: 0;box-shadow: none;">
      <img src="{{ asset('/assets/') }}/website_images/exploreTheWebHeader.jpg" alt="See the Web">
      <span class="currentSS"> - Current Screenshots of {{ $totalHourlydailyWebsites }} great websites </span>
      <span class="click"> - click on picture to go to site - <font color="#3b5998">click next to see all sites</font> - use menu for topics </span>
    </nav>
    <nav class="navbar navbar-default design_nav navbar-fixed-top" style="margin:0px !important;box-shadow: none;">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="{{ url('/') }}">
            <img src="{{ asset('/assets/') }}/website_images/logo2.jpg" alt="Eflip" />
          </a>
        </div>
        <div class="navbar-collapse collapse" id="bs-example-navbar-collapse-1">

          <div class="main_category_div">
            <ul class="nav navbar-nav main_category" id="mainCategoryNavbar">

              <li class="@empty($_GET['search']){{ $categoryName=='random'?'active':'' }}@endempty">
                <a class="visible-md-sub slide" style="color: #a94442 !important;" href="{{ url('/') }}" id='random'>
                  <i class="fa fa-arrows-alt"></i> Random <i class="fa fa-angle-right fa-2x"></i>
                </a>
                <!--<i class="fa fa-arrows-alt"></i>-->
                <a class="hidden-md-sub" href="{{ url('/') }}" id='random'> Random <span class="allWebsiteCount badge">{{ $totalHourlydailyWebsites }}</span></a>
              </li>

              @php
                $menuCategories=App\Models\Category::orderBy('priority')->with('getWebsites','subCategories','subCategories.getWebsites')->get();
              @endphp

              @forelse($menuCategories as $category)
                <li class="@if($categoryName!='random') {{ $categoryName==$category->name?'active':'' }} @endif">
                  <a class="visible-md-sub slide" style="color: #a94442 !important;" href="#" 
                    {{-- {{ route('browse',[$categoryName,'random']) }} --}}
                    id='{{ $category->id }}'>
                    <i class="fa fa-{{ $category->icon_name }}"></i> {{ $category->name }} <i class="fa fa-angle-right fa-2x"></i>
                  </a>
                  <!--<i class="fa fa-arrows-alt"></i>-->
                  <a class="hidden-md-sub" href="{{ route('browse',[ $category->name,'random']) }}" id='{{ $category->name }}'> 
                    {{ $category->name }} <span class="allWebsiteCount badge">{{ count($category->getWebsites) }}</span>
                  </a>
                </li>
              @empty
                
              @endforelse
              

            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li class="visible-md-sub">
                <a href="javascript:void(0)" id='suggested_site'>
                  <i class="fa fa-link"></i> Suggest A Site </a>
              </li>
              <li class="visible-md-sub">
                <a href="{{ route('browse',['News','TopNews']) }}">
                  <i class="fa fa-newspaper-o"></i> TopNews </a>
              </li>
              <li>
                <a href="{{ route('contest') }}">
                  <i class="fa fa-users"></i> Contest </a>
              </li>
              <li>
                <a href="{{ route('intro') }}">
                  <i class="fa fa-play-circle"></i>
                  <span>eFlip Intro</span>
                </a>
              </li>
              <li>
                <a href="{{ route('help') }}">
                  <i class="fa fa-question-circle"></i>
                  <span>Help</span>
                </a>
              </li>
              @auth 
                <li>
                  <a href="{{ route('home') }}" 
                  {{-- onclick="document.getElementById('logout-form').submit()" --}}
                  >
                    <i class="fa fa-user"></i> {{ auth()->user()->first_name }} 
                  </a>
                  {{-- <form action="{{ route('logout') }}" method="POST" id="logout-form">
                    @csrf
                  </form> --}}
                </li>
              @else 
                <li>
                  <a href="{{ route('register') }}">
                    <i class="fa fa-user-plus"></i> Register </a>
                </li>
                <li>
                  <a href="{{ route('login') }}">
                    <i class="fa fa-user"></i> Login </a>
                </li>
              @endauth
                <li>
                  <a id="search-btn" href="#">
                    <i class="fa fa-search"></i> </a>
                </li>
            </ul>
            <form style="position:absolute; top:0; right:-320px" class="hidden-xs navbar-form navbar-right p-0 m-0" action="{{ route('index') }}" method="GET">
              <div class="input-group">
                <input style="font-size:16px; padding:32px" type="text" class="form-control" value="" placeholder="Search..." name="search" id="term" required="required">
                <span class="input-group-btn">
                  <button style="font-size:16px; padding:19px" class="btn btn-default btn-sm" id="search_button" type="submit">
                    <i class="fa fa-search"></i>
                  </button>
                </span>
              </div>
              <!-- input-group -->
            </form>
          </div>
          
          <div class="child_category_div" id='my_eflip' style="display:none;">
            <div class='text-center child_category_title'>
              <i class="fa fa-angle-left fa-2x"></i>
              <span class="text-purple">
                <i class="fa fa-check-square-o"></i>&nbsp;myEflip </span>
            </div>
          </div>
          <div class="child_category_div" id='random' style="display:none;">
            <div class='text-center child_category_title'>
              <i class="fa fa-angle-left fa-2x"></i>
              <span style="color:#a94442 !important;">
                <i class="fa fa-arrows-alt"></i>&nbsp;Random </span>
            </div>
            <ul class='sub-cat-xs visible-md-sub'>
              <li class="active">
                <a style="line-height: 10px;color:#a94442 !important;" href="https://eflip.com/random/hourlydaily">
                  <i class='fa fa-clock-o'></i> Hourly & Daily-454 </a>
              </li>
              <li class="">
                <a style="line-height: 10px;color:#a94442 !important;" href="https://eflip.com/random/hourly">
                  <i class='fa fa-bolt'></i> Hourly-56 </a>
              </li>
              <li class="">
                <a style="line-height: 10px;color:#a94442 !important;" href="https://eflip.com/random/daily">
                  <i class='fa fa-cogs'></i> Daily-398 </a>
              </li>
              <li class="">
                <a style="line-height: 10px;color:#a94442 !important;" href="https://eflip.com/random/weeklymonthly">
                  <i class='fa fa-calendar'></i> Monthly-908 </a>
              </li>
              <li class="">
                <a style="line-height: 10px;color:#a94442 !important;" href="https://eflip.com/random/articles">
                  <i class='fa fa-list-alt'></i> Articles-388 </a>
              </li>
              <li class="">
                <a style="line-height: 10px;color:#a94442 !important;" href="https://eflip.com/random/articlespictures">
                  <i class='fa fa-picture-o'></i> Articles with pictures-203 </a>
              </li>
            </ul>
          </div>

          @foreach ($menuCategories as $menuCategory)
            <div class="child_category_div" id='{{ $menuCategory->id }}' style="display:none;">
              <div class='text-center child_category_title'>
                <i class="fa fa-angle-left fa-2x"></i>
                <i class="fa fa-{{ $category->icon_name }}" style="color:{{ $menuCategory->icon_color }}"></i>&nbsp;{{ $menuCategory->name }}
              </div>
              <ul class='sub-cat-xs visible-md-sub'>

                @forelse ($menuCategory->subCategories as $menuSubCategory)
                  <li class=''>
                    <a href="{{ route('browse',[$menuCategory->name,$menuSubCategory->name]) }}" class="mobile_child_click">
                      <i class="fa fa-{{ $menuSubCategory->icon_name }}" style="color:{{ $menuSubCategory->icon_color }}"></i> 
                      {{ $menuSubCategory->name }}
                      -{{ count($menuSubCategory->getWebsites) }} 
                    </a>
                  </li>
                @empty
                  
                @endforelse
                

              </ul>
            </div>
          @endforeach
          

        </div>
      </div>
    </nav>

    <script>
      $(document).ready(function() {
        $(document).off('click', '.mobile_child_click').on('click', '.mobile_child_click', function() {
          if ($('.navbar-collapse.collapse').hasClass('in')) {
            $('.navbar-collapse.collapse').removeClass('in');
          }
        });
        $(document).off('click', '.main_category li a.slide').on('click', '.main_category li a.slide', function() {
          var child_div_id = $(this).attr('id');
          if ($('#' + child_div_id + '.child_category_div').length) {
            $(this).parents('.main_category_div').css({
              'display': 'none'
            });
            $('.child_category_div').css({
              'display': 'none'
            });
            $('#' + child_div_id + '.child_category_div').show("slide", {
              direction: "right"
            });
          }
        });
        $(document).off('click', '.child_category_title').on('click', '.child_category_title', function(e) {
          $(this).parent().css({
            'display': 'none'
          });
          $(this).parents('.navbar-collapse').find('.main_category_div').show("slide", {
            direction: "left"
          });
        });
      
        // edit profile modal
        $(document).off("click", "#edit_profile").on("click", "#edit_profile", function(e) {
          e.preventDefault();
          var ele = $(this);
          generate_modal({
            element: ele,
            role: 'form',
            index: 2,
            header: "Update Profile",
            callback: function() {},
            view_path: 'https://eflip.com/editProfile'
          });
        });
        
        const form = document.querySelector('.navbar-form')
        const searchBtn = document.querySelector("#search-btn")
        document.addEventListener("click", (e) => {
            if(searchBtn.contains(e.target)){
                e.preventDefault();
                $(form).animate({right: "0"})
            }else if(!form.contains(e.target)){
                $(form).animate({right: "-320px"})
            }
        })
      });
    </script>

    <form class="visible-xs-inline" style="position: relative; z-index:111;" action="{{ route('index') }}" method="GET">
      <div class="input-group">
        <input type="text" class="form-control" value="" placeholder="Search..." name="search" id="term" required="required">
        <span class="input-group-btn">
          <button class="btn btn-default btn-sm" id="search_button" type="submit">
            <i class="fa fa-search"></i> Search </button>
        </span>
      </div>
      <!-- input-group -->
    </form>
    
    <div class="col-xs-12 explore_the_web visible-xs">
      <img alt="See the Web" class="img-responsive" src="{{ asset('/assets/') }}/website_images/exploreTheWebHeader.jpg">
      <span class="currentSS">Current Screenshots of {{ $totalHourlydailyWebsites }} great websites </span>
    </div>

    <div id="socialHolder" class="col-xs-12 visible-xs">
      <div id="socialShare" class="btn-group share-group">
        <ul>
          <li class='shares'>
            <a class="btn btn-xs static"> Shares <br />
              <span>
                <b class='total_count'></b>
              </span>
            </a>
          </li>
          <li>
            <a data-original-title="Facebook" rel="tooltip" href="#" class="btn btn-xs btn-circle share s_facebook" data-placement="left">
              <i class="fa fa-facebook"></i>
              <br />
              <span class='counter c_facebook'></span>
            </a>
          </li>
          <li>
            <a data-original-title="Google+" rel="tooltip" href="#" class="btn btn-xs btn-circle share s_plus" data-placement="left">
              <i class="fa fa-google-plus"></i>
              <br />
              <span class='counter c_plus'></span>
            </a>
          </li>
          <li>
            <a data-original-title="LinkedIn" rel="tooltip" href="#" class="btn btn-xs btn-circle share s_linkedin" data-placement="left">
              <i class="fa fa-linkedin"></i>
              <br />
              <span class='counter c_linkedin'></span>
            </a>
          </li>
          <li>
            <a data-original-title="Pinterest" rel="tooltip" class="btn btn-xs btn-circle share s_pinterest" data-placement="left">
              <i class="fa fa-pinterest"></i>
              <br />
              <span class='counter c_pinterest'></span>
            </a>
          </li>
          <li>
            <a data-original-title="Twitter" rel="tooltip" href="#" class="btn btn-xs btn-circle share_twitter s_twitter" data-placement="left">
              <i class="fa fa-twitter"></i>
              <br />
              <span class='counter c_twitter'>0</span>
            </a>
          </li>
          <li>
            <a data-original-title="Twitter" rel="tooltip" href="#" class="btn btn-xs btn-circle share_mail s_gmail" data-placement="left">
              <i class="fa fa-envelope"></i>
              <br />
              <span class='counter c_gmail'>0</span>
            </a>
          </li>
        </ul>
      </div>
    </div>

    
    <div class="clearfix"></div>