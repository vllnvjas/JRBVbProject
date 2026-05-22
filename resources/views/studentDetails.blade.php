@extends('format.layout')

@section('title','Student Details')

@section('content')
@if($canEdit)
<a href="{{ route('students.index') }}" class="btn btn-outline-secondary mb-3"><i class="bi bi-arrow-left me-1"></i> Back to Students</a>
@endif

<div class="card border-0" id="studentDetailsContainer" data-autoreload>
    <div class="card-body p-4 p-md-5">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h2 class="mb-0">Student Details</h2>
            @if($canEdit)
            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-primary btn-sm px-3">
                <i class="bi bi-pencil-square me-1"></i> Edit
            </a>
            @endif
        </div>

        <table class="table align-middle mb-0">
            <tr>
                <th style="width: 220px;">First Name</th>
                <td>{{ $student->fname }}</td>
            </tr>
            <tr>
                <th>Middle Name</th>
                <td>{{ $student->mname ?? '-' }}</td>
            </tr>
            <tr>
                <th>Last Name</th>
                <td>{{ $student->lname }}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{ $student->userAccount?->email ?? '-' }}</td>
            </tr>
            <tr>
                <th>Degree</th>
                <td>{{ $student->degree?->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Contact Info</th>
                <td>{{ $student->contactInfo }}</td>
            </tr>
        </table>
    </div>
</div>
@endsection
