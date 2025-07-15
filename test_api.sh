#!/bin/bash

# API Base URL
BASE_URL="http://localhost:8000/api"

echo "🧪 Testing Ordonance.ma API"
echo "=========================="

# Test 1: Register a new patient
echo -e "\n1️⃣ Testing Registration..."
REGISTER_RESPONSE=$(curl -s -X POST "$BASE_URL/register" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Test Patient",
    "email": "patient@test.com",
    "password": "password123",
    "password_confirmation": "password123",
    "role": "patient"
  }')

echo "Registration Response:"
echo $REGISTER_RESPONSE | jq '.'

# Extract token from registration response
TOKEN=$(echo $REGISTER_RESPONSE | jq -r '.data.token')

if [ "$TOKEN" = "null" ] || [ -z "$TOKEN" ]; then
    echo "❌ Failed to get token from registration"
    exit 1
fi

echo -e "\n✅ Token received: ${TOKEN:0:20}..."

# Test 2: Login
echo -e "\n2️⃣ Testing Login..."
LOGIN_RESPONSE=$(curl -s -X POST "$BASE_URL/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "patient@test.com",
    "password": "password123"
  }')

echo "Login Response:"
echo $LOGIN_RESPONSE | jq '.'

# Test 3: Get User Profile (Protected Route)
echo -e "\n3️⃣ Testing Protected Route - Get User Profile..."
PROFILE_RESPONSE=$(curl -s -X GET "$BASE_URL/user" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json")

echo "Profile Response:"
echo $PROFILE_RESPONSE | jq '.'

# Test 4: Test Invalid Login
echo -e "\n4️⃣ Testing Invalid Login..."
INVALID_LOGIN_RESPONSE=$(curl -s -X POST "$BASE_URL/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "patient@test.com",
    "password": "wrongpassword"
  }')

echo "Invalid Login Response:"
echo $INVALID_LOGIN_RESPONSE | jq '.'

# Test 5: Logout
echo -e "\n5️⃣ Testing Logout..."
LOGOUT_RESPONSE=$(curl -s -X POST "$BASE_URL/logout" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Accept: application/json")

echo "Logout Response:"
echo $LOGOUT_RESPONSE | jq '.'

echo -e "\n✅ API Testing Complete!"
