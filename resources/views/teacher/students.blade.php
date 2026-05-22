@extends('format.layout')

@section('title', 'Teacher Students')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <span class="badge rounded-pill text-bg-light border mb-2 px-3 py-2">Teacher</span>
        <h1 class="mb-0">Students</h1>
        <small class="text-muted">Total records: <span data-teacher-students-total>{{ $students->total() }}</span></small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a>
        <a href="{{ route('teacher.students.create') }}" class="btn btn-primary"><i class="bi bi-person-plus-fill me-1"></i> Add Student</a>
    </div>
</div>

<div class="card border-0">
    <div class="card-body p-0">
        <div class="table-responsive" id="teacherStudentsContainer" data-autoreload>
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Degree</th>
                        <th>Contact</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr data-teacher-student-row="{{ $student->id }}">
                        <td class="ps-3 fw-semibold">{{ $students->firstItem() + $loop->index }}</td>
                        <td data-teacher-student-name>{{ trim("{$student->fname} {$student->mname} {$student->lname}") }}</td>
                        <td data-teacher-student-email>{{ $student->userAccount?->email ?? '-' }}</td>
                        <td data-teacher-student-degree>{{ $student->degree?->name ?? '-' }}</td>
                        <td data-teacher-student-contact>{{ $student->contactInfo }}</td>
                        <td class="text-center text-nowrap">
                            <a href="{{ route('teacher.students.edit', $student->id) }}" class="btn btn-warning btn-sm px-3">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <div class="d-inline-block" data-js-form data-action="{{ route('teacher.students.destroy', $student->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm px-3" data-js-submit data-confirm="Are you sure you want to delete this student?">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">No students available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($students->lastPage() > 1)
