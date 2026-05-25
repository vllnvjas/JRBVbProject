@extends('format.layout')

@section('title','Add Student')

@section('content')
<a href="{{ route('students.index') }}" class="btn btn-outline-secondary mb-3"><i class="bi bi-arrow-left me-1"></i> Back to Students</a>

<div class="card border-0">
    <div class="card-body p-4 p-md-5">
        <h2 class="mb-4">Add Student</h2>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('students.store') }}" data-js-form data-action="{{ route('students.store') }}">
            @csrf

            <div class="row g-3">
                <div class="col-md-4">
                    <label for="f_name" class="form-label">First Name</label>
                    <input type="text" id="f_name" name="f_name" value="{{ old('f_name') }}" class="form-control @error('f_name') is-invalid @enderror" required>
                    @error('f_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="m_name" class="form-label">Middle Name</label>
                    <input type="text" id="m_name" name="m_name" value="{{ old('m_name') }}" class="form-control @error('m_name') is-invalid @enderror">
                    @error('m_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="l_name" class="form-label">Last Name</label>
                    <input type="text" id="l_name" name="l_name" value="{{ old('l_name') }}" class="form-control @error('l_name') is-invalid @enderror" required>
                    @error('l_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="e_mail" class="form-label">Email</label>
                    <input type="email" id="e_mail" name="e_mail" value="{{ old('e_mail') }}" class="form-control @error('e_mail') is-invalid @enderror" required>
                    @error('e_mail')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="degree_id" class="form-label">Degree</label>
                    <select id="degree_id" name="degree_id" class="form-select @error('degree_id') is-invalid @enderror" required>
                        <option value="" selected disabled>Select Degree</option>
                        @foreach($degrees as $degree)
                        <option value="{{ $degree->id }}" @selected(old('degree_id') == $degree->id)>{{ $degree->name }}</option>
                        @endforeach
                    </select>
                    @error('degree_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" class="form-control @error('username') is-invalid @enderror" required>
                    @error('username')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="contactInfo" class="form-label">Contact Info</label>
                    <input type="tel" id="contactInfo" name="contactInfo" value="{{ old('contactInfo') }}" class="form-control @error('contactInfo') is-invalid @enderror" inputmode="numeric" maxlength="11" pattern="[0-9]{11}" oninvalid="this.setCustomValidity('Contact number must be exactly 11 numbers.')" oninput="this.setCustomValidity('')" required>
                    @error('contactInfo')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button id="saveStudent" type="submit" class="btn btn-primary mt-4 px-4" data-js-submit>Save Student</button>
        </form>
    </div>
</div>
@endsection
