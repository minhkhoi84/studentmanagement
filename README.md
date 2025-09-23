# Student Management System

Hệ thống quản lý sinh viên được xây dựng bằng Laravel 11 với Boostrap.

## Tính năng

- ✅ **Quản lý sinh viên**: Thêm, sửa, xóa, xem danh sách sinh viên
- ✅ **Quản lý giáo viên**: Quản lý thông tin giáo viên
- ✅ **Quản lý khóa học**: Tạo và quản lý các khóa học
- ✅ **Xác thực người dùng**: Đăng ký, đăng nhập với phân quyền
- ✅ **Dashboard**: Trang tổng quan với thống kê
- ✅ **Responsive Design**: Giao diện thân thiện trên mọi thiết bị

## Công nghệ sử dụng

- **Backend**: Laravel 11
<<<<<<< HEAD
- **Frontend**: Blade Templates, Bootstrap 5
=======
- **Frontend**: Blade Templates, Bootstrap
>>>>>>> b1ff8c3bb7df1ed025eae5c26b2376b029872e81
- **Database**: SQLite
- **Authentication**: Laravel Breeze (với Tailwind CSS)
- **CSS Framework**: Bootstrap 5.0.2 (CDN)

## Cài đặt

### Yêu cầu hệ thống
- PHP >= 8.2
- Composer
- Node.js & npm

### Các bước cài đặt

1. **Clone repository**
   ```bash
   git clone https://github.com/minhkhoi84/studentmanagement.git
   cd studentmanagement
   ```

2. **Cài đặt dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Tạo file môi trường**
   ```bash
   cp .env.example .env
   ```

4. **Tạo application key**
   ```bash
   php artisan key:generate
   ```

5. **Chạy migrations**
   ```bash
   php artisan migrate
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Khởi chạy server**
   ```bash
   php artisan serve
   ```

Truy cập ứng dụng tại: `http://localhost:8000`

## Cấu trúc Database

### Bảng Users
- id, name, email, password, role (student/teacher/admin)

### Bảng Students
- id, user_id, student_id, full_name, date_of_birth, gender, phone, address, email, enrollment_date, status

### Bảng Teachers
- id, user_id, teacher_id, full_name, email, phone, department, hire_date, status

### Bảng Courses
- id, course_code, course_name, description, credits, teacher_id, semester, academic_year, status

## Sử dụng

### Đăng ký tài khoản
1. Truy cập `/register`
2. Nhập thông tin đăng ký
3. Chọn vai trò (Student/Teacher)

### Quản lý sinh viên
1. Đăng nhập với quyền admin hoặc teacher
2. Truy cập "Students" trong menu
3. Thêm, sửa, xóa thông tin sinh viên

### Quản lý khóa học
1. Đăng nhập với quyền teacher
2. Truy cập "Courses" 
3. Tạo và quản lý khóa học

## Screenshots

<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/e77f0201-3c6b-4f71-91c5-f9eb88ad037a" />


## Đóng góp

1. Fork project
2. Tạo feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Tạo Pull Request

## License

Dự án này được cấp phép theo [MIT License](https://opensource.org/licenses/MIT).

## Liên hệ

- **Developer**: [Phạm Minh Khôi]
- **Email**: [phamminhkhoi804]
- **GitHub**: [minhkhoi84]

---

⭐ Nếu project này hữu ích, hãy cho một star nhé!
