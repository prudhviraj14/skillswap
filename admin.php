<?php
// Database connection details
$host = 'localhost';
$db = 'skillswap_';  // Database name
$user = 'root';      // Username for your database
$pass = 'prudhvi@30'; // Password for your database

// Create a connection to MySQL
$conn = new mysqli($host, $user, $pass, $db);

// Check if the connection is successful
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch all collaboration requests
$query = "SELECT * FROM youtube_collaborations";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillSwap - Admin Panel</title>
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
            transition: background 0.5s ease, color 0.5s ease;
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

        .header-glass {
            background: linear-gradient(135deg, rgba(74, 144, 226, 0.85), rgba(53, 122, 189, 0.85));
            backdrop-filter: blur(12px);
            transform-style: preserve-3d;
            transition: transform 0.3s ease;
        }

        .table-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            box-shadow: var(--shadow-neumorphic);
            transform-style: preserve-3d;
            transition: transform 0.4s ease, box-shadow 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        [data-theme="dark"] .table-container {
            background: rgba(45, 55, 72, 0.95);
            box-shadow: var(--shadow-neumorphic-dark);
        }

        .table-container:hover {
            transform: translateZ(20px) rotateX(5deg) rotateY(5deg);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .table-container::before {
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

        .table-container:hover::before {
            opacity: 1;
        }

        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            overflow-x: auto;
            display: block;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        [data-theme="dark"] th, [data-theme="dark"] td {
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        tr:hover td {
            transform: translateZ(10px);
            background: rgba(0, 0, 0, 0.05);
        }

        [data-theme="dark"] tr:hover td {
            background: rgba(255, 255, 255, 0.05);
        }

        th {
            background: linear-gradient(135deg, #4a90e2, #0dcaf0);
            color: white;
            position: sticky;
            top: 0;
            z-index: 10;
            cursor: pointer;
        }

        th:hover {
            background: linear-gradient(135deg, #357abd, #0aa8cc);
        }

        .sort-icon {
            transition: transform 0.3s ease;
            margin-left: 0.5rem;
        }

        .sort-asc .sort-icon {
            transform: rotate(180deg);
        }

        .btn-3d {
            transform-style: preserve-3d;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .btn-3d:hover {
            transform: translateZ(10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

        .ripple {
            position: absolute;
            border-radius: 50%;
            background: rgba(11, 129, 233, 0.4);
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
            .table-container, .btn-3d, footer a, .social-icon, .ripple, tr:hover td {
                animation: none !important;
                transition: none !important;
                transform: none !important;
            }
        }

        @media (max-width: 640px) {
            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>
</head>
<body class="antialiased" data-theme="light">
    <div class="parallax-bg"></div>
    <!-- Header -->
    <header class="header-glass text-white py-4 shadow-2xl sticky top-0 z-50" id="header">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center">
                <h1 class="text-3xl font-bold tracking-tight">SkillSwap Admin Panel</h1>
                <div class="flex items-center gap-4">
                    <button id="theme-toggle" class="theme-toggle text-white focus:outline-none p-2 rounded-full bg-gray-800/50 relative overflow-hidden" aria-label="Toggle theme">
                        <i class="fas fa-moon text-xl"></i>
                    </button>
                    <a href="admin_logout.php" class="btn btn-danger btn-3d rounded-xl px-4 py-2 hover:bg-red-600">Logout</a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto my-10 px-4" role="main">
        <div class="table-container p-8 rounded-3xl shadow-2xl transform-gpu">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">YouTuber Collaboration Requests</h1>
                <div class="relative">
                    <input
                        type="text"
                        id="search"
                        placeholder="Search by channel or program..."
                        class="form-control py-2 px-4 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        aria-label="Search collaboration requests"
                    />
                    <i class="fas fa-search absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 dark:text-gray-400"></i>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table role="grid" aria-describedby="collaboration-table-info">
                    <thead>
                        <tr>
                            <th scope="col" class="sort" data-sort="channel_name">Channel Name <i class="fas fa-sort sort-icon"></i></th>
                            <th scope="col">Channel Link</th>
                            <th scope="col" class="sort" data-sort="program_to_train">Program to Train <i class="fas fa-sort sort-icon"></i></th>
                            <th scope="col">Certification</th>
                            <th scope="col">Contact Email</th>
                            <th scope="col" class="sort" data-sort="created_at">Created At <i class="fas fa-sort sort-icon"></i></th>
                        </tr>
                    </thead>
                    <tbody id="collaboration-table">
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['channel_name']); ?></td>
                            <td><a href="<?php echo htmlspecialchars($row['channel_link']); ?>" target="_blank" class="text-blue-500 hover:text-blue-600 dark:hover:text-blue-400" data-bs-toggle="tooltip" data-bs-placement="top" title="Visit channel">View Channel</a></td>
                            <td><?php echo htmlspecialchars($row['program_to_train']); ?></td>
                            <td data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo $row['provides_certification'] == 1 ? 'Provides certification' : 'No certification'; ?>">
                                <?php echo $row['provides_certification'] == 1 ? 'Yes' : 'No'; ?>
                            </td>
                            <td><?php echo htmlspecialchars($row['contact_email']); ?></td>
                            <td><?php echo $row['created_at']; ?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
            <div id="no-results" class="hidden mt-4 p-3 text-sm text-gray-600 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg" role="alert" aria-live="polite">
                No results found.
            </div>
        </div>
    </main>

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

        // GSAP Animations
        gsap.from('.table-container', { opacity: 0, y: 50, duration: 1, ease: 'power3.out' });
        gsap.from('.table-container tr', {
            opacity: 0,
            y: 20,
            stagger: 0.1,
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

        // Parallax Background
        window.addEventListener('mousemove', (e) => {
            const x = (e.clientX / window.innerWidth - 0.5) * 20;
            const y = (e.clientY / window.innerHeight - 0.5) * 20;
            gsap.to('.parallax-bg', { x, y, duration: 0.5, ease: 'power2.out' });
        });

        // Table Search and Sort
        const searchInput = document.getElementById('search');
        const tableBody = document.getElementById('collaboration-table');
        const noResults = document.getElementById('no-results');
        const rows = Array.from(tableBody.querySelectorAll('tr'));

        searchInput.addEventListener('input', () => {
            const query = searchInput.value.toLowerCase();
            let visibleRows = 0;
            rows.forEach(row => {
                const channel = row.cells[0].textContent.toLowerCase();
                const program = row.cells[2].textContent.toLowerCase();
                if (channel.includes(query) || program.includes(query)) {
                    row.style.display = '';
                    visibleRows++;
                } else {
                    row.style.display = 'none';
                }
            });
            noResults.classList.toggle('hidden', visibleRows > 0);
        });

        const sortButtons = document.querySelectorAll('.sort');
        sortButtons.forEach(button => {
            button.addEventListener('click', () => {
                const sortKey = button.dataset.sort;
                const isAsc = !button.classList.contains('sort-asc');
                button.classList.toggle('sort-asc', isAsc);
                
                rows.sort((a, b) => {
                    const aValue = a.cells[sortKey === 'channel_name' ? 0 : sortKey === 'program_to_train' ? 2 : 5].textContent;
                    const bValue = b.cells[sortKey === 'channel_name' ? 0 : sortKey === 'program_to_train' ? 2 : 5].textContent;
                    return isAsc ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
                });

                tableBody.innerHTML = '';
                rows.forEach(row => tableBody.appendChild(row));
            });
        });

        // Initialize Tooltips
        document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el));
    </script>
</body>
</html>

<?php
// Close the database connection
$conn->close();
?>