#!/bin/bash

echo "🐳 Starting Shoe Website with Docker Compose..."
echo "=============================================="

# Check if Docker is installed
if ! command -v docker &> /dev/null; then
    echo "❌ Docker is not installed. Please install Docker first."
    exit 1
fi

# Check if Docker Compose is installed
if ! command -v docker compose &> /dev/null; then
    echo "❌ Docker Compose is not installed. Please install Docker Compose first."
    exit 1
fi

# Create logs directory if it doesn't exist
mkdir -p logs

# Set proper permissions
chmod 777 logs

echo "🚀 Building and starting containers..."
docker compose up --build -d

echo ""
echo "✅ Application is starting up!"
echo ""
echo "🌐 Access your application:"
echo "   Website: http://localhost:8080"
echo "   phpMyAdmin: http://localhost:8081"
echo ""
echo "🔑 Default login credentials:"
echo "   Admin: admin1 / pass123"
echo "   User: user1 / userpass"
echo ""
echo "📊 View logs: docker-compose logs -f"
echo "🛑 Stop application: docker-compose down"
echo ""
echo "⏳ Please wait a moment for the database to initialize..."
