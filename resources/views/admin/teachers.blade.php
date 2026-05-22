@extends('format.layout')

@section('title', 'Admin Teachers')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
        <span class="badge rounded-pill text-bg-light border mb-2 px-3 py-2">Administrator</span>
        <h1 class="mb-0">Teachers</h1>
        <small class="text-muted">Total records: <span data-admin-teachers-total>{{ $teachers->total() }}</span></small>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary"><i class="bi bi-speedometer2 me-1"></i> Dashboard</a>
        <a href="{{ route('admin.teachers.create') }}" class="btn btn-primary"><i class="bi bi-person-plus-fill me-1"></i> Add Teacher</a>
    </div>
</div>

<div class="card border-0">
    <div class="card-body p-0">
        <div class="table-responsive" id="adminTeachersContainer">
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
                    @forelse($teachers as $teacher)
                    <tr data-admin-teacher-row="{{ $teacher->id }}">
                        <td class="ps-3 fw-semibold">{{ $teachers->firstItem() + $loop->index }}</td>
                        <td data-admin-teacher-name>{{ $teacher->fname }} {{ $teacher->mname ? $teacher->mname . ' ' : '' }}{{ $teacher->lname }}</td>
                        <td data-admin-teacher-username>{{ $teacher->userAccount->username }}</td>
                        <td data-admin-teacher-email>{{ $teacher->userAccount->email }}</td>
                        <td data-admin-teacher-status>{{ $teacher->userAccount->is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="text-center text-nowrap">
                            <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="btn btn-warning btn-sm px-3">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <div class="d-inline-block" data-js-form data-action="{{ route('admin.teachers.delete', $teacher->id) }}" data-reload-on-success="false" data-remove-closest="tr" data-empty-text="No teachers available" data-empty-colspan="6">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm px-3" data-js-submit data-confirm="Are you sure you want to delete this teacher?">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">No teachers available</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@if($teachers->lastPage() > 1)
<div class="mt-3 d-flex justify-content-end">
    <ul class="pagination pagination-sm mb-0 shadow-sm">
        <li class="page-item @if($teachers->onFirstPage()) disabled @endif">
            <a class="page-link" href="{{ $teachers->onFirstPage() ? '#' : $teachers->previousPageUrl() }}" aria-label="Previous">&lsaquo;</a>
        </li>

        @for($page = 1; $page <= $teachers->lastPage(); $page++)
            <li class="page-item @if($teachers->currentPage() === $page) active @endif">
                <a class="page-link" href="{{ $teachers->url($page) }}">{{ $page }}</a>
            </li>
        @endfor

        <li class="page-item @if(!$teachers->hasMorePages()) disabled @endif">
            <a class="page-link" href="{{ $teachers->hasMorePages() ? $teachers->nextPageUrl() : '#' }}" aria-label="Next">&rsaquo;</a>
        </li>
    </ul>
</div>
@endif

<script>
document.addEventListener('DOMContentLoaded', function () {
    var totalNode = document.querySelector('[data-admin-teachers-total]');
    var tableBody = document.querySelector('#adminTeachersContainer tbody');

    function updateTeacherRow(teacher) {
        if (!teacher || !teacher.id) return;

        var row = document.querySelector('[data-admin-teacher-row="' + teacher.id + '"]');

        // If not found, create a new row at the top
        if (!row) {
            if (!tableBody) return;

            var tr = document.createElement('tr');
            tr.setAttribute('data-admin-teacher-row', teacher.id);

            var index = 1;
            var editUrl = '/admin/teachers/' + teacher.id + '/edit';
            var deleteUrl = '/admin/teachers/' + teacher.id;
            var csrf = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';

            tr.innerHTML = '\n                <td class="ps-3 fw-semibold">' + index + '</td>\n                <td data-admin-teacher-name>' + (teacher.full_name || '-') + '</td>\n                <td data-admin-teacher-username>' + (teacher.username || '-') + '</td>\n                <td data-admin-teacher-email>' + (teacher.email || '-') + '</td>\n                <td data-admin-teacher-status>' + (teacher.status || '-') + '</td>\n                <td class="text-center text-nowrap">\n                    <a href="' + editUrl + '" class="btn btn-warning btn-sm px-3">\n                        <i class="bi bi-pencil-square"></i>\n                    </a>\n                    <div class="d-inline-block" data-js-form data-action="' + deleteUrl + '" data-reload-on-success="false" data-remove-closest="tr" data-empty-text="No teachers available" data-empty-colspan="6">\n                        <input type="hidden" name="_token" value="' + csrf + '">\n                        <input type="hidden" name="_method" value="DELETE">\n                        <button type="button" class="btn btn-danger btn-sm px-3" data-js-submit data-confirm="Are you sure you want to delete this teacher?">\n                            <i class="bi bi-trash-fill"></i>\n                        </button>\n                    </div>\n                </td>';

            var first = tableBody.querySelector('tr');
            if (first) tableBody.insertBefore(tr, first); else tableBody.appendChild(tr);

            row = tr;
        }

        var nameCell = row.querySelector('[data-admin-teacher-name]');
        var usernameCell = row.querySelector('[data-admin-teacher-username]');
        var emailCell = row.querySelector('[data-admin-teacher-email]');
        var statusCell = row.querySelector('[data-admin-teacher-status]');

        if (nameCell && typeof teacher.full_name === 'string') nameCell.textContent = teacher.full_name;
        if (usernameCell && typeof teacher.username === 'string') usernameCell.textContent = teacher.username || '-';
        if (emailCell && typeof teacher.email === 'string') emailCell.textContent = teacher.email || '-';
        if (statusCell && typeof teacher.status === 'string') statusCell.textContent = teacher.status;
    }

    function ensureEmptyState() {
        if (!tableBody) return;

        var dataRows = tableBody.querySelectorAll('tr[data-admin-teacher-row]');
        var emptyRow = tableBody.querySelector('[data-admin-teachers-empty-row]');

        if (dataRows.length === 0 && !emptyRow) {
            var row = document.createElement('tr');
            row.setAttribute('data-admin-teachers-empty-row', 'true');
            row.innerHTML = '<td colspan="6" class="text-center py-4">No teachers available</td>';
            tableBody.appendChild(row);
        }

        if (dataRows.length > 0 && emptyRow) emptyRow.remove();
    }

    function syncFromServer() {
        var separator = window.location.href.indexOf('?') === -1 ? '?' : '&';
        var url = window.location.href.replace(/\/admin\/teachers.*/,'/admin/teachers') + separator + '_sync=' + Date.now();

        fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            credentials: 'same-origin'
        })
        .then(function (res) {
            if (!res.ok) throw new Error('sync failed');
            return res.json();
        })
        .then(function (payload) {
            if (!payload || !Array.isArray(payload.teachers)) return;

            var idsOnServer = {};
            payload.teachers.forEach(function (t) {
                if (t && t.id) idsOnServer[String(t.id)] = true;
            });

            if (tableBody) {
                var existingRows = tableBody.querySelectorAll('tr[data-admin-teacher-row]');
                existingRows.forEach(function (row) {
                    var rowId = row.getAttribute('data-admin-teacher-row');
                    if (!idsOnServer[rowId]) row.remove();
                });
            }

            payload.teachers.forEach(updateTeacherRow);
            ensureEmptyState();

            if (totalNode && payload.meta && typeof payload.meta.total !== 'undefined') {
                totalNode.textContent = payload.meta.total;
            }
        }).catch(function () {});
    }

    window.addEventListener('admin-teachers:updated', function (e) {
        if (e && e.detail && e.detail.teacher) updateTeacherRow(e.detail.teacher);
    });

    if (window.BroadcastChannel) {
        var ch = new BroadcastChannel('admin-teachers-sync');
        ch.onmessage = function (me) {
            var d = me && me.data ? me.data : null;
            if (!d) return;
            if (d.type === 'teacher.updated') updateTeacherRow(d.teacher);
            if (d.type === 'teacher.deleted') {
                var r = document.querySelector('[data-admin-teacher-row="' + (d.teacher && d.teacher.id) + '"]');
                if (r) r.remove();
                ensureEmptyState();
            }
        };
    }

    syncFromServer();
    setInterval(syncFromServer, 5000);
});
</script>
@endsection