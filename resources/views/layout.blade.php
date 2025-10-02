<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <title>Student Management System</title>

  <style>
      /* Custom Header */
.custom-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: linear-gradient(90deg, #1e3a8a 0%, #3b82f6 100%);
  color: white;
  padding: 0;
  height: 56px;
  box-shadow: 0 2px 4px rgba(0,0,0,0.1);
  position: relative;
  z-index: 1000;
}

.header-left {
  display: flex;
  align-items: center;
  background-color: #1e3a8a;
  padding: 0 16px;
  height: 100%;
  width: 250px;
}

.header-left .header-title {
  margin: 0 auto;
}

.header-title {
  font-size: 20px;
  font-weight: bold;
  margin-right: 15px;
}

.hamburger-btn {
  background: none;
  border: none;
  color: white;
  font-size: 18px;
  cursor: pointer;
  padding: 8px;
  border-radius: 4px;
  transition: background-color 0.3s ease;
}

.hamburger-btn:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.header-right {
  display: flex;
  align-items: center;
  padding: 0 20px;
  height: 100%;
  flex: 1;
  justify-content: flex-end;
}

.user-info {
  font-size: 16px;
  font-weight: 500;
}

.logout-btn {
  background: none;
  border: none;
  color: white;
  font-size: 16px;
  cursor: pointer;
  padding: 8px 12px;
  border-radius: 4px;
  transition: background-color 0.3s ease;
}

.logout-btn:hover {
  background-color: rgba(255, 255, 255, 0.1);
}

.auth-buttons {
  display: flex;
  gap: 10px;
  align-items: center;
}

.auth-btn {
  color: white;
  text-decoration: none;
  padding: 8px 16px;
  border-radius: 4px;
  font-size: 14px;
  font-weight: 500;
  transition: all 0.3s ease;
  display: flex;
  align-items: center;
  gap: 5px;
}

.login-btn {
  background-color: rgba(255, 255, 255, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.3);
}

.login-btn:hover {
  background-color: rgba(255, 255, 255, 0.2);
  color: white;
  text-decoration: none;
}

.register-btn {
  background-color: rgba(255, 255, 255, 0.2);
  border: 1px solid rgba(255, 255, 255, 0.4);
}

.register-btn:hover {
  background-color: rgba(255, 255, 255, 0.3);
  color: white;
  text-decoration: none;
}

/* The side navigation menu */
.sidebar {
  margin: 0;
  padding: 0;
  width: 250px;
  background-color: #2c3e50;
  position: fixed;
  height: calc(100% - 56px);
  top: 56px;
  overflow: auto;
  box-shadow: 2px 0 5px rgba(0,0,0,0.1);
  transition: transform 0.3s ease;
}

/* Sidebar header */
.sidebar-header {
  display: none;
}

.sidebar-header h4 {
  margin: 0;
  font-size: 16px;
  font-weight: bold;
}

