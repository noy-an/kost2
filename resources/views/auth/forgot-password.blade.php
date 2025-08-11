<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Forgot Password - Reset Your Password</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #667eea;
            --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --secondary-color: #f093fb;
            --success-color: #4ade80;
            --danger-color: #f87171;
            --dark-color: #1f2937;
            --light-gray: #f8fafc;
            --border-radius: 16px;
            --box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
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

        /* Animated background elements */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: float 20s ease-in-out infinite;
            z-index: 0;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }

        .forgot-container {
            position: relative;
            z-index: 1;
            max-width: 450px;
            width: 100%;
        }

        .forgot-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 3rem 2.5rem;
            transition: all 0.3s ease;
            animation: slideUp 0.8s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .forgot-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
        }

        .logo-container {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo {
            width: 80px;
            height: 80px;
            background: var(--primary-gradient);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        .logo i {
            font-size: 2rem;
            color: white;
        }

        .forgot-title {
            color: var(--dark-color);
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .forgot-subtitle {
            color: #6b7280;
            font-size: 0.95rem;
            text-align: center;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .form-floating {
            margin-bottom: 1.5rem;
        }

        .form-floating > .form-control {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.2rem 1rem 0.8rem;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
        }

        .form-floating > .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
            background: white;
        }

        .form-floating > label {
            color: #6b7280;
            font-weight: 500;
        }

        .btn-submit {
            background: var(--primary-gradient);
            border: none;
            border-radius: 12px;
            padding: 0.85rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-submit::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .btn-submit:hover::before {
            left: 100%;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .btn-back {
            background: transparent;
            border: 2px solid #e5e7eb;
            color: #6b7280;
            border-radius: 12px;
            padding: 0.7rem 1.5rem;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            width: 100%;
            justify-content: center;
            margin-top: 1rem;
        }

        .btn-back:hover {
            border-color: var(--primary-color);
            color: var(--primary-color);
            background: rgba(102, 126, 234, 0.05);
            text-decoration: none;
        }

        .alert {
            border-radius: 12px;
            border: none;
            margin-bottom: 1.5rem;
            padding: 1rem;
            font-weight: 500;
        }

        .alert-success {
            background: rgba(74, 222, 128, 0.1);
            color: #059669;
            border-left: 4px solid var(--success-color);
        }

        .alert-danger {
            background: rgba(248, 113, 113, 0.1);
            color: #dc2626;
            border-left: 4px solid var(--danger-color);
        }

        .alert ul {
            margin: 0;
            padding-left: 1rem;
        }

        /* Loading animation */
        .btn-submit.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-submit.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border: 2px solid transparent;
            border-top: 2px solid white;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            right: 20px;
            top: 50%;
            transform: translateY(-50%);
        }

        @keyframes spin {
            0% { transform: translateY(-50%) rotate(0deg); }
            100% { transform: translateY(-50%) rotate(360deg); }
        }

        /* Responsive design */
        @media (max-width: 576px) {
            .forgot-card {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }
            
            .forgot-title {
                font-size: 1.5rem;
            }
        }

        /* Success checkmark animation */
        .success-checkmark {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--success-color);
            margin-bottom: 1rem;
            animation: successPop 0.6s ease-out;
        }

        @keyframes successPop {
            0% { transform: scale(0); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .success-checkmark i {
            color: white;
            font-size: 2rem;
        }
    </style>
</head>

<body>
    <div class="forgot-container">
        <div class="forgot-card">
            <div class="logo-container">
                <div class="logo">
                    <i class="fas fa-lock"></i>
                </div>
                <h1 class="forgot-title">Forgot Password?</h1>
                <p class="forgot-subtitle">
                    No worries! Enter your email address and we'll send you a reset link to get back into your account.
                </p>
            </div>

          <form action="{{ route('password.whatsapp') }}" method="POST">
    @csrf

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="form-floating mb-3">
        <input type="text" class="form-control" id="phone" name="phone" placeholder="Nomor WhatsApp" required>
        <label for="phone"><i class="fab fa-whatsapp me-2"></i>Nomor WhatsApp</label>
    </div>

    <button type="submit" class="btn btn-submit w-100">
        <i class="fas fa-paper-plane me-2"></i>
        Kirim Link Reset via WA
    </button>

    <a href="{{ route('login') }}" class="btn btn-back w-100">
        <i class="fas fa-arrow-left me-2"></i>
        Kembali ke Login
    </a>
</form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.getElementById('forgotForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const email = document.getElementById('email');
            const submitBtn = document.getElementById('submitBtn');
            const successAlert = document.getElementById('successAlert');
            const errorAlert = document.getElementById('errorAlert');
            
            // Reset previous states
            form.classList.remove('was-validated');
            successAlert.classList.add('d-none');
            errorAlert.classList.add('d-none');
            
            // Validate email
            if (!email.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }
            
            // Show loading state
            submitBtn.classList.add('loading');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
            
            // Simulate API call
            setTimeout(() => {
                // Reset button state
                submitBtn.classList.remove('loading');
                submitBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Send Reset Link';
                
                // Show success message
                successAlert.classList.remove('d-none');
                
                // Optionally clear the form
                email.value = '';
                form.classList.remove('was-validated');
                
                // Focus success message for accessibility
                successAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
            }, 2000);
        });
        
        function goBack() {
            // In a real application, this would navigate to the login page
            alert('Redirecting to login page...');
        }
        
        // Add some interactive feedback
        document.getElementById('email').addEventListener('input', function() {
            const successAlert = document.getElementById('successAlert');
            const errorAlert = document.getElementById('errorAlert');
            
            if (!successAlert.classList.contains('d-none')) {
                successAlert.classList.add('d-none');
            }
            if (!errorAlert.classList.contains('d-none')) {
                errorAlert.classList.add('d-none');
            }
        });
    </script>
</body>
</html>