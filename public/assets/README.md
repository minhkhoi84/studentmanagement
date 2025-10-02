# Assets Directory Structure

Thư mục này chứa các file tài nguyên tĩnh (CSS, JavaScript, hình ảnh) cho ứng dụng Student Management System.

## Cấu trúc thư mục

```
public/assets/
├── css/                    # File CSS
│   ├── layout.css         # CSS chính cho layout (header, sidebar, navigation)
│   ├── students.css       # CSS cho trang sinh viên
│   ├── home.css          # CSS cho trang chủ
│   └── README.md         # Hướng dẫn sử dụng CSS
├── images/               # Hình ảnh
│   ├── avatars/         # Ảnh đại diện
│   ├── logos/           # Logo
│   └── icons/           # Icon tùy chỉnh
└── js/                  # JavaScript tùy chỉnh
    ├── common.js        # JavaScript chung
    ├── students.js      # JavaScript cho sinh viên
    └── charts.js        # JavaScript cho biểu đồ
```

## File CSS

### layout.css
- **Mục đích**: CSS chính cho toàn bộ ứng dụng
- **Bao gồm**: Header, sidebar, navigation, responsive design
- **Sử dụng**: Được load trong `layout.blade.php`

### students.css
- **Mục đích**: CSS cho các trang liên quan đến sinh viên
- **Bao gồm**: Form styles, table styles, student cards
- **Sử dụng**: Được load trong `students/show.blade.php`

### home.css
- **Mục đích**: CSS cho trang chủ
- **Bao gồm**: Statistics cards, charts, welcome section
- **Sử dụng**: Được load trong `home.blade.php`

## Cách sử dụng

### 1. Thêm CSS mới
```php
@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/your-file.css') }}">
@endpush
```

### 2. Thêm JavaScript
```php
@push('js')
<script src="{{ asset('assets/js/your-file.js') }}"></script>
@endpush
```

### 3. Sử dụng hình ảnh
```php
<img src="{{ asset('assets/images/your-image.jpg') }}" alt="Description">
```

## Lợi ích

✅ **Tổ chức code gọn gàng**: CSS và JS được tách riêng khỏi Blade templates
✅ **Dễ bảo trì**: Mỗi file có trách nhiệm riêng biệt
✅ **Tái sử dụng**: CSS có thể được sử dụng ở nhiều trang
✅ **Performance**: Browser có thể cache các file CSS/JS
✅ **Teamwork**: Nhiều người có thể làm việc song song

## Quy tắc đặt tên

- **CSS files**: `kebab-case.css` (ví dụ: `student-profile.css`)
- **JS files**: `kebab-case.js` (ví dụ: `student-form.js`)
- **Images**: `kebab-case.ext` (ví dụ: `student-avatar.jpg`)

## Responsive Design

Tất cả CSS đều được thiết kế responsive với các breakpoint:
- **Desktop**: > 768px
- **Tablet**: 768px - 480px
- **Mobile**: < 480px

## Browser Support

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+










