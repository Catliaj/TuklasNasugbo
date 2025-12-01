<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Verify Email - TuklasNasugbo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Email Verification</h5>
                    <p class="text-muted">Enter the 6-digit code sent to your Gmail address.</p>
                    <form id="otpForm" method="post" action="<?= base_url('verify-otp'); ?>">
                        <?= csrf_field(); ?>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?= esc($this->request->getGet('email')); ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="otp" class="form-label">Verification Code</label>
                            <input type="text" pattern="[0-9]{6}" maxlength="6" class="form-control" id="otp" name="otp" required placeholder="123456">
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <button type="submit" class="btn btn-primary me-3">Verify</button>
                            <button type="button" id="resendBtn" class="btn btn-outline-secondary" disabled>Resend Code</button>
                            <small id="timer" class="text-muted ms-2"></small>
                        </div>
                        <div id="feedback" class="small"></div>
                    </form>
                </div>
            </div>
            <p class="text-center mt-3"><a href="<?= base_url('/signup'); ?>">Back to Signup</a></p>
        </div>
    </div>
</div>
<script>
(function(){
    const form = document.getElementById('otpForm');
    const feedback = document.getElementById('feedback');
    const resendBtn = document.getElementById('resendBtn');
    const timerEl = document.getElementById('timer');
    let cooldown = 60; // seconds
    const startTimer = () => {
        resendBtn.disabled = true;
        const iv = setInterval(()=>{
            cooldown--; timerEl.textContent = 'Resend available in ' + cooldown + 's';
            if(cooldown <= 0){ clearInterval(iv); resendBtn.disabled = false; timerEl.textContent=''; }
        },1000);
    };
    startTimer();
    form.addEventListener('submit', function(e){
        e.preventDefault();
        feedback.textContent='Verifying...';
        fetch(form.action, {method:'POST', body:new FormData(form)})
        .then(r=>r.json())
        .then(data=>{
            if(data.status==='success'){
                feedback.className='small text-success';
                feedback.textContent=data.message;
                if(data.redirect){ setTimeout(()=>window.location.href=data.redirect, 800); }
            } else {
                feedback.className='small text-danger';
                feedback.textContent=data.message;
            }
        })
        .catch(()=>{feedback.className='small text-danger';feedback.textContent='Request failed.'});
    });
    resendBtn.addEventListener('click', function(){
        const email=document.getElementById('email').value;
        feedback.textContent='Resending code...';
        fetch('<?= base_url('verify-otp'); ?>?resend=1', {method:'POST', body:new URLSearchParams({email})})
        .then(r=>r.json())
        .then(data=>{
            if(data.status==='success'){ feedback.className='small text-success'; feedback.textContent='New code sent.'; cooldown=60; startTimer(); }
            else { feedback.className='small text-danger'; feedback.textContent=data.message; }
        })
        .catch(()=>{feedback.className='small text-danger';feedback.textContent='Resend failed.'});
    });
})();
</script>
</body>
</html>