# Nursery App 2.0 🌿

A modern, API-first E-commerce application built for the "Botanical Premium" experience.

## 🏗 Architecture

-   **Backend:** Laravel 10 (API Mode)
-   **Frontend:** HTML5 + Vanilla JS + Alpine.js + Tailwind CSS
-   **Database:** MySQL

## 📚 Documentation

For a detailed project report including System Analysis, DFDs, and Testing, please refer to:
- [**College Project Report**](COLLEGE_PROJECT_REPORT.md) - Formal academic report.
- [**Technical Documentation**](PROJECT_DOCUMENTATION.md) - Deep dive into code & API.

## 🚀 Getting Started

### 1. Backend Setup
```bash
# Install dependencies
composer install

# Setup Environment
cp .env.example .env
php artisan key:generate

# Database Setup
# Ensure MySQL is running and create a database named 'nursery_app'
php artisan migrate:fresh --seed
```

### 2. Running the App
Since the frontend is built into the `public/` directory, you only need to serve the Laravel application:

```bash
php artisan serve
```

Visit `http://127.0.0.1:8000` in your browser.

## 🌟 Key Features

-   **SPA-like Experience:** Alpine.js handles routing and state without page reloads.
-   **Plant Finder Quiz:** Interactive wizard with GSAP animations.
-   **Admin Dashboard:** Real-time data visualization using Chart.js.
-   **Premium UI:** Custom Tailwind design system with glassmorphism effects.

## 📁 Structure

-   `app/Http/Controllers/Api`: API Endpoints (Orders, Products, Analytics).
-   `public/`: The entire Frontend application.
    -   `assets/js/app.js`: Core logic and State Management.
    -   `assets/css/main.css`: Custom styles.
    -   `*.html`: Views (Home, Shop, Cart, etc.).
