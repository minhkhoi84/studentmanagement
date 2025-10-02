# 🎓 Student Management System

[![Laravel](https://img.shields.io/badge/Laravel-11.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC.svg)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

Hệ thống quản lý sinh viên toàn diện được xây dựng bằng Laravel 11 với giao diện hiện đại sử dụng Tailwind CSS. Hệ thống cung cấp đầy đủ các tính năng quản lý từ sinh viên, giáo viên, khóa học đến điểm danh và chấm điểm.

## ✨ Tính năng chính

### 👥 Quản lý người dùng
- **Quản lý sinh viên**: Thêm, sửa, xóa, tìm kiếm thông tin sinh viên
- **Quản lý giáo viên**: Quản lý thông tin giáo viên và phân công giảng dạy
- **Hệ thống phân quyền**: Phân quyền chi tiết theo vai trò (Admin, Teacher, Student)
- **Xác thực bảo mật**: Đăng ký, đăng nhập với Laravel Breeze

### 📚 Quản lý học tập
- **Quản lý khóa học**: Tạo và quản lý các môn học, chương trình đào tạo
- **Quản lý lớp học**: Tổ chức lớp học theo khoa, chuyên ngành
- **Quản lý khoa**: Tổ chức cấu trúc khoa, phòng ban
- **Chấm điểm**: Quản lý điểm số, GPA, kết quả học tập
- **Điểm danh**: Theo dõi tình hình tham gia lớp học

### 📊 Dashboard & Báo cáo
- **Dashboard tổng quan**: Thống kê, biểu đồ trực quan
- **Báo cáo chi tiết**: Xuất báo cáo theo nhiều tiêu chí
- **Tìm kiếm nâng cao**: Lọc và tìm kiếm thông tin linh hoạt

### 🎨 Giao diện người dùng
- **Responsive Design**: Tương thích mọi thiết bị
- **UI/UX hiện đại**: Giao diện thân thiện, dễ sử dụng
- **Dark/Light Mode**: Hỗ trợ chế độ sáng/tối

## 🛠️ Công nghệ sử dụng

### Backend
- **Framework**: Laravel 11.x
- **Database**: MySQL
- **Authentication**: Laravel Breeze
- **PHP Version**: 8.2+

### Frontend
- **CSS Framework**: Tailwind CSS 3.x
- **JavaScript**: Alpine.js
- **Build Tool**: Vite
- **Charts**: Chart.js

### Development Tools
- **Package Manager**: Composer, npm
- **Testing**: PHPUnit
- **Code Quality**: Laravel Pint
- **Architecture**: Clean Code, SOLID Principles

## 🏗️ Kiến trúc & Chất lượng Code

### 🎯 **Clean Code Architecture**
Dự án được xây dựng theo các nguyên tắc Clean Code và SOLID:

#### **📁 Cấu trúc thư mục**
```
app/
├── Http/
│   ├── Controllers/     # Controllers với BaseController
│   ├── Requests/        # Form Request validation
│   └── Middleware/      # Custom middleware
├── Models/              # Eloquent models với relationships
├── Services/            # Business logic layer
└── Helpers/             # Utility classes
```

#### **🔧 Design Patterns**
- **Service Layer Pattern**: Tách business logic ra khỏi Controllers
- **Form Request Pattern**: Validation logic riêng biệt
- **Repository Pattern**: (Có thể mở rộng)
- **Dependency Injection**: Constructor injection cho Services

#### **✨ Code Quality Features**
- **Type Hints**: Đầy đủ return types và parameter types
- **Constants**: Sử dụng constants thay vì magic strings
- **Method Chaining**: Query builder với arrow functions
- **Error Handling**: Consistent error messages
- **Validation**: Custom validation rules với messages tiếng Việt

#### **📊 Code Metrics**
- **Controllers**: Gọn gàng, chỉ xử lý HTTP requests
- **Services**: Chứa business logic, dễ unit test
- **Models**: Rich models với relationships và scopes
- **Routes**: Organized với prefix và name groups

### 🧪 **Testing Ready**
- Service layer dễ dàng unit test
- Form Requests có thể test validation
- Models có thể test relationships
- Controllers có thể test với HTTP tests

## 📋 Yêu cầu hệ thống

- PHP >= 8.2
- Composer
- Node.js >= 16.x
- npm hoặc yarn
- SQLite (hoặc MySQL/PostgreSQL)

## 🚀 Cài đặt và chạy dự án

### 1. Clone repository
```bash
git clone https://github.com/minhkhoi84/studentmanagement.git
cd studentmanagement/studentmanagement-app
```

### 2. Cài đặt dependencies
```bash
# Cài đặt PHP dependencies
composer install

# Cài đặt Node.js dependencies
npm install
```

### 3. Cấu hình môi trường
```bash
# Copy file cấu hình
cp .env.example .env

# Tạo application key
php artisan key:generate

# Tạo database MySQL
# Tạo database 'dbstudentms' trong phpMyAdmin hoặc MySQL command line
```

### 4. Chạy migrations và seeders
```bash
# Chạy migrations
php artisan migrate

# Chạy seeders (tạo dữ liệu mẫu)
php artisan db:seed
```

### 5. Build assets
```bash
# Build cho production
npm run build

# Hoặc chạy development server
npm run dev
```

### 6. Khởi chạy server
```bash
php artisan serve
```

Truy cập ứng dụng tại: **http://localhost:8000**

## 🗄️ Cấu trúc Database

### Bảng chính

#### Users
- `id`, `name`, `email`, `password`, `role`, `email_verified_at`

#### Students
- **Thông tin cơ bản**: `name`, `email`, `student_code`, `class`, `date_of_birth`, `gender`
- **Liên hệ**: `mobile`, `phone`, `address`, `emergency_contact`
- **Học vấn**: `major`, `semester`, `gpa`, `status`
- **Cá nhân**: `nationality`, `religion`, `medical_conditions`
- **Gia đình**: `father_name`, `father_phone`, `mother_name`, `mother_phone`
- **Lịch sử**: `enrollment_date`, `previous_school`, `notes`

#### Teachers
- `name`, `email`, `teacher_code`, `phone`, `department`, `qualification`, `status`

#### Courses
- `name`, `code`, `description`, `credits`, `teacher_id`, `status`

#### Departments
- `name`, `description`, `head_teacher`, `status`

#### Classes
- `name`, `department_id`, `teacher_id`, `academic_year`, `status`

#### Grades
- `student_id`, `course_id`, `midterm_score`, `final_score`, `total_score`, `grade`, `status`

#### Attendances
- `student_id`, `course_id`, `attendance_date`, `status`, `notes`

#### Permissions
- `name`, `description`, `guard_name`

## 👤 Tài khoản mặc định

Sau khi chạy seeders, bạn có thể đăng nhập với:

- **Admin**: `admin@example.com` / `password`
- **Teacher**: `teacher@example.com` / `password`
- **Student**: `student@example.com` / `password`

## 🔐 Hệ thống phân quyền

### Quyền Admin
- Quản lý toàn bộ hệ thống
- Quản lý người dùng và phân quyền
- Xem tất cả báo cáo và thống kê

### Quyền Teacher
- Quản lý sinh viên trong lớp
- Chấm điểm và điểm danh
- Quản lý khóa học được phân công

### Quyền Student
- Xem thông tin cá nhân
- Xem điểm số và lịch học
- Cập nhật thông tin liên hệ

## 📱 Sử dụng hệ thống

### Đăng ký tài khoản mới
1. Truy cập `/register`
2. Nhập thông tin đăng ký
3. Chọn vai trò phù hợp
4. Xác thực email (nếu được cấu hình)

### Quản lý sinh viên
1. Đăng nhập với quyền Admin/Teacher
2. Truy cập menu "Students"
3. Sử dụng tính năng tìm kiếm và lọc
4. Thêm/sửa/xóa thông tin sinh viên

### Quản lý khóa học
1. Đăng nhập với quyền Teacher
2. Truy cập menu "Courses"
3. Tạo môn học mới hoặc chỉnh sửa
4. Phân công giáo viên giảng dạy

### Chấm điểm và điểm danh
1. Truy cập menu "Grades" hoặc "Attendances"
2. Chọn sinh viên và môn học
3. Nhập điểm số hoặc trạng thái điểm danh
4. Lưu thông tin

<img width="1920" height="1080" alt="image" src="https://github.com/user-attachments/assets/ab019f4d-cc12-4fc3-b9b6-7774284ae2e7" />


=======
## 🧪 Testing

```bash
# Chạy tất cả tests
php artisan test
>>>>>>> Stashed changes

# Chạy tests với coverage
php artisan test --coverage

# Chạy specific test
php artisan test --filter=StudentTest
```

## 🔧 Development Workflow

### **Code Standards**
- **PSR-12**: Tuân thủ PHP coding standards
- **Laravel Conventions**: Follow Laravel best practices
- **Type Hints**: Sử dụng đầy đủ type declarations
- **Documentation**: PHPDoc cho tất cả public methods

### **Code Organization**
```php
// ✅ Good: Service Layer Pattern
class StudentController extends BaseController
{
    public function __construct(private StudentService $studentService) {}
    
    public function store(StoreStudentRequest $request): RedirectResponse
    {
        $this->studentService->createStudent($request->validated());
        return $this->redirectWithSuccess('students.index', 'Tạo thành công');
    }
}

// ✅ Good: Form Request Validation
class StoreStudentRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:students,email',
            // ... more rules
        ];
    }
}
```

### **Best Practices**
- **Single Responsibility**: Mỗi class có một nhiệm vụ rõ ràng
- **Dependency Injection**: Sử dụng constructor injection
- **Error Handling**: Consistent error messages
- **Validation**: Tách validation logic ra Form Requests
- **Business Logic**: Đặt trong Service layer

## 📦 Deployment

### Production Build
```bash
# Build assets cho production
npm run build

# Optimize Laravel
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Environment Variables
Đảm bảo cấu hình các biến môi trường sau trong file `.env`:

```env
APP_NAME="Student Management System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dbstudentms
DB_USERNAME=root
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
```

## 🤝 Đóng góp

Chúng tôi hoan nghênh mọi đóng góp! Vui lòng làm theo các bước sau:

1. **Fork** dự án
2. Tạo **feature branch** (`git checkout -b feature/AmazingFeature`)
3. **Commit** thay đổi (`git commit -m 'Add some AmazingFeature'`)
4. **Push** lên branch (`git push origin feature/AmazingFeature`)
5. Tạo **Pull Request**

### Coding Standards
- Tuân thủ PSR-12 coding standards
- Viết tests cho các tính năng mới
- Cập nhật documentation khi cần thiết

## 📄 License

Dự án này được cấp phép theo [MIT License](https://opensource.org/licenses/MIT).

## 📞 Liên hệ

- **Developer**: Phạm Minh Khôi
- **Email**: phamminhkhoi804@gmail.com
- **GitHub**: [@minhkhoi84](https://github.com/minhkhoi84)

## 🙏 Lời cảm ơn

- [Laravel](https://laravel.com) - PHP Framework tuyệt vời
- [Tailwind CSS](https://tailwindcss.com) - Utility-first CSS framework
- [Alpine.js](https://alpinejs.dev) - Lightweight JavaScript framework
- [Chart.js](https://chartjs.org) - Beautiful charts

---

⭐ **Nếu dự án này hữu ích, hãy cho một star nhé!**

## 📈 Roadmap

### Version 2.0 (Planned)
- [ ] API RESTful cho mobile app
- [ ] Real-time notifications
- [ ] Advanced reporting system
- [ ] Multi-language support
- [ ] Integration with external systems

### Version 1.1 (In Progress)
- [ ] Email notifications
- [ ] Export to Excel/PDF
- [ ] Advanced search filters
- [ ] Bulk operations

### Version 1.0.1 (Completed ✅)
- [x] **Clean Code Architecture**: Service layer, Form Requests, Base Controller
- [x] **Code Quality**: Type hints, constants, error handling
- [x] **Route Optimization**: Prefix groups, organized structure
- [x] **Model Improvements**: Relationships, scopes, constants
- [x] **Documentation**: Comprehensive README with architecture details
