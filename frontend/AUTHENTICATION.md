# Hướng dẫn Authentication

## Tổng quan
Hệ thống đã được tích hợp đầy đủ chức năng đăng nhập và đăng ký với các tính năng sau:

## Các tính năng đã thêm

### 1. AuthContext (`src/contexts/AuthContext.js`)
- Quản lý trạng thái đăng nhập toàn cục
- Lưu trữ token và thông tin user trong localStorage
- Cung cấp các hàm: `login`, `register`, `logout`, `isAuthenticated`

### 2. Login Component (`src/components/Login.js`)
- Form đăng nhập với email và password
- Hiển thị thông báo lỗi và thành công
- Tự động chuyển hướng sau khi đăng nhập thành công

### 3. Register Component (`src/components/Register.js`)
- Form đăng ký với các trường: name, email, password, password_confirmation, role
- Validation mật khẩu khớp
- Chuyển hướng về trang đăng nhập sau khi đăng ký thành công

### 4. ProtectedRoute Component (`src/components/ProtectedRoute.js`)
- Bảo vệ các route cần đăng nhập
- Tự động chuyển hướng về `/login` nếu chưa đăng nhập
- Hiển thị loading spinner khi đang kiểm tra authentication

### 5. Cập nhật App.js
- Thêm routing cho `/login` và `/register`
- Bảo vệ tất cả các route quản lý
- Hiển thị thông tin user và nút đăng xuất trên header
- Sử dụng URL routing thay vì state

## Cách sử dụng

### Đăng ký tài khoản mới
1. Truy cập `/register`
2. Điền đầy đủ thông tin
3. Chọn vai trò (user, admin, teacher)
4. Nhấn "Đăng ký"

### Đăng nhập
1. Truy cập `/login`
2. Nhập email và password
3. Nhấn "Đăng nhập"

### Đăng xuất
- Nhấn nút "Đăng xuất" trên header

## API Endpoints cần thiết

Backend cần cung cấp các API sau:

### POST `/api/login`
```json
{
  "email": "user@example.com",
  "password": "password"
}
```

Response:
```json
{
  "token": "jwt_token_here",
  "user": {
    "id": 1,
    "name": "User Name",
    "email": "user@example.com",
    "role": "user"
  }
}
```

### POST `/api/register`
```json
{
  "name": "User Name",
  "email": "user@example.com",
  "password": "password",
  "password_confirmation": "password",
  "role": "user"
}
```

Response:
```json
{
  "message": "User registered successfully",
  "user": {
    "id": 1,
    "name": "User Name",
    "email": "user@example.com",
    "role": "user"
  }
}
```

## Bảo mật

- Token được lưu trong localStorage
- Tất cả các route quản lý đều được bảo vệ
- Tự động đăng xuất khi token hết hạn (cần implement thêm)
- Validation phía client cho form đăng ký

## Lưu ý

- Cần đảm bảo backend đã implement các API authentication
- CORS phải được cấu hình đúng để frontend có thể gọi API
- Có thể cần thêm refresh token mechanism cho production
