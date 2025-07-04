<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hotel Resepsionis Registration - Premium Experience</title>
  <link rel="shortcut icon" type="image/png" href="backend/assets/images/logos/favicon.png" />
  
  <!-- Bootstrap 5 CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
  
  <style>
    :root {
      --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
      --gold-gradient: linear-gradient(135deg, #ffd700 0%, #ffb347 100%);
      --glass-bg: rgba(255, 255, 255, 0.25);
      --glass-border: rgba(255, 255, 255, 0.18);
      --text-primary: #1a202c;
      --text-secondary: #4a5568;
      --text-light: #718096;
      --success: #48bb78;
      --warning: #ed8936;
      --error: #f56565;
      --shadow-light: 0 4px 16px rgba(0, 0, 0, 0.1);
      --shadow-medium: 0 8px 32px rgba(0, 0, 0, 0.15);
      --shadow-heavy: 0 20px 60px rgba(0, 0, 0, 0.2);
    }

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
      min-height: 100vh;
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
      border-radius: 50%;
      opacity: 0.1;
      animation: float 8s ease-in-out infinite;
    }

    .floating-element:nth-child(1) {
      width: 120px;
      height: 120px;
      background: white;
      top: 10%;
      left: 10%;
      animation-delay: 0s;
    }

    .floating-element:nth-child(2) {
      width: 80px;
      height: 80px;
      background: var(--gold-gradient);
      top: 20%;
      right: 15%;
      animation-delay: 2s;
    }

    .floating-element:nth-child(3) {
      width: 200px;
      height: 200px;
      background: rgba(255, 255, 255, 0.05);
      bottom: 10%;
      left: 5%;
      animation-delay: 4s;
    }

    .floating-element:nth-child(4) {
      width: 150px;
      height: 150px;
      background: var(--secondary-gradient);
      bottom: 30%;
      right: 10%;
      animation-delay: 6s;
    }

    @keyframes float {
      0%, 100% { 
        transform: translateY(0px) rotate(0deg) scale(1); 
      }
      33% { 
        transform: translateY(-30px) rotate(120deg) scale(1.1); 
      }
      66% { 
        transform: translateY(15px) rotate(240deg) scale(0.9); 
      }
    }

    /* Main Container */
    .registration-wrapper {
      position: relative;
      z-index: 10;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 2rem 1rem;
    }

    .registration-container {
      background: var(--glass-bg);
      backdrop-filter: blur(20px);
      border: 1px solid var(--glass-border);
      border-radius: 24px;
      box-shadow: var(--shadow-heavy);
      max-width: 900px;
      width: 100%;
      overflow: hidden;
      animation: slideInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
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
      padding: 3rem 2rem;
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
      background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
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
      width: 100px;
      height: 100px;
      background: rgba(255, 255, 255, 0.2);
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-radius: 24px;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto;
      backdrop-filter: blur(10px);
      animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); }
      50% { transform: scale(1.05); }
    }

    .logo-icon i {
      font-size: 2.5rem;
      color: white;
      filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));
    }

    .header-title {
      font-family: 'Playfair Display', serif;
      font-size: 2.5rem;
      font-weight: 700;
      color: white;
      margin-bottom: 0.5rem;
      text-shadow: 0 2px 4px rgba(0,0,0,0.2);
    }

    .header-subtitle {
      font-size: 1.1rem;
      color: rgba(255, 255, 255, 0.9);
      font-weight: 300;
      margin-bottom: 2rem;
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

    /* Form Container */
    .form-container {
      padding: 3rem;
    }

    .form-section {
      margin-bottom: 2.5rem;
    }

    .section-title {
      font-family: 'Playfair Display', serif;
      font-size: 1.5rem;
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .section-title i {
      color: var(--primary-gradient);
      background: var(--primary-gradient);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
    }

    .section-divider {
      height: 2px;
      background: linear-gradient(90deg, transparent, var(--primary-gradient), transparent);
      margin: 2rem 0;
      border-radius: 1px;
    }

    /* Form Fields */
    .form-group {
      margin-bottom: 2rem;
      position: relative;
    }

    .form-label {
      font-weight: 600;
      color: var(--text-primary);
      margin-bottom: 0.75rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
      font-size: 0.95rem;
    }

    .form-label i {
      color: var(--text-secondary);
      width: 16px;
    }

    .form-control {
      border: 2px solid #e2e8f0;
      border-radius: 16px;
      padding: 1rem 1.25rem;
      font-size: 1rem;
      transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
      background: rgba(255, 255, 255, 0.8);
      backdrop-filter: blur(10px);
    }

    .form-control:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
      outline: none;
      background: white;
      transform: translateY(-2px);
    }

    .input-group {
      position: relative;
    }

    .input-icon {
      position: absolute;
      right: 1.25rem;
      top: 50%;
      transform: translateY(-50%);
      z-index: 3;
      transition: all 0.3s ease;
    }

    .input-icon.success {
      color: var(--success);
      animation: checkmark 0.5s ease-in-out;
    }

    @keyframes checkmark {
      0% { transform: translateY(-50%) scale(0); }
      50% { transform: translateY(-50%) scale(1.2); }
      100% { transform: translateY(-50%) scale(1); }
    }

    /* File Upload - Luxury Style */
    .file-upload-container {
      position: relative;
      margin-bottom: 1.5rem;
    }

     .form-label {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .file-upload-area {
            border: 3px dashed #cbd5e0;
            border-radius: 20px;
            padding: 2.5rem 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            background: linear-gradient(135deg, rgba(255,255,255,0.1), rgba(255,255,255,0.05));
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
            min-height: 200px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .file-upload-area::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, transparent, rgba(102, 126, 234, 0.1), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .file-upload-area:hover {
            border-color: #667eea;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.05), rgba(118, 75, 162, 0.05));
            transform: translateY(-4px);
            box-shadow: var(--shadow-medium);
        }

        .file-upload-area:hover::before {
            opacity: 1;
        }

        .file-upload-area.dragover {
            border-color: #667eea;
            background: rgba(102, 126, 234, 0.1);
            box-shadow: 0 0 0 4px rgba(102, 126, 234, 0.1);
        }

        .file-upload-area.has-file {
            border-color: #48bb78;
            background: linear-gradient(135deg, rgba(72, 187, 120, 0.05), rgba(56, 178, 172, 0.05));
        }

        .upload-icon {
            width: 80px;
            height: 80px;
            background: var(--primary-gradient);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-light);
            transition: all 0.3s ease;
        }

        .upload-icon i {
            font-size: 2rem;
            color: white;
        }

        .upload-text {
            position: relative;
            z-index: 2;
        }

        .upload-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }

        .upload-subtitle {
            color: var(--text-secondary);
            font-size: 0.95rem;
            margin-bottom: 0.5rem;
        }

        .upload-info {
            color: var(--text-light);
            font-size: 0.85rem;
        }

        .file-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 3;
        }

        .file-preview {
            position: relative;
            z-index: 2;
        }

        .preview-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 15px;
            margin-bottom: 1rem;
            box-shadow: var(--shadow-light);
        }

        .file-info {
            background: rgba(255, 255, 255, 0.9);
            padding: 0.75rem 1rem;
            border-radius: 10px;
            margin-top: 1rem;
            backdrop-filter: blur(10px);
        }

        .file-name {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.9rem;
            margin-bottom: 0.25rem;
            word-break: break-all;
        }

        .file-size {
            color: var(--text-secondary);
            font-size: 0.8rem;
        }

        .remove-file {
            position: absolute;
            top: 10px;
            right: 10px;
            background: #e53e3e;
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 4;
            transition: all 0.3s ease;
        }

        .remove-file:hover {
            background: #c53030;
            transform: scale(1.1);
        }

        .form-section {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--shadow-medium);
            margin-bottom: 2rem;
        }

        .text-muted {
            color: var(--text-secondary) !important;
        }

        @media (max-width: 768px) {
            .file-upload-area {
                padding: 2rem 1rem;
            }
            
            .upload-icon {
                width: 60px;
                height: 60px;
            }
            
            .upload-icon i {
                font-size: 1.5rem;
            }
        
    }

    /* Submit Button - Premium */
    .submit-button {
      background: var(--primary-gradient);
      border: none;
      border-radius: 16px;
      padding: 1.25rem 2.5rem;
      font-weight: 700;
      font-size: 1.1rem;
      color: white;
      cursor: pointer;
      transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
      position: relative;
      overflow: hidden;
      box-shadow: var(--shadow-medium);
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .submit-button::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
      transition: left 0.6s;
    }

    .submit-button:hover {
      transform: translateY(-3px);
      box-shadow: var(--shadow-heavy);
    }

    .submit-button:hover::before {
      left: 100%;
    }

    .submit-button:active {
      transform: translateY(-1px);
    }

    .submit-button:disabled {
      opacity: 0.7;
      cursor: not-allowed;
      transform: none;
    }

    /* Footer */
    .form-footer {
      text-align: center;
      margin-top: 3rem;
      padding-top: 2rem;
      border-top: 1px solid rgba(0, 0, 0, 0.1);
    }

    .form-footer a {
      color: #667eea;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      position: relative;
    }

    .form-footer a::after {
      content: '';
      position: absolute;
      bottom: -2px;
      left: 0;
      width: 0;
      height: 2px;
      background: var(--primary-gradient);
      transition: width 0.3s ease;
    }

    .form-footer a:hover::after {
      width: 100%;
    }

    /* Progress Indicator */
    .progress-indicator {
      position: fixed;
      top: 0;
      left: 0;
      width: 0%;
      height: 4px;
      background: var(--gold-gradient);
      z-index: 1000;
      transition: width 0.3s ease;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .registration-container {
        margin: 1rem;
        border-radius: 20px;
      }

      .registration-header {
        padding: 2rem 1.5rem;
      }

      .header-title {
        font-size: 2rem;
      }

      .form-container {
        padding: 2rem 1.5rem;
      }

      .role-selector {
        flex-direction: column;
        gap: 0.75rem;
      }

      .role-card {
        padding: 1rem 1.5rem;
      }

      .upload-icon {
        width: 60px;
        height: 60px;
      }

      .upload-icon i {
        font-size: 1.5rem;
      }
    }

    @media (max-width: 480px) {
      .registration-wrapper {
        padding: 1rem 0.5rem;
      }

      .header-title {
        font-size: 1.75rem;
      }

      .section-title {
        font-size: 1.25rem;
      }

      .form-container {
        padding: 1.5rem 1rem;
      }
    }
  </style>
