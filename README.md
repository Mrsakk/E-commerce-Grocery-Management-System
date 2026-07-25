# FreshMart - E-commerce Grocery Management System

A modern, full-featured E-commerce Grocery Management System built with **Laravel**, **Bootstrap 5**, **JavaScript**, and **SQLite/MySQL**.

## Features

- 🛒 **Customer Storefront**: Product catalog, search, filtering, cart management, checkout, and order tracking.
- 📦 **Admin Dashboard**: Comprehensive catalog management (Products, Categories, Suppliers, Banners, Coupons), Stock & Inventory tracking, Order management, Sales Reports, and System Settings.
- 🛵 **Delivery Management**: Rider order assignments, status updates (`Assigned` → `On The Way` → `Delivered` / `Failed`), and zone management.
- 🔒 **Authentication & Roles**: Multi-role support (Admin, Customer, Delivery Staff) with custom access control and modern glassmorphism Auth pages.
- 📱 **Responsive Design**: Fully responsive layout optimized for Desktop, Tablet, and Mobile screens.
- ☁️ **Vercel Ready**: Pre-configured with `vercel.json` and serverless entry points for seamless Vercel deployment.

## Getting Started

### Prerequisites
- PHP >= 8.2
- Composer
- Node.js & NPM

### Local Installation

1. **Clone the repository**:
   ```bash
   git clone https://github.com/Mrsakk/E-commerce-Grocery-Management-System.git
   cd E-commerce-Grocery-Management-System
   ```

2. **Install PHP and Node dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Configure Environment**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Run Database Migrations & Seeders**:
   ```bash
   php artisan migrate --seed
   ```

5. **Build Assets & Start Development Server**:
   ```bash
   npm run build
   php artisan serve
   ```

## License
Licensed under the [MIT License](LICENSE).
