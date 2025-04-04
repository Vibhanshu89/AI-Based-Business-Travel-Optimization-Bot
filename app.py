
from flask import Flask, request, jsonify
from flask_cors import CORS
import requests
import re

app = Flask(__name__)
CORS(app)  # For frontend connection

# API details
API_KEY = "3de0d37e0amsh8c64aaf414e70c3p10a0dbjsn728ddccb3204"  # Your RapidAPI key

# Expedia API (for hotels)
HOTEL_API_HOST = "expedia-affiliate-api.p.rapidapi.com"

# Amadeus Flight Offers Search API (for flights)
FLIGHT_API_HOST = "amadeus-flight-offers-search.p.rapidapi.com"

# City to IATA code mapping for flights
CITY_AIRPORT_MAP = {
    "mumbai": "BOM",
    "delhi": "DEL",
    "bangalore": "BLR",
    "kolkata": "CCU",
    "chennai": "MAA",
    "pune": "PNQ",
    "hyderabad": "HYD",
    "jaipur": "JAI"
}

@app.route('/chat', methods=['POST'])
def chat():
    user_input = request.json.get('message').lower()
    response = process_input(user_input)
    return jsonify({'reply': response})

def get_hotel_deals(city_name, checkin_date, checkout_date):
    url = f"https://{HOTEL_API_HOST}/v1/hotels/search"
    headers = {
        "X-RapidAPI-Key": API_KEY,
        "X-RapidAPI-Host": HOTEL_API_HOST
    }
    params = {
        "destination": city_name.capitalize(),
        "checkIn": checkin_date,
        "checkOut": checkout_date,
        "adults": 1,
        "currency": "INR"
    }
    try:
        print(f"Requesting hotel data for {city_name} with params: {params}")
        response = requests.get(url, headers=headers, params=params)
        response.raise_for_status()
        data = response.json()
        print("Hotel API Response:", data)
        if data and "hotels" in data and len(data["hotels"]) > 0:
            hotel = data["hotels"][0]
            name = hotel.get("name", "Unknown")
            price = hotel.get("price", {}).get("totalPrice", "N/A")
            return f"Most cheapest hotel {city_name}: {name} for ₹{price} (from {checkin_date} to {checkout_date})"
        return "Hotel Not Found. API response: " + str(data)
    except requests.exceptions.RequestException as e:
        return f"Error fetching hotel data: {str(e)}"

def get_flight_deals(origin_city, destination_city, departure_date):
    origin = CITY_AIRPORT_MAP.get(origin_city.lower(), "DEL")
    destination = CITY_AIRPORT_MAP.get(destination_city.lower(), "BOM")
    url = f"https://{FLIGHT_API_HOST}/v2/shopping/flight-offers"
    headers = {
        "X-RapidAPI-Key": API_KEY,
        "X-RapidAPI-Host": FLIGHT_API_HOST
    }
    params = {
        "originLocationCode": origin,
        "destinationLocationCode": destination,
        "departureDate": departure_date,
        "adults": 1,
        "currencyCode": "INR"
    }
    try:
        print(f"Requesting flight data from {origin_city} to {destination_city} with params: {params}")
        response = requests.get(url, headers=headers, params=params)
        response.raise_for_status()
        data = response.json()
        print("Flight API Response:", data)
        if data and "data" in data and len(data["data"]) > 0:
            flight = data["data"][0]
            price = flight.get("price", {}).get("total", "N/A")
            airline = flight.get("itineraries", [{}])[0].get("segments", [{}])[0].get("operating", {}).get("carrierCode", "Unknown")
            return f"Most Cheapest flight from {origin_city} to {destination_city}: ₹{price} with {airline} on {departure_date}"
        return "No one flight found. API response: " + str(data)
    except requests.exceptions.RequestException as e:
        return f"Error fetching flight data: {str(e)}"

def process_input(user_input):
    user_input = re.sub(r'[^a-zA-Z\s]', '', user_input).lower().strip()
    words = user_input.split()

    if "flight" in user_input:
        origin_city = "delhi"
        destination_city = "mumbai"
        departure_date = "2025-04-15"
        if "from" in words:
            origin_index = words.index("from") + 1
            if origin_index < len(words):
                origin_city = words[origin_index]
        if "to" in words:
            to_index = words.index("to") + 1
            if to_index < len(words):
                destination_city = words[to_index]
        if "on" in words:
            on_index = words.index("on") + 1
            if on_index < len(words):
                departure_date = words[on_index]
        return get_flight_deals(origin_city, destination_city, departure_date)

    city_name = None
    if "in" in words:
        in_index = words.index("in")
        if in_index + 1 < len(words):
            potential_city = words[in_index + 1]
            if potential_city in CITY_AIRPORT_MAP or potential_city in ["mumbai", "delhi", "bangalore", "kolkata", "chennai", "pune", "hyderabad", "jaipur"]:
                city_name = potential_city
    if not city_name:
        for word in words:
            if word in CITY_AIRPORT_MAP or word in ["mumbai", "delhi", "bangalore", "kolkata", "chennai", "pune", "hyderabad", "jaipur"]:
                city_name = word
                break

    if "hotel" in user_input or city_name:
        if not city_name:
            city_name = "mumbai"
        checkin_date = "2025-04-02"
        checkout_date = "2025-04-03"
        return get_hotel_deals(city_name, checkin_date, checkout_date)

    return "I can't understand. Ask about 'Flight' or 'Hotel' ! Example: 'hotel in mumbai' or 'flight from delhi to mumbai on 2025-04-15'"

if __name__ == '__main__':
    app.run(debug=True, port=5000)