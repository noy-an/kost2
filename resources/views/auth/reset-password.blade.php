<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Basic Page Info -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password | DeskApp</title>

    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('backend/vendors/images/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('backend/vendors/images/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('backend/vendors/images/favicon-16x16.png') }}">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #f8f9fc;
            --accent-color: #2e59d9;
            --text-color: #5a5c69;
            --light-color: #ffffff;
            --shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--secondary-color);
            color: var(--text-color);
            height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .auth-header {
            background-color: var(--light-color);
            box-shadow: var(--shadow);
            padding: 1rem 0;
        }
        
        .auth-container {
            flex: 1;
            display: flex;
            align-items: center;
            padding: 2rem 0;
        }
        
        .auth-card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        
        .auth-card-header {
            background-color: var(--primary-color);
            color: var(--light-color);
            padding: 1.5rem;
            text-align: center;
        }
        
        .auth-card-body {
            padding: 2rem;
        }
        
        .auth-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .auth-subtitle {
            font-size: 0.9rem;
            margin-bottom: 1.5rem;
            color: var(--text-color);
        }
        
        .form-control {
            padding: 0.75rem 1rem;
            border-radius: 0.35rem;
            border: 1px solid #d1d3e2;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        
        .input-group-text {
            background-color: #eaecf4;
            border: 1px solid #d1d3e2;
            color: #6e707e;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            padding: 0.75rem;
            font-weight: 600;
        }
        
        .btn-primary:hover {
            background-color: var(--accent-color);
            border-color: var(--accent-color);
        }
        
        .auth-image {
            max-width: 100%;
            height: auto;
        }
        
        .password-toggle {
            cursor: pointer;
            background-color: #eaecf4;
            border: 1px solid #d1d3e2;
            border-left: none;
            display: flex;
            align-items: center;
            padding: 0 0.75rem;
        }
        
        .password-toggle:hover {
            background-color: #dddfeb;
        }
        
        .error-feedback {
            color: #e74a3b;
            font-size: 0.85rem;
            margin-top: 0.25rem;
        }
        
        .is-invalid {
            border-color: #e74a3b;
        }
        
        .is-invalid:focus {
            box-shadow: 0 0 0 0.2rem rgba(231, 74, 59, 0.25);
        }
        
        @media (max-width: 991.98px) {
            .auth-image-container {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="auth-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="brand-logo">
                    <a href="{{ route('login') }}" class="d-flex align-items-center text-decoration-none">
                        <img src="{{ asset('backend/vendors/images/deskapp-logo.svg') }}" alt="Logo" height="40">
                    </a>
                </div>
                <nav class="login-menu">
                    <ul class="list-unstyled mb-0 d-flex align-items-center">
                        <li class="ms-3"><a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Login</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="auth-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="row g-0">
                        <!-- Image Column (hidden on mobile) -->
                        
                        
                        <!-- Form Column -->
                        <div class="col-lg-6">
                            <div class="auth-card h-100">
                                <div class="auth-card-header">
                                    <h3 class="auth-title mb-0">Reset Password</h3>
                                </div>
                                <div class="auth-card-body">
                                    <p class="auth-subtitle">Enter your email and new password to reset your account.</p>
                                    
                                    <!-- Reset Password Form -->
                                    <form action="{{ route('password.update') }}" method="POST" novalidate>
                                        @csrf
                                        <input type="hidden" name="token" value="{{ $token }}">

                                        <!-- Email Field -->
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email Address</label>
                                            <div class="input-group">
                                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                                       id="email" name="email" placeholder="Enter your email" 
                                                       value="{{ old('email') }}" required>
                                                <span class="input-group-text">
                                                    <i class="fas fa-envelope"></i>
                                                </span>
                                            </div>
                                            @error('email')
                                                <div class="error-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Password Field -->
                                        <div class="mb-3">
                                            <label for="password" class="form-label">New Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                                       id="password" name="password" placeholder="Enter new password" required>
                                                <span class="password-toggle" onclick="togglePassword('password')">
                                                    <i class="fas fa-eye"></i>
                                                </span>
                                            </div>
                                            @error('password')
                                                <div class="error-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <!-- Confirm Password Field -->
                                        <div class="mb-4">
                                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" 
                                                       id="password_confirmation" name="password_confirmation" 
                                                       placeholder="Confirm new password" required>
                                                <span class="password-toggle" onclick="togglePassword('password_confirmation')">
                                                    <i class="fas fa-eye"></i>
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Submit Button -->
                                        <div class="d-grid mb-3">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-key me-2"></i> Reset Password
                                            </button>
                                        </div>

                                        <!-- Back to Login -->
                                        <div class="text-center">
                                            <a href="{{ route('login') }}" class="text-decoration-none">
                                                <i class="fas fa-arrow-left me-1"></i> Back to Login
                                            </a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JS -->
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