@extends('layout')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user-edit"></i> Edit Student Information
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Back Button -->
                    <div class="mb-3">
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Students List
                        </a>
                        <a href="{{ route('students.show', $student->id) }}" class="btn btn-info ms-2">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong><i class="fas fa-exclamation-triangle"></i> Please fix the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Student Info Card -->
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        <strong>Editing:</strong> {{ $student->name }} (ID: #{{ $student->id }})
                    </div>

                    <!-- Edit Form -->
                    <form method="POST" action="{{ route('students.update', $student->id) }}">
                        @csrf
                        @method('PUT')
                        
                        <!-- Basic Information Section -->
                        <div class="card mb-4">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="fas fa-user"></i> Basic Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="name" class="form-label">
                                                <i class="fas fa-user"></i> Full Name <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('name') is-invalid @enderror" 
                                                   id="name" 
                                                   name="name" 
                                                   value="{{ old('name', $student->name) }}" 
                                                   placeholder="Enter student full name"
                                                   required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="email" class="form-label">
                                                <i class="fas fa-envelope"></i> Email Address <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" 
                                                   class="form-control @error('email') is-invalid @enderror" 
                                                   id="email" 
                                                   name="email" 
                                                   value="{{ old('email', $student->email) }}" 
                                                   placeholder="Enter email address"
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
                                                <i class="fas fa-id-card"></i> Student Code <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('student_code') is-invalid @enderror" 
                                                   id="student_code" 
                                                   name="student_code" 
                                                   value="{{ old('student_code', $student->student_code) }}" 
                                                   placeholder="e.g. SV2025001"
                                                   required>
                                            @error('student_code')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="date_of_birth" class="form-label">
                                                <i class="fas fa-birthday-cake"></i> Date of Birth
                                            </label>
                                            <input type="date" 
                                                   class="form-control @error('date_of_birth') is-invalid @enderror" 
                                                   id="date_of_birth" 
                                                   name="date_of_birth" 
                                                   value="{{ old('date_of_birth', $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '') }}">
                                            @error('date_of_birth')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="gender" class="form-label">
                                                <i class="fas fa-venus-mars"></i> Gender
                                            </label>
                                            <select class="form-control @error('gender') is-invalid @enderror" 
                                                    id="gender" 
                                                    name="gender">
                                                <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                                                <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                                                <option value="other" {{ old('gender', $student->gender) == 'other' ? 'selected' : '' }}>Khác</option>
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
                                                <i class="fas fa-mobile-alt"></i> Mobile Number <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('mobile') is-invalid @enderror" 
                                                   id="mobile" 
                                                   name="mobile" 
                                                   value="{{ old('mobile', $student->mobile) }}" 
                                                   placeholder="Enter mobile number"
                                                   required>
                                            @error('mobile')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="phone" class="form-label">
                                                <i class="fas fa-phone"></i> Phone Number
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('phone') is-invalid @enderror" 
                                                   id="phone" 
                                                   name="phone" 
                                                   value="{{ old('phone', $student->phone) }}" 
                                                   placeholder="Enter phone number">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="nationality" class="form-label">
                                                <i class="fas fa-flag"></i> Nationality
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('nationality') is-invalid @enderror" 
                                                   id="nationality" 
                                                   name="nationality" 
                                                   value="{{ old('nationality', $student->nationality ?? 'Vietnamese') }}" 
                                                   placeholder="Enter nationality">
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
                                                <i class="fas fa-map-marker-alt"></i> Address <span class="text-danger">*</span>
                                            </label>
                                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                                      id="address" 
                                                      name="address" 
                                                      rows="3" 
                                                      placeholder="Enter complete address"
                                                      required>{{ old('address', $student->address) }}</textarea>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="religion" class="form-label">
                                                <i class="fas fa-pray"></i> Religion
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('religion') is-invalid @enderror" 
                                                   id="religion" 
                                                   name="religion" 
                                                   value="{{ old('religion', $student->religion) }}" 
                                                   placeholder="Enter religion">
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
                                <h5 class="mb-0"><i class="fas fa-graduation-cap"></i> Academic Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="class" class="form-label">
                                                <i class="fas fa-users"></i> Class <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('class') is-invalid @enderror" 
                                                   id="class" 
                                                   name="class" 
                                                   value="{{ old('class', $student->class) }}" 
                                                   placeholder="e.g. CNTT01"
                                                   required>
                                            @error('class')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="major" class="form-label">
                                                <i class="fas fa-book"></i> Major
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('major') is-invalid @enderror" 
                                                   id="major" 
                                                   name="major" 
                                                   value="{{ old('major', $student->major) }}" 
                                                   placeholder="e.g. Computer Science">
                                            @error('major')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="semester" class="form-label">
                                                <i class="fas fa-calendar"></i> Current Semester
                                            </label>
                                            <select class="form-control @error('semester') is-invalid @enderror" 
                                                    id="semester" 
                                                    name="semester">
                                                @for($i = 1; $i <= 8; $i++)
                                                    <option value="{{ $i }}" {{ old('semester', $student->semester ?? 1) == $i ? 'selected' : '' }}>
                                                        Semester {{ $i }}
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
                                                <i class="fas fa-chart-line"></i> GPA
                                            </label>
                                            <input type="number" 
                                                   class="form-control @error('gpa') is-invalid @enderror" 
                                                   id="gpa" 
                                                   name="gpa" 
                                                   value="{{ old('gpa', $student->gpa) }}" 
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
                                                <i class="fas fa-info-circle"></i> Status
                                            </label>
                                            <select class="form-control @error('status') is-invalid @enderror" 
                                                    id="status" 
                                                    name="status">
                                                <option value="active" {{ old('status', $student->status ?? 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="inactive" {{ old('status', $student->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                <option value="graduated" {{ old('status', $student->status) == 'graduated' ? 'selected' : '' }}>Graduated</option>
                                                <option value="suspended" {{ old('status', $student->status) == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="enrollment_date" class="form-label">
                                                <i class="fas fa-calendar-plus"></i> Enrollment Date
                                            </label>
                                            <input type="date" 
                                                   class="form-control @error('enrollment_date') is-invalid @enderror" 
                                                   id="enrollment_date" 
                                                   name="enrollment_date" 
                                                   value="{{ old('enrollment_date', $student->enrollment_date ? $student->enrollment_date->format('Y-m-d') : '') }}">
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
                                                <i class="fas fa-school"></i> Previous School
                                            </label>
                                            <input type="text" 
                                                   class="form-control @error('previous_school') is-invalid @enderror" 
                                                   id="previous_school" 
                                                   name="previous_school" 
                                                   value="{{ old('previous_school', $student->previous_school) }}" 
                                                   placeholder="Enter previous school name">
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
                                <h5 class="mb-0"><i class="fas fa-home"></i> Family Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="text-primary"><i class="fas fa-male"></i> Father's Information</h6>
                                        <div class="form-group mb-3">
                                            <label for="father_name" class="form-label">Father's Name</label>
                                            <input type="text" 
                                                   class="form-control @error('father_name') is-invalid @enderror" 
                                                   id="father_name" 
                                                   name="father_name" 
                                                   value="{{ old('father_name', $student->father_name) }}" 
                                                   placeholder="Enter father's name">
                                            @error('father_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="father_phone" class="form-label">Father's Phone</label>
                                            <input type="text" 
                                                   class="form-control @error('father_phone') is-invalid @enderror" 
                                                   id="father_phone" 
                                                   name="father_phone" 
                                                   value="{{ old('father_phone', $student->father_phone) }}" 
                                                   placeholder="Enter father's phone">
                                            @error('father_phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="father_occupation" class="form-label">Father's Occupation</label>
                                            <input type="text" 
                                                   class="form-control @error('father_occupation') is-invalid @enderror" 
                                                   id="father_occupation" 
                                                   name="father_occupation" 
                                                   value="{{ old('father_occupation', $student->father_occupation) }}" 
                                                   placeholder="Enter father's occupation">
                                            @error('father_occupation')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <h6 class="text-pink"><i class="fas fa-female"></i> Mother's Information</h6>
                                        <div class="form-group mb-3">
                                            <label for="mother_name" class="form-label">Mother's Name</label>
                                            <input type="text" 
                                                   class="form-control @error('mother_name') is-invalid @enderror" 
                                                   id="mother_name" 
                                                   name="mother_name" 
                                                   value="{{ old('mother_name', $student->mother_name) }}" 
                                                   placeholder="Enter mother's name">
                                            @error('mother_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="mother_phone" class="form-label">Mother's Phone</label>
                                            <input type="text" 
                                                   class="form-control @error('mother_phone') is-invalid @enderror" 
                                                   id="mother_phone" 
                                                   name="mother_phone" 
                                                   value="{{ old('mother_phone', $student->mother_phone) }}" 
                                                   placeholder="Enter mother's phone">
                                            @error('mother_phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="form-group mb-3">
                                            <label for="mother_occupation" class="form-label">Mother's Occupation</label>
                                            <input type="text" 
                                                   class="form-control @error('mother_occupation') is-invalid @enderror" 
                                                   id="mother_occupation" 
                                                   name="mother_occupation" 
                                                   value="{{ old('mother_occupation', $student->mother_occupation) }}" 
                                                   placeholder="Enter mother's occupation">
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
                                <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Additional Information</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="emergency_contact" class="form-label">
                                                <i class="fas fa-exclamation-triangle"></i> Emergency Contact
                                            </label>
                                            <textarea class="form-control @error('emergency_contact') is-invalid @enderror" 
                                                      id="emergency_contact" 
                                                      name="emergency_contact" 
                                                      rows="3" 
                                                      placeholder="Enter emergency contact details">{{ old('emergency_contact', $student->emergency_contact) }}</textarea>
                                            @error('emergency_contact')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="medical_conditions" class="form-label">
                                                <i class="fas fa-heartbeat"></i> Medical Conditions
                                            </label>
                                            <textarea class="form-control @error('medical_conditions') is-invalid @enderror" 
                                                      id="medical_conditions" 
                                                      name="medical_conditions" 
                                                      rows="3" 
                                                      placeholder="Enter any medical conditions or allergies">{{ old('medical_conditions', $student->medical_conditions) }}</textarea>
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
                                                <i class="fas fa-sticky-note"></i> Additional Notes
                                            </label>
                                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                                      id="notes" 
                                                      name="notes" 
                                                      rows="4" 
                                                      placeholder="Enter any additional notes about the student">{{ old('notes', $student->notes) }}</textarea>
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
                                        <button type="submit" class="btn btn-primary btn-lg">
                                            <i class="fas fa-save"></i> Update Student
                                        </button>
                                        <button type="reset" class="btn btn-warning btn-lg ms-2">
                                            <i class="fas fa-undo"></i> Reset Changes
                                        </button>
                                    </div>
                                    <div>
                                        <a href="{{ route('students.show', $student->id) }}" class="btn btn-info btn-lg me-2">
                                            <i class="fas fa-eye"></i> View Details
                                        </a>
                                        <a href="{{ route('students.index') }}" class="btn btn-secondary btn-lg">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Danger Zone -->
                    <div class="mt-5">
                        <div class="card border-danger">
                            <div class="card-header bg-danger text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-exclamation-triangle"></i> Danger Zone
                                </h6>
                            </div>
                            <div class="card-body">
                                <p class="text-muted">Once you delete this student, there is no going back. Please be certain.</p>
                                <form action="{{ route('students.destroy', $student->id) }}" method="POST" style="display:inline" id="deleteForm">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                        <i class="fas fa-trash"></i> Delete Student
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
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
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }
    
    .btn-lg {
        padding: 0.75rem 1.5rem;
        font-size: 1.1rem;
    }
    
    .text-danger {
        font-weight: bold;
    }
    
    .alert-info {
        border-left: 4px solid #17a2b8;
    }
    
    .border-danger {
        border-color: #dc3545 !important;
    }
</style>
@endpush

@push('js')
<script>
    // Form validation
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form[method="POST"]');
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
        
        // Mobile validation
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
            if (!confirm('Are you sure you want to update this student information?')) {
                e.preventDefault();
            }
        });
    });
    
    // Delete confirmation
    function confirmDelete() {
        if (confirm('⚠️ WARNING: This will permanently delete the student!\n\nAre you absolutely sure?')) {
            if (confirm('This action cannot be undone. Click OK to proceed with deletion.')) {
                document.getElementById('deleteForm').submit();
            }
        }
    }
</script>
@endpush
