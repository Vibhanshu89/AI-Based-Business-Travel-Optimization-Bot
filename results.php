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
    <title>AI Travel Optimizer - Results</title>
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
        .results { max-width: 90%; width: 800px; margin: 2rem auto; background: rgba(0, 0, 0, 0.9); padding: 2rem; border-radius: 20px; box-shadow: 0 0 40px rgba(255, 215, 0, 0.5); position: relative; z-index: 1; transition: transform 0.3s ease; }
        .results:hover { transform: scale(1.02); }
        h2 { font-size: 2rem; font-family: 'Orbitron', sans-serif; color: #ffd700; margin-bottom: 1.5rem; text-align: center; }
        h3 { font-size: 1.5rem; color: #ff4500; margin-bottom: 1rem; }
        .result-item { padding: 1.2rem; margin: 0.8rem 0; background: rgba(255, 255, 255, 0.1); border-radius: 12px; font-size: 1.1rem; font-weight: 600; color: #e0e0e0; transition: all 0.3s ease; display: flex; justify-content: space-between; align-items: center; cursor: pointer; border: 1px solid rgba(255, 215, 0, 0.3); }
        .result-item:hover { background: linear-gradient(135deg, rgba(255, 215, 0, 0.2), rgba(255, 69, 0, 0.2)); transform: translateX(15px); box-shadow: 0 0 20px rgba(255, 69, 0, 0.6); color: #fff; }
        .price { color: #ffd700; font-weight: 700; }
        footer { text-align: center; padding: 2rem; background: rgba(0, 0, 0, 0.75); position: relative; z-index: 1; box-shadow: 0 0 30px rgba(255, 215, 0, 0.3); margin-top: 2rem; }
        footer a { color: #ffd700; text-decoration: none; margin: 0 1rem; }
        footer a:hover { color: #ff4500; }
        @media (max-width: 600px) { header h1 { font-size: 2rem; } .results { padding: 1.5rem; width: 95%; } }
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
                <?php if ($logged_in): ?>
                    <span>Welcome, <?php echo $username; ?></span>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                <?php endif; ?>
            </div>
        </nav>
    </header>
    <section class="results">
        <h2>Your Travel Options</h2>
        <div id="flightResults"></div>
        <div id="hotelResults"></div>
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

        const travelData = JSON.parse(localStorage.getItem('travelData')) || {};

        // Dummy flight and hotel data (since no API integration here)
        const flights = [
            { id: 1, airline: "AirX", price: `₹${Math.floor(Math.random() * 5000) + 18000}`, departure: travelData.departDate, return: travelData.returnDate, details: { duration: "5h 30m", stops: "Non-stop", rating: "4.2/5" } },
            { id: 2, airline: "FlyHigh", price: `₹${Math.floor(Math.random() * 5000) + 20000}`, departure: travelData.departDate, return: travelData.returnDate, details: { duration: "6h", stops: "1 Stop", rating: "4.0/5" } },
            { id: 3, airline: "SkyJet", price: `₹${Math.floor(Math.random() * 5000) + 17000}`, departure: travelData.departDate, return: travelData.returnDate, details: { duration: "5h 45m", stops: "Non-stop", rating: "4.5/5" } }
        ];

        const hotels = [
            { id: 1, name: "Hotel Elite", price: `₹${Math.floor(Math.random() * 5000) + 8000}/night`, checkIn: travelData.departDate, checkOut: travelData.returnDate, details: { rooms: "2 Bedrooms", rating: "4.5/5", image: "https://images.unsplash.com/photo-1618773928121-c3222925c79f" } },
            { id: 2, name: "Business Inn", price: `₹${Math.floor(Math.random() * 5000) + 10000}/night`, checkIn: travelData.departDate, checkOut: travelData.returnDate, details: { rooms: "1 Suite", rating: "4.2/5", image: "https://images.unsplash.com/photo-1566073771259-6a8506099945" } },
            { id: 3, name: "Grand Palace", price: `₹${Math.floor(Math.random() * 5000) + 9000}/night`, checkIn: travelData.departDate, checkOut: travelData.returnDate, details: { rooms: "3 Bedrooms", rating: "4.8/5", image: "https://images.unsplash.com/photo-1571003123894-1f0594d2b5d9" } }
        ];

        function displayResults() {
            const flightResults = document.getElementById('flightResults');
            const hotelResults = document.getElementById('hotelResults');

            flightResults.innerHTML = '<h3>Cheapest Flights</h3>' + 
                flights.map(f => `<div class="result-item" onclick="showDetails('flight', ${f.id})">${f.airline}: <span class="price">${f.price}</span> (Depart: ${f.departure}, Return: ${f.return})</div>`).join('');

            hotelResults.innerHTML = '<h3>Cheapest Hotels</h3>' + 
                hotels.map(h => `<div class="result-item" onclick="showDetails('hotel', ${h.id})">${h.name}: <span class="price">${h.price}</span> (Check-in: ${h.checkIn}, Check-out: ${h.checkOut})</div>`).join('');

            localStorage.setItem('flights', JSON.stringify(flights));
            localStorage.setItem('hotels', JSON.stringify(hotels));
        }

        function showDetails(type, id) {
            localStorage.setItem('selectedType', type);
            localStorage.setItem('selectedId', id);
            window.location.href = 'details.php';
        }

        displayResults();
    </script>
</body>
</html>