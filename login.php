<?php
session_start();
$conn = new mysqli("localhost", "root", "", "travel_db");
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

if (isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT id, username, password FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if ($password === $user['password']) { // Plaintext comparison
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.php");
            exit();
        } else {
            $message = "Invalid password.";
        }
    } else {
        $message = "Username not found.";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - AI Travel Optimizer</title>
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
        .login-container { max-width: 90%; width: 400px; margin: 2rem auto; background: rgba(0, 0, 0, 0.9); padding: 2rem; border-radius: 20px; box-shadow: 0 0 40px rgba(255, 215, 0, 0.5); position: relative; z-index: 1; transition: transform 0.3s ease; }
        .login-container:hover { transform: scale(1.02); }
        form { display: flex; flex-direction: column; gap: 1rem; }
        input { padding: 1rem; border: 2px solid #ffd700; border-radius: 10px; background: rgba(255, 255, 255, 0.1); color: #fff; font-size: 1rem; transition: all 0.3s; }
        input:hover, input:focus { border-color: #ff4500; box-shadow: 0 0 15px #ff4500; outline: none; }
        button { padding: 1rem; border: none; border-radius: 10px; background: linear-gradient(135deg, #ffd700, #ff4500); color: #000; font-size: 1rem; cursor: pointer; transition: all 0.3s; }
        button:hover { background: linear-gradient(135deg, #ff4500, #ff1493); box-shadow: 0 0 20px #ff4500; transform: scale(1.05); }
        .message { color: #ff4500; text-align: center; margin-bottom: 1rem; }
        footer { text-align: center; padding: 2rem; background: rgba(0, 0, 0, 0.75); position: relative; z-index: 1; box-shadow: 0 0 30px rgba(255, 215, 0, 0.3); margin-top: 2rem; }
        footer a { color: #ffd700; text-decoration: none; margin: 0 1rem; }
        footer a:hover { color: #ff4500; }
        @media (max-width: 600px) { header h1 { font-size: 2rem; } .login-container { padding: 1.5rem; width: 95%; } }
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
            <a href="register.php">Register</a>
            <a href="login.php">Login</a>
        </div>
    </nav>
</header>
    <section class="login-container">
        <h2 style="font-family: 'Orbitron', sans-serif; color: #ffd700; text-align: center; margin-bottom: 1.5rem;">Login</h2>
        <?php if ($message): ?>
            <p class="message"><?php echo $message; ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </section>
    <footer>
        <p>© 2025 AI Travel Optimizer | Made with ❤️ for Travelers</p>
        <div>
            <a href="index.php">Home</a>
            <a href="login.php">Login</a>
        </div>
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
<?php $conn->close(); ?>