# GearBoost - Premium Sports Products eCommerce Platform

A modern, full-stack eCommerce web application built with Laravel and Vue.js, specializing in premium sports equipment and gear. Features include product browsing, shopping cart management, order processing, and user authentication with a clean and responsive interface.

## 🛠️ Tech Stack

### Backend
- **Laravel** - PHP framework
- **MySQL** - Database (XAMPP)

### Frontend
- **Blade** - Laravel's template engine
- **Vue.js** - JavaScript framework
- **Tailwind** - CSS framework

## 🚀 Setup Guide

### 1. Clone and Install Dependencies

```bash
# Clone the repository
git clone <repository-url>
cd GearBoost

# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install
```

### 2. Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

Update your `.env` file with database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gearboost
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Database Setup

```bash
# Run migrations
php artisan migrate

# Seed the database with sample data
php artisan db:seed
```

### 4. Storage Setup

```bash
# Create symbolic link for storage
php artisan storage:link

# Create products directory
mkdir -p storage/app/public/products
```

### 5. Build and Run

```bash
# Build frontend assets
npm run dev

# Start Laravel server to host
php artisan serve
```