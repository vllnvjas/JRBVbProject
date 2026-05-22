@extends('format.layout')

@section('title','Admin Degrees')

@section('content')
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <span class="badge rounded-pill text-bg-light border mb-2 px-3 py-2">Administrator</span>
        <h1 class="mb-0">Degrees</h1>
        <small class="text-muted">Total records: {{ $degrees->total() }}</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a>
        <a href="#degree-form" class="btn btn-primary"><i class="bi bi-mortarboard-fill me-1"></i> Add Degree</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card border-0 h-100" id="degree-form">
            <div class="card-body">
                <h4 class="mb-3">Add Degree</h4>
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <div data-js-form data-action="{{ route('admin.degrees.store') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="degree_name" class="form-label">Degree Name</label>
                        <input type="text" id="degree_name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="e.g. BSIT" minlength="2" oninvalid="this.setCustomValidity('Degree name must be at least 2 letters.')" oninput="this.setCustomValidity('')" required>
                        @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="button" class="btn btn-success" data-js-submit>Add Degree</button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card border-0 h-100">
            <div class="card-body p-0">
                <div class="p-3 pb-0">
                    <h4 class="mb-0">Degree Table</h4>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th class="ps-3" style="width: 80px;">#</th>
                                <th>Degree Name</th>
                                <th class="text-center" style="width: 180px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($degrees as $degree)
                            <tr>
                                <td class="ps-3 fw-semibold">{{ $degrees->firstItem() + $loop->index }}</td>
                                <td>{{ $degree->name }}</td>
                                <td class="text-center">
                                    <a href="{{ route('admin.degrees.edit', $degree->id) }}" class="btn btn-warning btn-sm px-3">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <div class="d-inline-block ms-1" data-js-form data-action="{{ route('admin.degrees.delete', $degree->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger btn-sm px-3" data-js-submit data-confirm="Are you sure you want to delete this degree?">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="text-center py-4">No degrees available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        @if($degrees->lastPage() > 1)
        <div class="mt-3 d-flex justify-content-end">
            <ul class="pagination pagination-sm mb-0 shadow-sm">
                <li class="page-item @if($degrees->onFirstPage()) disabled @endif">
                    <a class="page-link" href="{{ $degrees->onFirstPage() ? '#' : $degrees->previousPageUrl() }}" aria-label="Previous">&lsaquo;</a>
                </li>

                @for($page = 1; $page <= $degrees->lastPage(); $page++)
                    <li class="page-item @if($degrees->currentPage() === $page) active @endif">
                        <a class="page-link" href="{{ $degrees->url($page) }}">{{ $page }}</a>
                    </li>
                @endfor

                <li class="page-item @if(!$degrees->hasMorePages()) disabled @endif">
                    <a class="page-link" href="{{ $degrees->hasMorePages() ? $degrees->nextPageUrl() : '#' }}" aria-label="Next">&rsaquo;</a>
                </li>
            </ul>
        </div>
        @endif
    </div>
</div>
@endsection