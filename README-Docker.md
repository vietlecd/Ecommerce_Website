# 🐳 Docker Setup for Shoe Website

This guide will help you run the Shoe Website project using Docker Compose.

## 🚀 Quick Start

### Prerequisites
- Docker installed on your system
- Docker Compose installed

### 1. Clone/Download the Project
```bash
# Navigate to your project directory
cd /home/vietlh/Documents/LTW/Assignment/shoesWebsite
```

### 2. Start the Application
```bash
# Build and start all services
docker-compose up --build

# Or run in background
docker-compose up -d --build
```

### 3. Access the Application
- **Website**: http://localhost:8080
- **phpMyAdmin**: http://localhost:8081
- **MySQL**: localhost:3306

## 🎯 Available Services

### Web Application (Port 8080)
- PHP 8.2 with Apache
- Your shoe website application
- Auto-configured database connection

### MySQL Database (Port 3306)
- MySQL 8.0
- Database: `shoe`
- User: `shoes_user`
- Password: `shoes_pass`
- Root Password: `123456789`

### phpMyAdmin (Port 8081)
- Web-based MySQL administration
- Login with root/123456789

## 🔧 Docker Commands

### Start Services
```bash
# Start all services
docker-compose up

# Start in background
docker-compose up -d

# Rebuild and start
docker-compose up --build
```

### Stop Services
```bash
# Stop all services
docker-compose down

# Stop and remove volumes (WARNING: deletes database data)
docker-compose down -v
```

### View Logs
```bash
# View all logs
docker-compose logs

# View specific service logs
docker-compose logs web
docker-compose logs mysql
```

### Access Containers
```bash
# Access web container
docker-compose exec web bash

# Access MySQL container
docker-compose exec mysql mysql -u root -p
```

## 🗄️ Database Management

### Default Login Credentials
- **Admin Account**: admin1 / pass123
- **User Account**: user1 / userpass

### Database Structure
The database will be automatically initialized with:
- Sample products
- Categories
- Users and admins
- Orders and cart data
- News articles
- Promotions

## 🛠️ Development

### Making Changes
1. Edit files in your local directory
2. Changes are automatically reflected (volume mounted)
3. Restart containers if needed: `docker-compose restart`

### Adding Dependencies
1. Edit `Dockerfile` to add PHP extensions
2. Rebuild: `docker-compose up --build`

### Database Changes

#### Using the Migration System
This project includes a database migration system for managing schema changes:

1. Place SQL migration files in `assets/config/mysql/migrations/` with numbered prefixes (e.g., `001_create_tables.sql`)
2. Run migrations using one of these methods:

```bash
# Using the shell script (recommended from host machine)
./bin/migrate.sh all                   # Run all migrations
./bin/migrate.sh 001_create_qna_tables.sql  # Run a specific migration
./bin/migrate.sh --list                # List available migrations
./bin/migrate.sh --status              # Show migration status
./bin/migrate.sh rollback              # Roll back the last migration
./bin/migrate.sh rollback 3            # Roll back the last 3 migrations
./bin/migrate.sh reset                 # Roll back all migrations
./bin/migrate.sh --help                # Show help

# Using the PHP script (inside Docker container)
docker compose exec web php /var/www/html/bin/migrate.php all            # Run all migrations
docker compose exec web php /var/www/html/bin/migrate.php 001_create_qna_tables.sql  # Run a specific migration
docker compose exec web php /var/www/html/bin/migrate.php --list         # List available migrations
docker compose exec web php /var/www/html/bin/migrate.php --status       # Show migration status
docker compose exec web php /var/www/html/bin/migrate.php rollback       # Roll back the last migration
docker compose exec web php /var/www/html/bin/migrate.php rollback 3     # Roll back the last 3 migrations
docker compose exec web php /var/www/html/bin/migrate.php reset          # Roll back all migrations
docker compose exec web php /var/www/html/bin/migrate.php --help         # Show help
```

#### Creating New Migrations

For each migration, create two files:
1. `xxx_migration_name.sql` - Contains the SQL to apply the migration
2. `xxx_migration_name.down.sql` - Contains the SQL to roll back the migration

#### How Rollbacks Work

When you create a migration, you should also create a corresponding `.down.sql` file that undoes the changes:

```sql
-- 001_create_example_table.sql
CREATE TABLE example (id INT PRIMARY KEY, name VARCHAR(100));

-- 001_create_example_table.down.sql
DROP TABLE IF EXISTS example;
```

Then you can run:
```bash
# Apply migration
./bin/migrate.sh 001_create_example_table.sql

# Check status
./bin/migrate.sh --status

# Roll back migration
./bin/migrate.sh rollback
```

Important notes:
- The PHP script must be run inside the Docker container as it requires the PDO MySQL driver
- Each migration is executed independently, so errors in one won't affect others
- Migrations are idempotent - they include IF NOT EXISTS and similar checks to avoid errors when run multiple times

#### Manual Changes (not recommended)
1. Modify SQL files in `assets/config/mysql/`
2. Recreate containers: `docker-compose down && docker-compose up --build`

## 🐛 Troubleshooting

### Port Already in Use
```bash
# Check what's using the port
sudo netstat -tulpn | grep :8080

# Kill the process or change ports in docker-compose.yml
```

### Database Connection Issues
```bash
# Check if MySQL is running
docker-compose logs mysql

# Test connection
docker-compose exec web php -r "
\$pdo = new PDO('mysql:host=mysql;dbname=shoe', 'shoes_user', 'shoes_pass');
echo 'Database connected successfully!';
"
```

### Permission Issues
```bash
# Fix permissions
docker-compose exec web chown -R www-data:www-data /var/www/html
docker-compose exec web chmod -R 755 /var/www/html
```

### Reset Everything
```bash
# Stop and remove everything
docker-compose down -v

# Remove images
docker-compose down --rmi all

# Start fresh
docker-compose up --build
```

## 📁 Project Structure
```
shoesWebsite/
├── Dockerfile              # PHP/Apache container
├── docker-compose.yml      # Multi-container setup
├── .dockerignore           # Files to ignore in build
├── config/
│   └── database.php        # Database configuration
├── scripts/
│   └── start.sh           # Startup script
└── README-Docker.md       # This file
```

## 🌐 Production Deployment

For production deployment, consider:
1. Using environment variables for sensitive data
2. Setting up SSL certificates
3. Using a reverse proxy (nginx)
4. Setting up proper logging
5. Using managed database services

## 📞 Support

If you encounter issues:
1. Check the logs: `docker-compose logs`
2. Verify all services are running: `docker-compose ps`
3. Check database connection
4. Ensure ports are not in use

Happy coding! 🚀
