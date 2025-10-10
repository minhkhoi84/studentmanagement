@extends('layout')
@section('content')
<div class="container-fluid">
    <h3 class="mt-3 mb-4">Nhập Điểm</h3>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('grades.store') }}">
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
                        <label class="form-label">Điểm Giữa Kỳ (0-10)</label>
                        <input type="number" name="midterm_score" class="form-control" value="{{ old('midterm_score') }}" step="0.1" min="0" max="10" placeholder="8.5">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Điểm Cuối Kỳ (0-10)</label>
                        <input type="number" name="final_score" class="form-control" value="{{ old('final_score') }}" step="0.1" min="0" max="10" placeholder="7.0">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label class="form-label">Ghi Chú</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Ghi chú về điểm số...">{{ old('notes') }}</textarea>
                    </div>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> 
                    <strong>Công thức tính:</strong> Điểm TB = (Điểm GK × 40%) + (Điểm CK × 60%)
                    <br><strong>Xếp loại:</strong> A ≥ 8.5, B ≥ 7.0, C ≥ 5.5, D ≥ 4.0, F < 4.0
                </div>
                <button class="btn btn-success">
                    <i class="fas fa-save"></i> Lưu điểm
                </button>
                <a href="{{ route('grades.index') }}" class="btn btn-secondary ms-2">
                    <i class="fas fa-arrow-left"></i> Quay lại
                </a>
            </form>
        </div>
    </div>
</div>
@endsection
