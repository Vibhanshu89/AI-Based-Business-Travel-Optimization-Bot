# AI Travel Optimizer

A futuristic travel planning web application powered by artificial intelligence. This project integrates a PHP frontend with a Flask (Python) backend to provide users with a seamless experience for registering, logging in, and planning trips using a chatbot interface. The design features high-tech animations, a 3D video background, and a neon aesthetic.

---

## Features
- **User Authentication**: Register and login with a unique username and password.
- **AI Chatbot**: Plan your trip by chatting with an AI-powered bot (e.g., "i want to go delhi").
- **Travel Options**: View dummy flight and hotel options with detailed views.
- **High-Tech UI**: Neon gradients, animations, and a 3D video background for a futuristic feel.
- **Team Showcase**: Meet the team behind the project on the "About Us" page.
- **Contact Us**: Reach out for support or inquiries.

---

## Tech Stack
- **Frontend**: PHP, HTML, CSS, JavaScript
- **Backend**: Flask (Python)
- **Database**: SQLite (via Flask) + MySQL (optional for PHP)
- **Server**: XAMPP (for PHP) + Flask development server
- **Styling**: Custom CSS with Orbitron and Poppins fonts, neon gradients, and animations
- **Dependencies**: 
  - PHP: cURL extension (for API calls)
  - Python: Flask (`pip install flask`)

---

## Project Structure
AI-Travel-Optimizer/
├── C:\xampp1\htdocs\vibhansu\ (PHP Frontend)
│   ├── index.php          # Home page with chatbot
│   ├── login.php          # Login page
│   ├── register.php       # Registration page
│   ├── logout.php         # Logout functionality
│   ├── results.php        # Travel options page
│   ├── details.php        # Detailed view of travel options
│   ├── contactus.php      # Contact Us page
│   └── aboutus.php        # About Us page with team
├── C:\vibhansu_flask\ (Flask Backend)
│   └── app.py             # Flask API (register, login, travel_plan)
└── travel.db              # SQLite database (created by Flask)


#to run this project 
(1)- download XAMPP (for PHP) + Flask development server
(2)- start MYSQL and Apache
(3)-then open project file in code editor
(4)-the write domain- http://localhost/vibhansu/index.php on chrome
(5)- For dataset open- http://localhost/phpmyadmin/ 
(6)- open run on this domain-http://localhost/vibhansu/index.php;