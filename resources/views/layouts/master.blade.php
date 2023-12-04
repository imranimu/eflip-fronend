<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AdminLTE 3 | Blank Page</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    {{-- <link rel="stylesheet" href="{{asset('/public/adminlte/')}}/plugins/fontawesome-free/css/all.min.css"> --}}
    <link rel="stylesheet" href="{{asset('/public/adminlte/')}}/dist/css/adminlte.min.css?v=3.2.0">
    {{-- <script nonce="a9e7a04c-59e8-4990-8eb3-c5fcb8fb4883">
      (function(w, d) {
        ! function(e, f, g, h) {
          e.zarazData = e.zarazData || {};
          e.zarazData.executed = [];
          e.zaraz = {
            deferred: [],
            listeners: []
          };
          e.zaraz.q = [];
          e.zaraz._f = function(i) {
            return function() {
              var j = Array.prototype.slice.call(arguments);
              e.zaraz.q.push({
                m: i,
                a: j
              })
            }
          };
          for (const k of ["track", "set", "debug"]) e.zaraz[k] = e.zaraz._f(k);
          e.zaraz.init = () => {
            var l = f.getElementsByTagName(h)[0],
              m = f.createElement(h),
              n = f.getElementsByTagName("title")[0];
            n && (e.zarazData.t = f.getElementsByTagName("title")[0].text);
            e.zarazData.x = Math.random();
            e.zarazData.w = e.screen.width;
            e.zarazData.h = e.screen.height;
            e.zarazData.j = e.innerHeight;
            e.zarazData.e = e.innerWidth;
            e.zarazData.l = e.location.href;
            e.zarazData.r = f.referrer;
            e.zarazData.k = e.screen.colorDepth;
            e.zarazData.n = f.characterSet;
            e.zarazData.o = (new Date).getTimezoneOffset();
            if (e.dataLayer)
              for (const r of Object.entries(Object.entries(dataLayer).reduce(((s, t) => ({
                  ...s[1],
                  ...t[1]
                }))))) zaraz.set(r[0], r[1], {
                scope: "page"
              });
            e.zarazData.q = [];
            for (; e.zaraz.q.length;) {
              const u = e.zaraz.q.shift();
              e.zarazData.q.push(u)
            }
            m.defer = !0;
            for (const v of [localStorage, sessionStorage]) Object.keys(v || {}).filter((x => x.startsWith("_zaraz_"))).forEach((w => {
              try {
                e.zarazData["z_" + w.slice(7)] = JSON.parse(v.getItem(w))
              } catch {
                e.zarazData["z_" + w.slice(7)] = v.getItem(w)
              }
            }));
            m.referrerPolicy = "origin";
            m.src = "/cdn-cgi/zaraz/s.js?z=" + btoa(encodeURIComponent(JSON.stringify(e.zarazData)));
            l.parentNode.insertBefore(m, l)
          };
          ["complete", "interactive"].includes(f.readyState) ? zaraz.init() : e.addEventListener("DOMContentLoaded", zaraz.init)
        }(w, d, 0, "script");
      })(window, document);
    </script> --}}
  </head>

  <body class="hold-transition sidebar-mini">
    <div class="wrapper">
      <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
              <i class="fas fa-bars"></i>
            </a>
          </li>
          <li class="nav-item d-none d-sm-inline-block">
            <a href="{{asset('/public/adminlte/')}}/index3.html" class="nav-link">Home</a>
          </li>
          <li class="nav-item d-none d-sm-inline-block">
            <a href="#" class="nav-link">Contact</a>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" data-widget="navbar-search" href="#" role="button">
              <i class="fas fa-search"></i>
            </a>
            <div class="navbar-search-block">
              <form class="form-inline">
                <div class="input-group input-group-sm">
                  <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
                  <div class="input-group-append">
                    <button class="btn btn-navbar" type="submit">
                      <i class="fas fa-search"></i>
                    </button>
                    <button class="btn btn-navbar" type="button" data-widget="navbar-search">
                      <i class="fas fa-times"></i>
                    </button>
                  </div>
                </div>
              </form>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
              <i class="far fa-comments"></i>
              <span class="badge badge-danger navbar-badge">3</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
              <a href="#" class="dropdown-item">
                <div class="media">
                  <img src="{{asset('/public/adminlte/')}}/dist/img/user1-128x128.jpg" loading="lazy" alt="User Avatar" class="img-size-50 mr-3 img-circle">
                  <div class="media-body">
                    <h3 class="dropdown-item-title"> Brad Diesel <span class="float-right text-sm text-danger">
                        <i class="fas fa-star"></i>
                      </span>
                    </h3>
                    <p class="text-sm">Call me whenever you can...</p>
                    <p class="text-sm text-muted">
                      <i class="far fa-clock mr-1"></i> 4 Hours Ago
                    </p>
                  </div>
                </div>
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item">
                <div class="media">
                  <img src="{{asset('/public/adminlte/')}}/dist/img/user8-128x128.jpg" loading="lazy" alt="User Avatar" class="img-size-50 img-circle mr-3">
                  <div class="media-body">
                    <h3 class="dropdown-item-title"> John Pierce <span class="float-right text-sm text-muted">
                        <i class="fas fa-star"></i>
                      </span>
                    </h3>
                    <p class="text-sm">I got your message bro</p>
                    <p class="text-sm text-muted">
                      <i class="far fa-clock mr-1"></i> 4 Hours Ago
                    </p>
                  </div>
                </div>
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item">
                <div class="media">
                  <img src="{{asset('/public/adminlte/')}}/dist/img/user3-128x128.jpg" alt="User Avatar" class="img-size-50 img-circle mr-3">
                  <div class="media-body">
                    <h3 class="dropdown-item-title"> Nora Silvester <span class="float-right text-sm text-warning">
                        <i class="fas fa-star"></i>
                      </span>
                    </h3>
                    <p class="text-sm">The subject goes here</p>
                    <p class="text-sm text-muted">
                      <i class="far fa-clock mr-1"></i> 4 Hours Ago
                    </p>
                  </div>
                </div>
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
              <i class="far fa-bell"></i>
              <span class="badge badge-warning navbar-badge">15</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
              <span class="dropdown-item dropdown-header">15 Notifications</span>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item">
                <i class="fas fa-envelope mr-2"></i> 4 new messages <span class="float-right text-muted text-sm">3 mins</span>
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item">
                <i class="fas fa-users mr-2"></i> 8 friend requests <span class="float-right text-muted text-sm">12 hours</span>
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item">
                <i class="fas fa-file mr-2"></i> 3 new reports <span class="float-right text-muted text-sm">2 days</span>
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
              <i class="fas fa-expand-arrows-alt"></i>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
              <i class="fas fa-th-large"></i>
            </a>
          </li>
        </ul>
      </nav>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
          <!-- Brand Logo -->
          <a href="" class="brand-link bg-success" id="brand-logo">
            <img src="{{asset('/public/adminlte/')}}/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light">AdminLTE 3</span>
          </a>

          <!-- Sidebar -->
          <div class="sidebar">
            <!-- Sidebar user (optional) -->
            {{-- <div class="user-panel mt-3 pb-3 mb-3 d-flex">
              <div class="image">
                <img src="{{asset('/assets')}}/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
              </div>
              <div class="info">
                <a href="#" class="d-block">Alexander Pierce</a>
              </div>
            </div> --}}

            <!-- SidebarSearch Form -->
            {{-- <div class="form-inline">
              <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                  <button class="btn btn-sidebar">
                    <i class="fas fa-search fa-fw"></i>
                  </button>
                </div>
              </div>
            </div> --}}

            <!-- Sidebar Menu -->
            <nav class="mt-2">
              <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                
                {{-- <li class="nav-header">MULTI LEVEL EXAMPLE</li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="fas fa-circle nav-icon"></i>
                    <p>Level 1</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-circle"></i>
                    <p>
                      Level 1
                      <i class="right fas fa-angle-left"></i>
                    </p>
                  </a>
                  <ul class="nav nav-treeview">
                    <li class="nav-item">
                      <a href="#" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Level 2</p>
                      </a>
                    </li>
                    <li class="nav-item">
                      <a href="#" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>
                          Level 2
                          <i class="right fas fa-angle-left"></i>
                        </p>
                      </a>
                      <ul class="nav nav-treeview">
                        <li class="nav-item">
                          <a href="#" class="nav-link">
                            <i class="far fa-dot-circle nav-icon"></i>
                            <p>Level 3</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="#" class="nav-link">
                            <i class="far fa-dot-circle nav-icon"></i>
                            <p>Level 3</p>
                          </a>
                        </li>
                        <li class="nav-item">
                          <a href="#" class="nav-link">
                            <i class="far fa-dot-circle nav-icon"></i>
                            <p>Level 3</p>
                          </a>
                        </li>
                      </ul>
                    </li>
                    <li class="nav-item">
                      <a href="#" class="nav-link">
                        <i class="far fa-circle nav-icon"></i>
                        <p>Level 2</p>
                      </a>
                    </li>
                  </ul>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="fas fa-circle nav-icon"></i>
                    <p>Level 1</p>
                  </a>
                </li>
                <li class="nav-header">LABELS</li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="nav-icon far fa-circle text-danger"></i>
                    <p class="text">Important</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="nav-icon far fa-circle text-warning"></i>
                    <p>Warning</p>
                  </a>
                </li> --}}
                <li class="nav-item">
                  <a href="#" class="nav-link">
                    <i class="nav-icon far fa-circle text-info"></i>
                    <p>Informational</p>
                  </a>
                </li>
              </ul>
            </nav>
            <!-- /.sidebar-menu -->
          </div>
          <!-- /.sidebar -->
        </aside>

      <div class="content-wrapper">
        <section class="content-header">
          <div class="container">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1>Blank Page</h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item">
                    <a href="#">Home</a>
                  </li>
                  <li class="breadcrumb-item active">Blank Page</li>
                </ol>
              </div>
            </div>
          </div>
        </section>
        <section class="content">
          <div class="row">
            <div class="col-md-10">
              <div class="row">
                <div class="col-md-1"></div>
                <div class="col-md-10">
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
                    <div class="card border border-dark shadow">
                      <div class="card-header">
                        
                        <h3 class="card-title">Title</h3>
                        {{-- <div class="card-tools">
                          <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                            <i class="fas fa-minus"></i>
                          </button>
                          <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                            <i class="fas fa-times"></i>
                          </button>
                        </div> --}}
                      </div>
                      <div class="card-body p-0">
                        <img src="{{ url('/public/') }}/assets/website_images/original/{{ $website->image_url }}?{{ strtotime(date('Y-m-d H:m:s')).$key }}" 
                          style="max-width:100%;max-height:100%;object-fit: contain">
                      </div>
                      <div class="card-footer"> Footer </div>
                    </div>
    
                  @empty
                    
                  @endforelse
                </div>
                <div class="col-md-1"></div>
              </div>
            

            </div>
            <div class="col-md-2">
              <div class="border border-dark shadow" style="height:600px;overflow-y:scroll;position: fixed;">
                @forelse($websites as $key=>$website)
                  <div class="card border shadow">
                    <div class="card-body p-0">
                      <img src="{{ url('/public/') }}/assets/website_images/original/{{ $website->image_url }}?{{ strtotime(date('Y-m-d H:m:s')).$key }}" 
                      style="max-width:100%;max-height:100%;object-fit: contain">
                    </div>
                  </div>
                @empty
                  
                @endforelse

              </div>
            </div>
          </div>
        </section>
      </div>
      <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
          <b>Version</b> 3.2.0
        </div>
        <strong>Copyright &copy; 2014-2021 <a href="https://adminlte.io">AdminLTE.io</a>. </strong> All rights reserved.
      </footer>
      <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>
    
    <script src="{{asset('/public/adminlte/')}}/plugins/jquery/jquery.min.js"></script>
    <script src="{{asset('/public/adminlte/')}}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="{{asset('/public/adminlte/')}}/dist/js/adminlte.min.js?v=3.2.0"></script>
    {{-- <script src="{{asset('/public/adminlte/')}}/dist/js/demo.js"></script> --}}
  </body>

</html>