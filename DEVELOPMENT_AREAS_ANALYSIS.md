# E-commerce Online Grocery Management System
## Development Areas Analysis — Final Year Project Documentation

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [Technology Stack](#2-technology-stack)
3. [System Architecture](#3-system-architecture)
4. [Development Area 1: Product and Category Management](#4-product-and-category-management)
5. [Development Area 2: Inventory and Stock Management](#5-inventory-and-stock-management)
6. [Development Area 3: Customer Shopping Experience](#6-customer-shopping-experience)
7. [Development Area 4: Order Management](#7-order-management)
8. [Development Area 5: Payment Management](#8-payment-management)
9. [Development Area 6: Delivery Management](#9-delivery-management)
10. [Development Area 7: Reports and Dashboard](#10-reports-and-dashboard)
11. [Development Area 8: User Roles and Security](#11-user-roles-and-security)
12. [Development Area 9: UI/UX Improvement](#12-uiux-improvement)
13. [Development Area 10: Advanced Features](#13-advanced-features)
14. [Feature Classification](#14-feature-classification)
15. [Development Priority](#15-development-priority)
16. [Business Value Summary](#16-business-value-summary)
17. [Cambodia Market Context](#17-cambodia-market-context)
18. [Recommendations](#18-recommendations)
19. [Conclusion](#19-conclusion)
20. [Appendix: File Inventory](#20-appendix-file-inventory)

---

## 1. Introduction

This document analyzes the key development areas of the E-commerce Online Grocery Management System. The system is designed to solve real business problems faced by grocery stores in Cambodia, including:

- Lack of online presence for traditional grocery stores
- Manual order tracking causing errors and delays
- Inventory losses from overselling and spoilage
- Poor delivery coordination and customer communication
- No centralized reporting for business decisions

The system supports three types of users: **Admin** (store owner/manager), **Customer** (shoppers), and **Delivery Staff** (riders). It covers the complete grocery retail lifecycle from product browsing to delivery.

---

## 2. Technology Stack

| Layer | Technology | Purpose |
|---|---|---|
| Backend Framework | Laravel 13 (PHP 8.3) | Server-side logic, routing, database management |
| Frontend Styling | Tailwind CSS 3 | Responsive, mobile-first CSS framework |
| JavaScript | Alpine.js 3 | Lightweight interactivity without full SPA complexity |
| Build Tool | Vite 8 | Asset bundling and hot reload during development |
| Authentication | Laravel Breeze | User registration, login, password reset |
| Database | SQLite (dev) / MySQL (prod) | Data storage |
| Server | XAMPP / Laragon | Local development environment |

---

## 3. System Architecture

### High-Level Architecture

```
┌─────────────────────────────────────────────────────┐
│                    LARAVEL 13                        │
│                                                      │
│  ┌────────────┐  ┌────────────┐  ┌──────────────┐  │
│  │   Admin    │  │  Customer  │  │   Delivery   │  │
│  │  Dashboard │  │   Portal   │  │  Staff App   │  │
│  └─────┬──────┘  └─────┬──────┘  └──────┬───────┘  │
│        │               │                │            │
│  ┌─────┴───────────────┴────────────────┴───────┐   │
│  │           Controllers (27 files)              │   │
│  ├───────────────────────────────────────────────┤   │
│  │            Services (4 files)                 │   │
│  ├───────────────────────────────────────────────┤   │
│  │            Models (23 files)                  │   │
│  ├───────────────────────────────────────────────┤   │
│  │     Database (~30 tables, 24 migrations)      │   │
│  └───────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────┘
```

### Request Flow

```
Browser Request
    │
    ▼
Route (web.php)
    │
    ▼
Middleware (auth, role check)
    │
    ▼
Controller (processes request)
    │
    ├──> Service (business logic)
    │         │
    │         ▼
    │    Model (database operations)
    │         │
    │         ▼
    │    Database (SQLite/MySQL)
    │
    ▼
View (Blade template)
    │
    ▼
HTML Response to Browser
```

### Role-Based Access Flow

```
User Login
    │
    ├── Role: admin ──────> /admin/* (full access)
    │
    ├── Role: customer ──> /products, /cart, /checkout, /my-orders, /profile
    │
    └── Role: delivery ──> /delivery/* (delivery operations only)
```

---

## 4. Product and Category Management

### Why This Area Is Important

This is the foundation of the entire system. A grocery store cannot operate without products organized into categories. Customers need to find items quickly, see clear prices with correct units, and know whether items are in stock before ordering.

### How It Solves Business Problems

| Problem | Solution |
|---|---|
| Customers cannot find items | Categories organize products into logical groups (vegetables, fruits, meat, etc.) |
| Price confusion | Clear pricing with unit labels (per kg, per piece, per pack) |
| Ordering unavailable items | Stock availability displayed on product pages |
| Poor product presentation | Product images with descriptions help customers identify items |
| No search capability | Full-text search by name, description, and brand |

### Features Implemented

| Feature | Description | Code Location |
|---|---|---|
| Product listing | Paginated grid showing 12 products per page | `ProductController@index` |
| Product detail page | Full product info with image, price, stock, related products | `ProductController@show` |
| Category management | Admin CRUD for categories | `Admin\CategoryController` |
| Pricing | Decimal pricing support (DECIMAL 10,2) | `products.price` database column |
| Unit field | Per kg, per piece, per pack, per bottle | `products.unit` database column |
| Product images | Upload from local storage with fallback | `Product::getImageUrlAttribute` |
| Stock availability | In-stock or out-of-stock display | Linked via `Inventory` model |
| Category filtering | Browse products by category | `ProductController@category` |
| Search | Search by name, description, brand | `ProductController@search` |
| Sort options | Sort by price, popularity, or date | In product listing controller |
| Price range filter | Filter products within a price range | In product filters partial |
| Brand field | Products can be filtered by brand | `products.brand` column |
| Expiry date | Track expiration for perishable items | `products.expiry_date` column |
| Image upload | Admin uploads product images | `Admin\ProductController@store` |
| Activity logging | All product changes are logged | Via `ActivityLogger` service |

### Database Tables

```
categories
├── id (primary key)
├── category_name (VARCHAR 100)
├── description (TEXT)
└── status (default: active)

products
├── id (primary key)
├── category_id (foreign key → categories)
├── product_name (VARCHAR 200)
├── description (TEXT)
├── price (DECIMAL 10,2)
├── unit (VARCHAR 50)
├── image (VARCHAR 255)
├── brand (VARCHAR 100)
├── expiry_date (DATE)
└── status (default: active)
```

### Files Involved

- Controllers: `ProductController.php`, `Admin/ProductController.php`, `Admin/CategoryController.php`
- Models: `Product.php`, `Category.php`
- Views: `customer/products/index.blade.php`, `customer/products/show.blade.php`, `admin/products/` (3 views), `admin/categories/` (3 views)

---

## 5. Inventory and Stock Management

### Why This Area Is Important

Grocery stores deal with perishable goods. Without proper stock tracking, the store faces two major losses: overselling (promising items that are out of stock) and overstocking (buying too much of items that spoil). Both directly reduce profits.

### How It Solves Business Problems

| Problem | Solution |
|---|---|
| Overselling items | Stock validation at checkout and cart update |
| Not knowing what to reorder | Low stock alerts when quantity falls below reorder level |
| Spoiled goods inflating counts | Damaged goods recorded separately from available stock |
| No audit trail for stock changes | Stock movement history tracks every change with reason and user |
| Manual restocking errors | Purchase order receiving automatically updates inventory |

### Stock Movement Types

The system tracks five types of stock movements:

1. **stock_in** — New stock received from supplier
2. **stock_out** — Stock deducted when customer places order
3. **adjustment** — Manual correction by admin
4. **damaged** — Stock marked as damaged or spoiled
5. **returned** — Stock returned when order is cancelled

### Features Implemented

| Feature | Description | Code Location |
|---|---|---|
| Inventory table | Stores quantity in stock and reorder level | `inventories` migration |
| Stock validation at checkout | Blocks order if stock insufficient | `CheckoutController@store` |
| Stock validation at cart | Blocks quantity increase beyond stock | `CartController@update` |
| Low stock alerts | Notifies admin when stock is low | `NotificationService@sendLowStockAlert` |
| Low stock report | Admin views all low-stock products | `admin/inventory/low_stock.blade.php` |
| Manual adjustment | Admin can increase or decrease stock | `Admin\InventoryController@update` |
| Damaged goods | Record damaged items separately | `Admin\InventoryController` |
| Stock movement history | Full audit trail with filters | `Admin\StockMovementController@index` |
| Stock restoration | Returns stock on order cancellation | `OrderStatusService@handleCancelled` |
| Purchase order receiving | Updates stock when supplier delivers | `Admin\PurchaseOrderController@receive` |
| Auto-creation | Inventory record created with new product | `Admin\ProductController@store` |

### Database Tables

```
inventories
├── id (primary key)
├── product_id (foreign key → products)
├── qty_in_stock (INTEGER)
├── reorder_level (INTEGER, default: 10)
└── last_updated (TIMESTAMP)

stock_movements
├── id (primary key)
├── product_id (foreign key → products)
├── user_id (foreign key → users)
├── type (stock_in, stock_out, adjustment, damaged, returned)
├── quantity (INTEGER)
├── reference_type (VARCHAR)
├── reference_id (BIGINT)
└── note (TEXT)
```

---

## 6. Customer Shopping Experience

### Why This Area Is Important

This is the revenue-generating part of the system. If customers cannot easily browse products, add items to cart, and complete checkout, they will leave and use competitor apps. In Cambodia, over 80% of internet users shop on mobile phones, so the experience must work well on small screens.

### How It Solves Business Problems

| Problem | Solution |
|---|---|
| Customers cannot find products quickly | Search, category filtering, and sort options |
| Unclear product information | Product cards show image, name, price, and unit |
| Complicated ordering process | Simple add-to-cart and one-page checkout |
| Cart abandonment | Fast checkout with saved addresses |
| No way to save items for later | Wishlist feature for future purchases |
| No order follow-up | Notifications keep customers informed |

### Shopping Flow

```
Homepage
    │
    ├── Browse Categories
    │       │
    │       ▼
    │   Product Listing (search, filter, sort)
    │       │
    │       ▼
    │   Product Detail Page
    │       │
    │       ▼
    ├── Add to Cart ──> Shopping Cart ──> Checkout ──> Place Order
    │
    └── Buy Now (quick single-item purchase)
```

### Features Implemented

| Feature | Description | Code Location |
|---|---|---|
| Homepage | Hero banner, categories, featured/latest/best sellers, promotions | `HomeController@index` |
| Product browsing | Grid layout with product cards | `ProductController@index` |
| Product cards | Reusable component with image, name, price, add-to-cart | `partials/product-card.blade.php` |
| Product filters | Category, price range, sort options | `partials/product-filters.blade.php` |
| Search | Search bar for name, description, brand | `ProductController@search` |
| Add to cart | Click to add with quantity selector | `CartController@add` |
| Shopping cart | View, update quantity, remove, clear, see totals | `CartController` (full CRUD) |
| Checkout | Delivery address, payment method, coupon, order summary | `CheckoutController` |
| Buy now | Quick single-item purchase | In checkout flow |
| Free delivery threshold | Free delivery above configured amount | Configurable via settings |
| Wishlist | Save products for later | `WishlistController` |
| Profile management | Edit name, email, phone, address, password | `ProfileController` |
| Contact form | Send messages to store | `ContactController` |
| Notifications | View and manage notifications | `NotificationController` |

### Database Tables

```
carts
├── id (primary key)
└── customer_id (foreign key → customers)

cart_items
├── id (primary key)
├── cart_id (foreign key → carts)
├── product_id (foreign key → products)
├── quantity (INTEGER)
├── unit_price (DECIMAL)
└── subtotal (DECIMAL)

wishlists
├── customer_id (foreign key → customers)
└── product_id (foreign key → products)
```

---

## 7. Order Management

### Why This Area Is Important

Order management is the operational backbone. Every grocery order must go through a clear status flow so staff know what to process and customers know where their order is. Without this, orders get lost, customers call repeatedly, and the business loses money.

### Order Status Flow

```
┌─────────┐    ┌───────────┐    ┌─────────┐    ┌─────────┐    ┌───────────┐
│ Pending  │───>│ Confirmed │───>│ Packing │───>│ Shipped │───>│ Delivered │
└─────────┘    └───────────┘    └─────────┘    └─────────┘    └───────────┘
     │              │                │              │               │
     └──────────────┴────────────────┴──────────────┴───────────────┘
                                        │
                                        ▼
                                ┌──────────────┐
                                │  Cancelled   │
                                └──────────────┘
```

Any status can transition to Cancelled. When cancelled, stock is restored automatically.

### How It Solves Business Problems

| Problem | Solution |
|---|---|
| Orders get lost or forgotten | Status flow ensures every order is tracked |
| Customers call to ask about orders | Order history and status visible to customers |
| No accountability for changes | Status history records who changed what and when |
| Cancellation causes stock errors | Automatic stock restoration on cancellation |
| No delivery coordination | Delivery staff assignment from order management |

### Features Implemented

| Feature | Description | Code Location |
|---|---|---|
| Place order | Submit order with address and payment method | `CheckoutController@store` |
| Order details | View items, quantities, prices, totals | `OrderController@show` (customer) / `Admin\OrderController@show` |
| Order status flow | 6 states with transitions | `OrderStatusService` |
| Order history | View all past orders | `OrderController@index` |
| Cancel order | Cancel pending orders with stock restore | `OrderController@cancel` |
| Admin status update | Update order at each stage | `Admin\OrderController@updateStatus` |
| Cancel with reason | Admin provides reason for cancellation | `OrderStatusService@handleCancelled` |
| Assign delivery | Admin assigns delivery staff | `Admin\OrderController@assignDelivery` |
| Order filtering | Filter by status, payment method, date | `Admin\OrderController@index` |
| Status history | Complete audit trail with old/new values | `Admin\OrderStatusHistoryController` |
| Transactional creation | Database transaction prevents partial orders | `CheckoutController@store` |

### Database Tables

```
orders
├── id (primary key)
├── customer_id (foreign key → customers)
├── order_date (TIMESTAMP)
├── total_amount (DECIMAL 12,2)
├── payment_method (VARCHAR)
├── payment_status (VARCHAR)
├── order_status (VARCHAR)
├── delivery_address (TEXT)
└── note (TEXT)

order_details
├── id (primary key)
├── order_id (foreign key → orders)
├── product_id (foreign key → products)
├── quantity (INTEGER)
├── unit_price (DECIMAL)
└── subtotal (DECIMAL)

order_status_histories
├── id (primary key)
├── order_id (foreign key → orders)
├── old_status (VARCHAR)
├── new_status (VARCHAR)
├── changed_by (foreign key → users)
└── cancel_reason (TEXT)
```

---

## 8. Payment Management

### Why This Area Is Important

Cambodia has a unique payment landscape. Cash on Delivery (COD) is still dominant (~70% of transactions), but QR payments via Bakong, ABA Pay, and Wing are growing rapidly. Bank transfers are common for larger orders. The system must support all three to serve all customers.

### Payment Methods

| Method | How It Works | Use Case |
|---|---|---|
| Cash on Delivery (COD) | Customer pays cash when delivery arrives | Most common, preferred by many customers |
| Bank Transfer | Customer transfers money to store bank account | Larger orders, business customers |
| QR Payment | Customer scans QR code to pay | Growing in popularity, younger customers |

### Payment Status Flow

```
┌──────────┐    ┌────────────┐    ┌───────────┐    ┌──────────┐
│ Pending  │───>│  Submitted │───>│  Verified │───>│ Completed│
│          │    │(slip upload)│   │(admin ok) │    │          │
└──────────┘    └────────────┘    └───────────┘    └──────────┘
     │              │
     └──────────────┘──────────> Rejected
                                 (admin rejects slip)
```

### How It Solves Business Problems

| Problem | Solution |
|---|---|
| Limited payment options lose customers | Support COD, transfer, and QR |
| No proof of bank transfer/QR payment | Slip upload feature |
| Fake payment slips | Admin verification before confirming |
| No payment tracking | Full payment history with transaction references |
| Revenue leakage | Payment status linked to order status |

### Features Implemented

| Feature | Description | Code Location |
|---|---|---|
| Payment method selection | Choose COD, transfer, or QR at checkout | `CheckoutController` |
| Payment record creation | Automatic record created with order | `CheckoutController@store` |
| Slip upload | Customer uploads payment screenshot | Payment verification form |
| Admin verification | Review and confirm or reject payment | `Admin\PaymentController@confirm` |
| Payment rejection | Admin provides rejection reason | `Admin\PaymentController` |
| Transaction reference | Unique reference for each payment | `payments.transaction_ref` |
| Payment listing | Admin views all payments with filters | `Admin\PaymentController@index` |
| Payment details | Full payment and verification info | `Admin\PaymentController@show` |

### Database Tables

```
payments
├── id (primary key)
├── order_id (foreign key → orders)
├── payment_date (TIMESTAMP)
├── amount (DECIMAL 12,2)
├── payment_method (VARCHAR)
├── payment_status (VARCHAR)
└── transaction_ref (VARCHAR)

payment_verifications
├── id (primary key)
├── payment_id (foreign key → payments)
├── verified_by (foreign key → users)
├── slip_image (VARCHAR)
├── transaction_ref (VARCHAR)
├── verified_at (TIMESTAMP)
├── status (VARCHAR)
└── rejection_reason (TEXT)
```

---

## 9. Delivery Management

### Why This Area Is Important

In Cambodia, most grocery delivery is done by motorcycle riders within cities like Phnom Penh. Without proper delivery management, orders sit unprocessed, customers do not know when their order will arrive, and failed deliveries are not tracked.

### Delivery Status Flow

```
┌───────────┐    ┌──────────────┐    ┌───────────┐
│ Assigned  │───>│ On The Way   │───>│ Delivered │
└───────────┘    └──────────────┘    └───────────┘
     │                │
     └────────────────┘──────────> Failed
                                    (with reason)
```

### How It Solves Business Problems

| Problem | Solution |
|---|---|
| Orders sit unprocessed | Delivery staff assignment from admin |
| Customers do not know delivery status | Tracking number and status updates |
| Failed deliveries not recorded | Failed delivery reason tracking |
| No proof of delivery | Received-by field records who accepted |
| Delivery staff overloaded | Dashboard shows assigned count |

### Features Implemented

| Feature | Description | Code Location |
|---|---|---|
| Delivery record creation | Created when order status changes to shipped | `OrderStatusService` |
| Assign delivery staff | Admin assigns a delivery person | `Admin\OrderController@assignDelivery` |
| Delivery dashboard | Staff sees their assigned/in-transit/completed counts | `DeliveryDashboardController@index` |
| Delivery detail view | Shows order items, customer info, address | `DeliveryDashboardController@show` |
| Update delivery status | Mark as on-the-way, delivered, or failed | `DeliveryDashboardController@updateStatus` |
| Failed delivery reason | Record why delivery failed | `deliveries.failed_delivery_reason` |
| Received-by field | Name of person who received | `deliveries.received_by` |
| Tracking number | Unique tracking number | `deliveries.tracking_no` |
| Auto payment update | Payment status updates on delivery completion | In delivery status update |
| Separate layout | Delivery staff has own interface | `layouts/delivery.blade.php` |

### Database Table

```
deliveries
├── id (primary key)
├── order_id (foreign key → orders)
├── delivery_staff_id (foreign key → users)
├── delivery_date (TIMESTAMP)
├── delivery_status (VARCHAR)
├── tracking_no (VARCHAR)
├── received_by (VARCHAR)
└── failed_delivery_reason (TEXT)
```

---

## 10. Reports and Dashboard

### Why This Area Is Important

Store owners need simple, actionable reports. They need to know: How much did I sell today? What is running low? Which items sell best? The dashboard is what they check every morning to make business decisions.

### How It Solves Business Problems

| Problem | Solution |
|---|---|
| Owner does not know daily revenue | Today's sales displayed on dashboard |
| Guessing which products to restock | Best-selling products report |
| Popular items running out unexpectedly | Low stock alerts and report |
| Orders forgotten or delayed | Pending orders count on dashboard |
| No business trend visibility | Monthly sales data and chart |

### Dashboard Statistics

The admin dashboard displays 12+ key statistics:

```
┌──────────────────┬──────────────────┬──────────────────┬──────────────────┐
│  Total Products  │  Total Categories│  Total Customers │  Total Orders    │
├──────────────────┼──────────────────┼──────────────────┼──────────────────┤
│  Total Revenue   │  Today's Sales   │  Pending Orders  │  Pending Payments│
├──────────────────┴──────────────────┴──────────────────┴──────────────────┤
│  Low Stock Alerts                    │  Order Status Breakdown            │
├──────────────────────────────────────┼────────────────────────────────────┤
│  Recent Orders (10 latest)           │  Best Sellers (Top 5)              │
├──────────────────────────────────────┼────────────────────────────────────┤
│  Recent Stock Movements              │  Monthly Sales Data (6 months)     │
└──────────────────────────────────────┴────────────────────────────────────┘
```

### Features Implemented

| Feature | Description | Code Location |
|---|---|---|
| Admin dashboard | Central view with 12+ statistics | `Admin\DashboardController@index` |
| Total counts | Products, categories, customers, orders | In dashboard |
| Revenue tracking | Total revenue, today's sales | In dashboard |
| Pending items | Pending orders, pending payments | In dashboard |
| Low stock alerts | Products below reorder level | In dashboard |
| Recent orders | Latest 10 orders with status | In dashboard |
| Order status breakdown | Count per status | In dashboard |
| Best sellers | Top 5 selling products | In dashboard |
| Monthly sales data | 6-month trend | In dashboard |
| Reports page | Tabbed interface with multiple reports | `Admin\ReportController@index` |
| Daily sales report | Revenue for today | In reports |
| Monthly sales report | Revenue by month | In reports |
| Best selling products | Products ranked by quantity sold | In reports |
| Low stock report | All products below reorder level | In reports |
| Order summary by status | Count and total per status | In reports |
| Payment summary by method | Breakdown by COD, transfer, QR | In reports |

---

## 11. User Roles and Security

### Why This Area Is Important

A grocery store has three types of users with very different responsibilities. Role-based access control prevents customers from accessing admin functions, and delivery staff from modifying products or prices. Security protects customer data and payment information.

### Three User Roles

| Role | Access | Responsibilities |
|---|---|---|
| Admin | Full access to all features | Manage products, orders, payments, deliveries, inventory, suppliers, reports, settings |
| Customer | Shopping and account only | Browse products, add to cart, checkout, view orders, manage profile |
| Delivery | Delivery operations only | View assigned deliveries, update delivery status |

### How Role Security Works

```
Request comes in
    │
    ▼
Is user logged in? ──── No ──> Redirect to login
    │
    Yes
    │
    ▼
Does user have correct role? ──── No ──> Show 403 Forbidden
    │
    Yes
    │
    ▼
Allow access to controller
```

### How It Solves Business Problems

| Problem | Solution |
|---|---|
| Customers accessing admin panel | Role-based middleware blocks unauthorized access |
| Staff modifying products they should not | Route-level role restrictions |
| No accountability for changes | Activity logging with user, action, old/new values |
| Weak passwords | Laravel Breeze password hashing and reset |
| Session hijacking | Database sessions with encryption |

### Features Implemented

| Feature | Description | Code Location |
|---|---|---|
| Three user roles | admin, customer, delivery | `users.role` column |
| Role-based middleware | Checks role before accessing routes | `CheckRole.php` |
| Registration | New users register as customers | `RegisteredUserController` |
| Login with redirect | Redirect to role-appropriate dashboard | `AuthenticatedSessionController` |
| Password reset | Email-based recovery | Laravel Breeze |
| Profile management | Edit name, email, phone, address | `ProfileController` |
| Activity logging | All admin actions recorded | `ActivityLogger` service |
| Activity log viewer | Admin views audit trail with filters | `Admin\ActivityLogController` |
| Customer management | Admin views customer list and details | `Admin\CustomerController` |

### Database Tables

```
users
├── id (primary key)
├── name (VARCHAR)
├── email (VARCHAR, unique)
├── phone (VARCHAR)
├── role (VARCHAR: admin, customer, delivery)
├── status (VARCHAR: active, inactive)
└── password (VARCHAR, hashed)

customers
├── id (primary key)
├── user_id (foreign key → users)
├── address (TEXT)
├── city (VARCHAR)
└── note (TEXT)

activity_logs
├── id (primary key)
├── user_id (foreign key → users)
├── action (VARCHAR)
├── model_type (VARCHAR)
├── model_id (BIGINT)
├── description (TEXT)
├── old_values (JSON)
├── new_values (JSON)
└── ip_address (VARCHAR)
```

---

## 12. UI/UX Improvement

### Why This Area Is Important

Cambodian users expect clean, simple interfaces. If the grocery app is confusing or slow, they switch to competitors. Mobile-first design is essential because most orders come from phones.

### How It Solves Business Problems

| Problem | Solution |
|---|---|
| Poor mobile experience loses customers | Tailwind CSS responsive design by default |
| Confusing navigation | Separate layouts with role-appropriate menus |
| No feedback after actions | Component-based UI with consistent design |
| Slow page loads | Pagination, lightweight Alpine.js |

### Features Implemented

| Feature | Description | Code Location |
|---|---|---|
| Tailwind CSS | Utility-first responsive CSS | `tailwind.config.js` |
| Alpine.js | Lightweight JavaScript for interactivity | `resources/js/app.js` |
| Separate layouts | Different design per role | `layouts/admin.blade.php`, `layouts/customer.blade.php`, `layouts/delivery.blade.php` |
| Reusable components | 13 Blade components | `resources/views/components/` |
| Product card partial | Consistent product display | `partials/product-card.blade.php` |
| Product filters partial | Reusable filter sidebar | `partials/product-filters.blade.php` |
| Hero section | Homepage banner | `home.blade.php` |
| Pagination | 12 products per page | In product listing |
| Responsive grid | 4 columns desktop, 1 column mobile | Tailwind grid classes |

### Reusable Components

| Component | Purpose |
|---|---|
| `x-primary-button` | Main action buttons (green) |
| `x-secondary-button` | Secondary action buttons (gray) |
| `x-danger-button` | Destructive action buttons (red) |
| `x-text-input` | Form text input fields |
| `x-input-label` | Form labels |
| `x-input-error` | Validation error messages |
| `x-dropdown` | Dropdown menus |
| `x-dropdown-link` | Links inside dropdowns |
| `x-modal` | Modal dialog boxes |
| `x-nav-link` | Navigation links |
| `x-responsive-nav-link` | Mobile navigation links |
| `x-application-logo` | Logo component |
| `x-auth-session-status` | Login status messages |

---

## 13. Advanced Features

### Why This Area Is Important

These features go beyond basic shopping to provide a complete business solution. They add competitive advantage and are essential for long-term business operations.

### How It Solves Business Problems

| Problem | Solution |
|---|---|
| No customer promotions | Coupon system with flexible discount rules |
| No restocking process | Supplier management and purchase orders |
| Users not informed of updates | In-app notification system |
| No accountability for admin actions | Activity audit logging |
| Store settings hardcoded | Configurable settings management |

### Features Implemented

| Feature | Description | Code Location |
|---|---|---|
| Wishlist | Save products for later purchase | `WishlistController` |
| Coupon system | Percentage or fixed discounts with rules | `Admin\CouponController` |
| Supplier management | CRUD for suppliers with contact info | `Admin\SupplierController` |
| Purchase orders | Create POs, receive stock, track deliveries | `Admin\PurchaseOrderController` |
| Product-supplier linking | Many-to-many with supply price and lead time | `product_supplier` pivot |
| Notifications | In-app notifications for updates | `NotificationController` |
| Activity/audit log | Records all admin actions | `Admin\ActivityLogController` |
| Settings management | 23 configurable settings in 6 groups | `Admin\SettingController` |
| Contact messages | Customer messages stored in database | `ContactController` |

### Coupon System Rules

| Rule | Description |
|---|---|
| Discount type | Percentage (%) or fixed amount ($) |
| Minimum order amount | Coupon only works above this amount |
| Maximum discount cap | Limits percentage discount to maximum value |
| Usage limit | How many times coupon can be used total |
| Applies to | All products, specific category, specific product, or delivery fee |
| Date range | Start and end date for validity |

### Purchase Order Flow

```
Admin creates Supplier record
    │
    ├── Links products to supplier with supply price
    │
    ├── Creates Purchase Order (PO)
    │       │
    │       ├── Selects supplier
    │       ├── Adds products with quantities and costs
    │       └── PO Status: pending → ordered → partially_received → received
    │
    └── Receives Stock from supplier
            │
            ├── Updates inventory quantities
            ├── Records stock movement (stock_in)
            └── Updates PO received quantities
```

### Settings Configuration

| Group | Settings |
|---|---|
| General | store_name, store_email, store_phone, store_address |
| Delivery | delivery_fee, free_delivery_threshold |
| Payment | payment_instructions, bank_name, bank_account |
| Order | min_order_amount, max_order_amount, order_processing_time |
| Tax | tax_rate, tax_enabled |
| Notification | admin_notification_email, low_stock_threshold |

### Database Tables

```
suppliers
├── id (primary key)
├── supplier_name (VARCHAR)
├── contact_person (VARCHAR)
├── phone (VARCHAR)
├── email (VARCHAR)
├── address (TEXT)
└── status (VARCHAR)

product_supplier (pivot)
├── product_id (foreign key → products)
├── supplier_id (foreign key → suppliers)
├── supply_price (DECIMAL)
└── lead_time_days (INTEGER)

purchase_orders
├── id (primary key)
├── supplier_id (foreign key → suppliers)
├── order_number (VARCHAR, unique)
├── total_amount (DECIMAL)
├── status (VARCHAR)
├── ordered_by (foreign key → users)
├── received_by (foreign key → users)
├── received_at (TIMESTAMP)
└── note (TEXT)

purchase_order_items
├── id (primary key)
├── purchase_order_id (foreign key → purchase_orders)
├── product_id (foreign key → products)
├── quantity (INTEGER)
├── unit_cost (DECIMAL)
├── subtotal (DECIMAL)
└── received_qty (INTEGER)

coupons
├── id (primary key)
├── code (VARCHAR, unique)
├── discount_type (VARCHAR)
├── discount_value (DECIMAL)
├── min_order_amount (DECIMAL)
├── max_discount (DECIMAL)
├── usage_limit (INTEGER)
├── used_count (INTEGER)
├── applies_to (VARCHAR)
├── applies_to_id (BIGINT)
├── start_date (DATE)
├── end_date (DATE)
└── status (VARCHAR)

notifications
├── id (primary key)
├── user_id (foreign key → users)
├── title (VARCHAR)
├── message (TEXT)
├── type (VARCHAR)
├── reference_type (VARCHAR)
├── reference_id (BIGINT)
└── is_read (BOOLEAN)

settings
├── id (primary key)
├── key (VARCHAR, unique)
├── value (TEXT)
└── group (VARCHAR)
```

---

## 14. Feature Classification

### Core Features (Must Have)

These are essential for the system to function as a grocery store.

| # | Area | Why It Is Core |
|---|---|---|
| 1 | Product and Category Management | Without products, there is nothing to sell |
| 2 | Inventory and Stock Management | Prevents overselling and tracks availability |
| 3 | Customer Shopping Experience | The shopping flow generates revenue |
| 4 | Order Management | Processes and tracks all purchases |
| 5 | Payment Management | Collects money from customers |
| 6 | User Roles and Security | Protects system and separates responsibilities |

### Important Business Features (Should Have)

These make the business run efficiently.

| # | Area | Why It Is Important |
|---|---|---|
| 7 | Delivery Management | Fulfills orders and satisfies customers |
| 8 | Reports and Dashboard | Enables data-driven decisions |
| 9 | Supplier and Purchase Orders | Manages restocking and supply chain |
| 10 | Coupons and Promotions | Drives marketing and retention |

### Advanced Features (Nice to Have)

These add competitive advantage for future versions.

| # | Feature | Why It Is Advanced |
|---|---|---|
| 1 | Return/Refund System | Handles post-delivery issues |
| 2 | SMS/Telegram Notifications | Enhances communication |
| 3 | Real-time Payment Gateway | Reduces manual verification |
| 4 | Delivery GPS Tracking | Real-time delivery location |
| 5 | Customer Reviews | Adds social proof |
| 6 | Email Notifications | Professional communication |
| 7 | PDF Invoice Export | Professional documentation |
| 8 | Two-Factor Authentication | Enhanced security |

---

## 15. Development Priority

### Version 1 — Current Build (Complete)

All core and important business features are implemented.

```
✅ Product and Category Management (15 features)
✅ Inventory and Stock Management (11 features)
✅ Customer Shopping Experience (14 features)
✅ Order Management (11 features)
✅ Payment Management (8 features)
✅ Delivery Management (10 features)
✅ Reports and Dashboard (16 features)
✅ User Roles and Security (9 features)
✅ Supplier and Purchase Orders (7 features)
✅ Coupons and Promotions (9 features)
✅ Wishlist and Notifications (5 features)
✅ Activity Logging and Settings (6 features)
```

### Version 1.1 — Immediate Polish

| Priority | Task | Effort | Impact |
|---|---|---|---|
| 1 | Add Chart.js graphs to dashboard | 1 day | High |
| 2 | Add toast notifications for user feedback | 0.5 day | High |
| 3 | Add mobile hamburger menu | 0.5 day | High |
| 4 | Add loading states and spinners | 1 day | Medium |
| 5 | Add breadcrumb navigation | 0.5 day | Medium |
| 6 | Add image lazy loading | 0.5 day | Medium |

### Version 1.5 — Business Enhancement

| Priority | Task | Effort | Impact |
|---|---|---|---|
| 1 | Return and refund system | 2 days | High |
| 2 | Email notifications (SMTP) | 1 day | High |
| 3 | PDF invoice export | 1.5 days | Medium |
| 4 | Customer reviews and ratings | 2 days | Medium |
| 5 | Expiry date alerts | 0.5 day | High |
| 6 | Bulk product import (CSV) | 1.5 days | Medium |

### Version 2 — Advanced Integration

| Priority | Task | Effort | Impact |
|---|---|---|---|
| 1 | SMS notifications (Smart/Axiata) | 2 days | High |
| 2 | Real-time QR payment (ABA Pay/Bakong) | 3-5 days | High |
| 3 | Telegram bot notifications | 1 day | Medium |
| 4 | Delivery route optimization | 2 days | Medium |
| 5 | Two-factor authentication | 1 day | Medium |
| 6 | Customer analytics dashboard | 2 days | Medium |

---

## 16. Business Value Summary

| Module | Business Value | Impact |
|---|---|---|
| Product Management | Enables all sales activity | Critical |
| Shopping Cart and Checkout | Directly generates revenue | Critical |
| Order Management | Operational backbone | Critical |
| Payment Management | Collects and tracks revenue | Critical |
| Inventory Management | Prevents financial losses | High |
| Delivery Management | Ensures customer satisfaction | High |
| User Roles and Security | Protects business data | High |
| Reports and Dashboard | Supports decision making | High |
| Supplier and Purchase Orders | Maintains supply chain | Medium-High |
| Coupons and Promotions | Drives customer acquisition | Medium |
| Notifications | Improves communication | Medium |
| Activity Logs | Provides accountability | Medium |

---

## 17. Cambodia Market Context

### Payment Landscape

| Method | Market Share | System Support |
|---|---|---|
| Cash on Delivery | ~70% | ✅ Supported |
| QR Payment (Bakong, ABA, Wing) | ~20% growing | ✅ Supported |
| Bank Transfer | ~10% | ✅ Supported |

### Delivery Model

- Motorcycle-based delivery is standard in Phnom Penh
- Delivery radius typically 5-15 km from store
- Same-day delivery expected by most customers
- Traffic congestion is a major factor in delivery times

### Customer Behavior

- Over 80% of internet access is via smartphone
- Users expect Google-level simplicity
- Khmer language support is a plus but English is acceptable for tech-savvy users
- Social media (Facebook, Telegram) is primary marketing channel

### Competition

| Competitor | Type | Differentiator |
|---|---|---|
| Grab Mart | Multi-category marketplace | Established brand, wide selection |
| Nham24 | Food and grocery delivery | Fast delivery, popular app |
| FoodPanda | Food and grocery delivery | International brand, promotions |
| Local grocery stores | Traditional shops | Personal relationships, trust |

This system differentiates by being a dedicated grocery platform with full inventory management, supplier integration, and customizable for individual store brands.

---

## 18. Recommendations

### For a Student Final Year Project

| Aspect | Recommendation |
|---|---|
| Frontend | Blade + Tailwind CSS + Alpine.js — demonstrates full-stack skills |
| Backend | Laravel with Eloquent ORM — industry standard |
| Database | Use SQLite for demo; mention MySQL for production |
| Testing | Add 5-10 feature tests for quality assurance |
| Documentation | Include ERD diagram, use case diagrams, system architecture |
| Presentation | Demo the complete flow: register → browse → cart → checkout → admin process → delivery |
| Version Control | Use Git with meaningful commit messages |

### Front-End Recommendations

| Recommendation | Reason | Priority |
|---|---|---|
| Keep Tailwind CSS | Already integrated, mobile-first | ✅ Already using |
| Keep Alpine.js | Lightweight, no SPA complexity | ✅ Already using |
| Add Chart.js | Dashboard data needs visualization | High |
| Add toast notifications | User feedback on actions | High |
| Add lazy loading | Many product images affect load time | Medium |

### Back-End Recommendations

| Recommendation | Reason | Priority |
|---|---|---|
| Keep Laravel 13 | Excellent for this application type | ✅ Already using |
| Switch to MySQL for production | Better concurrency | High |
| Add rate limiting | Prevent abuse on checkout and login | Medium |
| Add Form Request validation | Stronger input validation | Medium |
| Add queue workers | Process notifications asynchronously | Medium |

---

## 19. Conclusion

This E-commerce Online Grocery Management System comprehensively covers all 10 key development areas with:

- **24 database migrations** creating approximately 30 tables
- **23 Eloquent models** with rich relationships
- **27 controllers** organized by role (admin, customer, delivery, auth)
- **84 Blade views** with separate layouts per role
- **4 service classes** for business logic separation

The system is designed for the Cambodian market with appropriate payment methods, delivery model, and store configuration. It demonstrates full-stack web development capabilities including database design, backend logic, frontend interface, role-based access control, and complete business process management.

All core features (product management, inventory, shopping experience, orders, payments, security) and important business features (delivery, reports, suppliers, coupons) are fully implemented and functional. The system is a complete, working prototype suitable for final year project submission.

---

## 20. Appendix: File Inventory

### Database Migrations (24 files)

| # | Migration File | Table |
|---|---|---|
| 1 | create_users_table | users |
| 2 | create_cache_table | cache |
| 3 | create_jobs_table | jobs, job_batches, failed_jobs |
| 4 | create_customers_table | customers |
| 5 | create_categories_table | categories |
| 6 | create_products_table | products |
| 7 | create_inventories_table | inventories |
| 8 | create_carts_table | carts |
| 9 | create_cart_items_table | cart_items |
| 10 | create_orders_table | orders |
| 11 | create_order_details_table | order_details |
| 12 | create_payments_table | payments |
| 13 | create_deliveries_table | deliveries |
| 14 | create_stock_movements_table | stock_movements |
| 15 | create_order_status_histories_table | order_status_histories |
| 16 | create_payment_verifications_table | payment_verifications |
| 17 | create_suppliers_table | suppliers, product_supplier |
| 18 | create_purchase_orders_table | purchase_orders, purchase_order_items |
| 19 | create_coupons_table | coupons |
| 20 | create_notifications_table | notifications |
| 21 | create_activity_logs_table | activity_logs |
| 22 | create_settings_table | settings |
| 23 | create_wishlists_table | wishlists |
| 24 | create_contact_messages_table | contact_messages |

### Models (23 files)

User, Customer, Category, Product, Inventory, Cart, CartItem, Order, OrderDetail, Payment, Delivery, StockMovement, OrderStatusHistory, PaymentVerification, Supplier, PurchaseOrder, PurchaseOrderItem, Coupon, AppNotification, ActivityLog, Setting, Wishlist, ContactMessage

### Controllers (27 files)

**Customer (8):** HomeController, ProductController, CartController, CheckoutController, OrderController, ProfileController, WishlistController, ContactController, NotificationController

**Admin (14):** DashboardController, CategoryController, ProductController, CustomerController, InventoryController, OrderController, PaymentController, DeliveryController, ReportController, StockMovementController, OrderStatusHistoryController, SupplierController, PurchaseOrderController, CouponController, ActivityLogController, SettingController

**Delivery (1):** DashboardController

**Auth (8):** AuthenticatedSessionController, RegisteredUserController, ConfirmablePasswordController, EmailVerificationNotificationController, EmailVerificationPromptController, NewPasswordController, PasswordController, PasswordResetLinkController, VerifyEmailController

### Services (4 files)

ActivityLogger, NotificationService, OrderStatusService, StockMovementService

### Views (84 files)

| Category | Count |
|---|---|
| Admin views | 34 |
| Customer views | 9 |
| Delivery views | 2 |
| Auth views | 7 |
| Profile views | 4 |
| Layouts | 5 |
| Components | 13 |
| Partials | 2 |
| Other | 8 |

---

*Document prepared for Final Year Project Documentation*
*E-commerce Online Grocery Management System*
*Technology: Laravel 13, PHP 8.3, Tailwind CSS, Alpine.js*
*Market: Cambodia (Phnom Penh)*
