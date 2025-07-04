<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Customer Registration - Hotel Booking</title>
  <link rel="shortcut icon" type="image/png" href="{{asset('backend/assets/images/logos/favicon.png')}}" />
  
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
  
  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
      --accent-color: #667eea;
      --text-primary: #2d3748;
      --text-secondary: #718096;
      --border-light: #e2e8f0;
      --success-color: #48bb78;
      --warning-color: #ed8936;
      --bg-glass: rgba(255, 255, 255, 0.25);
      --shadow-soft: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
      --shadow-glow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: var(--primary-gradient);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
      position: relative;
      overflow-x: hidden;
    }

    /* Animated Background Elements */
    .bg-decoration {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 0;
    }

    .floating-element {
      position: absolute;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      animation: float 8s ease-in-out infinite;
    }

    .floating-element:nth-child(1) {
      width: 120px;
      height: 120px;
      top: 10%;
      left: 15%;
      animation-delay: 0s;
    }

    .floating-element:nth-child(2) {
      width: 80px;
      height: 80px;
      top: 70%;
      right: 20%;
      animation-delay: 3s;
    }

    .floating-element:nth-child(3) {
      width: 60px;
      height: 60px;
      bottom: 20%;
      left: 10%;
      animation-delay: 6s;
    }

    .floating-element:nth-child(4) {
      width: 100px;
      height: 100px;
      top: 30%;
      right: 10%;
      animation-delay: 1.5s;
    }

    @keyframes float {
      0%, 100% { 
        transform: translateY(0px) rotate(0deg);
        opacity: 0.7;
      }
      33% { 
        transform: translateY(-30px) rotate(120deg);
        opacity: 0.4;
      }
      66% { 
        transform: translateY(-10px) rotate(240deg);
        opacity: 0.8;
      }
    }

    /* Main Container */
    .registration-container {
      background: var(--bg-glass);
      backdrop-filter: blur(20px);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 24px;
      box-shadow: var(--shadow-glow);
      max-width: 480px;
      width: 100%;
      position: relative;
      z-index: 10;
      overflow: hidden;
      animation: slideInUp 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes slideInUp {
      from {
        opacity: 0;
        transform: translateY(60px) scale(0.95);
      }
      to {
        opacity: 1;
        transform: translateY(0) scale(1);
      }
    }

    /* Header Section */
    .registration-header {
      background: var(--primary-gradient);
      padding: 3rem 2rem 2rem;
      text-align: center;
      position: relative;
      overflow: hidden;
    }

    .registration-header::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 50%);
      animation: shimmer 4s linear infinite;
    }

    @keyframes shimmer {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .logo-container {
      position: relative;
      z-index: 2;
      margin-bottom: 1.5rem;
    }

    .logo-icon {
      width: 90px;
      height: 90px;
      background: rgba(255, 255, 255, 0.2);
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-radius: 22px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto;
      backdrop-filter: blur(10px);
      transition: all 0.3s ease;
    }

    .logo-icon:hover {
      transform: translateY(-5px) rotate(5deg);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
    }

    .logo-icon i {
      font-size: 2.5rem;
      color: white;
    }

    .header-title {
      font-family: 'Playfair Display', serif;
      font-size: 2rem;
      font-weight: 700;
      color: white;
      margin-bottom: 0.5rem;
      position: relative;
      z-index: 2;
    }

    .header-subtitle {
      color: rgba(255, 255, 255, 0.85);
      font-size: 1rem;
      font-weight: 400;
      position: relative;
      z-index: 2;
    }

    /* Form Section */
    .registration-body {
      padding: 2.5rem;
      background: rgba(255, 255, 255, 0.95);
      backdrop-filter: blur(10px);
    }

    .form-group {
      margin-bottom: 2rem;
      position: relative;
    }

    .form-label {
      font-weight: 600;
      color: var(--text-primary);
      font-size: 0.95rem;
      margin-bottom: 0.75rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .form-label i {
      color: var(--accent-color);
      width: 16px;
    }

    .input-wrapper {
      position: relative;
    }

    .form-control {
      border: 2px solid var(--border-light);
      border-radius: 16px;
      padding: 16px 20px;
      font-size: 1rem;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      background: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(10px);
      width: 100%;
    }

    .form-control:focus {
      outline: none;
      border-color: var(--accent-color);
      background: white;
      box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
      transform: translateY(-2px);
    }

    .form-control.is-valid {
      border-color: var(--success-color);
      background: rgba(72, 187, 120, 0.05);
    }

    .form-control.is-invalid {
      border-color: #f56565;
      background: rgba(245, 101, 101, 0.05);
    }

    /* Input Icons */
    .input-icon {
      position: absolute;
      right: 20px;
      top: 50%;
      transform: translateY(-50%);
      transition: all 0.3s ease;
    }

    .input-icon.success {
      color: var(--success-color);
    }

    .input-icon.toggle {
      cursor: pointer;
      color: var(--text-secondary);
    }

    .input-icon.toggle:hover {
      color: var(--accent-color);
    }

    /* File Upload Styling */
    .file-upload-container {
      position: relative;
    }

    .file-upload-area {
      border: 2px dashed var(--border-light);
      border-radius: 16px;
      padding: 2rem;
      text-align: center;
      background: linear-gradient(145deg, #f8fafc, #edf2f7);
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .file-upload-area::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(102, 126, 234, 0.1), transparent);
      transition: left 0.5s ease;
    }

    .file-upload-area:hover::before {
      left: 100%;
    }

    .file-upload-area:hover {
      border-color: var(--accent-color);
      background: rgba(102, 126, 234, 0.05);
      transform: translateY(-2px);
      box-shadow: var(--shadow-soft);
    }

    .file-upload-area.dragover {
      border-color: var(--accent-color);
      background: rgba(102, 126, 234, 0.1);
      transform: scale(1.02);
    }

    .file-upload-icon {
      font-size: 3rem;
      color: var(--accent-color);
      margin-bottom: 1rem;
      position: relative;
      z-index: 2;
    }

    .file-upload-text {
      color: var(--text-primary);
      font-weight: 600;
      margin-bottom: 0.5rem;
      position: relative;
      z-index: 2;
    }

    .file-upload-hint {
      color: var(--text-secondary);
      font-size: 0.9rem;
      position: relative;
      z-index: 2;
    }

    .file-input {
      position: absolute;
      opacity: 0;
      width: 100%;
      height: 100%;
      cursor: pointer;
    }
/* Customer Redirect Button */
    .customer-redirect {
      margin-top: 2rem;
      position: relative;
      z-index: 2;
    }

    .customer-btn {
      display: inline-flex;
      align-items: center;
      background: rgba(255, 255, 255, 0.2);
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-radius: 50px;
      padding: 0.75rem 1.5rem;
      color: white;
      text-decoration: none;
      font-weight: 600;
      font-size: 0.9rem;
      transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
      backdrop-filter: blur(10px);
      position: relative;
      overflow: hidden;
    }

    .customer-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
      transition: left 0.6s;
    }

    .customer-btn:hover {
      background: rgba(255, 255, 255, 0.3);
      border-color: rgba(255, 255, 255, 0.5);
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
      color: white;
    }

    .customer-btn:hover::before {
      left: 100%;
    }
    /* Submit Button */
    .btn-register {
      background: var(--primary-gradient);
      border: none;
      border-radius: 16px;
      padding: 18px 24px;
      font-size: 1.1rem;
      font-weight: 600;
      color: white;
      width: 100%;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
      box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    }

    .btn-register::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
      transition: left 0.6s ease;
    }

    .btn-register:hover::before {
      left: 100%;
    }

    .btn-register:hover {
      transform: translateY(-3px);
      box-shadow: 0 15px 35px rgba(102, 126, 234, 0.4);
    }

    .btn-register:active {
      transform: translateY(-1px);
    }

    .btn-register.loading {
      pointer-events: none;
      opacity: 0.8;
    }

    /* Footer */
    .registration-footer {
      padding: 1.5rem 2.5rem;
      background: rgba(248, 250, 252, 0.8);
      backdrop-filter: blur(10px);
      text-align: center;
      border-top: 1px solid rgba(226, 232, 240, 0.5);
    }

    .footer-text {
      color: var(--text-secondary);
      font-size: 0.95rem;
      margin-bottom: 1rem;
    }

    .footer-link {
      color: var(--accent-color);
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      position: relative;
    }

    .footer-link::after {
      content: '';
      position: absolute;
      bottom: -2px;
      left: 0;
      width: 0;
      height: 2px;
      background: var(--accent-color);
      transition: width 0.3s ease;
    }

    .footer-link:hover::after {
      width: 100%;
    }

    .footer-link:hover {
      color: var(--accent-color);
      text-decoration: none;
    }

    /* Responsive Design */
    @media (max-width: 576px) {
      .registration-container {
        margin: 10px;
        border-radius: 20px;
      }
      
      .registration-header {
        padding: 2rem 1.5rem 1.5rem;
      }
      
      .registration-body {
        padding: 2rem 1.5rem;
      }
      
      .registration-footer {
        padding: 1.5rem;
      }
      
      .header-title {
        font-size: 1.75rem;
      }
      
      .form-control {
        padding: 14px 16px;
      }
    }

    /* Loading Animation */
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }

    .loading-spinner {
      animation: spin 1s linear infinite;
    }

    /* Success Animation */
    @keyframes bounceIn {
      0% {
        opacity: 0;
        transform: scale(0.3);
      }
      50% {
        opacity: 1;
        transform: scale(1.05);
      }
      70% {
        transform: scale(0.9);
      }
      100% {
        opacity: 1;
        transform: scale(1);
      }
    }

    .success-icon {
      animation: bounceIn 0.6s ease-out;
    }
  </style>
