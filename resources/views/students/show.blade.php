@extends('layout')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <!-- Student Profile Card -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-user-circle"></i> Student Profile Details
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Navigation Buttons -->
                    <div class="mb-4">
                        <a href="{{ route('students.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Students List
                        </a>
                        <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary ms-2">
                            <i class="fas fa-edit"></i> Edit Student
                        </a>
                        <button class="btn btn-success ms-2" onclick="printProfile()">
                            <i class="fas fa-print"></i> Print Profile
                        </button>
                    </div>

                    <div class="row">
                        <!-- Student Avatar -->
                        <div class="col-md-3 text-center">
                            <div class="student-avatar">
                                <div class="avatar-placeholder">
                                    <i class="fas fa-user-graduate fa-5x text-muted"></i>
                                </div>
                                <h5 class="mt-3 text-primary">Student ID: #{{ $student->id }}</h5>
                                <p class="text-muted">
                                    <i class="fas fa-calendar"></i> 
                                    Joined: {{ $student->created_at->format('M d, Y') }}
                                </p>
                            </div>
                        </div>

                        <!-- Student Information -->
                        <div class="col-md-9">
                            <div class="student-info">
                                <h2 class="text-primary mb-3">{{ $student->name }}</h2>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-user text-primary"></i> Full Name
                                            </label>
                                            <div class="info-value">{{ $student->name }}</div>
                                        </div>
                                        
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-phone text-success"></i> Mobile Number
                                            </label>
                                            <div class="info-value">
                                                <a href="tel:{{ $student->mobile }}" class="text-decoration-none">
                                                    {{ $student->mobile }}
                                                </a>
                                                <button class="btn btn-sm btn-outline-success ms-2" onclick="copyToClipboard('{{ $student->mobile }}')">
                                                    <i class="fas fa-copy"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-calendar-plus text-info"></i> Registration Date
                                            </label>
                                            <div class="info-value">{{ $student->created_at->format('F d, Y \a\t h:i A') }}</div>
                                        </div>
                                        
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-edit text-warning"></i> Last Updated
                                            </label>
                                            <div class="info-value">{{ $student->updated_at->format('F d, Y \a\t h:i A') }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="info-item mb-3">
                                            <label class="info-label">
                                                <i class="fas fa-map-marker-alt text-danger"></i> Address
                                            </label>
                                            <div class="info-value address-box">
                                                {{ $student->address }}
                                                <button class="btn btn-sm btn-outline-primary ms-2" onclick="openMaps('{{ urlencode($student->address) }}')">
                                                    <i class="fas fa-map"></i> View on Map
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Cards -->
            <div class="row mt-4">
                <div class="col-md-4">
                    <div class="card border-primary">
                        <div class="card-body text-center">
                            <i class="fas fa-edit fa-2x text-primary mb-3"></i>
                            <h5>Edit Information</h5>
                            <p class="text-muted">Update student details and contact information</p>
                            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Edit Student
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-success">
                        <div class="card-body text-center">
                            <i class="fas fa-share-alt fa-2x text-success mb-3"></i>
                            <h5>Share Profile</h5>
                            <p class="text-muted">Share student profile via email or social media</p>
                            <button class="btn btn-success" onclick="shareProfile()">
                                <i class="fas fa-share"></i> Share Profile
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card border-danger">
                        <div class="card-body text-center">
                            <i class="fas fa-trash fa-2x text-danger mb-3"></i>
                            <h5>Delete Student</h5>
                            <p class="text-muted">Permanently remove student from system</p>
                            <button class="btn btn-danger" onclick="confirmDelete()">
                                <i class="fas fa-trash"></i> Delete Student
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hidden Delete Form -->
            <form id="deleteForm" action="{{ route('students.destroy', $student->id) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
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
    
    .student-avatar {
        padding: 2rem 1rem;
    }
    
    .avatar-placeholder {
        width: 120px;
        height: 120px;
        background: #f8f9fa;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
        border: 3px solid #dee2e6;
    }
    
    .info-label {
        font-weight: 600;
        color: #6c757d;
        font-size: 0.9rem;
        display: block;
        margin-bottom: 0.5rem;
    }
    
    .info-value {
        font-size: 1.1rem;
        color: #495057;
        background: #f8f9fa;
        padding: 0.75rem;
        border-radius: 0.375rem;
        border-left: 4px solid #007bff;
    }
    
    .address-box {
        min-height: 60px;
        line-height: 1.5;
    }
    
    .border-primary {
        border-color: #007bff !important;
    }
    
    .border-success {
        border-color: #28a745 !important;
    }
    
    .border-danger {
        border-color: #dc3545 !important;
    }
    
    @media print {
        .btn, .card:not(:first-child) {
            display: none !important;
        }
        
        .card-body {
            border: none !important;
            box-shadow: none !important;
        }
    }
</style>
@endpush

@push('js')
<script>
    // Print profile function
    function printProfile() {
        window.print();
    }
    
    // Copy to clipboard function
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Success feedback
            const btn = event.target.closest('button');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check"></i>';
            btn.classList.remove('btn-outline-success');
            btn.classList.add('btn-success');
            
            setTimeout(function() {
                btn.innerHTML = originalHTML;
                btn.classList.remove('btn-success');
                btn.classList.add('btn-outline-success');
            }, 2000);
        });
    }
    
    // Open address in maps
    function openMaps(address) {
        const mapsUrl = `https://www.google.com/maps/search/?api=1&query=${address}`;
        window.open(mapsUrl, '_blank');
    }
    
    // Share profile function
    function shareProfile() {
        if (navigator.share) {
            navigator.share({
                title: 'Student Profile: {{ $student->name }}',
                text: 'Check out this student profile',
                url: window.location.href
            });
        } else {
            // Fallback - copy URL to clipboard
            copyToClipboard(window.location.href);
            alert('Profile URL copied to clipboard!');
        }
    }
    
    // Delete confirmation
    function confirmDelete() {
        if (confirm('⚠️ WARNING: This will permanently delete {{ $student->name }}!\n\nAre you absolutely sure?')) {
            if (confirm('This action cannot be undone. Click OK to proceed with deletion.')) {
                document.getElementById('deleteForm').submit();
            }
        }
    }
    
    // Add some interactivity on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Animate info items on scroll
        const infoItems = document.querySelectorAll('.info-item');
        
        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        });
        
        infoItems.forEach(function(item) {
            item.style.opacity = '0';
            item.style.transform = 'translateY(20px)';
            item.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            observer.observe(item);
        });
    });
</script>
@endpush





