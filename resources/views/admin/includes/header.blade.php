
      <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" id="messagesDropdown" href="#" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fa fa-fw fa-user"></i>{{ auth()->user()->first_name}} {{ auth()->user()->last_name}}
          </a>
          <div class="dropdown-menu" aria-labelledby="messagesDropdown">
            <a class="dropdown-header" href="{{ url('admin/profile') }}">
                <i class="fa fa-fw fa-edit"></i> Profile
            </a>
              <a class="dropdown-header text-danger" href="#"
                  onclick="event.preventDefault();
                  document.getElementById('logout-form').submit();">
                  <i class="fa fa-fw fa-sign-out"></i> {{ __('Logout') }}
              </a>

              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
              </form>
            </form>
          </div>
        </li>

      </ul>
    </div>
  </nav>