</head>

<body>
  <!-- Background Decoration -->
  <div class="bg-decoration">
    <div class="floating-element"></div>
    <div class="floating-element"></div>
    <div class="floating-element"></div>
    <div class="floating-element"></div>
  </div>

  <!-- Main Registration Container -->
  <div class="registration-container">
    <!-- Header Section -->
    <div class="registration-header">
      <div class="logo-container">
        <div class="logo-icon">
          <i class="fas fa-user-circle"></i>
        </div>
      </div>
      <h1 class="header-title">Join Us Today</h1>
      <p class="header-subtitle">Create your customer account and start booking amazing hotels</p>
      <div class="customer-redirect">
          <a href="{{route('registrasi')}}" class="customer-btn">
            <i class="fas fa-user-tie me-2"></i>
            Register as Resepsionis Instead
          </a>
        </div>
    </div>

    <!-- Form Section -->
    <div class="registration-body">
      <form class="form-horizontal form-material" id="registerForm" action="{{route('registrasipelanggan.submit')}}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="role" value="customer" readonly>

        <!-- Name Field -->
        <div class="form-group">
          <label for="name" class="form-label">
            <i class="fas fa-user"></i>
            Full Name
          </label>
          <div class="input-wrapper">
            <input type="text" class="form-control" name="name" id="name" placeholder="Enter your full name" required>
            <div class="input-icon success" style="display: none;">
              <i class="fas fa-check-circle"></i>
            </div>
          </div>
        </div>

        <!-- Email Field -->
        <div class="form-group">
          <label for="email" class="form-label">
            <i class="fas fa-envelope"></i>
            Email Address
          </label>
          <div class="input-wrapper">
            <input type="email" class="form-control" name="email" id="email" placeholder="Enter your email address" required>
            <div class="input-icon success" style="display: none;">
              <i class="fas fa-check-circle"></i>
            </div>
          </div>
        </div>

        <!-- Password Field -->
        <div class="form-group">
          <label for="password" class="form-label">
            <i class="fas fa-lock"></i>
            Password
          </label>
          <div class="input-wrapper">
            <input type="password" class="form-control" name="password" id="password" placeholder="Create a secure password" required>
            <div class="input-icon toggle">
              <i class="fas fa-eye" id="togglePassword"></i>
            </div>
          </div>
        </div>

        <!-- Phone Field -->
        <div class="form-group">
          <label for="phone" class="form-label">
            <i class="fas fa-phone"></i>
            Phone Number
          </label>
          <div class="input-wrapper">
            <input type="tel" class="form-control" name="phone" id="phone" placeholder="Enter your phone number" required>
            <div class="input-icon success" style="display: none;">
              <i class="fas fa-check-circle"></i>
            </div>
          </div>
        </div>

        <!-- Profile Photo Field -->
        <div class="form-group">
          <label for="url_diri" class="form-label">
            <i class="fas fa-camera"></i>
            Profile Photo
          </label>
          <div class="file-upload-container">
            <div class="file-upload-area" onclick="document.getElementById('url_diri').click()">
              <input type="file" class="file-input" name="url_diri" id="url_diri" accept="image/*" required>
              <div class="file-upload-icon">
                <i class="fas fa-cloud-upload-alt"></i>
              </div>
              <div class="file-upload-text">Click to upload your photo</div>
              <div class="file-upload-hint">or drag and drop (PNG, JPG up to 5MB)</div>
            </div>
          </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-register" id="submitBtn">
          <i class="fas fa-user-plus me-2"></i>
          <span id="submitText">Create My Account</span>
        </button>
       
      </form>
      
    </div>

    <!-- Footer Section -->
    <div class="registration-footer">
      
      <p class="footer-text">
        Already have an account? 
        <a href="{{route('login')}}" class="footer-link">Sign In</a>
      </p>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Form elements
      const form = document.getElementById('registerForm');
      const submitBtn = document.getElementById('submitBtn');
      const submitText = document.getElementById('submitText');
      const togglePassword = document.getElementById('togglePassword');
      const passwordField = document.getElementById('password');

      // Form fields for validation
      const nameField = document.getElementById('name');
      const emailField = document.getElementById('email');
      const phoneField = document.getElementById('phone');
      const photoField = document.getElementById('url_diri');

      // Password Toggle Functionality
      togglePassword.addEventListener('click', function() {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        
        // Toggle icon
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
      });

      // Real-time Validation
      function validateField(field) {
        const wrapper = field.closest('.input-wrapper');
        const successIcon = wrapper?.querySelector('.input-icon.success');
        
        if (field.checkValidity() && field.value.trim() !== '') {
          field.classList.remove('is-invalid');
          field.classList.add('is-valid');
          if (successIcon) {
            successIcon.style.display = 'block';
            successIcon.classList.add('success-icon');
          }
        } else {
          field.classList.remove('is-valid');
          field.classList.add('is-invalid');
          if (successIcon) {
            successIcon.style.display = 'none';
            successIcon.classList.remove('success-icon');
          }
        }
      }

      // Add validation listeners
      [nameField, emailField, phoneField].forEach(field => {
        field.addEventListener('input', () => validateField(field));
        field.addEventListener('blur', () => validateField(field));
      });

      // Email specific validation
      emailField.addEventListener('input', function() {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (emailRegex.test(this.value)) {
          validateField(this);
        }
      });

      // Phone number formatting
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
        validateField(this);
      });

      // File Upload Handling
      const fileUploadArea = document.querySelector('.file-upload-area');
      const fileUploadText = document.querySelector('.file-upload-text');
      const fileUploadHint = document.querySelector('.file-upload-hint');
      const fileUploadIcon = document.querySelector('.file-upload-icon i');

      photoField.addEventListener('change', function() {
        if (this.files.length > 0) {
          const fileName = this.files[0].name;
          const fileSize = (this.files[0].size / 1024 / 1024).toFixed(2);
          
          fileUploadIcon.className = 'fas fa-check-circle';
          fileUploadText.textContent = fileName;
          fileUploadHint.textContent = `File uploaded (${fileSize} MB)`;
          fileUploadArea.style.borderColor = 'var(--success-color)';
          fileUploadArea.style.background = 'rgba(72, 187, 120, 0.05)';
        }
      });

      // Drag and Drop
      fileUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
      });

      fileUploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
      });

      fileUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
          photoField.files = files;
          const event = new Event('change', { bubbles: true });
          photoField.dispatchEvent(event);
        }
      });

      // Form Submission
      form.addEventListener('submit', function(e) {
        // Add loading state
        const submitIcon = submitBtn.querySelector('i');
        
        submitBtn.classList.add('loading');
        submitIcon.className = 'fas fa-spinner loading-spinner me-2';
        submitText.textContent = 'Creating Account...';
        
        // Validate all fields before submission
        let isValid = true;
        [nameField, emailField, passwordField, phoneField].forEach(field => {
          if (!field.checkValidity() || field.value.trim() === '') {
            isValid = false;
            validateField(field);
          }
        });

        if (!photoField.files.length) {
          isValid = false;
          fileUploadArea.style.borderColor = '#f56565';
          fileUploadArea.style.background = 'rgba(245, 101, 101, 0.05)';
        }

        // If validation fails, prevent submission and reset button
        if (!isValid) {
          e.preventDefault();
          setTimeout(() => {
            submitBtn.classList.remove('loading');
            submitIcon.className = 'fas fa-user-plus me-2';
            submitText.textContent = 'Create My Account';
          }, 1000);
        }
      });

      // Add smooth scrolling for mobile
      if (window.innerWidth <= 576) {
        form.addEventListener('focusin', function(e) {
          setTimeout(() => {
            e.target.scrollIntoView({ behavior: 'smooth', block: 'center' });
          }, 300);
        });
      }
    });
  </script>
</body>
</html>