# API Base URL
$BASE_URL = "http://localhost:8000/api"

Write-Host "🧪 Testing Ordonance.ma API" -ForegroundColor Green
Write-Host "==========================" -ForegroundColor Green

# Test 1: Register a new patient
Write-Host "`n1️⃣ Testing Registration..." -ForegroundColor Yellow
$registerBody = @{
    name = "Test Patient"
    email = "patient@test.com"
    password = "password123"
    password_confirmation = "password123"
    role = "patient"
} | ConvertTo-Json

$registerResponse = Invoke-RestMethod -Uri "$BASE_URL/register" -Method POST -Body $registerBody -ContentType "application/json"

Write-Host "Registration Response:" -ForegroundColor Cyan
$registerResponse | ConvertTo-Json -Depth 10

# Extract token
$token = $registerResponse.data.token
if (-not $token) {
    Write-Host "❌ Failed to get token from registration" -ForegroundColor Red
    exit 1
}

Write-Host "`n✅ Token received: $($token.Substring(0, [Math]::Min(20, $token.Length)))..." -ForegroundColor Green

# Test 2: Login
Write-Host "`n2️⃣ Testing Login..." -ForegroundColor Yellow
$loginBody = @{
    email = "patient@test.com"
    password = "password123"
} | ConvertTo-Json

$loginResponse = Invoke-RestMethod -Uri "$BASE_URL/login" -Method POST -Body $loginBody -ContentType "application/json"

Write-Host "Login Response:" -ForegroundColor Cyan
$loginResponse | ConvertTo-Json -Depth 10

# Test 3: Get User Profile (Protected Route)
Write-Host "`n3️⃣ Testing Protected Route - Get User Profile..." -ForegroundColor Yellow
$headers = @{
    "Authorization" = "Bearer $token"
    "Accept" = "application/json"
}

$profileResponse = Invoke-RestMethod -Uri "$BASE_URL/user" -Method GET -Headers $headers

Write-Host "Profile Response:" -ForegroundColor Cyan
$profileResponse | ConvertTo-Json -Depth 10

# Test 4: Test Invalid Login
Write-Host "`n4️⃣ Testing Invalid Login..." -ForegroundColor Yellow
$invalidLoginBody = @{
    email = "patient@test.com"
    password = "wrongpassword"
} | ConvertTo-Json

try {
    $invalidLoginResponse = Invoke-RestMethod -Uri "$BASE_URL/login" -Method POST -Body $invalidLoginBody -ContentType "application/json"
    Write-Host "Invalid Login Response:" -ForegroundColor Cyan
    $invalidLoginResponse | ConvertTo-Json -Depth 10
} catch {
    Write-Host "Invalid Login Response (Expected Error):" -ForegroundColor Cyan
    Write-Host $_.Exception.Response.StatusCode.value__ -ForegroundColor Red
}

# Test 5: Logout
Write-Host "`n5️⃣ Testing Logout..." -ForegroundColor Yellow
$logoutResponse = Invoke-RestMethod -Uri "$BASE_URL/logout" -Method POST -Headers $headers

Write-Host "Logout Response:" -ForegroundColor Cyan
$logoutResponse | ConvertTo-Json -Depth 10

Write-Host "`n✅ API Testing Complete!" -ForegroundColor Green
