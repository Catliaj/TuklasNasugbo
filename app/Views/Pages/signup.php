<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Tuklas Nasugbu</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&family=Lato:wght@300;400;700&family=Pacifico&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url("assets/css/signup.css")?>">
</head>
<body>
    <div class="signup-container">
        <!-- Form Section -->
        <div class="signup-form-section">
            <div class="back-home">
                <a href="/">
                    <i class="bi bi-arrow-left"></i> Back to Home
                </a>
            </div>
            
            <div class="signup-form-wrapper">
                <div class="logo-section">
                    <h1><i class="bi bi-airplane-fill"></i> Tuklas Nasugbu</h1>
                    <p>Join our community of travelers!</p>
                </div>
                
                <h3 class="form-title">Create Your Account</h3>
                
                <form id="signupForm">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" class="form-control" placeholder="Full Name" required>
                    </div>
                    
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control" placeholder="Email Address" required>
                    </div>
                    
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-phone"></i></span>
                        <input type="tel" class="form-control" placeholder="Phone Number (optional)">
                    </div>
                    
                    <div class="input-group mb-2">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" class="form-control" id="password" placeholder="Password" required>
                    </div>
                    
                    <div class="password-strength">
                        <div class="password-strength-bar" id="strengthBar"></div>
                    </div>
                    
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm Password" required>
                    </div>
                    
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="terms" required>
                        <label class="form-check-label" for="terms">
                            I agree to the <a href="#" style="color: var(--ocean-blue);">Terms & Conditions</a>
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-signup">Create Account</button>
                </form>
                
                <div class="divider">
                    <span>OR</span>
                </div>
                
                <div class="social-signup">
                    <button class="btn-social" onclick="socialSignup('Google')">
                        <i class="bi bi-google"></i> Google
                    </button>
                    <button class="btn-social" onclick="socialSignup('Facebook')">
                        <i class="bi bi-facebook"></i> Facebook
                    </button>
                </div>
                
                <div class="login-link">
                    Already have an account? <a href="login.html">Login here</a>
                </div>
            </div>
        </div>
        
        <!-- Image Section -->
        <div class="signup-image-section">
            <div class="image-content">
                <h2>Begin Your Journey</h2>
                <p>Create an account to unlock exclusive features, personalized itineraries, and save your favorite destinations in Nasugbu.</p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Password Strength Checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('strengthBar');
            
            let strength = 0;
            if (password.length >= 8) strength++;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            strengthBar.className = 'password-strength-bar';
            if (strength <= 2) {
                strengthBar.classList.add('strength-weak');
            } else if (strength === 3) {
                strengthBar.classList.add('strength-medium');
            } else {
                strengthBar.classList.add('strength-strong');
            }
        });
        
        // Signup Form Submission
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                return;
            }
            
            if (password.length < 8) {
                alert('Password must be at least 8 characters long!');
                return;
            }
            
            // Simulate successful signup
            alert('Account created successfully! Redirecting to login...');
            window.location.href = 'login.html';
        });
        
        // Social Signup
        function socialSignup(provider) {
            alert('Social signup with ' + provider + ' will be implemented here.');
        }
    </script>
</body>
</html>
