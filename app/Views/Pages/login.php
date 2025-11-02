<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Tuklas Nasugbu</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
       <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Lato:wght@300;400;700&family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url("assets/css/login.css")?>">
</head>
<body>
    <div class="login-container">
        <!-- Form Section -->
        <div class="login-form-section">
            <div class="back-home">
                <a href="index.html">
                    <i class="bi bi-arrow-left"></i> Back to Home
                </a>
            </div>
            
            <div class="login-form-wrapper">
                <div class="logo-section">
                    <h1><i class="bi bi-airplane-fill"></i> Tuklas Nasugbu</h1>
                    <p>Welcome back, traveler!</p>
                </div>
                
                <h3 class="form-title">Login to Your Account</h3>
                
                <form id="loginForm" action="<?= base_url('/users/login') ?>" method="post">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control"  name="InputEmail" placeholder="Email Address" required>
                    </div>
                    
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password" name="InputPassword" placeholder="Password" required>
                    </div>
                    
                    <div class="forgot-password">
                        <a href="/under-development" id="forgotPasswordLink">Forgot Password?</a>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">
                            Remember me
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-login">Login</button>
                </form>
                
                <div class="divider">
                    <span>OR</span>
                </div>
                
                <div class="social-login">
                    <button class="btn-social" onclick="socialLogin('Google')">
                        <i class="bi bi-google"></i> Google
                    </button>
                    <button class="btn-social" onclick="socialLogin('Facebook')">
                        <i class="bi bi-facebook"></i> Facebook
                    </button>
                </div>
                
                <div class="signup-link">
                    Don't have an account? <a href="/under-development">Sign up here</a>
                </div>
            </div>
        </div>
        
        <!-- Image Section -->
        <div class="login-image-section">
            <div class="image-content">
                <h2>Start Your Adventure</h2>
                <p>Log in to access your personalized travel dashboard, save itineraries, and discover more of Nasugbu's hidden gems.</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
  <script>
    const loginForm = document.getElementById('loginForm');

    if (loginForm) {
      loginForm.addEventListener('submit', function(e) {
        e.preventDefault();

        Swal.fire({
          title: 'Logging in...',
          text: 'Please wait while we verify your credentials.',
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          }
        });

        setTimeout(() => {
          loginForm.submit();
        }, 1500);
      });
    }

    // ✅ Display session alerts from CodeIgniter
    <?php if (session()->getFlashdata('success')): ?>
      Swal.fire({
        icon: 'success',
        title: 'Welcome!',
        text: '<?= session()->getFlashdata('success') ?>',
        showConfirmButton: false,
        timer: 2000
      });
    <?php elseif (session()->getFlashdata('error')): ?>
      Swal.fire({
        icon: 'error',
        title: 'Oops...',
        text: '<?= session()->getFlashdata('error') ?>'
      });
    <?php endif; ?>
  </script>
    
</body>
</html>
