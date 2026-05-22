@extends('format.layout')

@section('title', 'Edit Admin')

@section('content')
<a href="{{ route('admin.admins.index') }}" class="btn btn-outline-secondary mb-3"><i class="bi bi-arrow-left me-1"></i> Back to Admins</a>

<div class="card border-0">
    <div class="card-body p-4 p-md-5">
        <h2 class="mb-4">Edit Admin</h2>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div data-js-form data-action="{{ route('admin.admins.update', $admin->id) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $admin->userAccount->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="e_mail" class="form-label">Email</label>
                    <input type="email" id="e_mail" name="e_mail" value="{{ old('e_mail', $admin->userAccount->email) }}" class="form-control @error('e_mail') is-invalid @enderror" required>
                    @error('e_mail')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" value="{{ $admin->userAccount->username }}" class="form-control" disabled>
                    <small class="text-muted">Username cannot be changed</small>
                </div>

                <div class="col-md-6">
                    <label for="role" class="form-label">Role</label>
                    <input type="text" id="role" value="{{ $admin->userAccount->role }}" class="form-control" disabled>
                </div>
            </div>

            <button type="button" class="btn btn-primary mt-4 px-4" data-js-submit>Update Admin</button>
        </div>
    </div>
</div>
@endsection
