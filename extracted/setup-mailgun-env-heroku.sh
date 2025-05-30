#!/bin/bash

# Mailgun Heroku Configuration Script
# Uses environment variables for secure credential management

echo "🚀 Configuring Heroku environment variables for Mailgun API..."
echo ""

# Read credentials from environment variables
MAILGUN_DOMAIN="${MAILGUN_DOMAIN}"
MAILGUN_API_KEY="${MAILGUN_SECRET}"
FROM_EMAIL="${MAIL_FROM_ADDRESS}"

# Fallback to manual input if environment variables are not set
if [[ -z "$MAILGUN_DOMAIN" ]]; then
    echo "📝 MAILGUN_DOMAIN not found in environment variables."
    echo "Please set it first:"
    echo "export MAILGUN_DOMAIN=\"your-mailgun-domain\""
    echo ""
    read -p "Or enter your Mailgun domain now: " MAILGUN_DOMAIN
fi

if [[ -z "$MAILGUN_API_KEY" ]]; then
    echo "📝 MAILGUN_SECRET not found in environment variables."
    echo "Please set it first:"
    echo "export MAILGUN_SECRET=\"your-mailgun-api-key\""
    echo ""
    read -s -p "Or enter your Mailgun API key now: " MAILGUN_API_KEY
    echo ""
fi

if [[ -z "$FROM_EMAIL" ]]; then
    FROM_EMAIL="postmaster@${MAILGUN_DOMAIN}"
fi

# Validate that we have the required credentials
if [[ -z "$MAILGUN_DOMAIN" ]] || [[ -z "$MAILGUN_API_KEY" ]]; then
    echo "❌ ERROR: Missing required Mailgun credentials!"
    echo ""
    echo "🔧 Setup Instructions:"
    echo "1. Get your credentials from: https://app.mailgun.com/app/domains"
    echo "2. Set environment variables:"
    echo "   export MAILGUN_DOMAIN=\"your-sandbox-domain.mailgun.org\""
    echo "   export MAILGUN_SECRET=\"your-private-api-key\""
    echo "   export MAIL_FROM_ADDRESS=\"postmaster@your-domain.com\""
    echo ""
    echo "3. Then run this script again"
    exit 1
fi

echo "📧 Using domain: $MAILGUN_DOMAIN"
echo "✉️ From address: $FROM_EMAIL"
echo ""

# Configure for Mailgun API (not SMTP)
heroku config:set MAIL_MAILER=mailgun
heroku config:set MAIL_FROM_ADDRESS="${FROM_EMAIL}"
heroku config:set MAIL_FROM_NAME="Phase 1"

# Mailgun API Configuration
heroku config:set MAILGUN_DOMAIN="${MAILGUN_DOMAIN}"
heroku config:set MAILGUN_SECRET="${MAILGUN_API_KEY}"
heroku config:set MAILGUN_ENDPOINT="api.mailgun.net"

# App name for email branding
heroku config:set APP_NAME="Phase 1"

echo ""
echo "✅ Mailgun configuration complete!"
echo "📧 Email system configured to use Mailgun API"
echo "🔗 Monitor emails at: https://app.mailgun.com/app/logs"
echo ""
echo "🧪 Test your configuration after deployment with:"
echo "   curl -X POST https://your-app.herokuapp.com/api/auth/forgot-password \\"
echo "     -H \"Content-Type: application/json\" \\"
echo "     -d '{\"email\": \"your-email@example.com\"}'"
