@extends('layout')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user-plus"></i> Thêm Sinh Viên Mới
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Back Button -->
                    <div class="mb-3">
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Quay lại danh sách sinh viên
                        </a>
                    </div>

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong><i class="fas fa-exclamation-triangle"></i> Vui lòng sửa các lỗi sau:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Create Form -->
                    <form method="POST" action="{{ route('students.store') }}">
                        @csrf
                        
                        <!-- Basic Information Section -->
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-user"></i> Thông Tin Cơ Bản</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="name" class="form-label">
                                                <i class="fas fa-user"></i> Họ và tên <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" 
                                                   name="name" 
                                                   value="{{ old('name') }}" 
                                                   placeholder="Nhập họ và tên sinh viên"
                                                   required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="email" class="form-label">
                                                <i class="fas fa-envelope"></i> Địa chỉ email <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" 
                                                   class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" 
                                                   name="email" 
                                                   value="{{ old('email') }}" 
                                                   placeholder="Nhập địa chỉ email"
                                                   required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="student_code" class="form-label">
                                                <i class="fas fa-id-card"></i> Mã sinh viên <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('student_code') is-invalid @enderror" 
                                                   id="student_code" 
                                                   name="student_code" 
                                                   value="{{ old('student_code') }}" 
                                                   placeholder="Ví dụ: SV2025001"
                                                   required>
                                            @error('student_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="date_of_birth" class="form-label">
                                                <i class="fas fa-birthday-cake"></i> Ngày sinh
                                            </label>
                                            <input type="date" 
                                                   class="form-control @error('date_of_birth') is-invalid @enderror" 
                                                   id="date_of_birth" 
                                                   name="date_of_birth" 
                                                   value="{{ old('date_of_birth') }}">
                                            @error('date_of_birth')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="gender" class="form-label">
                                                <i class="fas fa-venus-mars"></i> Giới tính
                                            </label>
                                            <select class="form-control @error('gender') is-invalid @enderror" 
                                                    id="gender" 
                                                    name="gender">
                                                <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Nam</option>
                                                <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Nữ</option>
                                                <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Khác</option>
                                            </select>
                                            @error('gender')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="mobile" class="form-label">
                                                <i class="fas fa-mobile-alt"></i> Số điện thoại di động <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('mobile') is-invalid @enderror" 
                                                   id="mobile" 
                                                   name="mobile" 
                                                   value="{{ old('mobile') }}" 
                                                   placeholder="Nhập số điện thoại di động"
                                                   required>
                                            @error('mobile')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="phone" class="form-label">
                                                <i class="fas fa-phone"></i> Số điện thoại bàn
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('phone') is-invalid @enderror" 
                                                   id="phone" 
                                                   name="phone" 
                                                   value="{{ old('phone') }}" 
                                                   placeholder="Nhập số điện thoại bàn">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="nationality" class="form-label">
                                                <i class="fas fa-flag"></i> Quốc tịch
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('nationality') is-invalid @enderror" 
                                                   id="nationality" 
                                                   name="nationality" 
                                                   value="{{ old('nationality', 'Việt Nam') }}" 
                                                   placeholder="Nhập quốc tịch">
                                            @error('nationality')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-8">
                                        <div class="form-group mb-3">
                                            <label for="address" class="form-label">
                                                <i class="fas fa-map-marker-alt"></i> Địa chỉ <span class="text-danger">*</span>
                                            </label>
                                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                                      id="address" 
                                                      name="address" 
                                                      rows="3" 
                                                      placeholder="Nhập địa chỉ đầy đủ"
                                                      required>{{ old('address') }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="religion" class="form-label">
                                                <i class="fas fa-pray"></i> Tôn giáo
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('religion') is-invalid @enderror" 
                                                   id="religion" 
                                                   name="religion" 
                                                   value="{{ old('religion') }}" 
                                                   placeholder="Nhập tôn giáo">
                                            @error('religion')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Academic Information Section -->
                        <div class="card mb-4">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="fas fa-graduation-cap"></i> Thông Tin Học Tập</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="class" class="form-label">
                                                <i class="fas fa-users"></i> Lớp <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('class') is-invalid @enderror" 
                                                   id="class" 
                                                   name="class" 
                                                   value="{{ old('class') }}" 
                                                   placeholder="Ví dụ: CNTT01"
                                                   required>
                                            @error('class')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="major" class="form-label">
                                                <i class="fas fa-book"></i> Chuyên ngành
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('major') is-invalid @enderror" 
                                                   id="major" 
                                                   name="major" 
                                                   value="{{ old('major') }}" 
                                                   placeholder="Ví dụ: Công nghệ thông tin">
                                            @error('major')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="semester" class="form-label">
                                                <i class="fas fa-calendar"></i> Học kỳ hiện tại
                                            </label>
                                            <select class="form-control @error('semester') is-invalid @enderror" 
                                                    id="semester" 
                                                    name="semester">
                                                @for($i = 1; $i <= 8; $i++)
                                                    <option value="{{ $i }}" {{ old('semester', 1) == $i ? 'selected' : '' }}>
                                                        Học kỳ {{ $i }}
                                                    </option>
                                                @endfor
                                            </select>
                                            @error('semester')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="gpa" class="form-label">
                                                <i class="fas fa-chart-line"></i> Điểm trung bình
                                            </label>
                                            <input type="number" 
                                                   class="form-control @error('gpa') is-invalid @enderror" 
                                                   id="gpa" 
                                                   name="gpa" 
                                                   value="{{ old('gpa') }}" 
                                                   placeholder="0.00"
                                                   step="0.01"
                                                   min="0"
                                                   max="4.00">
                                            @error('gpa')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="status" class="form-label">
                                                <i class="fas fa-info-circle"></i> Trạng thái
                                            </label>
                                            <select class="form-control @error('status') is-invalid @enderror" 
                                                    id="status" 
                                                    name="status">
                                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Đang học</option>
                                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tạm nghỉ</option>
                                                <option value="graduated" {{ old('status') == 'graduated' ? 'selected' : '' }}>Đã tốt nghiệp</option>
                                                <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>Bị đình chỉ</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="enrollment_date" class="form-label">
                                                <i class="fas fa-calendar-plus"></i> Ngày nhập học
                                            </label>
                                            <input type="date" 
                                                   class="form-control @error('enrollment_date') is-invalid @enderror" 
                                                   id="enrollment_date" 
                                                   name="enrollment_date" 
                                                   value="{{ old('enrollment_date') }}">
                                            @error('enrollment_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="previous_school" class="form-label">
                                                <i class="fas fa-school"></i> Trường học trước đó
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('previous_school') is-invalid @enderror" 
                                                   id="previous_school" 
                                                   name="previous_school" 
                                                   value="{{ old('previous_school') }}" 
                                                   placeholder="Nhập tên trường học trước đó">
                                            @error('previous_school')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Family Information Section -->
                        <div class="card mb-4">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0"><i class="fas fa-home"></i> Thông Tin Gia Đình</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-primary"><i class="fas fa-male"></i> Thông tin cha</h6>
                                        <div class="form-group mb-3">
                                            <label for="father_name" class="form-label">Họ tên cha</label>
                                            <input type="text" 
                                                   class="form-control @error('father_name') is-invalid @enderror" 
                                                   id="father_name" 
                                                   name="father_name" 
                                                   value="{{ old('father_name') }}" 
                                                   placeholder="Nhập họ tên cha">
                                            @error('father_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="father_phone" class="form-label">Số điện thoại cha</label>
                                            <input type="text" 
                                                   class="form-control @error('father_phone') is-invalid @enderror" 
                                                   id="father_phone" 
                                                   name="father_phone" 
                                                   value="{{ old('father_phone') }}" 
                                                   placeholder="Nhập số điện thoại cha">
                                            @error('father_phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="father_occupation" class="form-label">Nghề nghiệp cha</label>
                                            <input type="text" 
                                                   class="form-control @error('father_occupation') is-invalid @enderror" 
                                                   id="father_occupation" 
                                                   name="father_occupation" 
                                                   value="{{ old('father_occupation') }}" 
                                                   placeholder="Nhập nghề nghiệp cha">
                                            @error('father_occupation')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h6 class="text-pink"><i class="fas fa-female"></i> Thông tin mẹ</h6>
                                        <div class="form-group mb-3">
                                            <label for="mother_name" class="form-label">Họ tên mẹ</label>
                                            <input type="text" 
                                                   class="form-control @error('mother_name') is-invalid @enderror" 
                                                   id="mother_name" 
                                                   name="mother_name" 
                                                   value="{{ old('mother_name') }}" 
                                                   placeholder="Nhập họ tên mẹ">
                                            @error('mother_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="mother_phone" class="form-label">Số điện thoại mẹ</label>
                                            <input type="text" 
                                                   class="form-control @error('mother_phone') is-invalid @enderror" 
                                                   id="mother_phone" 
                                                   name="mother_phone" 
                                                   value="{{ old('mother_phone') }}" 
                                                   placeholder="Nhập số điện thoại mẹ">
                                            @error('mother_phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="mother_occupation" class="form-label">Nghề nghiệp mẹ</label>
                                            <input type="text" 
                                                   class="form-control @error('mother_occupation') is-invalid @enderror" 
                                                   id="mother_occupation" 
                                                   name="mother_occupation" 
                                                   value="{{ old('mother_occupation') }}" 
                                                   placeholder="Nhập nghề nghiệp mẹ">
                                            @error('mother_occupation')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Information Section -->
                        <div class="card mb-4">
                            <div class="card-header bg-secondary text-white">
                                <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Thông Tin Bổ Sung</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="emergency_contact" class="form-label">
                                                <i class="fas fa-exclamation-triangle"></i> Liên hệ khẩn cấp
                                            </label>
                                            <textarea class="form-control @error('emergency_contact') is-invalid @enderror" 
                                                      id="emergency_contact" 
                                                      name="emergency_contact" 
                                                      rows="3" 
                                                      placeholder="Nhập thông tin liên hệ khẩn cấp">{{ old('emergency_contact') }}</textarea>
                                            @error('emergency_contact')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="medical_conditions" class="form-label">
                                                <i class="fas fa-heartbeat"></i> Tình trạng sức khỏe
                                            </label>
                                            <textarea class="form-control @error('medical_conditions') is-invalid @enderror" 
                                                      id="medical_conditions" 
                                                      name="medical_conditions" 
                                                      rows="3" 
                                                      placeholder="Nhập tình trạng sức khỏe hoặc dị ứng">{{ old('medical_conditions') }}</textarea>
                                            @error('medical_conditions')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <label for="notes" class="form-label">
                                                <i class="fas fa-sticky-note"></i> Ghi chú thêm
                                            </label>
                                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                                      id="notes" 
                                                      name="notes" 
                                                      rows="4" 
                                                      placeholder="Nhập ghi chú thêm về sinh viên">{{ old('notes') }}</textarea>
                                            @error('notes')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row">
                            <div class="col-md-12">
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <button type="submit" class="btn btn-success btn-lg">
                                            <i class="fas fa-save"></i> Lưu sinh viên
                                        </button>
                                        <button type="reset" class="btn btn-warning btn-lg ms-2">
                                            <i class="fas fa-undo"></i> Đặt lại form
                                        </button>
                                    </div>
                                    <div>
                                        <a href="{{ route('students.index') }}" class="btn btn-secondary btn-lg">
                                            <i class="fas fa-times"></i> Hủy
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    .card {
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .form-label {
        font-weight: 600;
        color: #495057;
    }
    
    .form-control:focus {
        border-color: #28a745;
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }
    
    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1.1rem;
    }
    
    .text-danger {
        font-weight: bold;
    }
</style>
@endpush

@push('js')
<script>
    // Form validation
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const nameInput = document.getElementById('name');
        const mobileInput = document.getElementById('mobile');
        
        // Name validation
        nameInput.addEventListener('input', function() {
            if (this.value.length < 2) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
        
        // Mobile validation (basic)
        mobileInput.addEventListener('input', function() {
            const phoneRegex = /^[0-9+\-\s()]+$/;
            if (!phoneRegex.test(this.value) || this.value.length < 10) {
                this.classList.add('is-invalid');
            } else {
                this.classList.remove('is-invalid');
            }
        });
        
        // Form submit confirmation
        form.addEventListener('submit', function(e) {
            if (!confirm('Bạn có chắc chắn muốn thêm sinh viên này?')) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush



