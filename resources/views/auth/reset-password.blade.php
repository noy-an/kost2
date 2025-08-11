<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password | DeskApp</title>

    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('backend/vendors/images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('backend/vendors/images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('backend/vendors/images/favicon-16x16.png') }}">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f9fafb;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            color: #4b5563;
        }

        .auth-card {
            background-color: #ffffff;
            border-radius: 1rem;
            padding: 2rem;
            width: 100%;
            max-width: 400px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.04);
        }

        .auth-title {
            font-weight: 700;
            font-size: 1.5rem;
            text-align: center;
            margin-bottom: 0.25rem;
            color: #3b82f6;
        }

        .auth-subtitle {
            font-size: 0.9rem;
            text-align: center;
            color: #6b7280;
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 500;
            color: #374151;
            font-size: 0.9rem;
        }

        .input-group-text {
            background-color: #f9fafb;
            border-color: #d1d5db;
        }

        .password-toggle {
            cursor: pointer;
            padding: 0 0.75rem;
            color: #6b7280;
        }

        .form-control {
            border-radius: 0.375rem;
            background-color: #fff;
            border: 1px solid #d1d5db;
            padding: 0.65rem 0.75rem;
            font-size: 0.9rem;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
        }

        .btn-primary {
            background-color: #3b82f6;
            border-color: #3b82f6;
            padding: 0.65rem;
            font-weight: 600;
            font-size: 0.95rem;
            border-radius: 0.5rem;
        }

        .btn-primary:hover {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        .error-feedback {
            color: #ef4444;
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        .form-control.is-invalid:focus {
            border-color: #ef4444;
            box-shadow: none;
        }

        .text-center a {
            color: #3b82f6;
            font-weight: 500;
            font-size: 0.9rem;
            text-decoration: none;
        }

        .text-center a:hover {
            text-decoration: underline;
        }

        .btn i {
            margin-right: 0.5rem;
        }
    </style>
</head>

<body>
    <div class="auth-card">
        <h1 class="auth-title">Reset Password</h1>
        <p class="auth-subtitle">Enter your new password below to reset your account.</p>

      <form action="{{ route('password.update') }}" method="POST">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <input type="hidden" name="phone" value="{{ $phone }}">

    <div class="mb-3">
        <label for="password" class="form-label">Password Baru</label>
        <input type="password" class="form-control" name="password" required>
    </div>

    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
        <input type="password" class="form-control" name="password_confirmation" required>
    </div>

    <button type="submit" class="btn btn-primary w-100">Reset Password</button>
</form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const toggleIcon = field.nextElementSibling.querySelector('i');
            if (field.type === 'password') {
                field.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>
