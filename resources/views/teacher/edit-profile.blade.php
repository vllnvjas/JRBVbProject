@extends('format.layout')

@section('title', 'My Profile')

@section('content')
<a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-secondary mb-3"><i class="bi bi-arrow-left me-1"></i> Back to Dashboard</a>

<div class="card border-0">
    <div class="card-body p-4 p-md-5">
        <h2 class="mb-4">Edit Profile</h2>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('teacher.profile.update') }}" data-js-form data-action="{{ route('teacher.profile.update') }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-4">
                    <label for="f_name" class="form-label">First Name</label>
                    <input type="text" id="f_name" name="f_name" value="{{ old('f_name', $f) }}" class="form-control @error('f_name') is-invalid @enderror" required>
                    @error('f_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="m_name" class="form-label">Middle Name</label>
                    <input type="text" id="m_name" name="m_name" value="{{ old('m_name', $m) }}" class="form-control @error('m_name') is-invalid @enderror">
                    @error('m_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="l_name" class="form-label">Last Name</label>
                    <input type="text" id="l_name" name="l_name" value="{{ old('l_name', $l) }}" class="form-control @error('l_name') is-invalid @enderror" required>
                    @error('l_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="e_mail" class="form-label">Email</label>
                    <input type="email" id="e_mail" name="e_mail" value="{{ old('e_mail', $user->email) }}" class="form-control @error('e_mail') is-invalid @enderror" required>
                    @error('e_mail')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="password" class="form-label">New Password (leave blank to keep current)</label>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror">
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
                </div>

            </div>

            <button type="submit" class="btn btn-primary mt-4 px-4" data-js-submit>Update Profile</button>
        </form>
    </div>
</div>
@endsection
