@extends('format.layout')

@section('title', 'Admin Admins')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <span class="badge rounded-pill text-bg-light border mb-2 px-3 py-2">Administrator</span>
        <h1 class="mb-0">Admins</h1>
        <small class="text-muted">Total records: {{ $admins->total() }}</small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a>
        <a href="{{ route('admin.admins.create') }}" class="btn btn-primary"><i class="bi bi-person-plus-fill me-1"></i> Add Admin</a>
    </div>
</div>

<div class="card border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $admin)
                    <tr>
                        <td class="ps-3 fw-semibold">{{ $admins->firstItem() + $loop->index }}</td>
                        <td>{{ $admin->userAccount->name ?? '-' }}</td>
                        <td>{{ $admin->userAccount->username }}</td>
                        <td>{{ $admin->userAccount->email }}</td>
                        <td>{{ $admin->userAccount->is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="text-center text-nowrap">
                            <a href="{{ route('admin.admins.edit', $admin->id) }}" class="btn btn-warning btn-sm px-3">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            @if($admin->userAccount->id !== session('authenticated_user_id'))
                            <div class="d-inline-block" data-js-form data-action="{{ route('admin.admins.delete', $admin->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm px-3" data-js-submit data-confirm="Are you sure you want to delete this admin?">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">No admins available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($admins->lastPage() > 1)
<div class="mt-3 d-flex justify-content-end">
    <ul class="pagination pagination-sm mb-0 shadow-sm">
        <li class="page-item @if($admins->onFirstPage()) disabled @endif">
            <a class="page-link" href="{{ $admins->onFirstPage() ? '#' : $admins->previousPageUrl() }}" aria-label="Previous">&lsaquo;</a>
        </li>

        @for($page = 1; $page <= $admins->lastPage(); $page++)
            <li class="page-item @if($admins->currentPage() === $page) active @endif">
                <a class="page-link" href="{{ $admins->url($page) }}">{{ $page }}</a>
            </li>
        @endfor

        <li class="page-item @if(!$admins->hasMorePages()) disabled @endif">
            <a class="page-link" href="{{ $admins->hasMorePages() ? $admins->nextPageUrl() : '#' }}" aria-label="Next">&rsaquo;</a>
        </li>
    </ul>
</div>
@endif
@endsection
