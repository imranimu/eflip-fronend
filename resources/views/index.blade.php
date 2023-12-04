@extends('layouts.app')

@section('styles')
  <style>
    .mini-website-box{
      height: 160px;
      border-bottom:1px solid black;
    }
  </style>
@endsection

@section('scripts')
  <script>
    $(document).ready(function(){
        try{
          var container = document.getElementsByClassName("pagination")[0];
          var link = container.lastElementChild.lastElementChild.attributes.href.nodeValue;
          $("#next-page-link").attr('href',link);
        }catch{}
      
      

    });
    

    $(document).off('click', '.fav').on('click', '.fav', function(e) {
      @auth
        e.preventDefault();
      @endauth
      var website_id = $(this).attr('website_id');
      var ele = $(this);
        
      $.ajax({
          url: '{{ url("/favourite") }}/'+website_id,
          type: 'get',
          success: function(response) {
            if (response.status == 'success') {
              $(ele).find('i').toggleClass('fa-square-o fa-check-square-o');
              // alert(response.message);
            }
          },
        })

    });

    $(document).off('click', '.website_image_slide_div .prev_scroll').on('click', '.website_image_slide_div .prev_scroll', function() {
      $('.website_image_slide').slimScroll({
        scrollBy: '-501px',
        railVisible: false,
        alwaysVisible: true,
      });
    }).bind('slimscroll', function(e, pos){
        if(pos == 'top' && document.querySelector('.slimScrollBar').style.top == '0px' && $(window).width() > 500){
          @if(isset($_GET['page']) && $_GET['page']>1)
            window.location.href='{{ route('index','page='.($_GET['page']-1)) }}';
          @endif
        }
        
        else if(pos == 'bottom'){
            let nextPage=Number("{{ $_GET['page']??1 }}")+1;
            window.location.href='{{ route('index','page=') }}'+nextPage;
        }

    });

    $(document).off('click', '.website_image_slide_div .next_scroll').on('click', '.website_image_slide_div .next_scroll', function() {
      $('.website_image_slide').slimScroll({
        scrollBy: '501px',
        railVisible: false,
        alwaysVisible: true,
      })
    });
    
    
    let currentSlide = 0;
    
    $(".prev_div a,.next_div a").click(function(e) {
      e.preventDefault();
      var ele = $(this);
      currentSlide = $(this).data('target')
      var href = '#' + $(this).data('target');
      $('html, body').animate({
        scrollTop: ele.parents().find('.row' + href).offset().top - 100
      }, 0);
    });
    
    function nextSlide(){
        if(currentSlide < 39){
            currentSlide++;
            $('html, body').animate({
                scrollTop: $(`.row#${currentSlide}`).offset().top - 100
            }, 0);
        }else{
            const searchParams = new URLSearchParams(window.location.search);
            const currentPage = parseInt(searchParams.get('page')) || 1;
            searchParams.set('page', currentPage+1)
            window.location.href = `${window.location.origin}?${searchParams.toString()}`
        }
        
    }
    
    function prevSlide(){
        if(currentSlide > 0){
            currentSlide--;
            $('html, body').animate({
                scrollTop: $(`.row#${currentSlide}`).offset().top - 100
            }, 0);
        }else{
            const searchParams = new URLSearchParams(window.location.search);
            const currentPage = parseInt(searchParams.get('page')) || 1;
            if(currentPage > 1){
                searchParams.set('page', currentPage-1)
                window.location.href = `${window.location.origin}?${searchParams.toString()}`
            }
        }
    }

    document.onkeydown = checkKey;
    function checkKey(e) {
      e = e || window.event;

      if (e.keyCode == '33' || e.keyCode == '38') {
          // up arrow
          e.preventDefault();
          prevSlide();
          
      }
      else if (e.keyCode == '34' || e.keyCode == '40') {
          // down arrow
          e.preventDefault();
          nextSlide();
        
      }
      else if (e.keyCode == '37') {
        // left arrow
        $('.website_image_slide').slimScroll({
          scrollBy: '-501px',
          railVisible: false,
          alwaysVisible: true,
        });
      }
      else if (e.keyCode == '39') {
        // right arrow
          $('.website_image_slide').slimScroll({
          scrollBy: '501px',
          railVisible: false,
          alwaysVisible: true,
        });
      }

    }

    
    
  </script>


