@extends('format.layout')

@section('title', 'Admin Students')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <span class="badge rounded-pill text-bg-light border mb-2 px-3 py-2">Administrator</span>
        <h1 class="mb-0">Students</h1>
        <small class="text-muted">Total records: <span data-admin-students-total>{{ $students->total() }}</span></small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a>
        <a href="{{ route('admin.students.create') }}" class="btn btn-primary"><i class="bi bi-person-plus-fill me-1"></i> Add Student</a>
    </div>
</div>

<div class="card border-0">
    <div class="card-body p-0">
        <div class="table-responsive" id="adminStudentsContainer">
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
                    <tr data-admin-student-row="{{ $student->id }}">
                        <td class="ps-3 fw-semibold">{{ $students->firstItem() + $loop->index }}</td>
                        <td data-admin-student-name>{{ trim("{$student->fname} {$student->mname} {$student->lname}") }}</td>
                        <td data-admin-student-email>{{ $student->userAccount?->email ?? '-' }}</td>
                        <td data-admin-student-degree>{{ $student->degree?->name ?? '-' }}</td>
                        <td data-admin-student-contact>{{ $student->contactInfo }}</td>
                        <td class="text-center text-nowrap">
                            <a href="{{ route('admin.students.edit', $student->id) }}" class="btn btn-warning btn-sm px-3">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <div class="d-inline-block" data-js-form data-action="{{ route('admin.students.delete', $student->id) }}" data-reload-on-success="false" data-remove-closest="tr" data-empty-text="No students available" data-empty-colspan="6">
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
document.addEventListener('DOMContentLoaded', function () {
    var totalCountNode = document.querySelector('[data-admin-students-total]');
    var tableBody = document.querySelector('#adminStudentsContainer tbody');

    function updateStudentRow(student) {
        if (!student || !student.id) {
            return;
        }

        var row = document.querySelector('[data-admin-student-row="' + student.id + '"]');

        // If row doesn't exist, create it and insert at the top
        if (!row) {
            if (!tableBody) return;

            var tr = document.createElement('tr');
            tr.setAttribute('data-admin-student-row', student.id);

            var index = 1; // new item appears at top

            var editUrl = '/admin/students/' + student.id + '/edit';
            var deleteUrl = '/admin/students/' + student.id;
            var csrf = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

            tr.innerHTML = '\n                <td class="ps-3 fw-semibold">' + index + '</td>\n                <td data-admin-student-name>' + (student.full_name || '-') + '</td>\n                <td data-admin-student-email>' + (student.email || '-') + '</td>\n                <td data-admin-student-degree>' + (student.degree || '-') + '</td>\n                <td data-admin-student-contact>' + (student.contactInfo || '') + '</td>\n                <td class="text-center text-nowrap">\n                    <a href="' + editUrl + '" class="btn btn-warning btn-sm px-3">\n                        <i class="bi bi-pencil-square"></i>\n                    </a>\n                    <div class="d-inline-block" data-js-form data-action="' + deleteUrl + '" data-reload-on-success="false" data-remove-closest="tr" data-empty-text="No students available" data-empty-colspan="6">\n                        <input type="hidden" name="_token" value="' + csrf + '">\n                        <input type="hidden" name="_method" value="DELETE">\n                        <button type="button" class="btn btn-danger btn-sm px-3" data-js-submit data-confirm="Are you sure you want to delete this student?">\n                            <i class="bi bi-trash-fill"></i>\n                        </button>\n                    </div>\n                </td>';

            // insert as first row
            var firstDataRow = tableBody.querySelector('tr');
            if (firstDataRow) {
                tableBody.insertBefore(tr, firstDataRow);
            } else {
                tableBody.appendChild(tr);
            }

            row = tr;
        }

        var nameCell = row.querySelector('[data-admin-student-name]');
        var emailCell = row.querySelector('[data-admin-student-email]');
        var degreeCell = row.querySelector('[data-admin-student-degree]');
        var contactCell = row.querySelector('[data-admin-student-contact]');

        if (nameCell && typeof student.full_name === 'string') {
            nameCell.textContent = student.full_name;
        }

        if (emailCell && typeof student.email === 'string') {
            emailCell.textContent = student.email || '-';
        }

        if (degreeCell) {
            degreeCell.textContent = (typeof student.degree === 'string' && student.degree.length > 0) ? student.degree : '-';
        }

        if (contactCell && typeof student.contactInfo === 'string') {
            contactCell.textContent = student.contactInfo;
        }
    }

    function ensureEmptyState() {
        if (!tableBody) {
            return;
        }

        var dataRows = tableBody.querySelectorAll('tr[data-admin-student-row]');
        var emptyRow = tableBody.querySelector('[data-admin-students-empty-row]');

        if (dataRows.length === 0 && !emptyRow) {
            var row = document.createElement('tr');
            row.setAttribute('data-admin-students-empty-row', 'true');
            row.innerHTML = '<td colspan="6" class="text-center py-4">No students available</td>';
            tableBody.appendChild(row);
        }

        if (dataRows.length > 0 && emptyRow) {
            emptyRow.remove();
        }
    }

    function syncFromServer() {
        var separator = window.location.href.indexOf('?') === -1 ? '?' : '&';
        var url = window.location.href + separator + '_sync=' + Date.now();

        fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('Unable to sync student list.');
                }

                return response.json();
            })
            .then(function (payload) {
                if (!payload || !Array.isArray(payload.students)) {
                    return;
                }

                var idsOnServer = {};

                payload.students.forEach(function (student) {
                    if (student && student.id) {
                        idsOnServer[String(student.id)] = true;
                    }
                });

                if (tableBody) {
                    var existingRows = tableBody.querySelectorAll('tr[data-admin-student-row]');

                    existingRows.forEach(function (row) {
                        var rowId = row.getAttribute('data-admin-student-row');

                        if (!idsOnServer[rowId]) {
                            row.remove();
                        }
                    });
                }

                payload.students.forEach(updateStudentRow);
                ensureEmptyState();

                if (totalCountNode && payload.meta && typeof payload.meta.total !== 'undefined') {
                    totalCountNode.textContent = payload.meta.total;
                }
            })
            .catch(function () {
                // Silent fail: next polling cycle will retry.
            });
    }

    window.addEventListener('admin-students:updated', function (event) {
        if (!event || !event.detail) {
            return;
        }

        updateStudentRow(event.detail.student);
    });

    if (window.BroadcastChannel) {
        var channel = new BroadcastChannel('admin-students-sync');

        channel.onmessage = function (messageEvent) {
            var data = messageEvent ? messageEvent.data : null;

            if (!data || !data.type) return;

            if (data.type === 'student.updated' || data.type === 'student.created') {
                updateStudentRow(data.student);
            }

            if (data.type === 'student.deleted') {
                var row = document.querySelector('[data-admin-student-row="' + (data.student && data.student.id) + '"]');
                if (row) row.remove();
                ensureEmptyState();
            }
        };
    }

    syncFromServer();
    setInterval(syncFromServer, 5000);
});
</script>

@endsection