<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Profiles System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 12px;
        }

        .register-card {
            width: 100%;
            max-width: 460px;
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>

<body>
    <div class="card register-card p-4">
        <div class="card-body">
            <h3 class="text-center mb-2 fw-bold text-primary">Create Account</h3>
            <p class="text-center text-muted small mb-4">Register to access the Profiles dashboard</p>

            @if($errors->any())
            <div class="alert alert-danger py-2 small mb-3">
                <ul class="mb-0 ps-3">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST" class="needs-validation" novalidate>
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label small fw-semibold text-secondary">Full Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required maxlength="255" placeholder="Jane Doe">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label small fw-semibold text-secondary">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required maxlength="255" placeholder="name@company.com">
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label small fw-semibold text-secondary">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required minlength="8" placeholder="Minimum 8 characters">
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label small fw-semibold text-secondary">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required minlength="8" placeholder="Repeat your password">
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mb-3">Create Account</button>
            </form>

            <div class="text-center small">
                <span class="text-muted">Already have an account?</span>
                <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">Sign in</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.needs-validation').on('submit', function(event) {
                if (!this.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }

                $(this).addClass('was-validated');
            });
        });
    </script>
</body>

</html>
