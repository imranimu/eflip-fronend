<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-5L7TNS3');</script>
    <!-- End Google Tag Manager -->
    
    
    
    @include('meta-seo-eflip')

     <!--<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="At Eflip,discover fascinating sites with latest information with trending news! Click here to see all {{  $totalHourlydailyWebsites }}, and go to the sites by clicking on the images" / <meta property="og:image" content="{{ asset('/public/assets/') }}/website_images/eflipForFb.jpg" />
    <meta property="fb:app_id" content="1655023414766688" />
    <meta property="og:description" content="Discover fascinating sites! Click NEXT to see all {{  $totalHourlydailyWebsites }}, and go to the sites by clicking on the images" />
    <meta property="og:title" content="Eflip.com - INSTANTLY flip through the CURRENT FRONT web-pages of the BEST {{  $totalHourlydailyWebsites }} websites, UPDATED every HOUR/DAY" />
    <link rel="image_src" href="{{ asset('/public/assets/') }}/website_images/eflipForFb.jpg" alt="Eflip" />
    <link rel="canonical" href="https://www.eflip.com/" />
    
    
    
    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('/public/assets/') }}/css/bootstrap-utilities.min.css" rel="stylesheet" alt="Eflip">
    <link href="{{ asset('/public/assets/') }}/css/bootstrap.min.css" rel="stylesheet" alt="Eflip">
    <link href="{{ asset('/public/assets/') }}/css/bootstrap-tour.min.css" rel="stylesheet" alt="Eflip">
    <link href="{{ asset('/public/assets/') }}/css/style.css" rel="stylesheet" alt="Eflip">
    <link href="{{ asset('/public/assets/') }}/css/design.css" rel="stylesheet" alt="Eflip">
    <link href="{{ asset('/public/assets/') }}/font-awesome-4.4.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    
    <link rel="shortcut icon" href="{{ asset('/public/assets/') }}/website_images/favicon.png" type="image/x-icon">
    <link rel="icon" href="{{ asset('/public/assets/') }}/website_images/favicon.png" type="image/x-icon">
    <!--lightbox-->
    <link href="{{ asset('/public/assets/') }}/lightbox/css/lightbox.css" rel="stylesheet" type="text/css">
    <!-- jQuery -->
    <script src="{{ asset('/public/assets/') }}/js/jquery.js"></script>
    <script src="{{ asset('/public/assets/') }}/js/jquery-ui.min.js"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="{{ asset('/public/assets/') }}/js/bootstrap.min.js"></script>
    <script src="{{ asset('/public/assets/') }}/js/bootstrap-tour-standalone.js"></script>
    <script src="{{ asset('/public/assets/') }}/js/jquery.cookie.js"></script>
    <!-- Modal js -->
    <script src="{{ asset('/public/assets/') }}/js/modal_config.js"></script>
    <script src="{{ asset('/public/assets/') }}/js/redirection-mobile.js"></script>
    <script src="{{ asset('/public/assets/') }}/js/jquery.ba-bbq.js"></script>
    <!--lightbox-->
    <script src="{{ asset('/public/assets/') }}/lightbox/js/lightbox.js"></script>
    <script src="{{ asset('/public/assets/') }}/js/SocialShare.js"></script>
    <script src="{{ asset('/public/assets/') }}/js/jquery.slimscroll.js"></script>
    

  

    <noscript>
      <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1984721878465928&ev=PageView&noscript=1" alt="Facebook" />
    </noscript>
    <!-- DO NOT MODIFY -->
    <!-- End Facebook Pixel Code -->
    <style>
      .mb-5{
        margin-top:30px;
      }

      #wrapAll{min-height: 70vh;}

      .color-maroon{color:maroon!important;}
      .border-color-blue{border-color: #072E7C!important;}
      .color-blue{color: #072E7C!important;}
      .color-red{color:red!important;}
    </style>
    @yield('styles')
  </head>
  <body class='homeBody'>
    @include('inc/nav')
    
    <div id="wrapAll">

      @include('inc/sidebar')

      @yield('content')

    </div>

    @include('inc/footer')

    @yield('scripts')
    
    
    <meta name="revisit-after" content="2 days">
    <meta name="distribution" content="Global">
    <meta name="copyright" content=" Eflip, Copyright © 2023 All Right Reserved.">
  </body>

 
</html>