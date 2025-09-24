@extends('layout')
@section('content')
<div class="container-fluid">
    <h3 class="mt-3 mb-4">Chỉnh Sửa Điểm Danh</h3>
    <div class="card">
        <div class="card-body">
            <!-- Thông tin hiện tại -->
            <div class="alert alert-info">
                <i class="fas fa-info-circle"></i> 
                <strong>Đang sửa điểm danh:</strong> 
                {{ $attendance->student->name }} - {{ $attendance->course->name }} 
                ({{ $attendance->attendance_date->format('d/m/Y') }})
            </div>

            <form method="POST" action="{{ route('attendances.update', $attendance->id) }}">
                @csrf
                @method('PUT')
                
                <!-- Thông tin không đổi -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Sinh viên</label>
                        <input type="text" class="form-control" value="{{ $attendance->student->name }}" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Môn học</label>
                        <input type="text" class="form-control" value="{{ $attendance->course->name }}" readonly>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Ngày</label>
                        <input type="text" class="form-control" value="{{ $attendance->attendance_date->format('d/m/Y') }}" readonly>
                    </div>
                </div>

                <!-- Thông tin có thể sửa -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="present" {{ old('status', $attendance->status)=='present'?'selected':'' }}>✅ Có mặt</option>
                            <option value="absent" {{ old('status', $attendance->status)=='absent'?'selected':'' }}>❌ Vắng</option>
                            <option value="late" {{ old('status', $attendance->status)=='late'?'selected':'' }}>⏰ Muộn</option>
                            <option value="excused" {{ old('status', $attendance->status)=='excused'?'selected':'' }}>📝 Có phép</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Ghi chú về điểm danh...">{{ old('notes', $attendance->notes) }}</textarea>
                    </div>
                </div>

                <button class="btn btn-primary">
                    <i class="fas fa-save"></i> Cập nhật
                </button>
                <a href="{{ route('attendances.index') }}" class="btn btn-secondary ms-2">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
