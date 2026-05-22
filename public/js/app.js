$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function serializeJsForm(container) {
        var formData = new FormData();
        var fields = container.querySelectorAll('input, select, textarea');

        fields.forEach(function(field) {
            if (!field.name || field.disabled) {
                return;
            }

            var type = (field.type || '').toLowerCase();

            if (type === 'checkbox') {
                if (field.checked) {
                    formData.append(field.name, field.value || 'on');
                }

                return;
            }

            if (type === 'radio') {
                if (field.checked) {
                    formData.append(field.name, field.value);
                }

                return;
            }

            if (type === 'file') {
                Array.prototype.forEach.call(field.files || [], function(file) {
                    formData.append(field.name, file);
                });

                return;
            }

            formData.append(field.name, field.value);
        });

        return formData;
    }

    function submitContainerNatively(container) {
        var action = container.dataset.action || window.location.href;
        var method = (container.dataset.method || 'POST').toUpperCase();
        var formData = serializeJsForm(container);
        var nativeForm = document.createElement('form');

        nativeForm.method = method === 'GET' ? 'GET' : 'POST';
        nativeForm.action = action;
        nativeForm.style.display = 'none';

        formData.forEach(function(value, key) {
            if (value instanceof File) {
                return;
            }

            var input = document.createElement('input');
            input.type = 'hidden';
            input.name = key;
            input.value = value;
            nativeForm.appendChild(input);
        });

        if (method !== 'GET' && method !== 'POST') {
            var methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = method;
            nativeForm.appendChild(methodInput);
        }

        document.body.appendChild(nativeForm);
        nativeForm.submit();
    }

    function applyNoReloadSuccessState(container) {
        var removeClosestSelector = container.dataset.removeClosest;
        var successMessage = container.dataset.successMessage;

        if (successMessage) {
            var existingAlert = container.querySelector('[data-js-success-alert]');

            if (existingAlert) {
                existingAlert.remove();
            }

            var alertElement = document.createElement('div');

            alertElement.className = 'alert alert-success alert-dismissible fade show';
            alertElement.setAttribute('role', 'alert');
            alertElement.setAttribute('data-js-success-alert', 'true');
            alertElement.innerHTML = successMessage + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            container.prepend(alertElement);
        }

        if (removeClosestSelector) {
            var removableElement = container.closest(removeClosestSelector);

            if (removableElement) {
                var parentElement = removableElement.parentElement;

                removableElement.remove();

                if (
                    parentElement &&
                    parentElement.tagName === 'TBODY' &&
                    parentElement.children.length === 0
                ) {
                    var emptyRow = document.createElement('tr');
                    var emptyCell = document.createElement('td');

                    emptyCell.colSpan = parseInt(container.dataset.emptyColspan || '1', 10);
                    emptyCell.className = 'text-center py-4';
                    emptyCell.textContent = container.dataset.emptyText || 'No records available';

                    emptyRow.appendChild(emptyCell);
                    parentElement.appendChild(emptyRow);
                }
            }
        }

        container.dispatchEvent(new CustomEvent('js-form:success', {
            bubbles: true,
            detail: {
                action: container.dataset.action || window.location.href
            }
        }));
    }

    function getTeacherFullName(teacher) {
        if (!teacher) {
            return '';
        }

        return teacher.full_name || teacher.name || '';
    }

    function updateAdminTeacherRow(teacher) {
        if (!teacher || !teacher.id) {
            return;
        }

        var row = document.querySelector('[data-admin-teacher-row="' + teacher.id + '"]');

        if (!row) {
            return;
        }

        var nameCell = row.querySelector('[data-admin-teacher-name]');
        var usernameCell = row.querySelector('[data-admin-teacher-username]');
        var emailCell = row.querySelector('[data-admin-teacher-email]');
        var statusCell = row.querySelector('[data-admin-teacher-status]');

        if (nameCell) {
            nameCell.textContent = getTeacherFullName(teacher);
        }

        if (usernameCell && teacher.username !== undefined && teacher.username !== null) {
            usernameCell.textContent = teacher.username;
        }

        if (emailCell && teacher.email !== undefined && teacher.email !== null) {
            emailCell.textContent = teacher.email;
        }

        if (statusCell && teacher.status !== undefined && teacher.status !== null) {
            statusCell.textContent = teacher.status;
        }
    }

    function setupAdminTeacherSync() {
        if (window.BroadcastChannel) {
            var channel = new BroadcastChannel('admin-teachers-sync');

            channel.onmessage = function(event) {
                if (!event || !event.data || event.data.type !== 'teacher.updated') {
                    return;
                }

                updateAdminTeacherRow(event.data.teacher);
            };
        }

        window.addEventListener('admin-teachers:updated', function(event) {
            if (!event || !event.detail || !event.detail.teacher) {
                return;
            }

            updateAdminTeacherRow(event.detail.teacher);
        });
    }

    function sendJsForm(container, submitButton) {
        var action = container.dataset.action || window.location.href;
        var formData = serializeJsForm(container);
        var noReloadOnSuccess = container.dataset.reloadOnSuccess === 'false';
        var headers = {};

        if (noReloadOnSuccess) {
            headers['Accept'] = 'application/json';
            headers['X-Requested-With'] = 'XMLHttpRequest';
        }

        if (container.dataset.method && container.dataset.method.toUpperCase() !== 'POST') {
            formData.set('_method', container.dataset.method.toUpperCase());
        }

        if (submitButton) {
            submitButton.disabled = true;
        }

        return fetch(action, {
            method: 'POST',
            body: formData,
            headers: headers,
            credentials: 'same-origin'
        })
            .then(function(response) {
                if (response.redirected && response.url) {
                    if (noReloadOnSuccess) {
                        applyNoReloadSuccessState(container);
                        return;
                    }

                    window.location.href = response.url;
                    return;
                }

                if (response.ok) {
                    if (noReloadOnSuccess) {
                        applyNoReloadSuccessState(container);
                        return;
                    }

                    window.location.reload();
                    return;
                }

                submitContainerNatively(container);
            })
            .catch(function() {
                submitContainerNatively(container);
            })
            .finally(function() {
                if (submitButton) {
                    submitButton.disabled = false;
                }
            });
    }

    $(document).on('click', '[data-js-submit]', function(e) {
        var button = this;
        var container = button.closest('[data-js-form]');

        if (!container) {
            return;
        }

        if (button.dataset.confirm && !window.confirm(button.dataset.confirm)) {
            return;
        }

        e.preventDefault();

        if (container.dataset.nativeSubmit === 'true') {
            submitContainerNatively(container);
            return;
        }

        var buttons = container.querySelectorAll('[data-js-submit]');

        buttons.forEach(function(item) {
            item.disabled = true;
        });

        sendJsForm(container, button).finally(function() {
            buttons.forEach(function(item) {
                item.disabled = false;
            });
        });
    });

    $(document).on('keydown', '[data-js-form] input', function(e) {
        if (e.key !== 'Enter') {
            return;
        }

        var container = this.closest('[data-js-form]');

        if (!container) {
            return;
        }

        var submitButton = container.querySelector('[data-js-submit]');

        if (!submitButton || submitButton.disabled) {
            return;
        }

        e.preventDefault();
        submitButton.click();
    });

    $(document).on('submit', 'form', function(e) {
        var form = this;

        if (form.id === 'demoForm' || form.dataset.nativeSubmit === 'true') {
            return;
        }

        e.preventDefault();

        var submitButton = form.querySelector('[type="submit"]');
        var formData = new FormData(form);

        if (submitButton) {
            submitButton.disabled = true;
        }

        fetch(form.action || window.location.href, {
            method: 'POST',
            body: formData,
            credentials: 'same-origin'
        })
            .then(function(response) {
                if (response.redirected && response.url) {
                    window.location.href = response.url;
                    return;
                }

                if (response.ok) {
                    window.location.reload();
                    return;
                }

                window.location.reload();
            })
            .catch(function() {
                form.submit();
            })
            .finally(function() {
                if (submitButton) {
                    submitButton.disabled = false;
                }
            });
    });

    // Example: Show an alert when the demo page is loaded
    $('#demoButton').click(function() {
        // alert('Button clicked!');
            // $('#demoText').hide();
            //  $('#demoText').fadeOut();
            $('#demoText').slideDown();
    });

    $('#demoHover').hover(function() {
        $(this).css('background-color', 'red');
        // $('#demoText').toggle();
        //  $('#demoText').fadeIn();
        //  $('#demoText').toggle();
        $('#demoText').slideUp();

    });
    
    $('#demoForm').submit(function(e) {
        e.preventDefault();
        alert('Form submitted!');
    });

    setupAdminTeacherSync();
      

    function autoreload() {
        var targets = document.querySelectorAll('[data-autoreload]');

        if (!targets || targets.length === 0) {
            return;
        }

        // Fetch the current page once and update all matching containers by id
        $.get(window.location.href, function(data) {
            var parsedDocument = new DOMParser().parseFromString(data, 'text/html');

            targets.forEach(function(target) {
                var id = target.id;

                if (!id) {
                    return;
                }

                var updated = parsedDocument.querySelector('#' + id);

                if (updated) {
                    target.innerHTML = updated.innerHTML;
                }
            });
        });
    }

    autoreload();
    setInterval(autoreload, 3000);



});