<script>
  $(document).ready(function() {
    $(document).off('click', '#suggested_site').on('click', '#suggested_site', function() {
      generate_modal({
        element: $(this),
        role: 'form',
        index: 1,
        header: 'Suggest A Site',
        view_path: 'https://eflip.com/suggestedSite',
        modal_class: "modal-md"
      });
    });
    // footer management
    var w = $(window).height();
    var res = w - 146;
    //var res2 = w - 244;
    $("#wrapAll").css("min-height", res + "px");
    $('.website_image_slide').slimScroll({
      height: '505px',
      railVisible: false,
      alwaysVisible: true,
      size: '5px',
      position: 'right',
    });
    $(window).resize(function() {
      w = $(window).height();
      res = w - 146;
      //res2 = w - 175;
      $("#wrapAll").css("min-height", res + "px");
      $('.website_image_slide').slimScroll({
        height: '505px',
        railVisible: false,
        alwaysVisible: true,
        size: '5px',
        position: 'right',
      });
    });
    $('.share').ShareLink({
      title: 'INSTANTLY flip through the CURRENT FRONT web-pages of the BEST {{  $totalHourlydailyWebsites }} websites, UPDATED every HOUR/DAY',
      text: 'Discover fascinating sites! Click NEXT to see all {{  $totalHourlydailyWebsites }}, and go to the sites by clicking on the images',
      image: "{{ asset('/public/assets/') }}/website_images/eflipForFb.jpg",
      url: 'http://www.eflip.com'
    });
    $('.share_twitter').ShareLink({
      title: 'INSTANTLY flip through the CURRENT FRONT web-pages of the BEST {{  $totalHourlydailyWebsites }} websites, UPDATED every HOUR/DAY',
      text: 'Discover fascinating sites! Click NEXT to see all {{  $totalHourlydailyWebsites }}, and go to the sites by clicking on the images',
      image: "{{ asset('/public/assets/') }}/website_images/eflipForFb.jpg",
      url: 'http://www.eflip.com'
    });
    $('.share_mail').ShareLink({
      title: 'INSTANTLY flip through the CURRENT FRONT web-pages of the BEST {{  $totalHourlydailyWebsites }} websites, UPDATED every HOUR/DAY',
      text: 'Discover fascinating sites! Click NEXT to see all {{  $totalHourlydailyWebsites }}, and go to the sites by clicking on the images',
      image: "{{ asset('/public/assets/') }}/website_images/eflipForFb.jpg",
      url: 'http://www.eflip.com'
    });
    // $('.counter').ShareCounter({
    //   url: 'http://www.eflip.com'
    // });
    $(window).bind("load resize", function() {
      if ($(window).width() > 750) {

        // $('.div_resize').height($(window).height() - 100);
        // $('.div_resize img').css({
        //   'max-height': $(window).height() - 190
        // });

        $('.container').width($(window).width() - 200);
        $('.div_resize .prev_div a,.div_resize .next_div a').removeClass('none');
      }
      width = (this.window.innerWidth > 0) ? this.window.innerWidth : this.screen.width;
      if (width < 768 || width <= 1087) {
        $('div.navbar-collapse').addClass('collapse');
      } else {
        $('div.navbar-collapse').removeClass('collapse');
      }
    });

    $(document).off('click', '.firstLoadClose a').on('click', '.firstLoadClose a', function() {
      $('.firstTimeLoadImg').hide();
      $('.firstTimeLoad').hide();
    });
    // close intro image if clicked anywhere out side of the image
    $('body').click(function(evt) {
      if (evt.target.id == "intro_img") return;
      //Do processing of click event here for every element except with id intro_img
      $('.firstTimeLoadImg').hide();
      $('.firstTimeLoad').hide();
    });
    // hide intro_img after few seconds
    setTimeout(function() {
      $('.firstTimeLoadImg').hide();
      $('.firstTimeLoad').hide();
    }, 10000);
   
    $(document).off('click', '.lb-redirect').on('click', '.lb-redirect', function(e) {
      e.preventDefault();
      window.open($(this).parents().find('.lb-dataContainer .lb-details span a').attr('href'));
    });
    $(document).off('click', '.image_hover_effect').on('click', '.image_hover_effect', function() {
      var a = $(this).children('.hover_text');
      if (a.hasClass('top_mobile_0')) {
        a.removeClass('top_mobile_0');
        a.addClass('top_mobile');
      } else {
        a.removeClass('top_mobile');
        a.addClass('top_mobile_0');
      }
    });
    $("html,body").on('click', function(e) {
      if (!$(e.target).parents().hasClass('image_hover_effect')) {
        $('.hover_text').addClass('top_mobile_0');
        $('.hover_text').removeClass('top_mobile');
      }
    });
    
    $(".website_image_slide div a").click(function(e) {
      e.preventDefault();
      var ele = $(this);
      var href = '#' + $(this).data('slide-href');
      $('html, body').animate({
        scrollTop: $(href).offset().top - 100
      }, 500);
    });
    //Dropdown of Category on Button click event
    $("#btn_dropCat").click(function() {
      $("#category_drop").slideToggle("slow");
      $(this).children('.fa-angle-double-up, .fa-angle-double-down').toggleClass("fa-angle-double-up fa-angle-double-down");
    });
    //Move to Top Button
    $(window).scroll(function() {
      if ($(window).width() > 750) {
        if ($(this).scrollTop() > 50) {
          $('#back-to-top').fadeIn();
        } else {
          $('#back-to-top').fadeOut();
        }
      }
    });
    // scroll body to 0px on click
    $('#back-to-top').click(function() {
      $('body,html').animate({
        scrollTop: 0
      }, 800);
      return false;
    });
    $(document).off('click', '.read_more').on('click', '.read_more', function() {
      console.log($(this).parents('.show').find('.read_less_div').length);
      $(this).parents('.show').find('.read_less_div').show();
      $(this).parent().hide();
    });
    $(document).off('click', '.read_less').on('click', '.read_less', function() {
      $(this).parents('.show').find('.read_less_div').hide(2);
      $(this).parents('.show').find('.read_more_div').show();
    });
  });
  // left: 37, up: 38, right: 39, down: 40,
  // spacebar: 32, pageup: 33, pagedown: 34, end: 35, home: 36
  var keys = {
    37: 1,
    38: 1,
    39: 1,
    40: 1
  };

  function preventDefault(e) {
    e = e || window.event;
    if (e.preventDefault) e.preventDefault();
    e.returnValue = false;
  }

  function preventDefaultForScrollKeys(e) {
    if (keys[e.keyCode]) {
      preventDefault(e);
      return false;
    }
  }

  function disableScroll() {
    if (window.addEventListener) // older FF
      window.addEventListener('DOMMouseScroll', preventDefault, false);
    window.onwheel = preventDefault; // modern standard
    window.onmousewheel = document.onmousewheel = preventDefault; // older browsers, IE
    window.ontouchmove = preventDefault; // mobile
    document.onkeydown = preventDefaultForScrollKeys;
  }

  function enableScroll() {
    if (window.removeEventListener) window.removeEventListener('DOMMouseScroll', preventDefault, false);
    window.onmousewheel = document.onmousewheel = null;
    window.onwheel = null;
    window.ontouchmove = null;
    document.onkeydown = null;
  }
    
