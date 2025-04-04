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
    <title>About Us - AI Travel Optimizer</title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }
        body { color: #fff; min-height: 100vh; position: relative; background: linear-gradient(135deg, #1a0b2e, #2e1a47); overflow-y: auto; }
        #customCursor { position: absolute; width: 25px; height: 25px; background: radial-gradient(circle, #ffd700, #ff4500); border-radius: 50%; pointer-events: none; z-index: 1000; transition: transform 0.2s; }
        video#bgVideo { position: fixed; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: -1; filter: brightness(0.8) contrast(1.4); }
        header { text-align: center; padding: 2rem; background: rgba(0, 0, 0, 0.85); position: relative; z-index: 1; box-shadow: 0 0 30px rgba(255, 215, 0, 0.5); }
        header h1 { font-size: 2.5rem; font-family: 'Orbitron', sans-serif; text-transform: uppercase; letter-spacing: 3px; animation: neonGlow 1.5s infinite alternate; }
        @keyframes neonGlow { 0% { text-shadow: 0 0 10px #ffd700, 0 0 20px #ff4500, 0 0 30px #ff1493; } 100% { text-shadow: 0 0 20px #ffd700, 0 0 40px #ff4500, 0 0 60px #ff1493; } }
        nav { display: flex; justify-content: space-between; align-items: center; padding: 1rem 2rem; }
        nav a, nav span { color: #ffd700; text-decoration: none; font-size: 1.2rem; padding: 0.5rem 1rem; transition: all 0.3s ease; }
        nav a:hover { background: linear-gradient(135deg, #ff4500, #ff1493); border-radius: 10px; box-shadow: 0 0 20px #ff4500; transform: scale(1.1); }
        .about-container { max-width: 1000px; margin: 2rem auto; padding: 2rem; background: rgba(0, 0, 0, 0.9); border-radius: 20px; box-shadow: 0 0 50px rgba(255, 215, 0, 0.6); position: relative; z-index: 1; }
        h2 { font-size: 2rem; font-family: 'Orbitron', sans-serif; color: #ffd700; text-align: center; margin-bottom: 1.5rem; animation: fadeIn 1s ease-in; }
        p { font-size: 1.2rem; color: #e0e0e0; text-align: center; margin-bottom: 1rem; animation: fadeIn 1.5s ease-in; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .mission-vision { display: flex; justify-content: space-between; margin: 2rem 0; gap: 2rem; }
        .mission-vision div { flex: 1; background: rgba(255, 255, 255, 0.1); padding: 1.5rem; border-radius: 15px; border: 1px solid rgba(255, 215, 0, 0.3); transition: all 0.5s ease; }
        .mission-vision div:hover { transform: translateY(-10px); box-shadow: 0 0 30px rgba(255, 69, 0, 0.7); background: rgba(255, 215, 0, 0.2); }
        .mission-vision h3 { font-size: 1.5rem; color: #ff4500; margin-bottom: 1rem; animation: slideInLeft 1s ease-in; }
        @keyframes slideInLeft { from { opacity: 0; transform: translateX(-30px); } to { opacity: 1; transform: translateX(0); } }
        .team { margin-top: 2rem; }
        .team h2 { margin-bottom: 2rem; }
        .team-members { display: flex; flex-wrap: wrap; justify-content: center; gap: 2rem; }
        .team-member { background: rgba(255, 255, 255, 0.1); padding: 1.5rem; border-radius: 15px; width: 220px; text-align: center; border: 1px solid rgba(255, 215, 0, 0.3); transition: all 0.5s ease; }
        .team-member:hover { transform: scale(1.1) rotate(2deg); box-shadow: 0 0 40px rgba(255, 69, 0, 0.8); background: linear-gradient(135deg, rgba(255, 215, 0, 0.2), rgba(255, 69, 0, 0.2)); }
        .team-member img { width: 100px; height: 100px; border-radius: 50%; margin-bottom: 1rem; transition: transform 0.3s ease; }
        .team-member:hover img { transform: rotateY(360deg); }
        .team-member h3 { font-size: 1.2rem; color: #ffd700; animation: fadeIn 2s ease-in; }
        .team-member p { font-size: 1rem; color: #e0e0e0; }
        footer { text-align: center; padding: 2rem; background: rgba(0, 0, 0, 0.85); position: relative; z-index: 1; box-shadow: 0 0 30px rgba(255, 215, 0, 0.5); margin-top: 2rem; }
        @media (max-width: 600px) { header h1 { font-size: 2rem; } .about-container { padding: 1.5rem; width: 95%; } .mission-vision { flex-direction: column; } .team-member { width: 100%; } }
    </style>
</head>
<body>
    <!-- 3D High-Tech Background Video -->
    <video id="bgVideo" autoplay muted loop>
        <source src="https://cdn.pixabay.com/video/2021/07/14/80611-579858514_large.mp4" type="video/mp4">
        <!-- 3D futuristic tunnel video from Pixabay -->
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
    <section class="about-container">
        <h2>About AI Travel Optimizer</h2>
        <p>We harness the power of artificial intelligence to redefine travel planning, delivering cutting-edge solutions for a seamless journey into the future.</p>
        
        <div class="mission-vision">
            <div class="mission">
                <h3>Our Mission</h3>
                <p>To revolutionize travel with AI, providing personalized, efficient, and futuristic travel plans for explorers worldwide.</p>
            </div>
            <div class="vision">
                <h3>Our Vision</h3>
                <p>To lead the travel industry into a new era, where every trip is powered by intelligent technology and boundless possibilities.</p>
            </div>
        </div>

        <div class="team">
            <h2>Our Team</h2>
            <div class="team-members">
                <div class="team-member">
                    <h3>Vibhanshu</h3>
                    <p>Founder & AI Innovator</p>
                </div>
                <div class="team-member">
                    <!-- <img src="https://via.placeholder.com/100" alt="Rahul"> -->
                    <h3>Ankit Kumar</h3>
                    <p>Travel Futurist</p>
                </div>
                <div class="team-member">
                    <!-- <img src="https://via.placeholder.com/100" alt="Priya"> -->
                    <h3>Ashmita Jha</h3>
                    <p>Interface Architect</p>
                </div>
            </div>
        </div>
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