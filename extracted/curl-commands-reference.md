# Curl Commands for Testing OTP Email System

## Prerequisites
Make sure your Laravel server is running on localhost:8000

## 1. Register New User (Sends OTP via Email)
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "first_name": "John",
    "last_name": "Doe", 
    "phone": "+1234567890",
    "email": "john.doe@example.com",
    "password": "password123",
    "confirm_password": "password123",
    "zip_code": "12345",
    "country": "USA"
  }'
```

**Response:**
```json
{
  "status": true,
  "data": {
    "otp": 403536,
    "api_token": "6|JN6l7QYSW4G8uVzspoYpDAAq3vduRQDYSU8eUulO11324ffd",
    "user_id": 5
  }
}
```

## 2. Login Existing User (Sends OTP via Email)
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "john.doe@example.com",
    "password": "password123"
  }'
```

**Response:**
```json
{
  "status": true,
  "message": "Your account is not verified yet..!!",
  "data": {
    "is_verified": 0,
    "otp": 629727,
    "api_token": "7|DdCgfA0v5hYKNlUu86vC8Kgr8wCyRAnAoHsH5znRb64c91c1",
    "user_id": 5
  }
}
```

## 3. Forgot Password (Sends OTP via Email)
```bash
curl -X POST http://localhost:8000/api/auth/forgot-password \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "john.doe@example.com"
  }'
```

**Response:**
```json
{
  "status": true,
  "message": "OTP has been sent to your email.",
  "data": {
    "id": 5,
    "otp": "458133",
    "api_token": "8|1n60ssMnhYBhNWGf1JU8bvJv7iMN1ybDu8ZNP1g4e21ad286"
  }
}
```

## 4. Resend OTP Token (Sends New OTP via Email)
```bash
curl -X POST http://localhost:8000/api/auth/resend-otp-token \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "api_token": "YOUR_API_TOKEN_HERE"
  }'
```

**Response:**
```json
{
  "status": true,
  "message": "OTP resent successfully to your email",
  "data": {
    "otp": 715191
  }
}
```

## 5. Verify OTP Token
```bash
curl -X POST http://localhost:8000/api/auth/verify-token \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "api_token": "YOUR_API_TOKEN_HERE",
    "otp": "123456"
  }'
```

## Quick Test Commands

### Test with unique email (recommended):
```bash
# Generate unique email
TIMESTAMP=$(date +%s)
TEST_EMAIL="test${TIMESTAMP}@example.com"

# Register
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d "{
    \"first_name\": \"Test\",
    \"last_name\": \"User\", 
    \"phone\": \"+123456${TIMESTAMP: -4}\",
    \"email\": \"$TEST_EMAIL\",
    \"password\": \"password123\",
    \"confirm_password\": \"password123\",
    \"zip_code\": \"12345\",
    \"country\": \"USA\"
  }"
```

### Simple forgot password test:
```bash
curl -X POST http://localhost:8000/api/auth/forgot-password \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "test@example.com"
  }'
```

## Email Verification
- Check your email inbox for OTP codes
- Check Mailgun logs: https://app.mailgun.com/app/logs
- Email subject: "Your OTP Code"
- OTP codes are 6-digit numbers

## Notes
- Replace `YOUR_API_TOKEN_HERE` with actual token from registration/login response
- Use unique email addresses to avoid "already exists" errors
- All endpoints return OTP in response for testing (remove in production)
- OTP codes are automatically sent to the provided email address
