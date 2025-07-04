<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Customer Registration - Hotel Booking</title>
  <link rel="shortcut icon" type="image/png" href="{{asset('backend/assets/images/logos/favicon.png')}}" />
  
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    :root {
      --primary-color: #2563eb;
      --primary-light: #3b82f6;
      --text-primary: #1f2937;
      --text-secondary: #6b7280;
      --border-color: #e5e7eb;
      --success-color: #10b981;
      --error-color: #ef4444;
      --bg-light: #f9fafb;
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: #f3f4f6;
      color: var(--text-primary);
      min-height: 100vh;
      display: flex;
      align-items: center;
      padding: 20px;
    }

    .registration-container {
      background: white;
      border-radius: 12px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
      max-width: 500px;
      width: 100%;
      margin: 0 auto;
      overflow: hidden;
    }

    .registration-header {
      background-color: var(--primary-color);
      padding: 2rem;
      text-align: center;
      color: white;
    }

    .logo-icon {
      font-size: 2.5rem;
      margin-bottom: 1rem;
      color: white;
    }

    .header-title {
      font-weight: 600;
      font-size: 1.5rem;
      margin-bottom: 0.5rem;
    }

    .header-subtitle {
      font-size: 0.95rem;
      opacity: 0.9;
    }

    .registration-body {
      padding: 2rem;
    }

    .form-group {
      margin-bottom: 1.5rem;
    }

    .form-label {
      font-weight: 500;
      font-size: 0.95rem;
      margin-bottom: 0.5rem;
      color: var(--text-primary);
    }

    .form-control {
      border: 1px solid var(--border-color);
      border-radius: 8px;
      padding: 12px 16px;
      font-size: 0.95rem;
      transition: all 0.2s ease;
    }

    .form-control:focus {
      border-color: var(--primary-light);
      box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
    }

    .form-control.is-valid {
      border-color: var(--success-color);
    }

    .form-control.is-invalid {
      border-color: var(--error-color);
    }

    .input-icon {
      position: absolute;
      right: 16px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--text-secondary);
      cursor: pointer;
    }

    .input-icon:hover {
      color: var(--primary-color);
    }

    .file-upload-container {
      border: 1px dashed var(--border-color);
      border-radius: 8px;
      padding: 1.5rem;
      text-align: center;
      background-color: var(--bg-light);
      cursor: pointer;
      transition: all 0.2s ease;
    }

    .file-upload-container:hover {
      border-color: var(--primary-light);
      background-color: rgba(59, 130, 246, 0.05);
    }

    .file-upload-icon {
      font-size: 1.5rem;
      color: var(--primary-color);
      margin-bottom: 0.5rem;
    }

    .file-upload-text {
      font-weight: 500;
      margin-bottom: 0.25rem;
    }

    .file-upload-hint {
      font-size: 0.85rem;
      color: var(--text-secondary);
    }

    .btn-register {
      background-color: var(--primary-color);
      border: none;
      border-radius: 8px;
      padding: 12px 24px;
      font-weight: 500;
      width: 100%;
      transition: all 0.2s ease;
    }

    .btn-register:hover {
      background-color: var(--primary-light);
      transform: translateY(-2px);
    }

    .btn-register:active {
      transform: translateY(0);
    }

    .registration-footer {
      padding: 1.5rem;
      text-align: center;
      border-top: 1px solid var(--border-color);
      font-size: 0.95rem;
      color: var(--text-secondary);
    }

    .footer-link {
      color: var(--primary-color);
      font-weight: 500;
      text-decoration: none;
    }

    .footer-link:hover {
      text-decoration: underline;
    }

    @media (max-width: 576px) {
      .registration-container {
        border-radius: 8px;
      }
      
      .registration-header {
        padding: 1.5rem;
      }
      
      .registration-body {
        padding: 1.5rem;
      }
    }

    /* Loading spinner */
    .spinner {
      animation: spin 1s linear infinite;
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
  </style>
</head>

<body>
  <!-- Main Registration Container -->
  <div class="registration-container">
    <!-- Header Section -->
    <div class="registration-header">
      <div class="logo-icon">
        <i class="fas fa-user-circle"></i>
      </div>
      <h1 class="header-title">Create Your Account</h1>
      <p class="header-subtitle">Register to book hotels with us</p>
    </div>

    <!-- Form Section -->
    <div class="registration-body">
      <form class="form-horizontal form-material" id="registerForm" action="{{route('registrasipelanggan.submit')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="role" value="pelanggan" readonly>

        <!-- Name Field -->
        <div class="form-group">
          <label for="name" class="form-label">Full Name</label>
          <input type="text" class="form-control" name="name" id="name" placeholder="Enter your full name" required>
        </div>

        <!-- Email Field -->
        <div class="form-group">
          <label for="email" class="form-label">Email Address</label>
          <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email address" required>
        </div>

        <!-- Password Field -->
        <div class="form-group">
          <label for="password" class="form-label">Password</label>
          <div class="position-relative">
            <input type="password" class="form-control" name="password" id="password" placeholder="Create a password" required>
            <span class="input-icon" id="togglePassword">
              <i class="fas fa-eye"></i>
            </span>
          </div>
        </div>

        <!-- Phone Field -->
        <div class="form-group">
          <label for="phone" class="form-label">Phone Number</label>
          <input type="tel" class="form-control" name="phone" id="phone" placeholder="Enter your phone number" required>
        </div>

        <!-- Profile Photo Field -->
        <div class="form-group">
          <label for="avatar" class="form-label">Profile Photo</label>
          <div class="file-upload-container" onclick="document.getElementById('avatar').click()">
            <input type="file" class="d-none" name="avatar" id="avatar" accept="image/*" required>
            <div class="file-upload-icon">
              <i class="fas fa-cloud-upload-alt"></i>
            </div>
            <div class="file-upload-text">Click to upload your photo</div>
            <div class="file-upload-hint">PNG or JPG (max 5MB)</div>
          </div>
        </div>

        <!-- ID Card Field -->
        <div class="form-group">
          <label for="id_card" class="form-label">ID Card</label>
          <div class="file-upload-container" onclick="document.getElementById('id_card').click()">
            <input type="file" class="d-none" name="id_card" id="id_card" accept="image/*" required>
            <div class="file-upload-icon">
              <i class="fas fa-id-card"></i>
            </div>
            <div class="file-upload-text">Click to upload your ID card</div>
            <div class="file-upload-hint">PNG or JPG (max 5MB)</div>
          </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary btn-register mt-3" id="submitBtn">
          <span id="submitText">Register</span>
          <i class="fas fa-spinner spinner d-none ms-2" id="spinner"></i>
        </button>
      </form>
    </div>

    <!-- Footer Section -->
    <div class="registration-footer">
      Already have an account? 
      <a href="{{route('login')}}" class="footer-link">Sign in</a>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Password toggle
      const togglePassword = document.getElementById('togglePassword');
      const passwordField = document.getElementById('password');
      
      togglePassword.addEventListener('click', function() {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        
        // Toggle icon
        const icon = this.querySelector('i');
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
      });

      // Form validation
      const form = document.getElementById('registerForm');
      const submitBtn = document.getElementById('submitBtn');
      const submitText = document.getElementById('submitText');
      const spinner = document.getElementById('spinner');
      
      // Phone number formatting
      const phoneField = document.getElementById('phone');
      phoneField.addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        
        if (value.length >= 10) {
          if (value.startsWith('62')) {
            value = '+' + value;
          } else if (value.startsWith('0')) {
            value = '+62' + value.substring(1);
          } else if (!value.startsWith('+')) {
            value = '+62' + value;
          }
        }
        
        this.value = value;
      });

      // File upload feedback
      function setupFileUpload(fieldId, containerClass) {
        const field = document.getElementById(fieldId);
        const container = field.closest('.' + containerClass);
        
        field.addEventListener('change', function() {
          if (this.files.length > 0) {
            const fileName = this.files[0].name;
            const fileSize = (this.files[0].size / 1024 / 1024).toFixed(2);
            
            container.querySelector('.file-upload-text').textContent = fileName;
            container.querySelector('.file-upload-hint').textContent = `${fileSize} MB`;
            container.style.borderColor = 'var(--success-color)';
          }
        });
      }
      
      setupFileUpload('avatar', 'file-upload-container');
      setupFileUpload('id_card', 'file-upload-container');

      // Form submission
      form.addEventListener('submit', function(e) {
        // Show loading state
        submitBtn.disabled = true;
        submitText.textContent = 'Processing...';
        spinner.classList.remove('d-none');
        
        // Simple validation
        let isValid = true;
        const requiredFields = ['name', 'email', 'password', 'phone', 'avatar', 'id_card'];
        
        requiredFields.forEach(fieldId => {
          const field = document.getElementById(fieldId);
          if (!field.value.trim() || (field.type === 'file' && !field.files.length)) {
            isValid = false;
            field.classList.add('is-invalid');
          }
        });
        
        if (!isValid) {
          e.preventDefault();
          submitBtn.disabled = false;
          submitText.textContent = 'Register';
          spinner.classList.add('d-none');
          
          // Scroll to first invalid field
          const firstInvalid = document.querySelector('.is-invalid');
          if (firstInvalid) {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
          }
        }
      });
      
      // Remove invalid class when user starts typing
      const inputs = form.querySelectorAll('input');
      inputs.forEach(input => {
        input.addEventListener('input', function() {
          this.classList.remove('is-invalid');
        });
      });
    });
  </script>
</body>
</html>