/* Sidebar links */
.sidebar a {
  display: flex;
  align-items: center;
  gap: 10px;
  color: #c9d1d9;
  padding: 6px 16px;
  text-decoration: none;
  border-bottom: 1px solid #32485a;
  transition: background-color 0.2s ease, color 0.2s ease;
  font-size: 15px;
  line-height: 1;
  min-height: 40px;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.sidebar a i {
  width: 18px;
  text-align: center;
}

/* Active/current link */
.sidebar a.active {
  background-color: #24323d;
  color: #ffffff;
  font-weight: 600;
}

/* Links on mouse-over */
.sidebar a:hover:not(.active) {
  background-color: #2a3a46;
  color: #ffffff;
}

/* Page content. The value of the margin-left property should match the value of the sidebar's width property */
div.content {
  margin-left: 250px;
  padding: 20px;
  min-height: calc(100vh - 56px);
  margin-top: 56px;
}

/* Mobile responsive */
@media screen and (max-width: 768px) {
  .sidebar {
    width: 250px;
    transform: translateX(-100%);
    z-index: 999;
  }
  
  .sidebar.show {
    transform: translateX(0);
  }
  
  div.content {
    margin-left: 0;
    margin-top: 60px;
  }
  
  .header-left {
    min-width: 150px;
  }
  
  .header-title {
    font-size: 16px;
  }
  
  .user-info {
    font-size: 14px;
  }
  
  .auth-buttons {
    gap: 5px;
  }
  
  .auth-btn {
    padding: 6px 12px;
    font-size: 12px;
  }
  
  .logout-btn {
    padding: 6px 10px;
    font-size: 14px;
  }
}

@media screen and (max-width: 480px) {
  .header-left {
    min-width: 120px;
    padding: 0 10px;
  }
  
  .header-title {
    font-size: 14px;
    margin-right: 10px;
  }
  
  .header-right {
    padding: 0 10px;
  }
  
  .user-info {
    font-size: 12px;
  }
  
  .auth-buttons {
    gap: 3px;
  }
  
  .auth-btn {
    padding: 4px 8px;
    font-size: 11px;
  }
  
  .logout-btn {
    padding: 4px 8px;
    font-size: 12px;
  }
}


  </style>




















   
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-md-12">


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
      </div>
      </div>


      <!-- The sidebar -->
      <div class="sidebar">
          <div class="sidebar-header">
        
          </div>
          
          <a class="{{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
            <i class="fas fa-home"></i> Trang chủ
          </a>
          
          <a class="{{ request()->is('departments*') ? 'active' : '' }}" href="{{ route('departments.index') }}">
            <i class="fas fa-university"></i> Khoa
          </a>
          
          <a class="{{ request()->is('classes*') ? 'active' : '' }}" href="{{ route('classes.index') }}">
            <i class="fas fa-door-open"></i> Lớp
          </a>
          
          <a class="{{ request()->is('students*') ? 'active' : '' }}" href="{{ route('students.index') }}">
            <i class="fas fa-user-graduate"></i> Sinh viên
          </a>
          
          <a class="{{ request()->is('teachers*') ? 'active' : '' }}" href="{{ route('teachers.index') }}">
            <i class="fas fa-chalkboard-teacher"></i> Quản lý giảng viên
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
          
          @if(auth()->check() && in_array(auth()->user()->role, ['super_admin', 'admin']))
          <a class="{{ request()->is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
            <i class="fas fa-users-cog"></i> Thành viên
          </a>
          
          <a class="{{ request()->is('roles*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
            <i class="fas fa-user-shield"></i> Vai trò thành viên
          </a>
          @endif
          
      </div>
      
      <!-- Page content -->
      <div class="content">
        @yield('content')
      </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-cEVzO2X2gYdEx+kt1lEuzMdGII4XESyqCCpt5TR1+t0NenE2no0RvrRZtGJPD7hG" crossorigin="anonymous"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
        if (window.bootstrap && bootstrap.Dropdown) {
          document.querySelectorAll('.dropdown-toggle').forEach(function (el) {
            new bootstrap.Dropdown(el);
          });
        }
      });

      // Toggle sidebar function
      function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar');
        const content = document.querySelector('.content');
        
        if (sidebar.style.transform === 'translateX(-100%)') {
          sidebar.style.transform = 'translateX(0)';
          content.style.marginLeft = '250px';
        } else {
          sidebar.style.transform = 'translateX(-100%)';
          content.style.marginLeft = '0';
        }
      }

      // Close sidebar on mobile when clicking outside
      document.addEventListener('click', function(event) {
        const sidebar = document.querySelector('.sidebar');
        const hamburgerBtn = document.querySelector('.hamburger-btn');
        
        if (window.innerWidth <= 768 && 
            !sidebar.contains(event.target) && 
            !hamburgerBtn.contains(event.target)) {
          sidebar.style.transform = 'translateX(-100%)';
          document.querySelector('.content').style.marginLeft = '0';
        }
      });

      // Handle window resize
      window.addEventListener('resize', function() {
        const sidebar = document.querySelector('.sidebar');
        const content = document.querySelector('.content');
        
        if (window.innerWidth > 768) {
          sidebar.style.transform = 'translateX(0)';
          content.style.marginLeft = '250px';
        } else {
          sidebar.style.transform = 'translateX(-100%)';
          content.style.marginLeft = '0';
        }
      });
    </script>
    
    @stack('js')
</body>
</html>