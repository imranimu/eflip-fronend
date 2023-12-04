
  <!-- Navigation-->
  <nav class="navbar navbar-expand-lg navbar-dark bg-default fixed-top" id="mainNav">
    <a class="navbar-brand" href="{{url('/')}}">
        <img src="{{asset('public/logo.png')}}" data-retina="true" alt="" height="36">
     Admin
   </a>
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarResponsive">
      <ul class="navbar-nav navbar-sidenav" id="exampleAccordion">

        <li class="nav-item {{ preg_match('(dashboard)', request()->url())==1 ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Dashboard">
          <a class="nav-link" href="{{url('/admin/dashboard')}}">
            <i class="fa fa-fw fa-dashboard"></i>
            <span class="nav-link-text">Dashboard</span>
          </a>
        </li>
        <li class="nav-item {{ preg_match('(contest/essays)', request()->url())==1 ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Categories">
          <a class="nav-link" href="{{url('/admin/contest/essays')}}">
              <i class="fa fa-fw fa-file"></i>
              <span class="nav-link-text">Contest Essays</span>
          </a>
        </li>

        
        {{-- <li class="nav-item {{ preg_match('(service)', request()->url())==1 ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Services">
          <a class="nav-link" href="{{url('/admin/services')}}">
            <i class="fa fa-fw fa-th"></i>
            <span class="nav-link-text">Services</span>
          </a>
        </li>

        <li class="nav-item {{ preg_match('(areas)', request()->url())==1 ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Areas">
          <a class="nav-link" href="{{url('/admin/areas')}}">
            <i class="fa fa-fw fa-sitemap"></i>
            <span class="nav-link-text">Areas</span>
          </a>
        </li>

        <li class="nav-item {{ preg_match('(blog)', request()->url())==1 ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Blogs">
          <a class="nav-link" href="{{url('/admin/blogs')}}">
            <i class="fa fa-fw fa-file-o"></i>
            <span class="nav-link-text">Blogs</span>
          </a>
        </li>

        
        <li class="nav-item {{ preg_match('(page)', request()->url())==1 ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Pages">
          <a class="nav-link" href="{{url('/admin/pages')}}">
              <i class="fa fa-fw fa-file"></i>
              <span class="nav-link-text">Pages</span>
          </a>
        </li>
         --}}
        
        {{-- <li class="nav-item {{ preg_match('(quotation)', request()->url())==1 ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="quotations">
          <a class="nav-link" href="{{url('/admin/quotations')}}">
            <i class="fa fa-fw fa-list"></i>
            <span class="nav-link-text">Quotations</span>
          </a>
        </li>
        <li class="nav-item {{ preg_match('(clicks)', request()->url())==1 ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Clicks">
          <a class="nav-link" href="{{url('/admin/clicks')}}">
            <i class="fa fa-fw fa-mouse-pointer"></i>
            <span class="nav-link-text">Clicks</span>
          </a>
        </li>

        <li class="nav-item {{ preg_match('(product)', request()->url())==1 ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Products">
          <a class="nav-link" href="{{url('/admin/products')}}">
            <i class="fa fa-fw fa-list-alt"></i>
            <span class="nav-link-text">Products</span>
          </a>
        </li>

      


		 
		 

          <li class="nav-item {{ preg_match('(inbox)', request()->url())==1 ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Inbox">
              <a class="nav-link" href="{{url('/admin/inbox')}}">
                  <i class="fa fa-fw fa-envelope"></i>
                  <span class="nav-link-text">Inbox</span>
              </a>
          </li>
          <li class="nav-item {{ preg_match('(users)', request()->url())==1 ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Users">
              <a class="nav-link" href="{{url('/admin/users')}}">
                  <i class="fa fa-fw fa-users"></i>
                  <span class="nav-link-text">Users</span>
              </a>
          </li>
          <li class="nav-item {{ preg_match('(sitemap)', request()->url())==1 ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Sitemap">
            <a class="nav-link" target="_blank" href="{{url('/sitemap')}}">
                <i class="fa fa-fw fa-sitemap"></i>
                <span class="nav-link-text">Sitemap</span>
            </a>
        </li> --}}
        <li class="nav-item {{ preg_match('(contest/categor)', request()->url())==1 ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Categories">
          <a class="nav-link" href="{{url('/admin/contest/categories')}}">
              <i class="fa fa-fw fa-table"></i>
              <span class="nav-link-text">Contest Categories</span>
          </a>
      </li>

        <li class="nav-item {{ preg_match('(settings)', request()->url())==1 ? 'active' : '' }}" data-toggle="tooltip" data-placement="right" title="Settings">
          <a class="nav-link" href="{{url('/admin/settings')}}">
              <i class="fa fa-fw fa-file"></i>
              <span class="nav-link-text">Settings</span>
          </a>
        </li>

      </ul>
      <ul class="navbar-nav sidenav-toggler">
        <li class="nav-item">
          <a class="nav-link text-center" id="sidenavToggler">
            <i class="fa fa-fw fa-angle-left"></i>
          </a>
        </li>
      </ul>