</head>

<body>
  <!-- Progress Indicator -->
  <div class="progress-indicator" id="progressBar"></div>

  <!-- Animated Background -->
  <div class="bg-decoration">
    <div class="floating-element"></div>
    <div class="floating-element"></div>
    <div class="floating-element"></div>
    <div class="floating-element"></div>
  </div>

  <div class="registration-wrapper">
    <div class="registration-container">
      <!-- Header Section -->
      <div class="registration-header">
        <div class="logo-container">
          <div class="logo-icon">
            <i class="fas fa-crown"></i>
          </div>
        </div>
        <h1 class="header-title">Hotel Resepsionis Registration</h1>
        <p class="header-subtitle">Join our exclusive network of luxury hospitality partners</p>
        
        <!-- Customer Registration Button -->
        <div class="customer-redirect">
          <a href="{{route('registrasipelanggan')}}" class="customer-btn">
            <i class="fas fa-user-tie me-2"></i>
            Register as Customer Instead
          </a>
        </div>
      </div>

      <!-- Form Container -->
      <div class="form-container">
        <form id="registrationForm" action="{{route('registrasi.submit')}}" method="POST" enctype="multipart/form-data">
          @csrf
          <input type="hidden" name="role" id="roleInput" value="resepsionis">

          <!-- Basic Information Section -->
          <div class="form-section">
            <h3 class="section-title">
              <i class="fas fa-user-circle"></i>
              Personal Information
            </h3>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="name" class="form-label">
                    <i class="fas fa-signature"></i>
                    Full Name
                  </label>
                  <div class="input-group">
                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter your full name" required>
                    <div class="input-icon">
                      <i class="fas fa-check-circle" style="display: none;"></i>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
                <div class="form-group">
                  <label for="email" class="form-label">
                    <i class="fas fa-envelope"></i>
                    Email Address
                  </label>
                  <div class="input-group">
                    <input type="email" class="form-control" name="email" id="email" placeholder="your.email@example.com" required>
                    <div class="input-icon">
                      <i class="fas fa-check-circle" style="display: none;"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="password" class="form-label">
                    <i class="fas fa-lock"></i>
                    Password
                  </label>
                  <div class="input-group">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Create a strong password" required>
                    <div class="input-icon">
                      <i class="fas fa-eye toggle-password" style="cursor: pointer;"></i>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-6">
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
              </div>
            </div>
          </div>

          <!-- Verification Documents Section -->
          <div class="section-divider"></div>
          
          <div class="form-section">
            <h3 class="section-title">
                <i class="fas fa-shield-alt"></i>
                Verification Documents
            </h3>
            <p class="text-muted mb-4">Please upload clear, high-quality images of your identification documents</p>

            <div class="row">
                <div class="col-md-4">
                    <div class="file-upload-container">
                        <label class="form-label">
                            <i class="fas fa-id-card"></i>
                            National ID (KTP)
                        </label>
                        <div class="file-upload-area" id="upload-area-ktp">
                            <input type="file" class="file-input" name="url_ktp" id="url_ktp" accept="image/*">
                            <button class="remove-file" id="remove-ktp" style="display: none;">
                                <i class="fas fa-times"></i>
                            </button>
                            <div class="upload-content" id="upload-content-ktp">
                                <div class="upload-icon">
                                    <i class="fas fa-id-card"></i>
                                </div>
                                <div class="upload-text">
                                    <div class="upload-title">Upload ID Card</div>
                                    <div class="upload-subtitle">Click or drag to upload</div>
                                    <div class="upload-info">PNG, JPG up to 5MB</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="file-upload-container">
                        <label class="form-label">
                            <i class="fas fa-file-invoice-dollar"></i>
                            Tax ID (NPWP)
                        </label>
                        <div class="file-upload-area" id="upload-area-npwp">
                            <input type="file" class="file-input" name="url_npwp" id="url_npwp" accept="image/*">
                            <button class="remove-file" id="remove-npwp" style="display: none;">
                                <i class="fas fa-times"></i>
                            </button>
                            <div class="upload-content" id="upload-content-npwp">
                                <div class="upload-icon">
                                    <i class="fas fa-file-invoice-dollar"></i>
                                </div>
                                <div class="upload-text">
                                    <div class="upload-title">Upload Tax ID</div>
                                    <div class="upload-subtitle">Click or drag to upload</div>
                                    <div class="upload-info">PNG, JPG up to 5MB</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="file-upload-container">
                        <label class="form-label">
                            <i class="fas fa-camera"></i>
                            Profile Photo
                        </label>
                        <div class="file-upload-area" id="upload-area-diri">
                            <input type="file" class="file-input" name="url_diri" id="url_diri" accept="image/*">
                            <button class="remove-file" id="remove-diri" style="display: none;">
                                <i class="fas fa-times"></i>
                            </button>
                            <div class="upload-content" id="upload-content-diri">
                                <div class="upload-icon">
                                    <i class="fas fa-camera"></i>
                                </div>
                                <div class="upload-text">
                                    <div class="upload-title">Upload Photo</div>
                                    <div class="upload-subtitle">Click or drag to upload</div>
                                    <div class="upload-info">PNG, JPG up to 5MB</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

          <!-- Submit Button -->
          <div class="text-center mt-5">
            <button type="submit" class="submit-button w-100">
              <i class="fas fa-crown me-2"></i>
              <span id="submitText">Register as Hotel Resepsionis</span>
            </button>
          </div>
        </form>

        <!-- Footer -->
        <div class="form-footer">
          <p class="mb-0">Already have an account? <a href="{{route('login')}}">Sign In Here</a></p>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
  
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
   <script>
    function setupFileUpload(inputId, areaId, contentId, removeId) {
        const input = document.getElementById(inputId);
        const area = document.getElementById(areaId);
        const content = document.getElementById(contentId);
        const removeBtn = document.getElementById(removeId);

        // Bersihkan event listener sebelumnya untuk menghindari duplikasi
        area.replaceWith(area.cloneNode(true));
        const newArea = document.getElementById(areaId);
        const newContent = document.getElementById(contentId);
        const newRemoveBtn = document.getElementById(removeId);

        // Input file click handler - stop propagation
        input.addEventListener('click', (e) => {
            e.stopPropagation();
        });

        // Area click handler - langsung trigger input file
        newArea.addEventListener('click', () => {
            input.click();
        });

        // Drag and drop events
        newArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            newArea.classList.add('dragover');
        });

        newArea.addEventListener('dragleave', (e) => {
            e.preventDefault();
            newArea.classList.remove('dragover');
        });

        newArea.addEventListener('drop', (e) => {
            e.preventDefault();
            newArea.classList.remove('dragover');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                handleFile(files[0], input, newArea, newContent, newRemoveBtn);
            }
        });

        // File input change
        input.addEventListener('change', (e) => {
            if (e.target.files.length > 0) {
                handleFile(e.target.files[0], input, newArea, newContent, newRemoveBtn);
            }
        });

        // Remove file
        newRemoveBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            removeFile(input, newArea, newContent, newRemoveBtn, inputId);
        });
    }

    function handleFile(file, input, area, content, removeBtn) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('Please select an image file (PNG, JPG)');
            return;
        }

        // Validate file size (5MB)
        if (file.size > 5 * 1024 * 1024) {
            alert('File size must be less than 5MB');
            return;
        }

        // Create preview
        const reader = new FileReader();
        reader.onload = (e) => {
            showFilePreview(file, e.target.result, area, content, removeBtn);
        };
        reader.readAsDataURL(file);
    }

    function showFilePreview(file, imageSrc, area, content, removeBtn) {
        area.classList.add('has-file');
        removeBtn.style.display = 'flex';

        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        
        content.innerHTML = `
            <div class="file-preview">
                <img src="${imageSrc}" alt="Preview" class="preview-image">
                <div class="file-info">
                    <div class="file-name">${file.name}</div>
                    <div class="file-size">${fileSize} MB</div>
                </div>
            </div>
        `;
    }

    function removeFile(input, area, content, removeBtn, inputId) {
        input.value = '';
        area.classList.remove('has-file');
        removeBtn.style.display = 'none';

        // Reset to original content based on input type
        let iconClass, title;
        switch(inputId) {
            case 'url_ktp':
                iconClass = 'fas fa-id-card';
                title = 'Upload ID Card';
                break;
            case 'url_npwp':
                iconClass = 'fas fa-file-invoice-dollar';
                title = 'Upload Tax ID';
                break;
            case 'url_diri':
                iconClass = 'fas fa-camera';
                title = 'Upload Photo';
                break;
        }

        content.innerHTML = `
            <div class="upload-icon">
                <i class="${iconClass}"></i>
            </div>
            <div class="upload-text">
                <div class="upload-title">${title}</div>
                <div class="upload-subtitle">Click or drag to upload</div>
                <div class="upload-info">PNG, JPG up to 5MB</div>
            </div>
        `;
    }

    // Initialize all file uploads
    document.addEventListener('DOMContentLoaded', () => {
        setupFileUpload('url_ktp', 'upload-area-ktp', 'upload-content-ktp', 'remove-ktp');
        setupFileUpload('url_npwp', 'upload-area-npwp', 'upload-content-npwp', 'remove-npwp');
        setupFileUpload('url_diri', 'upload-area-diri', 'upload-content-diri', 'remove-diri');
    });
</script>
</body>
</html>