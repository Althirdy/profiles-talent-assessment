<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Employee Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }

        .navbar-custom {
            background-color: #0d6efd;
        }

        .dashboard-container {
            margin-top: 30px;
            margin-bottom: 50px;
        }

        .table-card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-dark navbar-custom shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Profiles Inc. Portal</a>
            <div class="d-flex align-items-center">
                <span class="text-white me-3 small fw-semibold">Welcome, {{ session('user_name') }}</span>

                <form action="{{ route('logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-light fw-bold text-primary">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container dashboard-container">
        <div class="row mb-4 align-items-center">
            <div class="col-md-8">
                <h2 class="fw-bold text-dark mb-1">Employee Directory</h2>
                @if (session('user_role') === 'admin')

                    <p class="text-muted small mb-0">Securely create, read, update, and delete company directory listings</p>
                @endif
            </div>
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                @if (session('user_role') === 'admin')
                <button type="button" class="btn btn-primary fw-bold px-4" data-bs-toggle="modal" data-bs-target="#createRecordModal">
                    + Add New Employee
                </button>
                @endif
            </div>
        </div>

        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        @if($errors->has('record') || $errors->has('auth'))
        <div class="alert alert-danger border-0 shadow-sm mb-4">
            {{ $errors->first('record') ?: $errors->first('auth') }}
        </div>
        @endif

        <div class="card table-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4 py-3 text-secondary text-uppercase small" style="width: 10%">ID</th>
                                <th class="py-3 text-secondary text-uppercase small" style="width: 30%">Full Name</th>
                                <th class="py-3 text-secondary text-uppercase small" style="width: 25%">Position</th>
                                <th class="py-3 text-secondary text-uppercase small" style="width: 20%">Email</th>
                                @if (session('user_role') === 'admin')
                                <th class="pe-4 py-3 text-end text-secondary text-uppercase small" style="width: 15%">Actions</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($records as $record)
                            <tr>
                                <td class="ps-4 fw-bold text-secondary">#{{ $record->id }}</td>
                                <td class="fw-semibold text-dark">{{ $record->full_name }}</td>
                                <td><span class="badge bg-light text-primary border border-primary-subtle px-2.5 py-1.5">{{ $record->position }}</span></td>
                                <td class="text-muted small">{{ $record->email }}</td>
                                @if (session('user_role') === 'admin')
                                <td class="pe-4 text-end">
                                    <div class="d-inline-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary fw-semibold px-2.5" data-bs-toggle="modal" data-bs-target="#editRecordModal{{ $record->id }}">Edit</button>

                                        <form action="{{ route('employees.destroy', $record->id) }}" method="POST" class="m-0" onsubmit="return confirm('Are you sure you want to permanently delete this record?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger fw-semibold px-2.5">Delete</button>
                                        </form>
                                    </div>
                                </td>
                                @endif
                            </tr>
                            @if(session('user_role') === 'admin')
                            <div class="modal fade" id="editRecordModal{{ $record->id }}" tabindex="-1" aria-labelledby="editRecordModalLabel{{ $record->id }}" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                                        <div class="modal-header bg-light border-0 py-3">
                                            <h5 class="modal-title fw-bold text-dark" id="editRecordModalLabel{{ $record->id }}">Edit Employee Record</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body p-4">
                                            <form action="{{ route('employees.update', $record->id) }}" method="POST" class="needs-validation" novalidate>
                                                @csrf
                                                @method('PUT')

                                                <div class="mb-3">
                                                    <label for="employee_name_{{ $record->id }}" class="form-label small fw-semibold text-secondary">Full Name</label>
                                                    <input type="text" name="employee_name" id="employee_name_{{ $record->id }}" class="form-control @error('employee_name') is-invalid @enderror" value="{{ old('employee_name', $record->full_name) }}" required maxlength="255">
                                                    <div class="invalid-feedback">
                                                        @error('employee_name')
                                                            {{ $message }}
                                                        @else
                                                            Please enter the employee full name.
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label for="position_{{ $record->id }}" class="form-label small fw-semibold text-secondary">Position</label>
                                                    <input type="text" name="position" id="position_{{ $record->id }}" class="form-control @error('position') is-invalid @enderror" value="{{ old('position', $record->position) }}" required maxlength="255">
                                                    <div class="invalid-feedback">
                                                        @error('position')
                                                            {{ $message }}
                                                        @else
                                                            Please enter the employee position.
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="mb-4">
                                                    <label for="email_{{ $record->id }}" class="form-label small fw-semibold text-secondary">Email Address</label>
                                                    <input type="email" name="email" id="email_{{ $record->id }}" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $record->email) }}" required maxlength="255">
                                                    <div class="invalid-feedback">
                                                        @error('email')
                                                            {{ $message }}
                                                        @else
                                                            Please enter a valid email address.
                                                        @enderror
                                                    </div>
                                                </div>

                                                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Save Changes</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5 text-muted">
                                    <p class="mb-0 small fw-medium">No records found inside the database directory. Click "+ Add New Employee" to insert one.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @if(session('user_role') === 'admin')
    <div class="modal fade" id="createRecordModal" tabindex="-1" aria-labelledby="createRecordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                <div class="modal-header bg-light border-0 py-3">
                    <h5 class="modal-title fw-bold text-dark" id="createRecordModalLabel">Add New Employee Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="small text-muted mb-3">Please fill out all directory form fields accurately.</p>
                    <form action="{{ route('employees.store') }}" method="POST" class="needs-validation" novalidate>
                        @csrf

                        <div class="mb-3">
                            <label for="employee_name" class="form-label small fw-semibold text-secondary">Full Name</label>
                            <input type="text" name="employee_name" id="employee_name" class="form-control @error('employee_name') is-invalid @enderror" value="{{ old('employee_name') }}" required maxlength="255">
                            <div class="invalid-feedback">
                                @error('employee_name')
                                    {{ $message }}
                                @else
                                    Please enter the employee full name.
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="position" class="form-label small fw-semibold text-secondary">Position</label>
                            <input type="text" name="position" id="position" class="form-control @error('position') is-invalid @enderror" value="{{ old('position') }}" required maxlength="255">
                            <div class="invalid-feedback">
                                @error('position')
                                    {{ $message }}
                                @else
                                    Please enter the employee position.
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="form-label small fw-semibold text-secondary">Email Address</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required maxlength="255">
                            <div class="invalid-feedback">
                                @error('email')
                                    {{ $message }}
                                @else
                                    Please enter a valid email address.
                                @enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Create Employee</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.needs-validation').forEach(function(form) {
            form.querySelectorAll('input').forEach(function(input) {
                input.addEventListener('input', function() {
                    input.classList.toggle('is-invalid', !input.checkValidity());
                    input.classList.toggle('is-valid', input.checkValidity());
                });
            });

            form.addEventListener('submit', function(event) {
                form.querySelectorAll('input').forEach(function(input) {
                    input.classList.toggle('is-invalid', !input.checkValidity());
                    input.classList.toggle('is-valid', input.checkValidity());
                });

                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                form.classList.add('was-validated');
            });
        });
    </script>
</body>

</html>
