<?php
session_start();
$logged_in = isset($_SESSION['user_id']);
$username = $logged_in && isset($_SESSION['username']) ? $_SESSION['username'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - AI Travel Optimizer</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { color: #fff; min-height: 100vh; position: relative; background: linear-gradient(135deg, #1a0b2e, #2e1a47); overflow-y: auto; }
        #customCursor { position: absolute; width: 25px; height: 25px; background: radial-gradient(circle, #ffd700, #ff4500); border-radius: 50%; pointer-events: none; z-index: 1000; transition: transform 0.2s; }
        video#bgVideo { position: fixed; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: -1; filter: brightness(0.7) contrast(1.3); }
        header { text-align: center; padding: 2rem; background: rgba(0, 0, 0, 0.75); position: relative; z-index: 1; box-shadow: 0 0 30px rgba(255, 215, 0, 0.3); }
        header h1 { font-size: 2.5rem; font-family: 'Orbitron', sans-serif; text-transform: uppercase; letter-spacing: 2px; animation: neonGlow 1.5s infinite alternate; }
        @keyframes neonGlow { 0% { text-shadow: 0 0 10px #ffd700, 0 0 20px #ff4500; } 100% { text-shadow: 0 0 20px #ffd700, 0 0 40px #ff4500; } }
        nav { display: flex; justify-content: space-between; align-items: center; padding: 1rem 2rem; }
        nav a, nav span { color: #ffd700; text-decoration: none; font-size: 1.2rem; padding: 0.5rem 1rem; transition: all 0.3s; }
        nav a:hover { background: linear-gradient(135deg, #ff4500, #ff1493); border-radius: 10px; box-shadow: 0 0 20px #ff4500; }
        .contact-container { max-width: 90%; width: 600px; margin: 2rem auto; background: rgba(0, 0, 0, 0.9); padding: 2rem; border-radius: 20px; box-shadow: 0 0 40px rgba(255, 215, 0, 0.5); position: relative; z-index: 1; transition: transform 0.3s ease; }
        .contact-container:hover { transform: scale(1.02); }
        h2 { font-size: 2rem; font-family: 'Orbitron', sans-serif; color: #ffd700; margin-bottom: 1.5rem; text-align: center; }
        p { font-size: 1.2rem; color: #e0e0e0; margin-bottom: 1rem; text-align: center; }
        .contact-info { margin: 1rem 0; padding: 1rem; background: rgba(255, 255, 255, 0.05); border-radius: 10px; }
        footer { text-align: center; padding: 2rem; background: rgba(0, 0, 0, 0.75); position: relative; z-index: 1; box-shadow: 0 0 30px rgba(255, 215, 0, 0.3); margin-top: 2rem; }
        @media (max-width: 600px) { header h1 { font-size: 2rem; } .contact-container { padding: 1.5rem; width: 95%; } }
    </style>
</head>
<body>
    <video id="bgVideo" autoplay muted loop>
        <source src="https://cdn.pixabay.com/video/2023/10/23/187158-876314614_large.mp4" type="video/mp4">
    </video>
    <div id="customCursor"></div>
    <header>
    <nav>
        <h1>AI Travel Optimizer</h1>
        <div>
            <a href="index.php">Home</a>
            <a href="contactus.php">Contact Us</a>
            <a href="aboutus.php">About Us</a>
            <?php if ($logged_in): ?>
                <span>Welcome, <?php echo $username; ?></span>
                <a href="logout.php">Logout</a>
            <?php else: ?>
                <a href="register.php">Register</a>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
    </nav>
</header>
    <section class="contact-container">
        <h2>Contact Us</h2>
        <p>Have questions about your next trip? Reach out to us!</p>
        <div class="contact-info">
            <p>Email: <a href="mailto:support@aitraveloptimizer.com" style="color: #ffd700;">support@aitraveloptimizer.com</a></p>
            <p>Phone: +91 98765 43210</p>
            <p>Address: 123 Travel Lane, AI City, India</p>
        </div>
        <p>We're here to optimize your travel experience with cutting-edge AI technology!</p>
    </section>
    <footer>
        <p>© 2025 AI Travel Optimizer | Made with ❤️ for Travelers</p>
    </footer>
    <script>
        const cursor = document.getElementById('customCursor');
        document.addEventListener('mousemove', (e) => {
            cursor.style.left = `${e.clientX - 12.5}px`;
            cursor.style.top = `${e.clientY - 12.5}px`;
        });
    </script>
</body>
</html>