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

            <form action="{{ route('register.post') }}" method="POST" class="needs-validation" novalidate>
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label small fw-semibold text-secondary">Full Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required maxlength="255">
                    <div class="invalid-feedback">
                        @error('name')
                            {{ $message }}
                        @else
                            Please enter your full name.
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
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

                <div class="mb-3">
                    <label for="password" class="form-label small fw-semibold text-secondary">Password</label>
                    <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" required minlength="8">
                    <div class="invalid-feedback">
                        @error('password')
                            {{ $message }}
                        @else
                            Password must be at least 8 characters.
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label small fw-semibold text-secondary">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required minlength="8">
                    <div class="invalid-feedback">Please confirm your password.</div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mb-3">Create Account</button>
            </form>

            <div class="text-center small">
                <span class="text-muted">Already have an account?</span>
                <a href="{{ route('login') }}" class="fw-semibold text-decoration-none">Sign in</a>
            </div>
        </div>
    </div>

    <script>
        document.querySelectorAll('.needs-validation').forEach(function(form) {
            const password = form.querySelector('#password');
            const confirmation = form.querySelector('#password_confirmation');

            function validatePasswordMatch() {
                if (!password || !confirmation) {
                    return;
                }

                if (confirmation.value && password.value !== confirmation.value) {
                    confirmation.setCustomValidity('Passwords do not match.');
                    confirmation.nextElementSibling.textContent = 'Passwords do not match.';
                } else {
                    confirmation.setCustomValidity('');
                    confirmation.nextElementSibling.textContent = 'Please confirm your password.';
                }
            }

            form.querySelectorAll('input').forEach(function(input) {
                input.addEventListener('input', function() {
                    validatePasswordMatch();
                    input.classList.toggle('is-invalid', !input.checkValidity());
                    input.classList.toggle('is-valid', input.checkValidity());
                });
            });

            form.addEventListener('submit', function(event) {
                validatePasswordMatch();

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
