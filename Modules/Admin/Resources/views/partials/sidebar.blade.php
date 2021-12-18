<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="ti-face-smile"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Admin</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="ti-dashboard"></i>
            <span>Dashboard</span></a>
    </li>
    <hr class="sidebar-divider my-0">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('list.order') }}">
            <i class="ti-shopping-cart-full"></i>
            <span>Đơn hàng</span></a>
    </li>
    <hr class="sidebar-divider my-0">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('list.product') }}">
            <i class="ti-package"></i>
            <span>Sản phẩm</span></a>
    </li>
    <hr class="sidebar-divider my-0">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('list.customer') }}">
            <i class="fa fa-users"></i>
            <span>Khách hàng</span></a>
    </li>
    <hr class="sidebar-divider my-0">
   
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
          <i class="ti-wallet"></i><span>Tích điểm</span>
        </a>
        <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{ route('setting.loyalty') }}">Thiết lập</a>
                <a class="collapse-item" href="{{ route('setting.card') }}">Hạng thẻ tích điểm</a>

                <a class="collapse-item" href="{{ route('customer.card') }}">Khách hàng</a>
            </div>
        </div>
    </li>
    <hr class="sidebar-divider my-0">
    <li class="nav-item ">
        <a class="nav-link" href="{{ route('list.inventory') }}" >
            <i class="ti-home"></i>
            <span>Tồn kho</span></a>
    </li>
    <hr class="sidebar-divider my-0">
    <li class="nav-item">
        <a class="nav-link" href="{{ route('list.revenue') }}" >
            <i class="ti-receipt"></i>
            <span>Báo cáo</span></a>
    </li>
    
    <hr class="sidebar-divider my-0">

    <li class="nav-item">
        <a class="nav-link " href="{{ route('list.user') }}">
            <i class="ti-user"></i>
            <span>User</span>
        </a>
    </li>
    <hr class="sidebar-divider my-0">
    <li class="nav-item">
        <a class="nav-link " href="{{ route('list.role') }}">
            <i class="ti-unlock"></i>
            <span>Role</span>
        </a>
    </li>
    <hr class="sidebar-divider my-0">
    <li class="nav-item">
        <a class="nav-link " href="{{ route('permission.add') }}">
            <i class="ti-layout-slider-alt"></i>
            <span>Thêm dữ liệu cho bảng Permission</span>
        </a>
    </li>

    
  
    
    



</ul>