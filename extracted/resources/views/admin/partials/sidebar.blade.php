<ul class="menu-inner py-1">
    <!-- Dashboard -->

    @if(auth()->user()->role_id == 1)
      <li class="menu-item {{ str_contains(url()->current(), 'admin/dashboard') ? 'active' : '' }}">
        <a href="{{ url('admin/dashboard') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-home-circle"></i>
          <div data-i18n="Analytics">Dashboard</div>
        </a>
      </li>
      <li class="menu-item {{ str_contains(url()->current(), 'admin/users') ? 'active' : '' }}">
        <a href="{{ route('admin.users') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-user"></i>
          <div data-i18n="Analytics">Users</div>
        </a>
      </li>
      {{-- <li class="menu-item {{ str_contains(url()->current(), 'admin/contractors') ? 'active' : '' }}">
        <a href="{{ url('admin/contractors') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-user"></i>
          <div data-i18n="Analytics">Contractors</div>
        </a>
      </li>
      <li class="menu-item {{ str_contains(url()->current(), 'admin/workers') ? 'active' : '' }}">
        <a href="{{ url('admin/workers') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-user"></i>
          <div data-i18n="Analytics">Workers</div>
        </a>
      </li>--}}
      <li class="menu-item {{ str_contains(url()->current(), 'admin/jobs') ? 'active' : '' }}">
        <a href="{{ url('admin/jobs') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-crown"></i>
          <div data-i18n="Analytics">Jobs</div>
        </a>
      </li>
      <li class="menu-item {{ str_contains(url()->current(), 'admin/events') ? 'active' : '' }}">
        <a href="{{ url('admin/events') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bxs-calendar-event"></i>
          <div data-i18n="Analytics">Events</div>
        </a>
      </li>
      <li class="menu-item {{ str_contains(url()->current(), 'admin/products') ? 'active' : '' }}">
        <a href="{{ url('admin/products') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-crown"></i>
          <div data-i18n="Analytics">Products</div>
        </a>
      </li>
      <li class="menu-item {{ str_contains(url()->current(), 'admin/services') ? 'active' : '' }}">
        <a href="{{ url('admin/services') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-crown"></i>
          <div data-i18n="Analytics">Services</div>
        </a>
      </li>
      <li class="menu-item {{ str_contains(url()->current(), 'admin/orders') ? 'active' : '' }}">
        <a href="{{ url('admin/orders') }}" class="menu-link">
          <i class="menu-icon tf-icons bx bx-crown"></i>
          <div data-i18n="Analytics">Orders</div>
        </a>
      </li>

      <li class="menu-item {{ str_contains(url()->current(), 'admin/banners') ? 'active' : '' }}">
        <a href="{{ route('admin.banners.index') }}" class="menu-link">
          <i class='menu-icon tf-icons bx bxs-book-content'></i>
          <div data-i18n="Analytics">Banners</div>
        </a>
      </li>
      @php
        $url = url()->current();
        $arr = [
          'privacy-policy',
          'terms-and-conditions',
          'about-us',
          'united-capitalism',
          'pdf_file'
        ];
        $className = '';
        if (in_array(explode('/', $url, 5)[4], $arr)) {
          $className = 'open';
        }
      @endphp
      <li class="menu-item {{$className}}">
        <a href="javascript:void(0);" class="menu-link menu-toggle">
          <i class="menu-icon tf-icons bx bx-layout"></i>
          <div data-i18n="Layouts">Site Setting</div>
        </a>

        <ul class="menu-sub">
          <li class="menu-item {{ str_contains(url()->current(), 'admin/privacy-policy') ? 'active' : '' }}">
          <a href="{{ url('admin/privacy-policy') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-dock-top"></i>
            <div data-i18n="Analytics">Privacy Policy</div>
          </a>
          </li>
          <li class="menu-item {{ str_contains(url()->current(), 'admin/terms-and-conditions') ? 'active' : '' }}">
            <a href="{{ url('admin/terms-and-conditions') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-cube-alt"></i>
              <div data-i18n="Analytics">Terms & Conditions</div>
            </a>
          </li> 
          <li class="menu-item {{ str_contains(url()->current(), 'admin/about-us') ? 'active' : '' }}">
            <a href="{{ url('admin/about-us') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-dock-top"></i>
              <div data-i18n="Analytics">About Us</div>
            </a>
          </li>
          <li class="menu-item {{ str_contains(url()->current(), 'admin/united-capitalism') ? 'active' : '' }}">
            <a href="{{ url('admin/united-capitalism') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-dock-top"></i>
              <div data-i18n="Analytics">United Captialism</div>
            </a>
          </li>
          <li class="menu-item {{ str_contains(url()->current(), 'admin/pdf_file') ? 'active' : '' }}">
            <a href="{{ url('admin/pdf_file') }}" class="menu-link">
              <i class="menu-icon tf-icons bx bx-file"></i>
              <div data-i18n="Analytics">Upload File</div>
            </a>
          </li>
        </ul>
      </li>
      <li class="menu-item {{ str_contains(url()->current(), 'admin/delete-data-request') ? 'active' : '' }}">
        <a href="{{ route('admin.deleteAccountRequests') }}" class="menu-link">
          <i class='menu-icon tf-icons bx bxs-book-content'></i>
          <div data-i18n="Analytics"> Delete Account Requests</div>
        </a>
      </li>
    @else
      <li class="menu-item {{ str_contains(url()->current(), 'admin/account-request') ? 'active' : '' }}">
        <a href="{{ route('admin.accRequest') }}" class="menu-link">
          <i class='menu-icon tf-icons bx bxs-book-content'></i>
          <div data-i18n="Analytics">Request For Account Delete</div>
        </a>
      </li>
    @endif
    

    <li class="menu-item">
      <a href="{{route('admin.logout')}}" class="menu-link">
        <i class="menu-icon tf-icons bx bx-power-off"></i>
        <div data-i18n="Analytics">Logout</div>
      </a>
    </li>
  </ul>