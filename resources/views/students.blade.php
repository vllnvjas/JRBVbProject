@extends('format.layout')

@section('title','Students')

@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
 
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <h2 class="mb-0">Student List</h2>
        <small class="text-muted">Total records: {{ $students->total() }}</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('degrees.index') }}" class="btn btn-outline-success"><i class="bi bi-mortarboard-fill me-1"></i> Manage Degrees</a>
        <a href="{{ route('students.create') }}" class="btn btn-primary"><i class="bi bi-person-plus-fill me-1"></i> Add Student</a>
    </div>
</div>

<div class="card border-0">
    <div class="card-body p-0">
        <div class="table-responsive" id="studentsContainer" data-autoreload>
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
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr>
                        <td class="ps-3 fw-semibold">{{ $students->firstItem() + $loop->index }}</td>
                        <td>{{ $student->fname }}</td>
                        <td>{{ $student->mname ?? '-' }}</td>
                        <td>{{ $student->lname }}</td>
                        <td>{{ $student->userAccount?->email ?? '-' }}</td>
                        <td>{{ $student->degree?->name ?? '-' }}</td>
                        <td>{{ $student->contactInfo }}</td>
                        <td class="text-center text-nowrap">
                            <a href="{{ route('students.show', $student->id) }}" class="btn btn-info btn-sm px-3">
                                <i class="bi bi-eye-fill"></i>
                            </a>
                            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning btn-sm px-3">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <div class="d-inline-block" data-js-form data-action="{{ route('students.destroy', $student->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm" data-js-submit data-confirm="Are you sure you want to delete this student?">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">No students available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($students->lastPage() > 1)
<div class="mt-3 d-flex justify-content-end">
    <ul class="pagination pagination-sm mb-0 shadow-sm">
        <li class="page-item @if($students->onFirstPage()) disabled @endif">
            <a class="page-link" href="{{ $students->onFirstPage() ? '#' : $students->previousPageUrl() }}" aria-label="Previous">&lsaquo;</a>
        </li>

        @for($page = 1; $page <= $students->lastPage(); $page++)
            <li class="page-item @if($students->currentPage() === $page) active @endif">
                <a class="page-link" href="{{ $students->url($page) }}">{{ $page }}</a>
            </li>
        @endfor

        <li class="page-item @if(!$students->hasMorePages()) disabled @endif">
            <a class="page-link" href="{{ $students->hasMorePages() ? $students->nextPageUrl() : '#' }}" aria-label="Next">&rsaquo;</a>
        </li>
    </ul>
</div>
@endif
@endsection
