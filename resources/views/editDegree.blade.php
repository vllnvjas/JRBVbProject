@extends('format.layout')

@section('title','Edit Degree')

@section('content')
<a href="{{ route('degrees.index') }}" class="btn btn-outline-secondary mb-3"><i class="bi bi-arrow-left me-1"></i> Back to Degrees</a>

<div class="card border-0">
    <div class="card-body p-4 p-md-5">
        <h2 class="mb-4">Edit Degree</h2>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div data-js-form data-action="{{ route('degrees.update', $degree->id) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Degree Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $degree->name) }}" class="form-control @error('name') is-invalid @enderror" minlength="2" oninvalid="this.setCustomValidity('Degree name must be at least 2 letters.')" oninput="this.setCustomValidity('')" required>
                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="button" class="btn btn-primary mt-4 px-4" data-js-submit>Update Degree</button>
        </div>
    </div>
</div>
@endsection