</script>

@endsection

@section('content')

  <div class="center main_div">
    <div class="col-md-12 col-xs-12 center_main_div_fc">
      <div id="breadCrumb" class="visible-xs">
        <legend>
          <a href="https://eflip.com/random/hourlydaily" style="text-transform: capitalize;">Random</a> | Hourly & Daily
        </legend>
      </div>
    
      @php
        $timeNow=strtotime(date('Y-m-d H:m:s'));
        $slideImages="";
      @endphp

      @forelse($websites as $key=>$website)


        @if(!empty($_GET['page']))
          @php 
            $websiteNumber=(($_GET['page']-1)*$perpage)+$key+1; 
          @endphp
        @else 
          @php  
            $websiteNumber=$key+1;
          @endphp
        @endif

        
        <div class="row div_resize random mb-2 mb-md-5" id="{{  $key }}">
          <div class="col-lg-1 prev_div visible-lg">

            @if($key==0 && isset($_GET['page']) && $_GET['page']>1)
              <a href="{{ route('index','page='.($_GET['page']-1)) }}" onclick="window.location.href=this.getAttribute('href')">
                <span>Prev</span>
                <i class="fa fa-chevron-up fa-3x"></i>
                <span>or use arrow keys</span>
              </a>
            @elseif($key>0)
              <a class="nav_dir" data-target="{{  $key-1 }}">
                <span>Prev</span>
                <i class="fa fa-chevron-up fa-3x"></i>
                <span>or use arrow keys</span>
              </a>
            @endif

          </div>

          <div class="col-12 col-lg-10 mx-auto">
            <div class="text-center website_title visible-xs">
              <a target="_blank" href="{{ $website->url }}">
                <span class="title">{{ $website->name }}</span>
                <span style="color:#000;">|</span>

                @if(!empty($website->getCategory))
                  <span class="category">{{ $website->getCategory->name }},</span>&nbsp; 
                  @endif
                @if(!empty($website->getSubCategory))
                  <span class="sub_category">{{ $website->getSubCategory->name }}</span>
                @endif

                <span class="label"> Alexa: {{ $website->alexa }}</span>
                <br>
                <span class="updated_at">
                  
                  @if($website->get_image_hourly=='H')
                    {{-- <i class='fa fa-bolt'></i>  --}}
                    Hourly
                  @elseif($website->get_image_hourly=='W')
                    {{-- <i class='fa fa-calendar'></i>  --}}
                    Weekly
                  @elseif($website->get_image_hourly=='D')
                    {{-- <i class='fa fa-cogs'></i>  --}}
                    Daily
                  @else
                    {{-- <i class='fa fa-calendar'></i>  --}}
                    Monthly
                  @endif
                  
                  - <span>
                    {{ Helper::getTime($website->image_updated_at,$website->get_image_hourly) }}
                  </span>
                </span>
              </a>
              <a href="@auth javascript:void(0);  @else {{ route('login') }} @endauth" class="show_subcat fav" website_id="1366">
                <i class="fa fa-{{ in_array($website->id,$myFlips)?'check-square-o':'square-o' }}"></i> myFlip </a> | <span class="count_of">
                  {{ $websiteNumber }}
                  
                  of {{ $totalWebsites }} Sites</span>
            </div>
            <div class="text-left website_title hidden-xs">
              <a target="_blank" href="{{ $website->url }}" class="pull-left">
                <span class="title">{{ $website->name }}</span> &nbsp; <span class="label"> Alexa: {{ $website->alexa }}</span> &nbsp;&nbsp; 
                @if(!empty($website->getCategory))
                  <span class="category">{{ $website->getCategory->name }},</span>&nbsp; 
                  @endif
                @if(!empty($website->getSubCategory))
                  <span class="sub_category">{{ $website->getSubCategory->name }}</span>
                @endif &nbsp; 
                <span class="updated_at">| 
                  @if($website->get_image_hourly=='H')
                    {{-- <i class='fa fa-bolt'></i>  --}}
                    Hourly
                  @elseif($website->get_image_hourly=='W')
                    {{-- <i class='fa fa-calendar'></i>  --}}
                    Weekly
                  @elseif($website->get_image_hourly=='D')
                    {{-- <i class='fa fa-cogs'></i>  --}}
                    Daily
                  @else
                    {{-- <i class='fa fa-calendar'></i>  --}}
                    Monthly
                  @endif

                  - <span>
                  {{ Helper::getTime($website->image_updated_at,$website->get_image_hourly) }}
                  </span> | </span>
                <span class="count_of">
                  {{ $websiteNumber }}
                  
                  of {{ $totalWebsites }} Sites</span>
              </a>
              <a href="@auth javascript:void(0);  @else {{ route('login') }} @endauth" class="show_subcat fav pull-right" website_id="{{ $website->id }}" my_eflip_id="">
                <i class="fa fa-{{ in_array($website->id,$myFlips)?'check-square-o':'square-o' }}"></i> myFlip </a>
              <div class="clearfix"></div>
            </div>
            
            <div class="thumbnail">
              <div class="hover_thumbnail">
                <div class="image_hover_effect">
                  <div class="hover_text top_mobile_0 hidden-lg hidden-xs">
                    <div class="hover_button">
                      <a class="btn btn-xlarge lightbox_btn hidden-xs" data-show-more="true" data-lightbox="image-1" data-next-cat="" data-prev-cat="" 
                              data-title="<a style='color:#fff' target='_blank' href='{{ $website->url }}'>
                                                <span style='color:yellow'>{{ $website->name }}</span> (Alexa: 5650) - 
                                                <span class='cat_blue'>
                                                    <i class='fa fa-bullseye'></i> Ideas
                                                </span> &nbsp;
                                                <span class='time_red'>
                                                    <i class='fa fa-info-circle'></i> Specialized
                                                </span> &nbsp;
                                                <span class='updated_at'>
                                                    <i class='fa fa-cogs'></i> Daily - 
                                                    <span class='cat_blue'>8h</span> - 
                                                    <span style='color:yellow'>2 of 454</span>
                                                </span>
                                                <br/>
                                            </a>" href="{{ url('/public/') }}/assets/website_images/original/{{ $website->image_url }}?{{ $timeNow.$website->id }}">
                        <i class="fa fa-search-plus"></i>
                      </a>
                      <a class="btn btn-xlarge btn_link" href="{{ $website->url }}" ref="nofollow" target="_blank">
                        <i class="fa fa-link" title="Go to this link"></i>
                      </a>
                    </div>
                  </div>
                  <a target="_blank" ref="nofollow" href="{{ $website->url }}" alt="{{ $website->url }}">
                    <img src="{{ url('/public/') }}/assets/website_images/original/{{ $website->image_url }}?{{ $timeNow.$website->id }}"
                          alt="{{ $website->name }}" class="img-responsive"  display: block;">
                  </a>
                </div>
              </div>
            </div>
            <div class="website_url text-left">
              <div class="col-sm-5 p-0 mt-3">
                <a target="_blank" href="{{ $website->url }}" ref="nofollow">{{ str_replace('https://','',$website->url) }}</a>
              </div>
              <div class="col-sm-7"></div>
              <div class="clearfix"></div>
            </div> 
          </div>

          @if(($key+1)%40==0)
            <div class="col-lg-1 visible-lg" style="position: relative;height:100%;">
              <a class="nav_dir" href="" id="next-page-link" style="position: absolute;top: 40%;right: 90%;left: 0;color: #e74c3c !important;">
                <span>or use arrow keys</span>
                <i class="fa fa-chevron-down fa-3x"></i>
                <span>Next</span>
              </a>
            </div>
          @else
            <div class="col-lg-1 next_div visible-lg">
              <a class="nav_dir" data-target="{{ $key+1 }}">
                  <span>or use arrow keys</span>
                <i class="fa fa-chevron-down fa-3x"></i>
                <span>Next</span>
              </a>
            </div>
          @endif
        </div>
        
        <div class="clearfix"></div>

        @php
          if(!empty($_GET['page'])){
            $pageNumber=(($_GET['page']-1)*$perpage)+$key+1;
          }else{
            $pageNumber=$key+1;
          }
          $slideImages.='<div class="mini-website-box">
              <a class="sidebar_nav" data-slide-href="'. $key .'" ref="nofollow">
                <img src="'.url("/public/").'/assets/website_images/original/'.$website->image_url.'?'.$timeNow.$website->id.'" 
                  alt="'. $website->name .'" class="img-responsive">
              </a>
              <a class="sidebar_nav" data-slide-href="'. $key .'"><strong>
                
                '.$pageNumber.' - '.$website->name.'</strong>
              </a>
          </div>';
        @endphp

      @empty
        
      @endforelse

      {{ $websites->onEachSide(1)->withQueryString()->links() }}



    </div>
    <div class="clearfix"></div>
    <script>
      var page = 1;
      var firstLighBox = false;
      var new_ele = '';
      var minusHeight = 0;
      var scrollToNext = true;
      if ($(window).width() > 750) {
        minusHeight = 165;
      } else if ($(window).width() < 460) {
        minusHeight = 300;
      }
    </script>
  </div>
  
  <div class="rightSidebar padding-left0 text-center visible-lg">
    <div class="website_image_slide_div">
      <a href="javascript:void(0)" class="prev_scroll scroll_btn">
        <span>Prev</span>
        <i class="fa fa-angle-up fa-2x"></i>
      </a>
      <div class="text-center website_image_slide">
        <div id="sidebar_scroll" class="col-sm-12">

          {{-- @if(isset($_GET['page']) && $_GET['page']>1)
            <a href="{{ route('index','page='.($_GET['page']-1)) }}" class="btn btn-primary">Show Prev</a>
          @endif --}}

          {!! $slideImages !!}


          {{-- @forelse($websites as $key=>$website)
            <div>
                <a class="sidebar_nav" data-slide-href="{{ $key }}" ref="nofollow">
                  <img src="{{ url('/public/') }}/assets/website_images/original/{{ $website->image_url }}?{{ $timeNow.$website->id }}" 
                    alt="{{ $website->name }}" class="img-responsive" loading="eager">
                </a>
                <a class="sidebar_nav" data-slide-href="{{ $key }}"><strong>
                  @if(!empty($_GET['page']))
                    {{ (($_GET['page']-1)*$perpage)+$key+1 }}
                  @else 
                    {{ $key+1 }}
                  @endif
                  - {{ $website->name }}</strong>
                </a>
            </div>
          @empty
            
          @endforelse --}}


          {{-- @if(count($websites))
            <a href="" class="btn btn-primary">Show More</a>
          @endif --}}

        </div>
      </div>
      <div class="clearfix"></div>
      <a href="javascript:void(0)" class="next_scroll scroll_btn">
        <i class="fa fa-angle-down fa-2x"></i>
        <span>Next</span>
      </a>
    </div>
  </div>
  
  <div class="clearfix"></div>

@endsection