<div class="mt-3 d-flex justify-content-end">
    <ul class="pagination pagination-sm mb-0 shadow-sm">
        <li class="page-item @if($students->onFirstPage()) disabled @endif">
            <a class="page-link" href="{{ $students->onFirstPage() ? '#' : $students->previousPageUrl() }}" aria-label="Previous">&lsaquo;</a>
        </li>

        @for($page = 1; $page <= $students->lastPage(); $page++)
            <li class="page-item @if($students->currentPage() === $page) active @endif">
                <a class="page-link" href="{{ $students->url($page) }}">{{ $page }}</a>
            </li>
        @endfor

        <li class="page-item @if(!$students->hasMorePages()) disabled @endif">
            <a class="page-link" href="{{ $students->hasMorePages() ? $students->nextPageUrl() : '#' }}" aria-label="Next">&rsaquo;</a>
        </li>
    </ul>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function() {
    var totalNode = document.querySelector('[data-teacher-students-total]');
    var tableBody = document.querySelector('#teacherStudentsContainer tbody');

    function updateStudentRow(student) {
        if (!student || !student.id) return;

        var row = document.querySelector('[data-teacher-student-row="' + student.id + '"]');

        // create row if missing
        if (!row) {
            if (!tableBody) return;

            var tr = document.createElement('tr');
            tr.setAttribute('data-teacher-student-row', student.id);

            var index = 1;
            var editUrl = '/teacher/students/' + student.id + '/edit';
            var deleteUrl = '/teacher/students/' + student.id;
            var csrf = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

            tr.innerHTML = '\n                <td class="ps-3 fw-semibold">' + index + '</td>\n                <td data-teacher-student-name>' + (student.full_name || '-') + '</td>\n                <td data-teacher-student-email>' + (student.email || '-') + '</td>\n                <td data-teacher-student-degree>' + (student.degree || '-') + '</td>\n                <td data-teacher-student-contact>' + (student.contactInfo || '') + '</td>\n                <td class="text-center text-nowrap">\n                    <a href="' + editUrl + '" class="btn btn-warning btn-sm px-3">\n                        <i class="bi bi-pencil-square"></i>\n                    </a>\n                    <div class="d-inline-block" data-js-form data-action="' + deleteUrl + '">\n                        <input type="hidden" name="_token" value="' + csrf + '">\n                        <input type="hidden" name="_method" value="DELETE">\n                        <button type="button" class="btn btn-danger btn-sm px-3" data-js-submit data-confirm="Are you sure you want to delete this student?">\n                            <i class="bi bi-trash-fill"></i>\n                        </button>\n                    </div>\n                </td>';

            var first = tableBody.querySelector('tr');
            if (first) tableBody.insertBefore(tr, first); else tableBody.appendChild(tr);
            row = tr;
        }

        var nameCell = row.querySelector('[data-teacher-student-name]');
        var emailCell = row.querySelector('[data-teacher-student-email]');
        var degreeCell = row.querySelector('[data-teacher-student-degree]');
        var contactCell = row.querySelector('[data-teacher-student-contact]');

        if (nameCell && typeof student.full_name === 'string') nameCell.textContent = student.full_name;
        if (emailCell && typeof student.email === 'string') emailCell.textContent = student.email || '-';
        if (degreeCell) degreeCell.textContent = (typeof student.degree === 'string' && student.degree.length > 0) ? student.degree : '-';
        if (contactCell && typeof student.contactInfo === 'string') contactCell.textContent = student.contactInfo;
    }

    function ensureEmptyState() {
        if (!tableBody) return;
        var dataRows = tableBody.querySelectorAll('tr[data-teacher-student-row]');
        var emptyRow = tableBody.querySelector('[data-teacher-students-empty-row]');
        if (dataRows.length === 0 && !emptyRow) {
            var row = document.createElement('tr');
            row.setAttribute('data-teacher-students-empty-row', 'true');
            row.innerHTML = '<td colspan="6" class="text-center py-4">No students available</td>';
            tableBody.appendChild(row);
        }
        if (dataRows.length > 0 && emptyRow) emptyRow.remove();
    }

    function syncFromServer() {
        var separator = window.location.href.indexOf('?') === -1 ? '?' : '&';
        var url = window.location.href.replace(/\/teacher\/students.*/,'/teacher/students') + separator + '_sync=' + Date.now();

        fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }, credentials: 'same-origin' })
            .then(function (res) { if (!res.ok) throw new Error('sync failed'); return res.json(); })
            .then(function (payload) {
                if (!payload || !Array.isArray(payload.students)) return;
                var idsOnServer = {};
                payload.students.forEach(function (s) { if (s && s.id) idsOnServer[String(s.id)] = true; });
                if (tableBody) {
                    var existing = tableBody.querySelectorAll('tr[data-teacher-student-row]');
                    existing.forEach(function (row) { var id = row.getAttribute('data-teacher-student-row'); if (!idsOnServer[id]) row.remove(); });
                }
                payload.students.forEach(updateStudentRow);
                ensureEmptyState();
                if (totalNode && payload.meta && typeof payload.meta.total !== 'undefined') totalNode.textContent = payload.meta.total;
            }).catch(function () {});
    }

    function removeStudentRow(studentId) {
        if (!studentId || !tableBody) {
            return;
        }

        var row = document.querySelector('[data-teacher-student-row="' + studentId + '"]');

        if (row) {
            row.remove();
        }

        ensureEmptyState();
    }

    document.addEventListener('click', function (event) {
        var submitButton = event.target.closest ? event.target.closest('#teacherStudentsContainer [data-js-submit]') : null;

        if (!submitButton) {
            return;
        }

        var formContainer = submitButton.closest('[data-js-form]');

        if (!formContainer) {
            return;
        }

        var confirmMessage = submitButton.getAttribute('data-confirm');

        if (confirmMessage && !window.confirm(confirmMessage)) {
            event.preventDefault();
            return;
        }

        event.preventDefault();
        event.stopImmediatePropagation();

        var submitWasDisabled = submitButton.disabled;
        submitButton.disabled = true;

        var formData = new FormData();
        var fields = formContainer.querySelectorAll('input, select, textarea');

        fields.forEach(function (field) {
            if (!field.name || field.disabled) {
                return;
            }

            formData.append(field.name, field.value);
        });

        var xhr = new XMLHttpRequest();
        xhr.open('POST', formContainer.dataset.action, true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        xhr.onload = function () {
            submitButton.disabled = submitWasDisabled;

            if (xhr.status >= 200 && xhr.status < 300) {
                var payload = null;

                try {
                    payload = JSON.parse(xhr.responseText);
                } catch (error) {
                    payload = null;
                }

                var row = formContainer.closest('tr[data-teacher-student-row]');
                var studentId = row ? row.getAttribute('data-teacher-student-row') : null;

                if (studentId) {
                    removeStudentRow(studentId);
                }

                if (payload && payload.student && window.BroadcastChannel) {
                    var channel = new BroadcastChannel('teacher-students-sync');
                    channel.postMessage({
                        type: 'student.deleted',
                        student: payload.student
                    });
                    channel.close();
                } else if (studentId && window.BroadcastChannel) {
                    var fallbackChannel = new BroadcastChannel('teacher-students-sync');
                    fallbackChannel.postMessage({
                        type: 'student.deleted',
                        student: { id: studentId }
                    });
                    fallbackChannel.close();
                }

                if (totalNode && payload && payload.meta && typeof payload.meta.total !== 'undefined') {
                    totalNode.textContent = payload.meta.total;
                }

                return;
            }

            if (xhr.status === 422) {
                return;
            }

            if (row) {
                row.classList.add('table-danger');
                setTimeout(function () {
                    row.classList.remove('table-danger');
                }, 1200);
            }
        };

        xhr.onerror = function () {
            submitButton.disabled = submitWasDisabled;
        };

        xhr.send(formData);
    }, true);

    window.addEventListener('teacher-students:updated', function (e) { if (e && e.detail && e.detail.student) updateStudentRow(e.detail.student); });

    if (window.BroadcastChannel) {
        var ch = new BroadcastChannel('teacher-students-sync');
        ch.onmessage = function (me) {
            var d = me && me.data ? me.data : null; if (!d) return;
            if (d.type === 'student.created' || d.type === 'student.updated') updateStudentRow(d.student);
            if (d.type === 'student.deleted') { removeStudentRow(d.student && d.student.id); }
        };
    }

    syncFromServer();
    setInterval(syncFromServer, 5000);
});
</script>
@endsection
