from flask import Flask, request, jsonify
import sqlite3
import os

app = Flask(__name__)

# Database setup
def init_db():
    conn = sqlite3.connect('travel.db')
    c = conn.cursor()
    c.execute('''CREATE TABLE IF NOT EXISTS users 
                 (id INTEGER PRIMARY KEY AUTOINCREMENT, username TEXT UNIQUE, password TEXT)''')
    c.execute('''CREATE TABLE IF NOT EXISTS travel_plans 
                 (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, departure TEXT, destination TEXT, depart_date TEXT, return_date TEXT)''')
    conn.commit()
    conn.close()

# Initialize database
if not os.path.exists('travel.db'):
    init_db()

# Register endpoint
@app.route('/register', methods=['POST'])
def register():
    data = request.get_json()
    username = data.get('username')
    password = data.get('password')  # Plaintext password as per your request

    conn = sqlite3.connect('travel.db')
    c = conn.cursor()
    try:
        c.execute("INSERT INTO users (username, password) VALUES (?, ?)", (username, password))
        conn.commit()
        return jsonify({"message": "Registration successful! Please login."}), 200
    except sqlite3.IntegrityError:
        return jsonify({"message": "Username already taken. Try another one."}), 400
    finally:
        conn.close()

# Login endpoint
@app.route('/login', methods=['POST'])
def login():
    data = request.get_json()
    username = data.get('username')
    password = data.get('password')

    conn = sqlite3.connect('travel.db')
    c = conn.cursor()
    c.execute("SELECT id, username FROM users WHERE username = ? AND password = ?", (username, password))
    user = c.fetchone()
    conn.close()

    if user:
        return jsonify({"user_id": user[0], "username": user[1], "message": "Login successful!"}), 200
    else:
        return jsonify({"message": "Invalid username or password."}), 401

# Travel plan endpoint
@app.route('/travel_plan', methods=['POST'])
def travel_plan():
    data = request.get_json()
    user_id = data.get('user_id')
    departure = data.get('departure')
    destination = data.get('destination')
    depart_date = data.get('depart_date')
    return_date = data.get('return_date')

    conn = sqlite3.connect('travel.db')
    c = conn.cursor()
    c.execute("INSERT INTO travel_plans (user_id, departure, destination, depart_date, return_date) VALUES (?, ?, ?, ?, ?)", 
              (user_id, departure, destination, depart_date, return_date))
    conn.commit()
    conn.close()

    return jsonify({"message": "Travel plan saved!", "departure": departure, "destination": destination, 
                    "depart_date": depart_date, "return_date": return_date}), 200

if __name__ == '__main__':
    app.run(debug=True, host='0.0.0.0', port=5000)