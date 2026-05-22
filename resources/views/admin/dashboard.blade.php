@extends('format.layout')

@section('title', 'Admin Dashboard')

@section('content')
<div class="card border-0 overflow-hidden mb-4">
    <div class="card-body p-4 p-md-5">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between mb-3 gap-3">
            <div>
                <span class="badge rounded-pill text-bg-light border mb-2 px-3 py-2">Administrator</span>
                <h1 class="display-6 mb-1">Admin dashboard</h1>
                <p class="text-secondary mb-0 col-lg-8">Manage student records and create teacher login accounts from one place.</p>
            </div>
            <div class="text-end" aria-live="polite">
                <small class="text-muted">Welcome back,</small>
                <div class="fw-bold">{{ auth()->user()->name ?? 'Administrator' }}</div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-md-4">
                <div class="card h-100 border-0 bg-white">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-3 p-3" style="background:rgba(23,89,74,0.08);">
                            <i class="bi bi-people-fill fs-4 text-success"></i>
                        </div>
                        <div>
                            <div class="text-uppercase text-secondary small fw-bold">Students</div>
                            <div class="display-6 fw-bold">{{ $studentCount }}</div>
                            <div class="small text-muted">Active this semester</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-md-4">
                <div class="card h-100 border-0 bg-white">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-3 p-3" style="background:rgba(11,68,56,0.07);">
                            <i class="bi bi-book-half fs-4 text-primary"></i>
                        </div>
                        <div>
                            <div class="text-uppercase text-secondary small fw-bold">Teachers</div>
                            <div class="display-6 fw-bold">{{ $teacherCount }}</div>
                            <div class="small text-muted">Active instructors</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-4">
                <div class="card h-100 border-0 bg-white">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-3 p-3" style="background:rgba(191,127,53,0.08);">
                            <i class="bi bi-shield-lock-fill fs-4 text-warning"></i>
                        </div>
                        <div>
                            <div class="text-uppercase text-secondary small fw-bold">Admins</div>
                            <div class="display-6 fw-bold">{{ $adminCount }}</div>
                            <div class="small text-muted">Site administrators</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card h-100 border-0 bg-white">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h3 class="h6 mb-0">Recent Students</h3>
                            <a href="{{ route('admin.students.index') }}" class="small">View all</a>
                        </div>

                        @if(!empty($recentStudents) && $recentStudents->isNotEmpty())
                        <div class="table-responsive" id="adminRecentStudentsContainer" data-autoreload>
                            <table class="table table-sm align-middle">
                                <thead>
                                    <tr>
                                        <th class="ps-3">#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Degree</th>
                                        <th>Contact</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentStudents as $i => $s)
                                    <tr>
                                        <td class="ps-3 fw-semibold">{{ $i + 1 }}</td>
                                        <td>{{ trim("{$s->fname} {$s->mname} {$s->lname}") }}</td>
                                        <td>{{ $s->userAccount?->email ?? '-' }}</td>
                                        <td>{{ $s->degree?->name ?? '-' }}</td>
                                        <td>{{ $s->contactInfo ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="text-muted">No students available</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card h-100 border-0 bg-white mb-3">
                    <div class="card-body">
                        <h3 class="h6">Quick Actions</h3>
                        <div class="d-grid gap-2 mt-3">
                            <a href="{{ route('admin.students.create') }}" class="btn btn-primary btn-sm" aria-label="Add student">
                                <i class="bi bi-person-plus-fill me-1"></i> Add Student
                            </a>
                            <a href="{{ route('admin.teachers.create') }}" class="btn btn-outline-secondary btn-sm" aria-label="Add teacher">
                                <i class="bi bi-person-badge-fill me-1"></i> Add Teacher
                            </a>
                            <a href="{{ route('admin.degrees.index') }}" class="btn btn-outline-success btn-sm" aria-label="Manage degrees">
                                <i class="bi bi-mortarboard-fill me-1"></i> Manage Degrees
                            </a>
                        </div>
                    </div>
                </div>
@endsection