<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="csrf-token" content="{{csrf_token()}}">
  <meta name="author" content="Altaf Hossain Limon">
  <title>@yield('title') -{{ Helper::getSetting('website_name') }} | Admin</title>

  <!-- Favicons-->
  <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
  <link rel="apple-touch-icon" type="image/x-icon" href="img/apple-touch-icon-57x57-precomposed.png')}}">
  <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="{{asset('admin/img/apple-touch-icon-72x72-precomposed.png')}}">
  <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="{{asset('admin/img/apple-touch-icon-114x114-precomposed.png')}}">
  <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="{{asset('admin/img/apple-touch-icon-144x144-precomposed.png')}}">

  <!-- Bootstrap core CSS-->
  <link href="{{asset('admin/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <!-- Main styles -->
  <link href="{{asset('admin/css/admin.css')}}" rel="stylesheet">
  <!-- Icon fonts-->
  <link href="{{asset('admin/vendor/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
  <!-- Plugin styles -->
  <link href="{{asset('admin/vendor/datatables/dataTables.bootstrap4.css')}}" rel="stylesheet">
  <!-- Your custom styles -->
  <link href="{{asset('css/toastr.min.css')}}" rel="stylesheet">
  @yield('custom-css')
  <link href="{{asset('admin/css/custom.css')}}" rel="stylesheet">

</head>

<body class="fixed-nav sticky-footer" id="page-top">
  @include('admin/includes/sidebar')
  @include('admin/includes/header')

    <div class="content-wrapper">
      <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="{{url('/admin/dashboard')}}">Admin</a>
          </li>
          @yield('breadcrumbs')
        </ol>
      <!-- Icon Cards-->
      
      @yield('content')

    </div>

  @include('admin/includes/footer')
  <!-- Bootstrap core JavaScript-->
  <script src="{{asset('admin/vendor/jquery/jquery.min.js')}}"></script>
  <script src="{{asset('admin/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <!-- Core plugin JavaScript-->
  <script src="{{asset('admin/vendor/jquery-easing/jquery.easing.min.js')}}"></script>
  <!-- Page level plugin JavaScript-->
  <script src="{{asset('admin/vendor/datatables/jquery.dataTables.js')}}"></script>
  <script src="{{asset('admin/vendor/datatables/dataTables.bootstrap4.js')}}"></script>
  <script src="{{asset('admin/vendor/jquery.selectbox-0.2.js')}}"></script>
  <script src="{{asset('admin/vendor/retina-replace.min.js')}}"></script>
  <script src="{{asset('admin/vendor/jquery.magnific-popup.min.js')}}"></script>
  <script src="{{asset('js/toastr.min.js')}}"></script>
  <!-- Custom scripts for all pages-->
  <script src="{{asset('admin/js/admin.js')}}"></script>
  <script>
    @if(session('success'))

      toastr.success("{{session('success')}}");

    @endif

    @if(session('error'))

      toastr.warning("{{session('error')}}");

    @endif
    @if ($errors->any())
      @foreach ($errors->all() as $error)

        toastr.warning("{{$error}}");

      @endforeach
    @endif
  </script>
 

  @yield('scripts')

</body>
</html>
