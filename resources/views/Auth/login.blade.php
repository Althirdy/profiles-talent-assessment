<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Profiles System</title>
    <!-- Bootstrap 5 CDN for Maximum Setup Speed -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>

<body>

    <div class="card login-card p-4">
        <div class="card-body">
            <h3 class="text-center mb-2 fw-bold text-primary">Profiles</h3>
            <!-- <p class="text-center text-muted mb-4">Sign in to manage records</p> -->

            <!-- Display Success Message (e.g., from successful registration) -->
            @if(session('success'))
            <div class="alert alert-success py-2 text-center small mb-3">
                {{ session('success') }}
            </div>
            @endif

            <!-- Form Handling: Uses POST method for sensitive data submission -->
            <form action="{{ route('login.post') }}" method="POST" class="needs-validation" novalidate>
                <!-- Core Security Measure: CSRF Token Protection -->
                @csrf

                <!-- Email Input Field -->
                <div class="mb-3">
                    <label for="email" class="form-label small fw-semibold text-secondary">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required>
                    <div class="invalid-feedback">
                        @error('email')
                            {{ $message }}
                        @else
                            Please enter a valid email address.
                        @enderror
                    </div>
                </div>

                <!-- Password Input Field -->
                <div class="mb-4">
                    <label for="password" class="form-label small fw-semibold text-secondary">Password</label>
                    <input type="password" name="password" id="password" class="form-control @if($errors->has('password') || $errors->has('error')) is-invalid @endif" required>
                    <div class="invalid-feedback">
                        @error('password')
                            {{ $message }}
                        @else
                            @error('error')
                                {{ $message }}
                            @else
                                Please enter your password.
                            @enderror
                        @enderror
                    </div>
                </div>

                <!-- Action Button -->
                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold mb-3">Sign In</button>
            </form>
            <div class="text-center small">
                <span class="text-muted">Don't have an account?</span>
                <a href="{{ route('register') }}" class="fw-semibold text-decoration-none">Create one</a>
            </div>
        </div>
    </div>

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
