<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">

        @if(isset($app_store))
            <li class="inventory-item">
                <label style="margin: 13px 15px;">
                    Cửa hàng
                </label>
            </li>
            <li class="inventory-item" style="border-right: 1px solid #E1E1E1; padding-right: 15px;">
                <select id="store-id" class="form-control" style="margin: 8px auto">
                    @foreach ($app_store as $key => $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                    @endforeach
                </select>
            </li>
        
        @endif

        <div class="topbar-divider d-none d-sm-block"></div>

        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Xin chào: {{ Auth::user()->name }}</span>
                
            </a>
            <!-- Dropdown - User Information -->
            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                aria-labelledby="userDropdown">
                
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="" data-toggle="modal" data-target="#logoutModal">
                    <i class="ti-shift-right mr-2 text-gray-400"></i>
                    Logout
                </a>
            </div>
        </li> 

    </ul>

</nav>