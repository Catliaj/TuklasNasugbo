<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Tourism Admin</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .login-card {
      background: white;
      border-radius: 20px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      overflow: hidden;
      max-width: 400px;
      width: 100%;
    }

    .login-header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      padding: 40px 30px;
      text-align: center;
      color: white;
    }

    .login-header i {
      font-size: 50px;
      margin-bottom: 15px;
    }

    .login-header h4 {
      margin: 0;
      font-weight: 600;
    }

    .login-body {
      padding: 40px 30px;
    }

    .form-control {
      padding: 12px 15px;
      border-radius: 10px;
      border: 1px solid #e0e0e0;
    }

    .form-control:focus {
      border-color: #667eea;
      box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }

    .btn-login {
      padding: 12px;
      border-radius: 10px;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      color: white;
      font-weight: 600;
      transition: transform 0.2s;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }

    .input-group-text {
      background: #f8f9fa;
      border: 1px solid #e0e0e0;
      border-right: none;
    }

    .input-group .form-control {
      border-left: none;
    }
  </style>
</head>

<body>
  <div class="login-card">
    <div class="login-header">
      <i class="bi bi-compass"></i>
      <h4>Login to Your Account</h4>
      <p class="mb-0 small"> </p>
    </div>

    <div class="login-body">
      <form id="loginForm" action="<?= base_url('/users/login') ?>" method="post">
        <div class="mb-3">
          <label class="form-label">Email Address</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-envelope"></i></span>
            <input type="email" class="form-control" name="InputEmail" placeholder="Enter your email" required>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label">Password</label>
          <div class="input-group">
            <span class="input-group-text"><i class="bi bi-lock"></i></span>
            <input type="password" class="form-control" name="InputPassword" placeholder="Enter your password" required>
          </div>
        </div>

        <div class="mb-3 form-check">
          <input type="checkbox" class="form-check-input" id="rememberMe">
          <label class="form-check-label" for="rememberMe">Remember me</label>
        </div>

        <button type="submit" class="btn btn-login w-100">
          <i class="bi bi-box-arrow-in-right me-2"></i>Login
        </button>

        <div class="text-center mt-3">
          <a href="#" class="text-decoration-none small">Forgot password?</a>
        </div>
      </form>

      
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
