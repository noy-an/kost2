<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Yua Ultima - Login</title>
  <link rel="shortcut icon" type="image/png" href="backend/assets/images/logos/favicon.png" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    :root {
      --primary-color: #3b82f6;
      --primary-dark: #1e40af;
      --secondary-color: #64748b;
      --background-color: #f8fafc;
      --card-background: #ffffff;
      --text-primary: #1e293b;
      --text-secondary: #64748b;
      --border-color: #e2e8f0;
      --border-focus: #3b82f6;
      --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
      --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
      --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: var(--background-color);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 1rem;
    }

    .login-container {
      width: 100%;
      max-width: 400px;
    }

    .login-card {
      background: var(--card-background);
      border-radius: 12px;
      padding: 2.5rem;
      box-shadow: var(--shadow-lg);
      border: 1px solid var(--border-color);
    }

    .logo-section {
      text-align: center;
      margin-bottom: 2rem;
    }

    .logo-title {
      font-size: 2rem;
      font-weight: 700;
      color: var(--text-primary);
      margin-bottom: 0.5rem;
    }

    .logo-subtitle {
      color: var(--text-secondary);
      font-size: 0.95rem;
      font-weight: 400;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-label {
      color: var(--text-primary);
      font-weight: 500;
      margin-bottom: 0.5rem;
      font-size: 0.875rem;
      display: block;
    }

    .form-control {
      width: 100%;
      padding: 0.875rem 1rem;
      border: 1px solid var(--border-color);
      border-radius: 8px;
      font-size: 0.9rem;
      transition: all 0.2s ease;
      background-color: #fff;
      color: var(--text-primary);
    }

    .form-control:focus {
      outline: none;
      border-color: var(--border-focus);
      box-shadow: 0 0 0 3px rgb(59 130 246 / 0.1);
    }

    .form-control::placeholder {
      color: var(--text-secondary);
    }

    .password-container {
      position: relative;
    }

    .password-toggle {
      position: absolute;
      right: 0.75rem;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: var(--text-secondary);
      cursor: pointer;
      padding: 0.25rem;
      transition: color 0.2s ease;
    }

    .password-toggle:hover {
      color: var(--text-primary);
    }

    .form-options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 1.5rem;
      font-size: 0.875rem;
    }

    .form-check {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .form-check-input {
      margin: 0;
    }

    .form-check-label {
      color: var(--text-secondary);
      cursor: pointer;
    }

    .forgot-password {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 500;
      transition: color 0.2s ease;
    }

    .forgot-password:hover {
      color: var(--primary-dark);
      text-decoration: underline;
    }

    .btn-primary {
      width: 100%;
      background-color: var(--primary-color);
      border: none;
      border-radius: 8px;
      padding: 0.875rem 1rem;
      font-size: 0.95rem;
      font-weight: 600;
      color: white;
      transition: all 0.2s ease;
      margin-bottom: 1.5rem;
    }

    .btn-primary:hover {
      background-color: var(--primary-dark);
      transform: translateY(-1px);
    }

    .btn-primary:active {
      transform: translateY(0);
    }

    .btn-primary.loading {
      position: relative;
      color: transparent;
    }

    .btn-primary.loading::after {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 20px;
      height: 20px;
      margin: -10px 0 0 -10px;
      border: 2px solid transparent;
      border-top: 2px solid white;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .alert {
      padding: 0.875rem 1rem;
      margin-bottom: 1.5rem;
      border: 1px solid transparent;
      border-radius: 8px;
      font-size: 0.875rem;
    }

    .alert-danger {
      color: #dc2626;
      background-color: #fef2f2;
      border-color: #fecaca;
    }

    .register-section {
      text-align: center;
      padding-top: 1.5rem;
      border-top: 1px solid var(--border-color);
    }

    .register-text {
      color: var(--text-secondary);
      font-size: 0.875rem;
      margin-bottom: 0.5rem;
    }

    .register-link {
      color: var(--primary-color);
      text-decoration: none;
      font-weight: 600;
      font-size: 0.875rem;
      transition: color 0.2s ease;
    }

    .register-link:hover {
      color: var(--primary-dark);
      text-decoration: underline;
    }

    /* Responsive Design */
    @media (max-width: 480px) {
      .login-card {
        padding: 2rem 1.5rem;
      }
      
      .logo-title {
        font-size: 1.75rem;
      }
    }

    /* Reduced motion */
    @media (prefers-reduced-motion: reduce) {
      *, *::before, *::after {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
      }
    }
  </style>
</head>

<body>
  <div class="login-container">
    <div class="login-card">
      
      <!-- Logo Section -->
      <div class="logo-section">
        <h1 class="logo-title">Yua Ultima</h1>
        <p class="logo-subtitle">Silahkan masuk ke akun Anda</p>
      </div>

      <!-- Login Form -->
      <form class="form-horizontal form-material" id="loginForm" action="{{route('login.submit')}}" method="POST">
        @csrf
        
        <!-- Error Alert -->
        @if(session('gagal'))
        <div class="alert alert-danger" id="errorAlert">
          <i class="fas fa-exclamation-circle me-2"></i>
          {{ session('gagal') }}
        </div>
        @endif

        <!-- Email Field -->
        <div class="form-group">
          <label for="email" class="form-label">Email</label>
          <input type="email" 
            class="form-control" 
            name="email" 
            id="email" 
            placeholder="Masukkan email Anda"
            value="{{ old('email') }}"
            required>
        </div>

        <!-- Password Field -->
        <div class="form-group">
          <label for="password" class="form-label">Password</label>
          <div class="password-container">
            <input type="password" 
                   class="form-control" 
                   name="password" 
                   id="password" 
                   placeholder="Masukkan password Anda"
                   required>
            <button type="button" class="password-toggle" id="passwordToggle">
              <i class="fas fa-eye"></i>
            </button>
          </div>
        </div>

        <!-- Form Options -->
        <div class="form-options">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="rememberMe" name="remember">
            <label class="form-check-label" for="rememberMe">Ingat saya</label>
          </div>
          <a href="{{ route('password.request') }}" class="forgot-password">Lupa password?</a>
        </div>

        <!-- Login Button -->
        <button type="submit" class="btn btn-primary" id="loginBtn">
          Masuk
        </button>

        <!-- Register Section -->
        <div class="register-section">
          <p class="register-text">Belum memiliki akun?</p>
          <a href="{{route('registrasipelanggan')}}" class="register-link">
            Daftar sekarang
          </a>
        </div>
      </form>
    </div>
  </div>

  <!-- Scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  
  <script>
    // Password Toggle Functionality
    function initPasswordToggle() {
      const passwordInput = document.getElementById('password');
      const toggleButton = document.getElementById('passwordToggle');
      const toggleIcon = toggleButton.querySelector('i');

      toggleButton.addEventListener('click', function() {
        if (passwordInput.type === 'password') {
          passwordInput.type = 'text';
          toggleIcon.classList.remove('fa-eye');
          toggleIcon.classList.add('fa-eye-slash');
        } else {
          passwordInput.type = 'password';
          toggleIcon.classList.remove('fa-eye-slash');
          toggleIcon.classList.add('fa-eye');
        }
      });
    }

    // Form Enhancement
    function initFormEnhancement() {
      const form = document.getElementById('loginForm');
      const loginBtn = document.getElementById('loginBtn');

      form.addEventListener('submit', function(e) {
        loginBtn.classList.add('loading');
        loginBtn.textContent = 'Memproses...';
        loginBtn.disabled = true;
      });

      // Focus enhancement for better UX
      const inputs = form.querySelectorAll('.form-control');
      inputs.forEach(input => {
        input.addEventListener('focus', function() {
          this.parentElement.style.transform = 'translateY(-1px)';
        });

        input.addEventListener('blur', function() {
          this.parentElement.style.transform = 'translateY(0)';
        });
      });
    }

    // Auto-hide error alerts
    function initErrorAlerts() {
      const errorAlert = document.getElementById('errorAlert');
      if (errorAlert) {
        setTimeout(() => {
          errorAlert.style.opacity = '0';
          errorAlert.style.transition = 'opacity 0.3s ease';
          setTimeout(() => {
            errorAlert.style.display = 'none';
          }, 300);
        }, 5000);
      }
    }

    // Keyboard Navigation
    function initKeyboardNavigation() {
      document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
          const activeElement = document.activeElement;
          if (activeElement.id === 'email') {
            document.getElementById('password').focus();
            e.preventDefault();
          }
        }
      });
    }

    // Initialize all functions when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
      initPasswordToggle();
      initFormEnhancement();
      initErrorAlerts();
      initKeyboardNavigation();
    });
  </script>
  
  @if(session('sukses'))
  <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 mt-3 me-3 z-3" role="alert" style="min-width: 300px;">
    {{ session('sukses') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
  </div>
@endif

</body>



</html>