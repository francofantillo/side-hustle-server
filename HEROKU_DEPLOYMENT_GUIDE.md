# 🚀 Side Hustle - Heroku Deployment Guide

This guide will help you deploy your Side Hustle Laravel application with OTP email functionality to Heroku.

## 📋 Prerequisites

1. **Heroku CLI installed**
   ```bash
   # Install if you don't have it
   brew tap heroku/brew && brew install heroku
   ```

2. **Git repository initialized**
3. **Docker setup working locally**
4. **Mailgun credentials ready**

## 🔧 Step 1: Prepare for Deployment

### Check current directory and login to Heroku:
```bash
cd "/Users/francofantillo/Documents/Swiftech Solutions Inc/customers/Side Hustle/server/Side_Hustle_SourceCode"
heroku login
```

### Create Heroku app (if not exists):
```bash
# Replace 'your-app-name' with your preferred app name
heroku create your-side-hustle-app

# Or if you have an existing app
heroku git:remote -a your-existing-app-name
```

## 🗄️ Step 2: Configure Database

### Add Heroku Postgres:
```bash
heroku addons:create heroku-postgresql:essential-0
```

### Set database configuration:
```bash
# Heroku automatically sets DATABASE_URL, but we need to configure Laravel format
heroku config:set DB_CONNECTION=pgsql
heroku config:set DB_HOST=
heroku config:set DB_PORT=5432
heroku config:set DB_DATABASE=
heroku config:set DB_USERNAME=
heroku config:set DB_PASSWORD=

# Note: Heroku will automatically configure these from DATABASE_URL
```

## 📧 Step 3: Configure Mailgun (Email OTP System)

### Option 1: Use the provided script (recommended)
First, update the script with your credentials:
```bash
# Edit the setup script with your actual Mailgun credentials
nano extracted/setup-mailgun-heroku.sh

# Update these variables:
MAILGUN_DOMAIN="your-mailgun-domain-here"
MAILGUN_API_KEY="your-mailgun-private-api-key-here"  
FROM_EMAIL="postmaster@your-mailgun-domain-here"
```

Then run the script:
```bash
./extracted/setup-mailgun-heroku.sh
```

### Option 2: Set manually
```bash
heroku config:set MAIL_MAILER=mailgun
heroku config:set MAIL_FROM_ADDRESS="postmaster@your-mailgun-domain"
heroku config:set MAIL_FROM_NAME="Side Hustle"
heroku config:set MAILGUN_DOMAIN="your-mailgun-domain"
heroku config:set MAILGUN_SECRET="your-mailgun-private-api-key"
heroku config:set MAILGUN_ENDPOINT="api.mailgun.net"
```

This will configure:
- ✅ MAIL_MAILER=mailgun
- ✅ MAILGUN_DOMAIN (your actual domain)
- ✅ MAILGUN_SECRET (your private API key)
- ✅ APP_NAME="Side Hustle"

## 🔑 Step 4: Set Additional Environment Variables

```bash
# Laravel specific
heroku config:set APP_ENV=production
heroku config:set APP_DEBUG=false
heroku config:set APP_KEY=$(php -r "echo base64_encode(random_bytes(32));")

# Generate a new app key for production
cd extracted && php artisan key:generate --show
# Copy the output and set it:
heroku config:set APP_KEY="base64:YOUR_GENERATED_KEY_HERE"

# Set app URL (replace with your actual Heroku app URL)
heroku config:set APP_URL=https://your-side-hustle-app.herokuapp.com

# Other services (if needed)
heroku config:set STRIPE_SECRET_KEY="your-stripe-secret-key-here"
```

## 🐳 Step 5: Configure Container Stack

```bash
# Set Heroku to use container stack
heroku stack:set container

# Verify heroku.yml is configured for container deployment
cat heroku.yml
```

## 🚀 Step 6: Deploy to Heroku

### Commit your changes:
```bash
git add .
git commit -m "Deploy Side Hustle with OTP email system to Heroku"
```

### Deploy:
```bash
git push heroku main

# Or if your main branch is named differently:
git push heroku master
```

## 🗄️ Step 7: Run Database Migrations

```bash
# Run migrations on Heroku
heroku run php extracted/artisan migrate --force

# Optionally seed the database
heroku run php extracted/artisan db:seed --force
```

## 🔍 Step 8: Verify Deployment

### Check app status:
```bash
heroku ps
heroku logs --tail
```

### Test the OTP email system:
```bash
# Get your app URL
heroku info

# Test registration endpoint
curl -X POST https://your-side-hustle-app.herokuapp.com/api/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "first_name": "Test",
    "last_name": "User",
    "phone": "+1234567890",
    "email": "test@example.com",
    "password": "password123",
    "confirm_password": "password123",
    "zip_code": "12345",
    "country": "USA"
  }'

# Test forgot password
curl -X POST https://your-side-hustle-app.herokuapp.com/api/auth/forgot-password \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "your-email@example.com"
  }'
```

## 🛠️ Troubleshooting

### Check logs:
```bash
heroku logs --tail
heroku logs --source app --tail
```

### Debug container:
```bash
heroku run bash
```

### Reset database if needed:
```bash
heroku pg:reset DATABASE_URL --confirm your-app-name
heroku run php extracted/artisan migrate --force
```

### Check environment variables:
```bash
heroku config
```

## 📧 Email Testing

After deployment:

1. **Monitor Mailgun**: https://app.mailgun.com/app/logs
2. **Test OTP endpoints** using the curl commands above
3. **Check email delivery** in your inbox
4. **Verify branded emails** with "Side Hustle" branding

## 🎯 Expected Results

- ✅ Laravel app running on Heroku
- ✅ PostgreSQL database connected
- ✅ OTP emails sending via Mailgun
- ✅ All API endpoints working
- ✅ Branded "Side Hustle" emails
- ✅ Production-ready configuration

## 🔗 Useful Commands

```bash
# Scale app
heroku ps:scale web=1

# View app
heroku open

# Access logs
heroku logs --tail

# Run Laravel commands
heroku run php extracted/artisan migrate
heroku run php extracted/artisan queue:work
heroku run php extracted/artisan config:cache

# Check app info
heroku info
```

Your Side Hustle app is now ready for production! 🚀
