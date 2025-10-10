@extends('layout')
@section('content')
<div class="container-fluid">
    <h3 class="mt-3 mb-4">Điểm Danh Sinh Viên</h3>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('attendances.store') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Sinh viên <span class="text-danger">*</span></label>
                        <select name="student_id" class="form-select" required>
                            <option value="">Chọn sinh viên</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                    {{ $student->name }} ({{ $student->student_code ?? 'SV' . str_pad($student->id, 3, '0', STR_PAD_LEFT) }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Môn học <span class="text-danger">*</span></label>
                        <select name="course_id" class="form-select" required>
                            <option value="">Chọn môn học</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                                    {{ $course->name }} ({{ $course->code }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ngày điểm danh <span class="text-danger">*</span></label>
                        <input type="date" name="attendance_date" class="form-control" value="{{ old('attendance_date', date('Y-m-d')) }}" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Trạng thái <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="present" {{ old('status','present')=='present'?'selected':'' }}>✅ Có mặt</option>
                            <option value="absent" {{ old('status')=='absent'?'selected':'' }}>❌ Vắng</option>
                            <option value="late" {{ old('status')=='late'?'selected':'' }}>⏰ Muộn</option>
                            <option value="excused" {{ old('status')=='excused'?'selected':'' }}>📝 Có phép</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Ghi chú</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Ghi chú về điểm danh...">{{ old('notes') }}</textarea>
                    </div>
                </div>
                <button class="btn btn-success">
                    <i class="fas fa-save"></i> Lưu điểm danh
                </button>
                <a href="{{ route('attendances.index') }}" class="btn btn-secondary ms-2">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
