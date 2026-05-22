@extends('format.layout')

@section('title', 'Teacher Dashboard')

@section('content')
@php
    $teacherName = $teacher?->name ?? $teacher?->username ?? 'Teacher';
@endphp

<div class="card border-0 overflow-hidden mb-4">
    <div class="card-body p-4 p-md-5">
        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
            <div>
                <span class="badge rounded-pill text-bg-light border mb-2 px-3 py-2">Teacher</span>
                <h1 class="display-6 mb-1">Teacher Dashboard</h1>
                <p class="text-secondary mb-0 col-lg-8">Manage students, review records, and keep your classroom data organized.</p>
            </div>
            <div class="text-md-end">
                <small class="text-muted">Signed in as</small>
                <div class="fw-bold">{{ $teacherName }}</div>
                <div class="small text-muted">{{ $teacher?->email ?? 'No email available' }}</div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 border-0 bg-white">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-3 p-3" style="background:rgba(23,89,74,0.08);">
                            <i class="bi bi-people-fill fs-4 text-success"></i>
                        </div>
                        <div>
                            <div class="text-uppercase text-secondary small fw-bold">Students</div>
                            <div class="display-6 fw-bold">{{ $students->total() }}</div>
                            <div class="small text-muted">Total records</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 border-0 bg-white">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-3 p-3" style="background:rgba(11,68,56,0.07);">
                            <i class="bi bi-list-check fs-4 text-primary"></i>
                        </div>
                        <div>
                            <div class="text-uppercase text-secondary small fw-bold">Page</div>
                            <div class="display-6 fw-bold">{{ $students->currentPage() }}</div>
                            <div class="small text-muted">Current page</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 border-0 bg-white">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-3 p-3" style="background:rgba(191,127,53,0.08);">
                            <i class="bi bi-person-badge-fill fs-4 text-warning"></i>
                        </div>
                        <div>
                            <div class="text-uppercase text-secondary small fw-bold">Account</div>
                            <div class="display-6 fw-bold">{{ $teacher ? 'On' : 'Off' }}</div>
                            <div class="small text-muted">Profile status</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card h-100 border-0 bg-white">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-3 p-3" style="background:rgba(47,54,64,0.06);">
                            <i class="bi bi-clock-history fs-4 text-secondary"></i>
                        </div>
                        <div>
                            <div class="text-uppercase text-secondary small fw-bold">Access</div>
                            <div class="display-6 fw-bold">Live</div>
                            <div class="small text-muted">Dashboard ready</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card h-100 border-0 bg-white mb-3" id="teacherProfileDetailsContainer" data-autoreload>
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <h3 class="h6 mb-0">My Profile</h3>
                            <span class="badge text-bg-success-subtle text-success border border-success-subtle">Teacher</span>
                        </div>

                        @if($teacher)
                        <div class="d-flex align-items-center gap-3 mb-4">
                            <div class="rounded-circle d-inline-flex align-items-center justify-content-center" style="width:56px;height:56px;background:rgba(23,89,74,0.1);">
                                <i class="bi bi-person-fill fs-4 text-success"></i>
                            </div>
                            <div>
                                <div class="fw-bold">{{ $teacherName }}</div>
                                <div class="text-muted small">{{ $teacher->username }}</div>
                            </div>
                        </div>

                        <div class="small text-uppercase text-secondary fw-bold mb-2">Details</div>
                        <ul class="list-unstyled mb-0 small">
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">Name</span>
                                <span class="fw-semibold">{{ $teacherName }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">Username</span>
                                <span class="fw-semibold">{{ $teacher->username }}</span>
                            </li>
                            <li class="d-flex justify-content-between py-2 border-bottom">
                                <span class="text-muted">Email</span>
                                <span class="fw-semibold text-break text-end">{{ $teacher->email }}</span>
                            </li>
                            <li class="d-flex justify-content-between pt-2">
                                <span class="text-muted">Status</span>
                                <span class="fw-semibold">{{ $teacher->is_active ? 'Active' : 'Inactive' }}</span>
                            </li>
                        </ul>
                        @else
                        <p class="text-muted mb-0">Teacher information not available.</p>
                        @endif
                    </div>
                </div>

                
            </div>

            <div class="col-lg-8">
                <div class="card h-100 border-0 bg-white">
                    <div class="card-body">
                        <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-2 mb-3">
                            <div>
                                <h3 class="h6 mb-1">Students</h3>
                                <div class="text-muted small">Latest records from your list</div>
                            </div>
                            <a href="{{ route('teacher.students.index') }}" class="small">View full list</a>
                        </div>

                        @if($students->count() > 0)
                        <div class="table-responsive" id="teacherRecentStudentsContainer" data-autoreload>
                            <table class="table table-hover align-middle mb-0">
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
                                    @foreach($students as $student)
                                    <tr>
                                        <td class="ps-3 fw-semibold">{{ $students->firstItem() + $loop->index }}</td>
                                        <td>
                                            <div class="fw-semibold">{{ trim("{$student->fname} {$student->mname} {$student->lname}") }}</div>
                                        </td>
                                        <td>{{ $student->userAccount?->email ?? '-' }}</td>
                                        <td>{{ $student->degree?->name ?? '-' }}</td>
                                        <td>{{ $student->contactInfo ?? '-' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                            <small class="text-muted">Showing {{ $students->firstItem() }} to {{ $students->lastItem() }} of {{ $students->total() }} students</small>
                            <div>{{ $students->links() }}</div>
                        </div>
                        @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1 d-block mb-2"></i>
                            No students available.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
