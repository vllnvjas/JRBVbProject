@extends('format.layout')

@section('title', 'Edit Teacher')

@section('content')
<a href="{{ route('admin.teachers.index') }}" class="btn btn-outline-secondary mb-3"><i class="bi bi-arrow-left me-1"></i> Back to Teachers</a>

<div class="card border-0">
    <div class="card-body p-4 p-md-5">
        <h2 class="mb-4">Edit Teacher</h2>

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div id="adminEditTeacherForm" data-js-form data-action="{{ route('admin.teachers.update', $teacher->id) }}" data-reload-on-success="false" data-success-message="Teacher updated successfully.">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-4">
                    <label for="f_name" class="form-label">First Name</label>
                    <input type="text" id="f_name" name="f_name" value="{{ old('f_name', $teacher->fname) }}" class="form-control @error('f_name') is-invalid @enderror" required>
                    @error('f_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="m_name" class="form-label">Middle Name</label>
                    <input type="text" id="m_name" name="m_name" value="{{ old('m_name', $teacher->mname) }}" class="form-control @error('m_name') is-invalid @enderror">
                    @error('m_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-4">
                    <label for="l_name" class="form-label">Last Name</label>
                    <input type="text" id="l_name" name="l_name" value="{{ old('l_name', $teacher->lname) }}" class="form-control @error('l_name') is-invalid @enderror" required>
                    @error('l_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label for="e_mail" class="form-label">Email</label>
                    <input type="email" id="e_mail" name="e_mail" value="{{ old('e_mail', $teacher->userAccount->email) }}" class="form-control @error('e_mail') is-invalid @enderror" required>
                    @error('e_mail')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="degree_id" class="form-label">Degree</label>
                    <select id="degree_id" name="degree_id" class="form-select @error('degree_id') is-invalid @enderror">
                        <option value="" disabled>Select Degree</option>
                        @foreach($degrees as $degree)
                        <option value="{{ $degree->id }}" @selected(old('degree_id', $teacher->degree_id) == $degree->id)>{{ $degree->name }}</option>
                        @endforeach
                    </select>
                    @error('degree_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="contactInfo" class="form-label">Contact Info</label>
                    <input type="tel" id="contactInfo" name="contactInfo" value="{{ old('contactInfo', $teacher->contactInfo) }}" class="form-control @error('contactInfo') is-invalid @enderror" inputmode="numeric" maxlength="11" pattern="[0-9]{11}" oninvalid="this.setCustomValidity('Contact number must be exactly 11 numbers.')" oninput="this.setCustomValidity('')">
                    @error('contactInfo')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <button type="button" class="btn btn-primary mt-4 px-4" data-js-submit>Update Teacher</button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var container = document.getElementById('adminEditTeacherForm');

    if (!container) {
        return;
    }

    var submitButton = container.querySelector('[data-js-submit]');

    function showSuccess(message) {
        var existing = container.querySelector('[data-teacher-update-alert]');

        if (existing) {
            existing.remove();
        }

        var alertBox = document.createElement('div');
        alertBox.className = 'alert alert-success alert-dismissible fade show mt-3';
        alertBox.setAttribute('role', 'alert');
        alertBox.setAttribute('data-teacher-update-alert', 'true');
        alertBox.innerHTML = message + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';

        container.prepend(alertBox);
    }

    function showError(message) {
        var existing = container.querySelector('[data-teacher-update-alert]');

        if (existing) {
            existing.remove();
        }

        var alertBox = document.createElement('div');
        alertBox.className = 'alert alert-danger mt-3';
        alertBox.setAttribute('role', 'alert');
        alertBox.setAttribute('data-teacher-update-alert', 'true');
        alertBox.textContent = message;

        container.prepend(alertBox);
    }

    document.addEventListener('click', function (event) {
        if (!event.target.closest || !event.target.closest('#adminEditTeacherForm [data-js-submit]')) {
            return;
        }

        event.preventDefault();
        event.stopImmediatePropagation();

        if (submitButton) {
            submitButton.disabled = true;
        }

        var formData = new FormData();
        var fields = container.querySelectorAll('input, select, textarea');

        fields.forEach(function (field) {
            if (!field.name || field.disabled) {
                return;
            }

            formData.append(field.name, field.value);
        });

        var xhr = new XMLHttpRequest();
        xhr.open('POST', container.dataset.action, true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.onload = function () {
            if (submitButton) {
                submitButton.disabled = false;
            }

            if (xhr.status >= 200 && xhr.status < 300) {
                var payload = null;

                try {
                    payload = JSON.parse(xhr.responseText);
                } catch (error) {
                    payload = null;
                }

                showSuccess((payload && payload.message) ? payload.message : 'Teacher updated successfully.');

                if (payload && payload.teacher) {
                    var syncEvent = new CustomEvent('admin-teachers:updated', {
                        detail: {
                            teacher: payload.teacher
                        }
                    });

                    window.dispatchEvent(syncEvent);

                    if (window.BroadcastChannel) {
                        var channel = new BroadcastChannel('admin-teachers-sync');
                        channel.postMessage({
                            type: 'teacher.updated',
                            teacher: payload.teacher
                        });
                        channel.close();
                    }
                }

                return;
            }

            if (xhr.status === 422) {
                try {
                    var payload = JSON.parse(xhr.responseText);
                    var firstError = payload && payload.errors ? Object.values(payload.errors).flat()[0] : null;

                    showError(firstError || 'Please fix the validation errors and try again.');
                } catch (error) {
                    showError('Please fix the validation errors and try again.');
                }

                return;
            }

            showError('Unable to update the teacher right now.');
        };

        xhr.onerror = function () {
            if (submitButton) {
                submitButton.disabled = false;
            }

            showError('Network error while updating the teacher.');
        };

        xhr.send(formData);
    }, true);
});
</script>
@endsection
