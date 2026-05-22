@extends('format.layout')

@section('title','Home')

@section('content')
<div class="card border-0 overflow-hidden">
    <div class="card-body p-4 p-md-5">
        <span class="badge rounded-pill text-bg-light border mb-3 px-3 py-2">Student Record System</span>
        <h1 class="display-6 mb-3">Simple management. Elegant workflow.</h1>
        <p class="text-secondary mb-4 col-lg-8">Create, update, and review student records from one clear dashboard with fast actions and readable data views.</p>

        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('students.index') }}" class="btn btn-primary px-4">
                <i class="bi bi-people-fill me-1"></i> Open Students
            </a>
            <a href="{{ route('students.create') }}" class="btn btn-outline-secondary px-4">
                <i class="bi bi-person-plus-fill me-1"></i> Add Student
            </a>
        </div>
    </div>
</div>
@endsection
