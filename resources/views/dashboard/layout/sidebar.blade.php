<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">AdminLTE 3</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    
            <!-- Dashboard -->
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" 
                   class="nav-link {{ Request::is('super-admin/dashboard') ? 'active' : '' }}">
                    <i class="nav-icon fi fi-rr-dashboard"></i>
                    <p>Dashboard</p>
                </a>
            </li>
    
            @role('superAdmin')
            <!-- Data Pajak -->
            <li class="nav-item">
                <a href="{{ route('detail-pajak.index') }}" 
                   class="nav-link {{ Request::is('super-admin/detail-pajak') ? 'active' : '' }}">
                    <i class="nav-icon fi fi-tr-document-paid"></i>
                    <p>Data Pajak</p>
                </a>
            </li>
            @endrole
    
            
            <!-- Data Tagihan (bisa untuk semua role) -->
            <li class="nav-item">
              <a href="{{ route('detail-tagihan.index') }}" 
              class="nav-link {{ Request::is('super-admin/detail-tagihan') ? 'active' : '' }}">
              <i class="nav-icon fi fi-ts-to-do-alt"></i>
              <p>Data Tagihan</p>
            </a>
          </li>
            <!-- Data Pajak -->
          <li class="nav-item">
              <a href="{{ route('riwayat-pajak.index') }}" 
                  class="nav-link {{ Request::is('super-admin/riwayat-pajak') ? 'active' : '' }}">
                  <i class="nav-icon fi-rr-time-past"></i>
                  <p>Data History Pembayaran</p>
              </a>
          </li>

          @role('superAdmin')
          <!-- Data User -->
          <li class="nav-item">
              <a href="{{ route('user.index') }}" 
                 class="nav-link {{ Request::is('super-admin/user') ? 'active' : '' }}">
                  <i class="nav-icon fi fi-rr-users-alt"></i>
                  <p>Data User</p>
              </a>
          </li>
          @endrole
    
            <!-- Logout -->
            <li class="nav-item mt-auto">
                <a href="{{ route('auth.logout') }}" 
                   class="nav-link {{ Request::is('auth.logout') ? 'active' : '' }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="nav-icon fi fi-rr-sign-out-alt"></i>
                    <p>Logout</p>
                </a>
    
                <form id="logout-form" action="{{ route('auth.logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
    
        </ul>
    </nav>
    
      
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>