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
      /* The side navigation menu */
.sidebar {
  margin: 0;
  padding: 0;
  width: 250px;
  background-color: #2c3e50;
  position: fixed;
  height: 100%;
  overflow: auto;
  box-shadow: 2px 0 5px rgba(0,0,0,0.1);
}

/* Sidebar header */
.sidebar-header {
  background-color: #34495e;
  color: white;
  padding: 20px 15px;
  text-align: center;
  border-bottom: 1px solid #1a252f;
}

.sidebar-header h4 {
  margin: 0;
  font-size: 16px;
  font-weight: bold;
}

/* Sidebar links */
.sidebar a {
  display: block;
  color: #bdc3c7;
  padding: 12px 20px;
  text-decoration: none;
  border-bottom: 1px solid #34495e;
  transition: all 0.3s ease;
}

.sidebar a i {
  margin-right: 10px;
  width: 20px;
}

/* Active/current link */
.sidebar a.active {
  background-color: #3498db;
  color: white;
}

/* Links on mouse-over */
.sidebar a:hover:not(.active) {
  background-color: #34495e;
  color: white;
  padding-left: 25px;
}

/* Page content. The value of the margin-left property should match the value of the sidebar's width property */
div.content {
  margin-left: 250px;
  padding: 1px 16px;
  height: 1000px;
}

/* On screens that are less than 700px wide, make the sidebar into a topbar */
@media screen and (max-width: 700px) {
  .sidebar {
    width: 100%;
    height: auto;
    position: relative;
  }
  .sidebar a {float: left;}
  div.content {margin-left: 0;}
}

/* On screens that are less than 400px, display the bar vertically, instead of horizontally */
@media screen and (max-width: 400px) {
  .sidebar a {
    text-align: center;
    float: none;
  }
}


  </style>




















   
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row">
            <div class="col-md-12">


  <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#"><h2>Hệ Thống Quản Lý Sinh Viên</h2></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        @guest
          <li class="nav-item me-2">
            <a class="btn btn-outline-primary" href="{{ route('login') }}">Đăng nhập</a>
          </li>
          <li class="nav-item">
            <a class="btn btn-primary" href="{{ route('register') }}">Đăng ký</a>
          </li>
        @endguest
        @auth
          <li class="nav-item me-2">
            <span class="navbar-text">Hi, {{ Auth::user()->name }}!</span>
          </li>
          <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
              @csrf
              <button class="btn btn-outline-danger btn-sm" type="submit">Đăng xuất</button>
            </form>
          </li>
        @endauth
      </ul>
    </div>
  </div>
  </nav>
      </div>
      </div>


      <!-- The sidebar -->
      <div class="sidebar">
          <div class="sidebar-header">
            <h4>Quản Lý SV</h4>
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
          
          <a class="{{ request()->is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
            <i class="fas fa-users-cog"></i> Thành viên
          </a>
          
          <a class="{{ request()->is('roles*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
            <i class="fas fa-user-shield"></i> Vai trò thành viên
          </a>
          
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
    </script>
</body>
</html>