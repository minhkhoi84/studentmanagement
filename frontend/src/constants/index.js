// API Configuration
export const API_CONFIG = {
  BASE_URL: 'http://127.0.0.1:8000/api',
  TIMEOUT: 10000,
};

// Status Options
export const STATUS_OPTIONS = [
  { value: 'active', label: 'Hoạt động' },
  { value: 'inactive', label: 'Không hoạt động' },
  { value: 'graduated', label: 'Đã tốt nghiệp' },
];

// Role Options
export const ROLE_OPTIONS = [
  { value: 'super_admin', label: 'Quản trị viên' },
  { value: 'user', label: 'Người dùng' },
];

// Attendance Status Options
export const ATTENDANCE_STATUS_OPTIONS = [
  { value: 'present', label: 'Có mặt' },
  { value: 'absent', label: 'Vắng mặt' },
  { value: 'late', label: 'Đi muộn' },
  { value: 'excused', label: 'Có phép' },
];

// Grade Status Options
export const GRADE_STATUS_OPTIONS = [
  { value: 'pass', label: 'Đạt' },
  { value: 'fail', label: 'Không đạt' },
  { value: 'pending', label: 'Chờ xử lý' },
];

// Pagination Options
export const PAGINATION_OPTIONS = [
  { value: 10, label: '10' },
  { value: 15, label: '15' },
  { value: 25, label: '25' },
  { value: 50, label: '50' },
];

// Table Columns Configuration
export const TABLE_COLUMNS = {
  STUDENTS: [
    { field: 'id', label: '#', align: 'center' },
    { field: 'name', label: 'Họ tên' },
    { field: 'student_code', label: 'Mã sinh viên' },
    { field: 'class', label: 'Lớp học' },
    { field: 'email', label: 'Email' },
    { field: 'phone', label: 'Số điện thoại' },
    { field: 'status', label: 'Trạng thái' },
    { field: 'created_at', label: 'Ngày tạo' },
    { field: 'actions', label: 'Thao tác', align: 'center' },
  ],
  TEACHERS: [
    { field: 'id', label: '#', align: 'center' },
    { field: 'name', label: 'Họ tên' },
    { field: 'teacher_code', label: 'Mã giảng viên' },
    { field: 'email', label: 'Email' },
    { field: 'phone', label: 'Số điện thoại' },
    { field: 'status', label: 'Trạng thái' },
    { field: 'created_at', label: 'Ngày tạo' },
    { field: 'actions', label: 'Thao tác', align: 'center' },
  ],
  COURSES: [
    { field: 'id', label: '#', align: 'center' },
    { field: 'name', label: 'Tên môn học' },
    { field: 'code', label: 'Mã môn' },
    { field: 'credits', label: 'Tín chỉ' },
    { field: 'teacher_id', label: 'Giảng viên' },
    { field: 'status', label: 'Trạng thái' },
    { field: 'created_at', label: 'Ngày tạo' },
    { field: 'actions', label: 'Thao tác', align: 'center' },
  ],
};

// Form Validation Rules
export const VALIDATION_RULES = {
  REQUIRED: 'Trường này là bắt buộc',
  EMAIL: 'Email không hợp lệ',
  MIN_LENGTH: (min) => `Tối thiểu ${min} ký tự`,
  MAX_LENGTH: (max) => `Tối đa ${max} ký tự`,
  UNIQUE: 'Giá trị này đã tồn tại',
};

// Notification Messages
export const NOTIFICATION_MESSAGES = {
  SUCCESS: {
    CREATE: 'Tạo mới thành công',
    UPDATE: 'Cập nhật thành công',
    DELETE: 'Xóa thành công',
  },
  ERROR: {
    CREATE: 'Có lỗi xảy ra khi tạo mới',
    UPDATE: 'Có lỗi xảy ra khi cập nhật',
    DELETE: 'Có lỗi xảy ra khi xóa',
    FETCH: 'Có lỗi xảy ra khi tải dữ liệu',
  },
};




