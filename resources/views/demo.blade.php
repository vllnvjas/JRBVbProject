@extends('format.layout')

@section('title', 'Demo')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <h1 class="h3 mb-1">Demo</h1>
        <p class="text-muted mb-0">Student list preview from the demo page.</p>
    </div>
</div>

<div class="card border-0">
    <div class="card-body p-0">
        <div class="table-responsive" id="demoContainer" data-autoreload>
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>First Name</th>
                        <th>Middle Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Degree</th>
                        <th>Contact</th>
                    </tr>
                </thead>
                <tbody id="demoStudentsBody">
                    @forelse($students as $student)
                        <tr>
                            <td class="ps-3 fw-semibold">{{ $loop->iteration }}</td>
                            <td>{{ $student->fname }}</td>
                            <td>{{ $student->mname ?? '-' }}</td>
                            <td>{{ $student->lname }}</td>
                            <td>{{ $student->userAccount?->email ?? '-' }}</td>
                            <td>{{ $student->degree?->name ?? '-' }}</td>
                            <td>{{ $student->contactInfo }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">No students found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
