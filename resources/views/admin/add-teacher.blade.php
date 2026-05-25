@extends('format.layout')

@section('title', 'Add Teacher')

@section('content')
<a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary mb-3"><i class="bi bi-arrow-left me-1"></i> Back to Dashboard</a>

<div class="card border-0">
    <div class="card-body p-4 p-md-5">
        <h2 class="mb-4">Add Teacher</h2>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form id="adminAddTeacherForm" method="POST" action="{{ route('admin.teachers.store') }}" data-js-form data-action="{{ route('admin.teachers.store') }}" data-reload-on-success="false" data-success-message="Teacher account added successfully.">
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
                    <select id="degree_id" name="degree_id" class="form-select @error('degree_id') is-invalid @enderror">
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
                    <input type="tel" id="contactInfo" name="contactInfo" value="{{ old('contactInfo') }}" class="form-control @error('contactInfo') is-invalid @enderror" inputmode="numeric" maxlength="11" pattern="[0-9]{11}" oninvalid="this.setCustomValidity('Contact number must be exactly 11 numbers.')" oninput="this.setCustomValidity('')">
                    @error('contactInfo')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-4 px-4" data-js-submit>Save Teacher</button>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var container = document.getElementById('adminAddTeacherForm');
    if (!container) return;

    var submitButton = container.querySelector('[data-js-submit]');

    function showSuccess(message) {
        var existing = container.querySelector('[data-teacher-create-alert]');
        if (existing) existing.remove();
        var alertBox = document.createElement('div');
        alertBox.className = 'alert alert-success alert-dismissible fade show mt-3';
        alertBox.setAttribute('role', 'alert');
        alertBox.setAttribute('data-teacher-create-alert', 'true');
        alertBox.innerHTML = message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
        container.prepend(alertBox);
    }

    function showError(message) {
        var existing = container.querySelector('[data-teacher-create-alert]');
        if (existing) existing.remove();
        var alertBox = document.createElement('div');
        alertBox.className = 'alert alert-danger mt-3';
        alertBox.setAttribute('role', 'alert');
        alertBox.setAttribute('data-teacher-create-alert', 'true');
        alertBox.textContent = message;
        container.prepend(alertBox);
    }

    document.addEventListener('click', function (event) {
        if (!event.target.closest || !event.target.closest('#adminAddTeacherForm [data-js-submit]')) return;
        event.preventDefault();
        event.stopImmediatePropagation();

        if (submitButton) submitButton.disabled = true;

        var formData = new FormData();
        var fields = container.querySelectorAll('input, select, textarea');
        fields.forEach(function (field) {
            if (!field.name || field.disabled) return;
            formData.append(field.name, field.value);
        });

        var xhr = new XMLHttpRequest();
        xhr.open('POST', container.dataset.action, true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.onload = function () {
            if (submitButton) submitButton.disabled = false;

            if (xhr.status >= 200 && xhr.status < 300) {
                var payload = null;
                try { payload = JSON.parse(xhr.responseText); } catch (e) { payload = null; }
                showSuccess((payload && payload.message) ? payload.message : 'Teacher account added successfully.');

                var teacher = payload && payload.teacher ? payload.teacher : null;
                if (teacher) {
                    var evt = new CustomEvent('admin-teachers:updated', { detail: { teacher: teacher } });
                    window.dispatchEvent(evt);

                    if (window.BroadcastChannel) {
                        var ch = new BroadcastChannel('admin-teachers-sync');
                        ch.postMessage({ type: 'teacher.created', teacher: teacher });
                        ch.close();
                    }
                }

                return;
            }

            if (xhr.status === 422) {
                try {
                    var payload = JSON.parse(xhr.responseText);
                    var firstError = payload && payload.errors ? Object.values(payload.errors).flat()[0] : null;
                    showError(firstError || 'Please fix the validation errors and try again.');
                } catch (e) { showError('Please fix the validation errors and try again.'); }
                return;
            }

            showError('Unable to add the teacher right now.');
        };

        xhr.onerror = function () {
            if (submitButton) submitButton.disabled = false;
            showError('Network error while adding the teacher.');
        };

        xhr.send(formData);
    }, true);
});
</script>
@endsection