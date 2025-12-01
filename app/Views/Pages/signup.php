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
                        <input type="text" class="form-control" id="fullName" placeholder="Full Name" required>
                    </div>
                    
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control" id="email" placeholder="Email Address" required>
                    </div>
                    
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="bi bi-phone"></i></span>
                        <input type="tel" class="form-control" id="touristContact" placeholder="Phone Number (optional)">
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

    <!-- Verification Waiting Overlay -->
    <div id="verifyOverlay" style="display:none;position:fixed;inset:0;background:rgba(255,255,255,0.96);z-index:9999">
        <div style="position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;width:90%;max-width:520px">
            <div class="spinner-border text-primary" role="status" style="width:3rem;height:3rem">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h3 class="mt-4">Check your Gmail</h3>
            <p class="text-muted">We sent a verification link to your Gmail. Please click the Verify button in the email to complete your registration. Keep this window open — we'll be ready once you're verified.</p>
            <p class="mt-3"><small>If you don't see it, check Spam/Promotions.</small></p>
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
            const email = document.getElementById('email').value.trim();
            const fullName = document.getElementById('fullName').value.trim();
            const touristContact = document.getElementById('touristContact').value.trim();
            
            if (password !== confirmPassword) {
                alert('Passwords do not match!');
                return;
            }
            
            if (password.length < 8) {
                alert('Password must be at least 8 characters long!');
                return;
            }
            
            // Call backend to start email verification signup
            const formData = new URLSearchParams();
            const [firstName, ...rest] = fullName.split(' ');
            const lastName = rest.join(' ');
            formData.append('firstName', firstName || '');
            formData.append('middleName', '');
            formData.append('lastName', lastName || '');
            formData.append('email', email);
            formData.append('role', 'tourist');
            formData.append('password', password);
            formData.append('confirmPassword', confirmPassword);
            formData.append('touristContact', touristContact);
            formData.append('touristAddress', '');
            formData.append('emergencyContact', '');
            formData.append('emergencyNumber', '');
            formData.append('businessName', '');
            formData.append('businessContact', '');
            formData.append('businessAddress', '');
            formData.append('govIdType', '');
            formData.append('govIdNumber', '');

            // Show waiting overlay immediately
            const overlay = document.getElementById('verifyOverlay');
            overlay.style.display = 'block';

            fetch('<?= base_url('signup/submit') ?>', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: formData.toString()
            }).then(async (res) => {
                const data = await res.json().catch(() => ({}));
                if (data.status === 'verification_sent') {
                    // Keep overlay visible; user will click link in email.
                    // Optionally we could start a poll to check token consumption.
                } else {
                    overlay.style.display = 'none';
                    const msg = data.message || 'Signup failed. Please try again.';
                    alert(msg);
                }
            }).catch(() => {
                overlay.style.display = 'none';
                alert('Network error starting verification. Please try again.');
            });
        });
        
        // Social Signup
        function socialSignup(provider) {
            alert('Social signup with ' + provider + ' will be implemented here.');
        }
    </script>
</body>
</html>
