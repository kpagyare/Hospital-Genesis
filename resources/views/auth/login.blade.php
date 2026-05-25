<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Hospital Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('assets/css/hms.css') }}" rel="stylesheet">
</head>
<body>
<div class="login-page">
    <div class="login-card">
        <!-- Logo -->
        <div class="login-logo">
            <i class="bi bi-hospital-fill"></i>
        </div>
        <h1 class="login-title">Welcome Back</h1>
        <p class="login-subtitle">Hospital Management System — Sign in to continue</p>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success small">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger small">
                @foreach($errors->all() as $error) <div>{{ $error }}</div> @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="login-form">
            @csrf

            <!-- Email -->
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <div class="login-input-group">
                    <i class="bi bi-envelope input-icon"></i>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror"
                           placeholder="Enter your email" required autofocus>
                </div>
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="login-input-group">
                    <i class="bi bi-lock input-icon"></i>
                    <input type="password" name="password" id="passwordInput"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="Enter your password" required>
                    <button type="button" onclick="togglePassword()"
                            style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:#9ca3af;cursor:pointer;">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <!-- Remember Me -->
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                    <label class="form-check-label small" for="remember">Remember me</label>
                </div>
            </div>

            <button type="submit" class="btn btn-login">
                <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
            </button>
        </form>

        <!-- Demo accounts -->
        <div class="mt-4 p-3 rounded" style="background:#f8fafc;border:1px dashed #e2e8f0;">
            <p class="text-center text-muted small mb-2 fw-600">Demo Login Credentials</p>
            <div class="row g-1">
                <div class="col-6">
                    <div class="small text-center p-2 rounded" style="background:#fff;border:1px solid #e2e8f0;cursor:pointer;" onclick="fillLogin('admin@hms.com','admin123')">
                        <i class="bi bi-shield-check text-primary"></i><br>
                        <span style="font-size:11px;color:#4a5568;">Super Admin</span>
                    </div>
                </div>
                <div class="col-6">
                    <div class="small text-center p-2 rounded" style="background:#fff;border:1px solid #e2e8f0;cursor:pointer;" onclick="fillLogin('doctor@hms.com','doctor123')">
                        <i class="bi bi-person-badge" style="color:var(--accent);"></i><br>
                        <span style="font-size:11px;color:#4a5568;">Doctor</span>
                    </div>
                </div>
            </div>
        </div>

        <p class="text-center text-muted mt-3" style="font-size:12px;">
            &copy; {{ date('Y') }} Hospital Management System
        </p>
    </div>
</div>

<script>
function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon  = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'bi bi-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'bi bi-eye';
    }
}
function fillLogin(email, pass) {
    document.querySelector('[name="email"]').value = email;
    document.querySelector('[name="password"]').value = pass;
}
</script>
</body>
</html>
