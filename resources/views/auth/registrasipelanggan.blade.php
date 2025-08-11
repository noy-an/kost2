<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Customer Registration - Hotel Booking</title>
  <link rel="shortcut icon" type="image/png" href="{{asset('backend/assets/images/logos/favicon.png')}}" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
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

    .text-danger {
      font-size: 0.875rem;
    }

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
  <div class="registration-container">
    <div class="registration-header">
      <div class="logo-icon"><i class="fas fa-user-circle"></i></div>
      <h1 class="header-title">Create Your Account</h1>
      <p class="header-subtitle">Register to book kost with us</p>
    </div>

    <div class="registration-body">
      <form class="form-horizontal form-material" id="registerForm" action="{{route('registrasipelanggan.submit')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="role" value="pelanggan" readonly>

        <div class="form-group">
          <label for="name" class="form-label">Full Name</label>
          <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" placeholder="Enter your full name" required value="{{ old('name') }}">
          @error('name') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        

       

        <div class="form-group">
          <label for="phone" class="form-label">Phone</label>
          <input type="tel" class="form-control @error('phone') is-invalid @enderror" name="phone" id="phone" placeholder="Enter your phone number" required value="{{ old('phone') }}">
          @error('phone') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

 <div class="form-group">
          <label for="password" class="form-label">Password</label>
          <div class="position-relative">
            <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Create a password" required value="{{ old('password') }}">
            <span class="input-icon" id="togglePassword"><i class="fas fa-eye"></i></span>
          </div>
          @error('password') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label for="avatar" class="form-label">Profile Photo</label>
          <div class="file-upload-container" onclick="document.getElementById('avatar').click()">
            <input type="file" class="d-none @error('avatar') is-invalid @enderror" name="avatar" id="avatar" accept="image/*">
            <div class="file-upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
            <div class="file-upload-text">Click to upload your photo</div>
            <div class="file-upload-hint">PNG or JPG (max 5MB)</div>
          </div>
          @error('avatar') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
          <label for="id_card" class="form-label">ID Card</label>
          <div class="file-upload-container" onclick="document.getElementById('id_card').click()">
            <input type="file" class="d-none @error('id_card') is-invalid @enderror" name="id_card" id="id_card" accept="image/*">
            <div class="file-upload-icon"><i class="fas fa-id-card"></i></div>
            <div class="file-upload-text">Click to upload your ID card</div>
            <div class="file-upload-hint">PNG or JPG (max 5MB)</div>
          </div>
          @error('id_card') <div class="text-danger mt-1">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-register mt-3" id="submitBtn">
          <span id="submitText">Register</span>
          <i class="fas fa-spinner spinner d-none ms-2" id="spinner"></i>
        </button>
      </form>
    </div>

    <div class="registration-footer">
      Already have an account? 
      <a href="{{route('login')}}" class="footer-link">Sign in</a>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const togglePassword = document.getElementById('togglePassword');
      const passwordField = document.getElementById('password');
      togglePassword.addEventListener('click', function() {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        this.querySelector('i').classList.toggle('fa-eye');
        this.querySelector('i').classList.toggle('fa-eye-slash');
      });

      const form = document.getElementById('registerForm');
      const submitBtn = document.getElementById('submitBtn');
      const submitText = document.getElementById('submitText');
      const spinner = document.getElementById('spinner');

     
      const fileUploadFields = ['avatar', 'id_card'];
      fileUploadFields.forEach(function(fieldId) {
        const field = document.getElementById(fieldId);
        const container = field.closest('.file-upload-container');
        field.addEventListener('change', function() {
          if (this.files.length > 0) {
            const fileName = this.files[0].name;
            const fileSize = (this.files[0].size / 1024 / 1024).toFixed(2);
            container.querySelector('.file-upload-text').textContent = fileName;
            container.querySelector('.file-upload-hint').textContent = `${fileSize} MB`;
            container.style.borderColor = 'var(--success-color)';
          }
        });
      });

      form.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitText.textContent = 'Processing...';
        spinner.classList.remove('d-none');

        let isValid = true;
        const requiredFields = ['name', , 'password', 'phone'];
        requiredFields.forEach(fieldId => {
          const field = document.getElementById(fieldId);
          if (!field.value.trim()) {
            isValid = false;
            field.classList.add('is-invalid');
          }
        });

        if (!isValid) {
          e.preventDefault();
          submitBtn.disabled = false;
          submitText.textContent = 'Register';
          spinner.classList.add('d-none');
          const firstInvalid = document.querySelector('.is-invalid');
          if (firstInvalid) {
            firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
          }
        }
      });

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
