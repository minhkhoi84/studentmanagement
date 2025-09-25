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
        </div>
    </div>
</div>
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('assets/css/students.css') }}">
@endpush

@push('js')
<script>
    
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







