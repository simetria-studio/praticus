@extends('layouts.app', ['hideNavbar' => true])

@section('content')
<div class="login-page-container">
    <!-- Background fixo azul simples -->
    <div class="fixed-background"></div>

    <div class="container-fluid h-100 d-flex align-items-center justify-content-center">
        <div class="login-form-container">
            <div class="login-card">
                <div class="card-header-custom">
                    <div class="logo-container mb-4">
                        <div class="logo-wrapper">
                            <img src="{{ asset('img/logo.jpg') }}" alt="Logo" class="login-logo">
                            <div class="logo-glow"></div>
                        </div>
                    </div>
                    <h1 class="system-title">Certificado Digital Escolar</h1>
                    <h2 class="login-title">Bem-vindo de volta!</h2>
                    <p class="login-subtitle">Faça login para acessar sua conta</p>
                </div>

                <div class="card-body-custom">
                    @if($errors->any())
                        <div class="alert alert-danger alert-modern" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <div>
                                @foreach($errors->all() as $error)
                                    <div>{{ $error }}</div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if(session('status'))
                        <div class="alert alert-success alert-modern" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="login-form">
                        @csrf

                        <div class="form-group-modern">
                            <div class="input-container">
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="email"
                                       class="form-control-modern @error('email') is-invalid @enderror"
                                       id="email"
                                       name="email"
                                       value="{{ old('email') }}"
                                       required
                                       autocomplete="email"
                                       autofocus
                                       placeholder=" ">
                                <label for="email" class="form-label-modern">E-mail</label>
                                <div class="input-border"></div>
                            </div>
                            @error('email')
                                <div class="invalid-feedback-modern">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-group-modern">
                            <div class="input-container">
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password"
                                       class="form-control-modern @error('password') is-invalid @enderror"
                                       id="password"
                                       name="password"
                                       required
                                       autocomplete="current-password"
                                       placeholder=" ">
                                <label for="password" class="form-label-modern">Senha</label>
                                <div class="input-border"></div>
                                <button type="button" class="password-toggle" onclick="togglePassword()">
                                    <i class="fas fa-eye" id="toggleIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback-modern">
                                    <i class="fas fa-exclamation-circle me-1"></i>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="form-check-modern">
                            <input type="checkbox"
                                   class="form-check-input-modern"
                                   id="remember"
                                   name="remember"
                                   {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label-modern" for="remember">
                                Lembrar-me
                            </label>
                        </div>

                        <button type="submit" class="btn-login">
                            <span class="btn-text">Entrar</span>
                            <div class="btn-loader">
                                <div class="spinner"></div>
                            </div>
                            <i class="fas fa-arrow-right btn-icon"></i>
                        </button>

                        @if (Route::has('password.request'))
                            <div class="forgot-password">
                                <a href="{{ route('password.request') }}" class="forgot-link">
                                    <i class="fas fa-key me-1"></i>
                                    Esqueceu sua senha?
                                </a>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Reset e configurações básicas */
* {
    box-sizing: border-box;
}

:root {
    --primary-color: #058D4F;
    --secondary-color: #C82222;
    --accent-color: #667eea;
    --dark-blue: #1a365d;
    --light-blue: #e6f3ff;
}

.login-page-container {
    min-height: 100vh;
    position: relative;
    overflow: hidden;
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* Fundo fixo azul sólido */
.fixed-background {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
    background: #058D4F;
    background-attachment: fixed;
}



/* Container do formulário */
.login-form-container {
    width: 100%;
    max-width: 520px;
    position: relative;
    z-index: 2;
    padding: 2rem;
}

.login-card {
    background: rgba(255, 255, 255, 0.95);
    border-radius: 24px;
    overflow: hidden;
    box-shadow:
        0 32px 64px rgba(0, 0, 0, 0.15),
        0 16px 32px rgba(0, 0, 0, 0.1),
        inset 0 1px 0 rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    animation: card-entrance 1s ease-out;
    transform: translateZ(0);
}

@keyframes card-entrance {
    from {
        opacity: 0;
        transform: translateY(30px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.card-header-custom {
    padding: 3.5rem 2.5rem 2rem;
    text-align: center;
    background: rgba(5, 141, 79, 0.05);
    position: relative;
}

.card-header-custom::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: rgba(5, 141, 79, 0.4);
}

.logo-wrapper {
    position: relative;
    display: inline-block;
    margin-bottom: 1.5rem;
}

.login-logo {
    height: 120px;
    max-width: 120px;
    object-fit: contain;
    position: relative;
    z-index: 2;
    filter: drop-shadow(0 8px 16px rgba(5, 141, 79, 0.2));
    transition: all 0.4s ease;
    animation: logo-entrance 1.2s ease-out;
}

.logo-glow {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 140px;
    height: 140px;
    background: radial-gradient(circle, rgba(5, 141, 79, 0.3) 0%, transparent 70%);
    border-radius: 50%;
    animation: glow-pulse 3s ease-in-out infinite;
    z-index: 1;
}

@keyframes logo-entrance {
    0% {
        opacity: 0;
        transform: translateY(-30px) scale(0.8) rotate(-10deg);
    }
    60% {
        transform: translateY(5px) scale(1.05) rotate(2deg);
    }
    100% {
        opacity: 1;
        transform: translateY(0) scale(1) rotate(0deg);
    }
}

@keyframes glow-pulse {
    0%, 100% { opacity: 0.5; transform: translate(-50%, -50%) scale(1); }
    50% { opacity: 0.8; transform: translate(-50%, -50%) scale(1.1); }
}

.login-logo:hover {
    transform: scale(1.05) rotate(2deg);
    filter: drop-shadow(0 12px 24px rgba(0, 74, 173, 0.3));
}

.system-title {
    color: var(--primary-color);
    font-weight: 800;
    font-size: 2.2rem;
    margin-bottom: 1rem;
    text-shadow: 0 2px 4px rgba(5, 141, 79, 0.1);
}



.login-title {
    color: #2d3748;
    font-weight: 600;
    margin-bottom: 0.8rem;
    font-size: 1.6rem;
    animation: slide-in-down 0.8s ease-out 0.2s both;
}

.login-subtitle {
    color: #718096;
    margin-bottom: 0;
    font-size: 1rem;
    animation: slide-in-down 0.8s ease-out 0.4s both;
}

@keyframes slide-in-down {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card-body-custom {
    padding: 1rem 2.5rem 2.5rem;
}

/* Alertas modernos */
.alert-modern {
    border: none;
    border-radius: 16px;
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    margin-bottom: 1.5rem;
    backdrop-filter: blur(10px);
    animation: alert-slide-in 0.5s ease-out;
}

@keyframes alert-slide-in {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.alert-danger {
    background: rgba(245, 87, 108, 0.1);
    color: #e53e3e;
    border-left: 4px solid #e53e3e;
}

.alert-success {
    background: rgba(72, 187, 120, 0.1);
    color: #38a169;
    border-left: 4px solid #38a169;
}

/* Campos de entrada modernos */
.form-group-modern {
    margin-bottom: 1.8rem;
    animation: form-slide-in 0.6s ease-out;
    animation-fill-mode: both;
}

.form-group-modern:nth-child(1) { animation-delay: 0.1s; }
.form-group-modern:nth-child(2) { animation-delay: 0.2s; }
.form-group-modern:nth-child(3) { animation-delay: 0.3s; }

@keyframes form-slide-in {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.input-container {
    position: relative;
}

.form-control-modern {
    width: 100%;
    padding: 1.2rem 1.2rem 1.2rem 3.2rem;
    border: 2px solid #e2e8f0;
    border-radius: 16px;
    font-size: 1rem;
    background: rgba(248, 250, 252, 0.8);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    outline: none;
    font-weight: 500;
}

.form-control-modern:focus {
    border-color: var(--primary-color);
    background: rgba(255, 255, 255, 0.95);
    box-shadow:
        0 0 0 4px rgba(5, 141, 79, 0.15),
        0 8px 25px rgba(5, 141, 79, 0.2);
    transform: translateY(-2px);
}

.form-control-modern:focus + .form-label-modern,
.form-control-modern:not(:placeholder-shown) + .form-label-modern {
    transform: translateY(-2rem) scale(0.85);
    color: var(--primary-color);
    font-weight: 600;
}

.input-icon {
    position: absolute;
    left: 1.2rem;
    top: 50%;
    transform: translateY(-50%);
    color: #a0aec0;
    z-index: 2;
    transition: all 0.3s ease;
    font-size: 1.1rem;
}

.form-control-modern:focus ~ .input-icon {
    color: var(--primary-color);
    transform: translateY(-50%) scale(1.1);
}

.form-label-modern {
    position: absolute;
    left: 3.2rem;
    top: 50%;
    transform: translateY(-50%);
    background: transparent;
    padding: 0 0.5rem;
    color: #a0aec0;
    pointer-events: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 1;
    font-weight: 500;
}

.input-border {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 0;
    height: 3px;
    background: var(--primary-color);
    border-radius: 2px;
    transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.form-control-modern:focus ~ .input-border {
    width: 100%;
}

.password-toggle {
    position: absolute;
    right: 1.2rem;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #a0aec0;
    cursor: pointer;
    transition: all 0.3s ease;
    z-index: 2;
    font-size: 1.1rem;
    padding: 0.5rem;
    border-radius: 8px;
}

.password-toggle:hover {
    color: var(--primary-color);
    background: rgba(5, 141, 79, 0.1);
}

.invalid-feedback-modern {
    color: #e53e3e;
    font-size: 0.875rem;
    margin-top: 0.75rem;
    display: flex;
    align-items: center;
    font-weight: 500;
}

.form-check-modern {
    margin-bottom: 2rem;
    display: flex;
    align-items: center;
    animation: form-slide-in 0.6s ease-out 0.4s both;
}

.form-check-input-modern {
    width: 1.25rem;
    height: 1.25rem;
    margin-right: 0.75rem;
    accent-color: var(--accent-color);
    cursor: pointer;
}

.form-check-label-modern {
    color: #4a5568;
    font-size: 0.95rem;
    cursor: pointer;
    font-weight: 500;
}

/* Botão de login aprimorado */
.btn-login {
    width: 100%;
    padding: 1.2rem 2rem;
    background: var(--primary-color);
    border: none;
    border-radius: 16px;
    color: white;
    font-weight: 600;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
    margin-bottom: 1.5rem;
    box-shadow: 0 8px 25px rgba(5, 141, 79, 0.4);
    animation: button-entrance 0.8s ease-out 0.5s both;
}

@keyframes button-entrance {
    from {
        opacity: 0;
        transform: translateY(20px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.btn-login:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 15px 35px rgba(5, 141, 79, 0.5);
    background: #046a3f;
}

.btn-login:active {
    transform: translateY(-1px) scale(1.01);
}

.btn-login::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s ease;
}

.btn-login:hover::before {
    left: 100%;
}

.btn-text {
    transition: opacity 0.3s ease;
}

.btn-loader {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.spinner {
    width: 24px;
    height: 24px;
    border: 3px solid rgba(255, 255, 255, 0.3);
    border-top: 3px solid white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.btn-icon {
    margin-left: 0.5rem;
    transition: all 0.3s ease;
}

.btn-login:hover .btn-icon {
    transform: translateX(8px) scale(1.1);
}

.forgot-password {
    text-align: center;
    animation: form-slide-in 0.6s ease-out 0.6s both;
}

.forgot-link {
    color: var(--primary-color);
    text-decoration: none;
    font-size: 0.95rem;
    font-weight: 500;
    transition: all 0.3s ease;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    display: inline-block;
}

.forgot-link:hover {
    color: #046a3f;
    background: rgba(5, 141, 79, 0.1);
    transform: translateY(-1px);
}

/* Responsividade aprimorada */
@media (max-width: 768px) {
    .login-form-container {
        max-width: 95%;
        padding: 1rem;
    }

    .login-card {
        border-radius: 20px;
    }

    .card-header-custom {
        padding: 2.5rem 1.5rem 1.5rem;
    }

    .card-body-custom {
        padding: 1rem 1.5rem 2rem;
    }

    .login-logo {
        height: 90px;
        max-width: 90px;
    }

    .logo-glow {
        width: 110px;
        height: 110px;
    }

    .system-title {
        font-size: 1.8rem;
    }

    .login-title {
        font-size: 1.4rem;
    }

    .form-control-modern {
        padding: 1rem 1rem 1rem 2.8rem;
    }

    .input-icon {
        left: 1rem;
    }

    .form-label-modern {
        left: 2.8rem;
    }
}

@media (max-width: 480px) {
    .system-title {
        font-size: 1.6rem;
    }

    .login-title {
        font-size: 1.2rem;
    }

    .login-subtitle {
        font-size: 0.9rem;
    }
}

/* Performance optimizations */
.login-card,
.btn-login,
.form-control-modern {
    will-change: transform;
}

/* Accessibility improvements */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .login-card {
        border: 2px solid #000;
    }

    .form-control-modern {
        border: 2px solid #000;
    }
}
</style>

<script>
function togglePassword() {
    const passwordField = document.getElementById('password');
    const toggleIcon = document.getElementById('toggleIcon');

    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordField.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

// Enhanced loading animation
document.querySelector('.login-form').addEventListener('submit', function(e) {
    const btn = document.querySelector('.btn-login');
    const btnText = document.querySelector('.btn-text');
    const btnLoader = document.querySelector('.btn-loader');
    const btnIcon = document.querySelector('.btn-icon');

    btnText.style.opacity = '0';
    btnIcon.style.opacity = '0';
    btnLoader.style.opacity = '1';
    btn.style.pointerEvents = 'none';
    btn.style.transform = 'translateY(-1px) scale(1.01)';
});

// Enhanced form interactions
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.form-control-modern');

    inputs.forEach((input, index) => {
        // Pre-populate animation
        if (input.value) {
            input.nextElementSibling.style.transform = 'translateY(-2rem) scale(0.85)';
            input.nextElementSibling.style.color = 'var(--primary-color)';
        }

        // Add focus ripple effect
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'translateY(-2px)';
        });

        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'translateY(0)';
        });

        // Stagger animation delays
        input.parentElement.parentElement.style.animationDelay = `${0.1 + index * 0.1}s`;
    });

    // Add keyboard navigation improvements
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && e.target.type !== 'submit') {
            const form = e.target.closest('form');
            const inputs = Array.from(form.querySelectorAll('input'));
            const currentIndex = inputs.indexOf(e.target);

            if (currentIndex < inputs.length - 1) {
                e.preventDefault();
                inputs[currentIndex + 1].focus();
            }
        }
    });

    // Add touch feedback for mobile
    if ('ontouchstart' in window) {
        const btn = document.querySelector('.btn-login');
        btn.addEventListener('touchstart', function() {
            this.style.transform = 'translateY(-1px) scale(1.01)';
        });

        btn.addEventListener('touchend', function() {
            setTimeout(() => {
                this.style.transform = 'translateY(-3px) scale(1.02)';
            }, 100);
        });
    }
});

// Add smooth scrolling for better UX on mobile
if (window.innerHeight < 700) {
    document.addEventListener('focusin', function(e) {
        if (e.target.matches('input')) {
            setTimeout(() => {
                e.target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });
            }, 300);
        }
    });
}
</script>

<!-- Enhanced Font Loading -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
@endsection
