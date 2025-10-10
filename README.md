# Student Management System

Hệ thống quản lý sinh viên được xây dựng với Laravel backend và React frontend.

## Tính năng chính

- 👥 Quản lý sinh viên
- 👨‍🏫 Quản lý giáo viên
- 📚 Quản lý khóa học và lớp học
- 📊 Quản lý điểm số
- ✅ Quản lý điểm danh
- 🏢 Quản lý khoa/phòng ban
- 👤 Quản lý người dùng và phân quyền
- 📈 Thống kê và báo cáo

## Cấu trúc dự án

```
studentmanagement/
├── backend/          # Laravel API backend
│   ├── app/
│   ├── config/
│   ├── database/
│   ├── routes/
│   └── ...
├── frontend/         # React frontend
│   ├── src/
│   │   ├── components/
│   │   ├── contexts/
│   │   ├── hooks/
│   │   └── ...
│   └── public/
└── README.md
```

## Công nghệ sử dụng

### Backend
- **Laravel 11** - PHP Framework
- **Mysql** - Database
- **Laravel Sanctum** - API Authentication

### Frontend
- **React 18** - JavaScript Library
- **Material-UI** - UI Component Library
- **Axios** - HTTP Client
- **React Router** - Routing

## Cài đặt

### Yêu cầu
- PHP >= 8.2
- Composer
- Node.js >= 16
- npm hoặc yarn

### Backend Setup

1. Di chuyển vào thư mục backend:
```bash
cd backend
```

2. Cài đặt dependencies:
```bash
composer install
```

3. Tạo file .env:
```bash
cp .env.example .env
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Chạy migration và seeder:
```bash
php artisan migrate --seed
```

6. Khởi động server:
```bash
php artisan serve
```

Backend sẽ chạy tại: `http://localhost:8000`

### Frontend Setup

1. Di chuyển vào thư mục frontend:
```bash
cd frontend
```

2. Cài đặt dependencies:
```bash
npm install
```

3. Khởi động development server:
```bash
npm start
```

Frontend sẽ chạy tại: `http://localhost:3000`

## Tài khoản mặc định

Sau khi chạy seeder, bạn có thể đăng nhập với các tài khoản sau:

- **Admin**: 
  - Email: `admin@example.com`
  - Password: `password`


## API Documentation

API endpoints có sẵn tại `/api/*`. Xem file `backend/routes/api.php` để biết chi tiết.

## Phân quyền

Hệ thống có các vai trò sau:
- **Admin** - Toàn quyền quản lý hệ thống (giảng viên cũng có thể dùng)
- **Student** - Xem thông tin cá nhân

## License

This project is open-sourced software.

## Tác giả

Phát triển bởi minhkhoi84

