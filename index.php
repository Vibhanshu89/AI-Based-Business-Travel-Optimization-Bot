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
    <title>AI Travel Optimizer</title>
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
        .chatbot-container { max-width: 90%; width: 600px; margin: 2rem auto; background: rgba(0, 0, 0, 0.9); padding: 2rem; border-radius: 20px; box-shadow: 0 0 40px rgba(255, 215, 0, 0.5); position: relative; z-index: 1; transition: transform 0.3s ease; }
        .chatbot-container:hover { transform: scale(1.02); }
        .chatbox { height: 250px; overflow-y: auto; padding: 1rem; background: rgba(255, 255, 255, 0.05); border-radius: 10px; margin-bottom: 1rem; border: 1px solid rgba(255, 215, 0, 0.3); }
        .message { padding: 0.8rem; margin: 0.5rem 0; border-radius: 8px; font-size: 1rem; }
        .user-message { background: linear-gradient(135deg, #ffd700, #ff4500); color: #000; text-align: right; }
        .bot-message { background: linear-gradient(135deg, #ff4500, #ff1493); color: #fff; text-align: left; }
        .chat-input { display: flex; gap: 1rem; }
        input#chatInput { flex: 1; padding: 1rem; border: 2px solid #ffd700; border-radius: 10px; background: rgba(255, 255, 255, 0.1); color: #fff; font-size: 1rem; transition: all 0.3s; }
        input#chatInput:hover, input#chatInput:focus { border-color: #ff4500; box-shadow: 0 0 15px #ff4500; outline: none; }
        button { padding: 1rem 1.5rem; border: none; border-radius: 10px; background: linear-gradient(135deg, #ffd700, #ff4500); color: #000; font-size: 1rem; cursor: pointer; transition: all 0.3s; }
        button:hover { background: linear-gradient(135deg, #ff4500, #ff1493); box-shadow: 0 0 20px #ff4500; transform: scale(1.05); }
        footer { text-align: center; padding: 2rem; background: rgba(0, 0, 0, 0.75); position: relative; z-index: 1; box-shadow: 0 0 30px rgba(255, 215, 0, 0.3); margin-top: 2rem; }
        footer a { color: #ffd700; text-decoration: none; margin: 0 1rem; }
        footer a:hover { color: #ff4500; }
        @media (max-width: 600px) { header h1 { font-size: 2rem; } .chatbot-container { padding: 1.5rem; width: 95%; } .chatbox { height: 200px; } }
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
    <section class="chatbot-container">
        <div class="chatbox" id="chatbox">
            <div class="message bot-message">Hi! Tell me where you want to travel.</div>
        </div>
        <div class="chat-input">
            <input type="text" id="chatInput" placeholder="Type your message..." required>
            <button onclick="sendMessage()">Send</button>
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

        const chatbox = document.getElementById('chatbox');
        const chatInput = document.getElementById('chatInput');

        function sendMessage() {
            const userInput = chatInput.value.trim();
            if (!userInput) return;

            const userMessage = document.createElement('div');
            userMessage.className = 'message user-message';
            userMessage.textContent = userInput;
            chatbox.appendChild(userMessage);

            processInput(userInput);

            chatInput.value = '';
            chatbox.scrollTop = chatbox.scrollHeight;
        }

        function processInput(input) {
            const lowerInput = input.toLowerCase();
            let departure, destination, departDate, returnDate;

            const cityRegex = /(?:mujhe|from)?\s*([a-z]+)\s*(?:se|to|tak)?\s*([a-z]+)/i;
            const match = lowerInput.match(cityRegex);

            if (match) {
                departure = match[1].charAt(0).toUpperCase() + match[1].slice(1);
                destination = match[2].charAt(0).toUpperCase() + match[2].slice(1);

                const today = new Date();
                departDate = today.toISOString().split('T')[0];
                returnDate = new Date(today.setDate(today.getDate() + 7)).toISOString().split('T')[0];

                const botMessage = document.createElement('div');
                botMessage.className = 'message bot-message';
                botMessage.textContent = `Got it! Planning your trip from ${departure} to ${destination}. Departing on ${departDate}, returning on ${returnDate}. Let's optimize it!`;
                chatbox.appendChild(botMessage);

                setTimeout(() => {
                    const travelData = { departure, destination, departDate, returnDate };
                    localStorage.setItem('travelData', JSON.stringify(travelData));
                    window.location.href = 'results.php';
                }, 1500);
            } else {
                const botMessage = document.createElement('div');
                botMessage.className = 'message bot-message';
                botMessage.textContent = "Sorry, I didn't understand. Please say something like 'Mujhe Delhi se Mumbai tak jana hai.'";
                chatbox.appendChild(botMessage);
            }

            chatbox.scrollTop = chatbox.scrollHeight;
        }

        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') sendMessage();
        });
    </script>
</body>
</html>