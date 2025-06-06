<?php
session_start();

// Check if already logged in
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: admin.php");
    exit();
}

// Admin credentials
$admin_username = 'admin';
$admin_password = 'password';
$admin_username = 'admin1';
$admin_password = 'password';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === $admin_username && $password === $admin_password) {
        $_SESSION['admin_logged_in'] = true;
        header("Location: admin.php");
        exit();
    } else {
        $error_message = "Invalid login credentials.";
    }
}
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillSwap - Admin Login</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- GSAP -->
    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.5/gsap.min.js"></script>
    <style>
        :root {
            --primary: #4a90e2;
            --primary-dark: #357abd;
            --accent: #ff4d4d;
            --bg-light: #f5f7fa;
            --bg-dark: #1a202c;
            --shadow-neumorphic: 8px 8px 16px rgba(0, 0, 0, 0.1), -8px -8px 16px rgba(255, 255, 255, 0.9);
            --shadow-neumorphic-dark: 8px 8px 16px rgba(0, 0, 0, 0.4), -8px -8px 16px rgba(45, 55, 72, 0.9);
        }

        [data-theme="dark"] {
            --bg-light: #1a202c;
            --bg-dark: #2d3748;
            --text-primary: #e2e8f0;
            --shadow-neumorphic: var(--shadow-neumorphic-dark);
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: radial-gradient(circle at center, var(--bg-light), #d1d5db);
            color: #2d3748;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow-x: hidden;
            perspective: 1000px;
        }

        [data-theme="dark"] body {
            background: radial-gradient(circle at center, var(--bg-dark), #4a5568);
        }

        .parallax-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(74, 144, 226, 0.2), rgba(53, 122, 189, 0.2));
            z-index: -1;
            transform: translateZ(-100px) scale(1.1);
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            box-shadow: var(--shadow-neumorphic);
            transform-style: preserve-3d;
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        [data-theme="dark"] .login-container {
            background: rgba(45, 55, 72, 0.95);
            box-shadow: var(--shadow-neumorphic-dark);
        }

        .login-container:hover {
            transform: translateZ(30px) rotateX(8deg) rotateY(8deg);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .login-container::before {
            content: '';
            position: absolute;
            inset: 0;
            border: 2px solid transparent;
            border-radius: 16px;
            background: linear-gradient(45deg, #4a90e2, #0dcaf0) border-box;
            -webkit-mask: linear-gradient(#fff 0 0) padding-box, linear-gradient(#fff 0 0);
            mask-composite: exclude;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .login-container:hover::before {
            opacity: 1;
        }

        .input-icon {
            transition: transform 0.3s ease, opacity 0.3s ease;
            opacity: 0;
        }

        .form-control:focus + .input-icon {
            transform: translateX(-5px);
            opacity: 1;
        }

        .password-toggle {
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: var(--primary);
        }

        .btn-3d {
            transform-style: preserve-3d;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
        }

        .btn-3d:hover {
            transform: translateZ(10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .btn-3d:disabled {
            opacity: 0.7;
            transform: none;
            cursor: not-allowed;
        }

        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.4);
            transform: scale(0);
            animation: rippleEffect 0.6s linear;
            pointer-events: none;
        }

        @keyframes rippleEffect {
            to {
                transform: scale(4);
                opacity: 0;
            }
        }

        .error-message {
            animation: fadeIn 0.5s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        footer {
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.8), rgba(0, 0, 0, 0.6));
            backdrop-filter: blur(10px);
            color: #e2e8f0;
            transform-style: preserve-3d;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            position: relative;
            overflow: hidden;
        }

        footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320"><path fill="rgba(255,255,255,0.1)" fill-opacity="1" d="M0,192L48,186.7C96,181,192,171,288,160C384,149,480,139,576,149.3C672,160,768,192,864,192C960,192,1056,160,1152,144C1248,128,1344,128,1392,128L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path></svg>');
            background-size: cover;
            animation: wave 10s linear infinite;
        }

        @keyframes wave {
            0% { transform: translateX(0); }
            100% { transform: translateX(-1440px); }
        }

        footer a {
            transition: transform 0.3s ease, color 0.3s ease;
        }

        footer a:hover {
            color: #0dcaf0;
            transform: translateZ(10px);
        }

        .social-icon {
            transition: transform 0.4s ease;
        }

        .social-icon:hover {
            transform: rotateY(180deg);
        }

        @media (prefers-reduced-motion: reduce) {
            .login-container, .btn-3d, footer a, .error-message, .social-icon, .ripple {
                animation: none !important;
                transition: none !important;
                transform: none !important;
            }
        }
    </style>
</head>
<body class="antialiased" data-theme="light">
    <div class="parallax-bg"></div>
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="login-container w-full max-w-md p-8">
            <div class="bg-gradient-to-r from-blue-600 to-cyan-500 py-6 px-8 text-center rounded-t-xl">
                <h1 class="text-2xl font-semibold text-white mb-1">Admin Login</h1>
                <p class="text-blue-100 text-sm">Access the SkillSwap admin panel</p>
            </div>
            <div class="p-8">
                <form method="POST" action="" id="loginForm">
                    <!-- Username Input -->
                    <div class="mb-4 position-relative">
                        <label for="username" class="form-label text-gray-700 dark:text-gray-200 text-sm font-medium mb-1">Username</label>
                        <div class="relative">
                            <input
                                type="text"
                                id="username"
                                name="username"
                                class="form-control w-full py-3 px-4 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                placeholder="Enter username"
                                required
                                aria-describedby="usernameHelp"
                            />
                            <i class="fas fa-user input-icon text-gray-500 dark:text-gray-400 absolute right-4 top-1/2 transform -translate-y-1/2"></i>
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="mb-5 position-relative">
                        <label for="password" class="form-label text-gray-700 dark:text-gray-200 text-sm font-medium mb-1">Password</label>
                        <div class="relative">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control w-full py-3 px-4 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                placeholder="Enter password"
                                required
                                aria-describedby="passwordHelp"
                            />
                            <i class="fas fa-eye input-icon password-toggle text-gray-500 dark:text-gray-400 absolute right-4 top-1/2 transform -translate-y-1/2" data-toggle="password"></i>
                        </div>
                    </div>

                    <!-- Error Message -->
                    <?php if (isset($error_message)): ?>
                        <div id="errorMessage" class="error-message mb-4 p-3 text-sm text-red-700 bg-red-100 dark:bg-red-900 dark:text-red-200 rounded-lg" role="alert" aria-live="assertive">
                            <?php echo htmlspecialchars($error_message); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary btn-3d w-full py-3 px-4 bg-gradient-to-r from-blue-600 to-cyan-500 hover:from-blue-700 hover:to-cyan-600 text-white font-medium rounded-lg shadow-md hover:shadow-lg transition-all duration-300 relative overflow-hidden">
                        <span class="button-text">Login</span>
                        <span class="spinner hidden"><i class="fas fa-spinner fa-spin"></i></span>
                    </button>
                </form>
                <!-- Theme Toggle -->
                <div class="mt-6 text-center">
                    <button id="theme-toggle" class="theme-toggle text-gray-600 dark:text-gray-300 focus:outline-none p-2 rounded-full bg-gray-200 dark:bg-gray-700 relative overflow-hidden" aria-label="Toggle theme">
                        <i class="fas fa-moon text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="w-full py-12 transform-gpu" role="contentinfo" id="footer">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <div class="flex flex-col items-center md:items-start">
                    <h3 class="text-2xl font-bold text-gray-100 dark:text-gray-200 mb-4 transform hover:translate-z-10 transition-transform duration-300">SkillSwap</h3>
                    <p class="text-gray-400 dark:text-gray-300 text-sm">© 2025 SkillSwap. All Rights Reserved.</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-gray-100 dark:text-gray-200 mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="home.php" class="text-gray-400 dark:text-gray-300 hover:text-blue-400 dark:hover:text-blue-300 transition-colors duration-300 transform hover:translate-z-5">Home</a></li>
                        <li><a href="discover.html" class="text-gray-400 dark:text-gray-300 hover:text-blue-400 dark:hover:text-blue-300 transition-colors duration-300 transform hover:translate-z-5">Discover Skills</a></li>
                        <li><a href="connect.html" class="text-gray-400 dark:text-gray-300 hover:text-blue-400 dark:hover:text-blue-300 transition-colors duration-300 transform hover:translate-z-5">Swap Requests</a></li>
                        <li><a href="chat.php" class="text-gray-400 dark:text-gray-300 hover:text-blue-400 dark:hover:text-blue-300 transition-colors duration-300 transform hover:translate-z-5">Chats</a></li>
                        <li><a href="privacy-policy.php" class="text-gray-400 dark:text-gray-300 hover:text-blue-400 dark:hover:text-blue-300 transition-colors duration-300 transform hover:translate-z-5">Privacy Policy</a></li>
                        <li><a href="terms-of-service.php" class="text-gray-400 dark:text-gray-300 hover:text-blue-400 dark:hover:text-blue-300 transition-colors duration-300 transform hover:translate-z-5">Terms of Service</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold text-gray-100 dark:text-gray-200 mb-4">Connect With Us</h4>
                    <div class="flex justify-center md:justify-start gap-4">
                        <a href="#" class="text-gray-400 dark:text-gray-300 social-icon" aria-label="Twitter" data-bs-toggle="tooltip" data-bs-placement="top" title="Follow us on Twitter">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 dark:text-gray-300 social-icon" aria-label="LinkedIn" data-bs-toggle="tooltip" data-bs-placement="top" title="Connect on LinkedIn">
                            <i class="fab fa-linkedin text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 dark:text-gray-300 social-icon" aria-label="GitHub" data-bs-toggle="tooltip" data-bs-placement="top" title="Check our GitHub">
                            <i class="fab fa-github text-xl"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript -->
    <script>
        // Theme Toggle with Ripple Effect
        const themeToggle = document.getElementById('theme-toggle');
        const body = document.body;
        const storedTheme = localStorage.getItem('theme') || 'light';
        body.dataset.theme = storedTheme;
        themeToggle.innerHTML = storedTheme === 'dark' ? '<i class="fas fa-sun text-xl"></i>' : '<i class="fas fa-moon text-xl"></i>';

        themeToggle.addEventListener('click', (e) => {
            const newTheme = body.dataset.theme === 'light' ? 'dark' : 'light';
            body.dataset.theme = newTheme;
            localStorage.setItem('theme', newTheme);
            themeToggle.innerHTML = newTheme === 'dark' ? '<i class="fas fa-sun text-xl"></i>' : '<i class="fas fa-moon text-xl"></i>';
            gsap.to(themeToggle, { rotationY: 360, duration: 0.5 });

            // Ripple Effect
            const ripple = document.createElement('span');
            ripple.classList.add('ripple');
            const rect = themeToggle.getBoundingClientRect();
            const size = Math.max(rect.width, rect.height);
            ripple.style.width = ripple.style.height = `${size}px`;
            ripple.style.left = `${e.clientX - rect.left - size / 2}px`;
            ripple.style.top = `${e.clientY - rect.top - size / 2}px`;
            themeToggle.appendChild(ripple);
            setTimeout(() => ripple.remove(), 600);
        });

        // Password Toggle
        const passwordToggle = document.querySelector('.password-toggle');
        const passwordInput = document.getElementById('password');
        passwordToggle.addEventListener('click', () => {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            passwordToggle.classList.toggle('fa-eye');
            passwordToggle.classList.toggle('fa-eye-slash');
        });

        // Form Submission with Spinner
        const loginForm = document.getElementById('loginForm');
        const submitButton = loginForm.querySelector('button[type="submit"]');
        const buttonText = submitButton.querySelector('.button-text');
        const spinner = submitButton.querySelector('.spinner');
        
        loginForm.addEventListener('submit', (e) => {
            submitButton.disabled = true;
            buttonText.classList.add('hidden');
            spinner.classList.remove('hidden');
            setTimeout(() => {
                submitButton.disabled = false;
                buttonText.classList.remove('hidden');
                spinner.classList.add('hidden');
            }, 2000); // Simulate async submission
        });

        // GSAP Animations
        gsap.from('.login-container', { opacity: 0, y: 50, duration: 1, ease: 'power3.out' });
        gsap.from('.login-container .form-control', {
            opacity: 0,
            x: -30,
            stagger: 0.2,
            duration: 0.8,
            ease: 'power3.out',
            delay: 0.5
        });
        gsap.from('#footer', {
            opacity: 0,
            y: 50,
            duration: 1,
            ease: 'power3.out',
            scrollTrigger: {
                trigger: '#footer',
                start: 'top 90%',
            }
        });

        // Error Message Animation
        const errorMessage = document.getElementById('errorMessage');
        if (errorMessage) {
            gsap.from(errorMessage, { opacity: 0, y: -10, duration: 0.5, ease: 'power2.out' });
            setTimeout(() => {
                gsap.to(errorMessage, { opacity: 0, duration: 0.5, onComplete: () => errorMessage.classList.add('hidden') });
            }, 3000);
        }

        // Parallax Background
        window.addEventListener('mousemove', (e) => {
            const x = (e.clientX / window.innerWidth - 0.5) * 20;
            const y = (e.clientY / window.innerHeight - 0.5) * 20;
            gsap.to('.parallax-bg', { x, y, duration: 0.5, ease: 'power2.out' });
        });

        // Initialize Tooltips
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
    </script>
</body>
</html>