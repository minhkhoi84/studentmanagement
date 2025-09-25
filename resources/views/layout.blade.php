<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/layout.css') }}">
    <title>Student Management System</title>
    @stack('css')
</head>
<body>
    <!-- Custom Header -->
    <div class="custom-header">
      <div class="header-left">
        <span class="header-title">Quản Lý SV</span>
        <button class="hamburger-btn" onclick="toggleSidebar()">
          <i class="fas fa-bars"></i>
        </button>
      </div>
      <div class="header-right">
        @auth
          <span class="user-info">{{ Auth::user()->name }}</span>
          <form method="POST" action="{{ route('logout') }}" style="display: inline; margin-left: 15px;">
            @csrf
            <button class="logout-btn" type="submit" title="Đăng xuất">
              <i class="fas fa-sign-out-alt"></i>
            </button>
          </form>
        @else
          <div class="auth-buttons">
            <a href="{{ route('login') }}" class="auth-btn login-btn">
              <i class="fas fa-sign-in-alt"></i> Đăng nhập
            </a>
            <a href="{{ route('register') }}" class="auth-btn register-btn">
              <i class="fas fa-user-plus"></i> Đăng ký
            </a>
          </div>
        @endauth
      </div>
    </div>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
            <i class="fas fa-home"></i> Trang chủ
        </a>
        <a href="{{ route('departments.index') }}" class="{{ request()->routeIs('departments.*') ? 'active' : '' }}">
            <i class="fas fa-building"></i> Khoa
        </a>
        <a href="{{ route('classes.index') }}" class="{{ request()->routeIs('classes.*') ? 'active' : '' }}">
            <i class="fas fa-graduation-cap"></i> Lớp
        </a>
        <a href="{{ route('students.index') }}" class="{{ request()->routeIs('students.*') ? 'active' : '' }}">
            <i class="fas fa-user-graduate"></i> Sinh viên
        </a>
        <a href="{{ route('teachers.index') }}" class="{{ request()->routeIs('teachers.*') ? 'active' : '' }}">
            <i class="fas fa-chalkboard-teacher"></i> Quản lý giảng viên
<<<<<<< Updated upstream
          </a>
          
          <a class="{{ request()->is('courses*') ? 'active' : '' }}" href="{{ route('courses.index') }}">
            <i class="fas fa-book-open"></i> Quản môn học
          </a>
          
          <a class="{{ request()->is('grades*') ? 'active' : '' }}" href="{{ route('grades.index') }}">
            <i class="fas fa-chart-line"></i> Quản lý điểm
          </a>
          
          <a class="{{ request()->is('attendances*') ? 'active' : '' }}" href="{{ route('attendances.index') }}">
            <i class="fas fa-clipboard-list"></i> Quản lý điểm danh
          </a>
          
          <a class="{{ request()->is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
            <i class="fas fa-users-cog"></i> Thành viên
          </a>
          
          <a class="{{ request()->is('roles*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
            <i class="fas fa-user-shield"></i> Vai trò thành viên
          </a>
          
      </div>
      
      <!-- Page content -->
      <div class="content">
=======
        </a>
        <a href="{{ route('courses.index') }}" class="{{ request()->routeIs('courses.*') ? 'active' : '' }}">
            <i class="fas fa-book"></i> Quản môn học
        </a>
        <a href="{{ route('grades.index') }}" class="{{ request()->routeIs('grades.*') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i> Quản lý điểm
        </a>
        <a href="{{ route('attendances.index') }}" class="{{ request()->routeIs('attendances.*') ? 'active' : '' }}">
            <i class="fas fa-clipboard-list"></i> Quản lý điểm danh
        </a>
        @if(Auth::check() && Auth::user()->role === 'super_admin')
        <a href="{{ route('users.index') }}" class="{{ request()->routeIs('users.*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> Thành viên
        </a>
        <a href="{{ route('roles.index') }}" class="{{ request()->routeIs('roles.*') ? 'active' : '' }}">
            <i class="fas fa-user-tag"></i> Vai trò thành viên
        </a>
        @endif
    </nav>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

    <!-- Main Content -->
    <div class="content">
>>>>>>> Stashed changes
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    
    <!-- Custom JavaScript -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const content = document.querySelector('.content');
            
            if (window.innerWidth <= 768) {
                // Mobile behavior
                if (sidebar.classList.contains('open')) {
                    sidebar.classList.remove('open');
                    overlay.classList.remove('show');
                } else {
                    sidebar.classList.add('open');
                    overlay.classList.add('show');
                }
            } else {
                // Desktop behavior
                if (sidebar.style.transform === 'translateX(-100%)') {
                    sidebar.style.transform = 'translateX(0)';
                    content.style.marginLeft = '250px';
                } else {
                    sidebar.style.transform = 'translateX(-100%)';
                    content.style.marginLeft = '0';
                }
            }
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const hamburgerBtn = document.querySelector('.hamburger-btn');
            const overlay = document.getElementById('sidebarOverlay');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !hamburgerBtn.contains(event.target) &&
                sidebar.classList.contains('open')) {
                closeSidebar();
            }
        });

        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const content = document.querySelector('.content');
            
            if (window.innerWidth > 768) {
                // Desktop: reset mobile classes
                sidebar.classList.remove('open');
                document.getElementById('sidebarOverlay').classList.remove('show');
                sidebar.style.transform = '';
                content.style.marginLeft = '';
            } else {
                // Mobile: hide sidebar by default
                sidebar.classList.remove('open');
                document.getElementById('sidebarOverlay').classList.remove('show');
            }
        });

        // Initialize sidebar state on page load
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const content = document.querySelector('.content');
            
            if (window.innerWidth <= 768) {
                sidebar.classList.remove('open');
                document.getElementById('sidebarOverlay').classList.remove('show');
            }
        });
    </script>

    @stack('js')
</body>
</html>