@extends('layout')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h4 class="mb-0">
                        <i class="fas fa-edit"></i> Edit Grade
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Navigation Buttons -->
                    <div class="mb-4">
                        <a href="{{ route('grades.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Grades List
                        </a>
                        <a href="{{ route('grades.show', $grade->id) }}" class="btn btn-info ms-2">
                            <i class="fas fa-eye"></i> View Grade
                        </a>
                    </div>

                    <form action="{{ route('grades.update', $grade->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <!-- Student and Course Info (Read-only) -->
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="mb-0">Student Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-item mb-2">
                                            <label class="info-label">Student Name</label>
                                            <div class="info-value">{{ $grade->student->name }}</div>
                                        </div>
                                        <div class="info-item mb-2">
                                            <label class="info-label">Student ID</label>
                                            <div class="info-value">#{{ $grade->student->id }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-header">
                                        <h6 class="mb-0">Course Information</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="info-item mb-2">
                                            <label class="info-label">Course Name</label>
                                            <div class="info-value">{{ $grade->course->name }}</div>
                                        </div>
                                        <div class="info-item mb-2">
                                            <label class="info-label">Course Code</label>
                                            <div class="info-value">{{ $grade->course->code }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-calculator"></i> Grade Information
                                </h5>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="midterm_score" class="form-label">
                                        <i class="fas fa-chart-bar text-primary"></i> Midterm Score
                                    </label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control @error('midterm_score') is-invalid @enderror" 
                                               id="midterm_score" 
                                               name="midterm_score" 
                                               value="{{ old('midterm_score', $grade->midterm_score) }}" 
                                               min="0" 
                                               max="10" 
                                               step="0.1"
                                               placeholder="Enter midterm score">
                                        <span class="input-group-text">/ 10</span>
                                    </div>
                                    @error('midterm_score')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Enter score from 0 to 10</small>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label for="final_score" class="form-label">
                                        <i class="fas fa-chart-line text-success"></i> Final Score
                                    </label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control @error('final_score') is-invalid @enderror" 
                                               id="final_score" 
                                               name="final_score" 
                                               value="{{ old('final_score', $grade->final_score) }}" 
                                               min="0" 
                                               max="10" 
                                               step="0.1"
                                               placeholder="Enter final score">
                                        <span class="input-group-text">/ 10</span>
                                    </div>
                                    @error('final_score')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Enter score from 0 to 10</small>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-calculator text-warning"></i> Total Score (Auto-calculated)
                                    </label>
                                    <div class="input-group">
                                        <input type="text" 
                                               class="form-control bg-light" 
                                               id="total_score_display" 
                                               value="{{ $grade->total_score ?? 'Not calculated' }}" 
                                               readonly>
                                        <span class="input-group-text">/ 10</span>
                                    </div>
                                    <small class="form-text text-muted">Formula: (Midterm × 0.4) + (Final × 0.6)</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-trophy text-warning"></i> Grade Letter (Auto-assigned)
                                    </label>
                                    <div class="input-group">
                                        <input type="text" 
                                               class="form-control bg-light" 
                                               id="grade_display" 
                                               value="{{ $grade->grade ?? 'Not assigned' }}" 
                                               readonly>
                                    </div>
                                    <small class="form-text text-muted">
                                        A: 8.5+, B: 7.0+, C: 5.5+, D: 4.0+, F: <4.0
                                    </small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-check-circle text-success"></i> Status (Auto-determined)
                                    </label>
                                    <div class="input-group">
                                        <input type="text" 
                                               class="form-control bg-light" 
                                               id="status_display" 
                                               value="{{ ucfirst($grade->status ?? 'Not determined') }}" 
                                               readonly>
                                    </div>
                                    <small class="form-text text-muted">Passed: ≥4.0, Failed: <4.0</small>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group mb-3">
                                    <label for="notes" class="form-label">
                                        <i class="fas fa-sticky-note text-secondary"></i> Notes
                                    </label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" 
                                              id="notes" 
                                              name="notes" 
                                              rows="4" 
                                              placeholder="Enter any additional notes about this grade...">{{ old('notes', $grade->notes) }}</textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Optional: Add any comments or notes about this grade</small>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <button type="button" class="btn btn-secondary" onclick="resetForm()">
                                            <i class="fas fa-undo"></i> Reset
                                        </button>
                                        <button type="button" class="btn btn-info ms-2" onclick="calculateGrade()">
                                            <i class="fas fa-calculator"></i> Calculate
                                        </button>
                                    </div>
                                    <div>
                                        <a href="{{ route('grades.index') }}" class="btn btn-secondary me-2">
                                            <i class="fas fa-times"></i> Cancel
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save"></i> Update Grade
                                        </button>
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
        margin-bottom: 1rem;
    }
    
    .info-label {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.9rem;
        display: block;
        margin-bottom: 0.5rem;
    }
    
    .info-value {
        font-size: 1rem;
        color: #495057;
        background: #f8f9fa;
        padding: 0.5rem;
        border-radius: 0.375rem;
        border-left: 3px solid #007bff;
    }
    
    .form-label {
        font-weight: 600;
        color: #495057;
    }
    
    .input-group-text {
        background-color: #e9ecef;
        border-color: #ced4da;
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
</style>
@endpush

@push('js')
<script>
    // Auto-calculate total score when midterm or final score changes
    document.getElementById('midterm_score').addEventListener('input', calculateGrade);
    document.getElementById('final_score').addEventListener('input', calculateGrade);
    
    function calculateGrade() {
        const midterm = parseFloat(document.getElementById('midterm_score').value) || 0;
        const final = parseFloat(document.getElementById('final_score').value) || 0;
        
        // Calculate total score (40% midterm + 60% final)
        const total = (midterm * 0.4) + (final * 0.6);
        
        // Update total score display
        const totalDisplay = document.getElementById('total_score_display');
        if (midterm > 0 || final > 0) {
            totalDisplay.value = total.toFixed(2);
        } else {
            totalDisplay.value = 'Not calculated';
        }
        
        // Determine grade letter
        const gradeDisplay = document.getElementById('grade_display');
        let grade = 'Not assigned';
        if (total >= 8.5) grade = 'A';
        else if (total >= 7.0) grade = 'B';
        else if (total >= 5.5) grade = 'C';
        else if (total >= 4.0) grade = 'D';
        else if (total > 0) grade = 'F';
        
        gradeDisplay.value = grade;
        
        // Determine status
        const statusDisplay = document.getElementById('status_display');
        let status = 'Not determined';
        if (total >= 4.0 && total > 0) status = 'Passed';
        else if (total < 4.0 && total > 0) status = 'Failed';
        
        statusDisplay.value = status;
    }
    
    // Reset form to original values
    function resetForm() {
        if (confirm('Are you sure you want to reset all changes?')) {
            document.getElementById('midterm_score').value = '{{ $grade->midterm_score }}';
            document.getElementById('final_score').value = '{{ $grade->final_score }}';
            document.getElementById('notes').value = '{{ $grade->notes }}';
            calculateGrade();
        }
    }
    
    // Form validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const midterm = parseFloat(document.getElementById('midterm_score').value);
        const final = parseFloat(document.getElementById('final_score').value);
        
        if (midterm < 0 || midterm > 10) {
            e.preventDefault();
            alert('Midterm score must be between 0 and 10');
            return false;
        }
        
        if (final < 0 || final > 10) {
            e.preventDefault();
            alert('Final score must be between 0 and 10');
            return false;
        }
    });
    
    // Initialize calculation on page load
    document.addEventListener('DOMContentLoaded', function() {
        calculateGrade();
    });
</script>
@endpush
