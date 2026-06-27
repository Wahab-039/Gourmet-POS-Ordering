# 🍽️ Gourmet POS & Restaurant Ordering Management System

A full-stack, secure, and scalable **Restaurant Ordering & Management System** built as a university-level web development project. The system handles the complete restaurant workflow — from customers browsing the menu and placing orders, to kitchen staff preparing food, cashiers processing payments, and administrators managing the entire operation.

---

## 📌 Table of Contents

1. [Project Overview](#project-overview)
2. [Technology Stack](#technology-stack)
3. [System Requirements](#system-requirements)
4. [Installation & Setup](#installation--setup)
5. [Project Structure](#project-structure)
6. [Database Schema](#database-schema)
7. [User Roles & Access Control](#user-roles--access-control)
8. [Feature Breakdown](#feature-breakdown)
9. [Security Implementation](#security-implementation)
10. [Complete Workflow](#complete-workflow)
11. [Default Credentials](#default-credentials)
12. [File Reference Guide](#file-reference-guide)

---

## 📖 Project Overview

This system digitizes the complete restaurant operation cycle, replacing paper-based order taking with a real-time, role-based web platform. The project demonstrates core university-level concepts including:

- Relational database design with normalization
- Server-side MVC-inspired architecture using PHP
- Role-Based Access Control (RBAC)
- Secure web application development practices
- Database transactions for data integrity
- Session management and state handling

The system supports **4 distinct user roles**, each with their own dashboard and restricted access, ensuring that no user can access another role's features.

---

## 🛠️ Technology Stack

| Layer | Technology | Purpose |
|---|---|---|
| **Backend Language** | PHP 8.0+ | All application logic, routing, session handling |
| **Database** | MySQL 8.0 (via XAMPP) | Data persistence, relational storage |
| **Database Driver** | PDO (PHP Data Objects) | Secure, prepared statement-based queries |
| **Frontend Markup** | HTML5 | Page structure and semantic elements |
| **Frontend Styling** | CSS3 + Bootstrap 5.3 | Responsive UI, layout, components |
| **Icons** | Bootstrap Icons 1.11 | UI iconography |
| **Web Server** | Apache 2.4 (via XAMPP) | Local development server |
| **JavaScript** | ❌ None | Zero JS used — all logic is pure PHP |

> **Important Design Decision:** The entire application runs without any JavaScript or frontend frameworks. All form submissions, navigation, filtering, and state management are handled exclusively through PHP and HTML forms with GET/POST methods.

---

## 💻 System Requirements

- [XAMPP](https://www.apachefriends.org/) (or any stack with Apache + MySQL + PHP 8+)
- A modern web browser (Chrome, Firefox, Edge)
- PHP 8.0 or higher
- MySQL 5.7 or higher

---

## ⚙️ Installation & Setup

### Step 1: Place the Project in XAMPP's Web Root
Copy the entire project folder into your XAMPP `htdocs` directory:
```
D:\xampp\htdocs\Monkey Web Dev project\
```

### Step 2: Start XAMPP Services
Open the **XAMPP Control Panel** and start:
- ✅ **Apache**
- ✅ **MySQL**

### Step 3: Create the Database
1. Open your browser and go to: `http://localhost/phpmyadmin`
2. Click the **Import** tab at the top.
3. Click **Choose File** and select `setup.sql` from the project root.
4. Click **Import**. This will:
   - Create the `restaurant_db` database
   - Build all 9 tables with foreign key relationships
   - Insert the 4 default roles (Customer, Kitchen, Cashier, Admin)
   - Insert a default Admin user

### Step 4: Fix the Admin Password
Visit this URL in your browser to properly hash the admin password:
```
http://localhost/Monkey%20Web%20Dev%20project/fix_admin.php
```

### Step 5: Access the Application
```
http://localhost/Monkey%20Web%20Dev%20project/index.php
```

### Step 6: (Optional) Seed the Menu
To auto-populate the menu with 20 food items and 5 categories, visit:
```
http://localhost/Monkey%20Web%20Dev%20project/seed_menu.php
```

---

## 📁 Project Structure

```
Monkey Web Dev project/
│
├── index.php                  # Front controller — handles all routing
├── setup.sql                  # Full database schema + seed data
├── fix_admin.php              # One-time admin password reset utility
├── seed_menu.php              # Populates DB with 20 sample food items
│
├── config/
│   ├── database.php           # PDO connection (strict error mode)
│   └── constants.php          # App name, BASE_URL, upload paths
│
├── includes/
│   ├── session.php            # Session init, RBAC helpers (requireRole)
│   ├── security.php           # CSRF token generation/validation, XSS escaping
│   └── helpers.php            # Flash messages, formatCurrency, redirect, logAction
│
├── controllers/
│   ├── auth_controller.php    # Login, Register logic
│   ├── admin_controller.php   # Category/Food/User management POST handlers
│   ├── cart_controller.php    # Add/Update/Remove/Clear cart (session-based)
│   ├── order_controller.php   # Place order with DB transaction
│   ├── kitchen_controller.php # Update order status (Pending → Ready)
│   ├── cashier_controller.php # Process payment, mark order Completed
│   └── review_controller.php  # Submit food review
│
├── views/
│   ├── layouts/
│   │   ├── header.php         # HTML head, Bootstrap CSS, navbar with role-aware nav
│   │   └── footer.php         # Footer, Bootstrap JS bundle
│   │
│   ├── auth/
│   │   ├── login.php          # Login form
│   │   └── register.php       # Customer registration form
│   │
│   ├── admin/
│   │   ├── dashboard.php      # Stats overview (users, categories, foods, orders)
│   │   ├── categories.php     # Category list + Add/Delete modal
│   │   ├── foods.php          # Food item list + Add/Delete modal (with image upload)
│   │   └── users.php          # User list + role update form
│   │
│   ├── customer/
│   │   ├── menu.php           # Browsable menu with search + category filter
│   │   ├── cart.php           # Shopping cart with quantity update & remove
│   │   ├── checkout.php       # Delivery info + order summary confirmation
│   │   ├── history.php        # List of all past orders with status badges
│   │   └── order_details.php  # Full order breakdown + review submission form
│   │
│   ├── kitchen/
│   │   └── queue.php          # Live order queue with color-coded status cards
│   │
│   └── cashier/
│       ├── dashboard.php      # Table of "Ready" orders awaiting payment
│       └── receipt.php        # Clean printable HTML receipt
│
└── assets/
    ├── css/                   # Custom CSS override files
    └── images/
        └── foods/             # Uploaded & generated food images (JPG/PNG/WEBP)
```

---

## 🗄️ Database Schema

The database `restaurant_db` follows 3rd Normal Form (3NF) with proper foreign key relationships and cascading deletes.

### Entity Relationship Diagram

```
roles ──────────────── users
  (1)              (many)
                      │
          ┌───────────┼───────────┐
          │                       │
        orders               audit_logs
          │ (1)
          │
    ┌─────┴─────┐
    │           │
order_items   payments
    │ (many)
    │
  foods ─────── categories
    │ (1)
    │
  reviews
```

### Tables

#### `roles`
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK | Auto-increment |
| name | VARCHAR(50) | Customer, Kitchen, Cashier, Admin |

#### `users`
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK | Auto-increment |
| role_id | INT FK | References `roles.id` |
| name | VARCHAR(100) | Full name |
| email | VARCHAR(100) UNIQUE | Login identifier |
| password | VARCHAR(255) | Bcrypt hashed |
| phone | VARCHAR(20) | Optional contact |
| address | TEXT | Delivery address |
| created_at | TIMESTAMP | Auto-set on creation |

#### `categories`
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK | Auto-increment |
| name | VARCHAR(100) | e.g., Burgers, Pizzas |
| description | TEXT | Short description |
| image_path | VARCHAR(255) | Filename in assets/images/ |

#### `foods`
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK | Auto-increment |
| category_id | INT FK | References `categories.id` CASCADE |
| name | VARCHAR(100) | Food item name |
| description | TEXT | Ingredients / details |
| price | DECIMAL(10,2) | Price in USD |
| image_path | VARCHAR(255) | Filename in assets/images/foods/ |
| is_available | BOOLEAN | Show/hide on menu |

#### `orders`
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK | Auto-increment |
| user_id | INT FK | References `users.id` |
| total_amount | DECIMAL(10,2) | Calculated at checkout |
| status | ENUM | Pending, Accepted, Preparing, Ready, Completed, Cancelled |
| created_at | TIMESTAMP | When order was placed |
| updated_at | TIMESTAMP | Auto-updated on status change |

#### `order_items`
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK | Auto-increment |
| order_id | INT FK | References `orders.id` CASCADE |
| food_id | INT FK | References `foods.id` |
| quantity | INT | Number of units ordered |
| price_at_time | DECIMAL(10,2) | Locked price (protects against menu price changes) |

#### `payments`
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK | Auto-increment |
| order_id | INT FK | References `orders.id` CASCADE |
| cashier_id | INT FK | References `users.id` (the cashier who processed it) |
| amount | DECIMAL(10,2) | Total paid |
| method | VARCHAR(50) | Cash, Card on Delivery |
| status | VARCHAR(50) | Paid |
| created_at | TIMESTAMP | When payment was recorded |

#### `reviews`
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK | Auto-increment |
| user_id | INT FK | References `users.id` CASCADE |
| food_id | INT FK | References `foods.id` CASCADE |
| rating | INT | 1–5 stars (CHECK constraint) |
| comment | TEXT | Written review |
| created_at | TIMESTAMP | When review was submitted |

#### `audit_logs`
| Column | Type | Description |
|--------|------|-------------|
| id | INT PK | Auto-increment |
| user_id | INT FK | Who performed the action (SET NULL on delete) |
| action | VARCHAR(255) | Short action label |
| details | TEXT | Additional context |
| created_at | TIMESTAMP | When action occurred |

---

## 👥 User Roles & Access Control

The system uses a centralized RBAC mechanism defined in `includes/session.php`. Every protected page calls `requireRole($role_id)` before rendering anything.

| Role | Role ID | Default Access After Login | Can Access |
|------|---------|--------------------------|------------|
| **Customer** | 1 | `/index.php?page=menu` | Menu, Cart, Checkout, Order History, Reviews |
| **Kitchen** | 2 | `/index.php?page=kitchen_dashboard` | Kitchen Queue, Order Status Updates |
| **Cashier** | 3 | `/index.php?page=cashier_dashboard` | Cashier Register, Payment Processing, Receipts |
| **Admin** | 4 | `/index.php?page=admin_dashboard` | Everything above + User Management, Full CRUD |

> If any user tries to access a URL above their role, the server returns a **403 Forbidden** and kills the script before any data is rendered.

---

## 🔧 Feature Breakdown

### 🔐 Authentication Module
- **Register:** New users register as Customers. Passwords are hashed using `password_hash()` with `PASSWORD_DEFAULT` (bcrypt).
- **Login:** `password_verify()` checks the hashed password. On success, `session_regenerate_id(true)` is called to prevent session fixation attacks.
- **Logout:** Destroys the entire session with `session_destroy()`.
- **Redirect Logic:** Users are automatically redirected to their role-appropriate dashboard after login.

### 🛡️ Admin Module
- **Dashboard:** Displays live counts of total users, categories, food items, and orders in color-coded stat cards.
- **Category Management:** Create and delete food categories using Bootstrap modals. Deletion cascades to all foods in that category.
- **Food Management:** Add food items with name, price, description, category, availability toggle, and image upload. Images are validated for MIME type and saved with a unique filename.
- **User Management:** View all registered users and update their roles. Admins cannot change their own role (a safety guard).

### 🛒 Customer Module
- **Menu Page:** Displays all available foods in a responsive card grid. Includes a real-time search bar (by name/description) and a category dropdown filter. Both use PHP `GET` parameters — no JavaScript required.
- **Shopping Cart:** Fully session-based. When a user adds an item, it is stored in `$_SESSION['cart']` as an associative array keyed by `food_id`. Users can update quantities or remove individual items. A live cart counter badge appears in the navbar.
- **Checkout:** Shows an order summary and collects delivery address and payment method. Saves any changes to the user's profile.
- **Place Order:** Uses a **MySQL database transaction** (`beginTransaction()` / `commit()` / `rollBack()`) to insert the order and all order items atomically. If any part fails, the entire transaction rolls back.
- **Order History:** Tabular list of all past orders with status badges and links to full order details.
- **Order Details & Reviews:** Full item-level breakdown of an order. Once an order is `Completed`, a review form unlocks allowing the customer to rate and review specific food items they ordered.

### 🔥 Kitchen Module
- **Kitchen Queue:** Shows all active orders (Pending, Accepted, Preparing) as visual Kanban-style cards, color-coded by status (yellow = Pending, blue = Accepted, dark blue = Preparing).
- **Status Updates:** Single-click form buttons advance the order through the workflow: `Pending → Accepted → Preparing → Ready`.

### 💳 Cashier Module
- **Cashier Register:** Shows only `Ready` orders. Displays customer name and order total. Sorted by the time the order became ready (oldest first).
- **Payment Processing:** Clicking "Process Payment" marks the order as `Completed`, inserts a record into the `payments` table, and logs the cashier's ID — all in a single database transaction.
- **Printable Receipt:** A clean, minimal HTML receipt page is generated with order details, itemized list, total, and payment method. A print button triggers the browser's native print dialog. The print button is hidden in the printed output via CSS `@media print`.

### ⭐ Reviews Module
- Reviews can only be submitted for `Completed` orders.
- The system verifies (via a JOIN query) that the user actually ordered the specific food item before allowing a review.
- Reviews are auto-approved and publicly stored in the `reviews` table.

---

## 🔒 Security Implementation

Security was the #1 priority in this project. The following measures are implemented throughout:

### 1. SQL Injection Prevention — PDO Prepared Statements
Every single database query in the entire application uses PDO prepared statements with parameterized queries. No user input is ever directly concatenated into a SQL string.
```php
// ✅ CORRECT — Used everywhere in this project
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);

// ❌ WRONG — Never done in this project
$result = $pdo->query("SELECT * FROM users WHERE email = '$email'");
```

### 2. XSS (Cross-Site Scripting) Prevention
All user-supplied data is sanitized before being output to the browser using the `escape()` helper function defined in `security.php`.
```php
function escape($string) {
    return htmlspecialchars((string)$string, ENT_QUOTES, 'UTF-8');
}
// Usage: <?= escape($user['name']) ?>
```

### 3. CSRF (Cross-Site Request Forgery) Protection
Every HTML form that performs a state-changing action (POST request) includes a hidden CSRF token. The token is generated using cryptographically secure `random_bytes(32)`, stored in the session, and verified before any POST handler executes.
```php
// In every form:
<?= csrfField() ?>
// Generates: <input type="hidden" name="csrf_token" value="...">

// In every POST handler:
verifyCSRFToken($_POST['csrf_token'] ?? '');
```

### 4. Password Security
All passwords are hashed with PHP's `password_hash()` using the `PASSWORD_DEFAULT` algorithm (currently bcrypt). Password verification uses the constant-time `password_verify()` to prevent timing attacks.

### 5. Session Security
- Sessions are started with secure parameters: `httponly: true`, `samesite: Lax`.
- `session_regenerate_id(true)` is called on every login to prevent session fixation.
- Role is stored in `$_SESSION['role_id']` and checked via `requireRole()` on every protected page.

### 6. Secure File Uploads
Food images are validated for:
- ✅ MIME type must be `image/jpeg`, `image/png`, or `image/webp`
- ✅ File is renamed to a random `uniqid()` string to prevent path traversal
- ✅ Saved only to the designated `assets/images/foods/` directory

### 7. Role-Based Authorization
Every protected route calls `requireRole($role_id)` before any HTML or data is rendered. This ensures that accessing a URL directly (e.g., `/index.php?page=admin_dashboard`) without the correct session role returns a 403 Forbidden error immediately.

### 8. Database Transactions
Order placement and payment processing both use database transactions to ensure data integrity. If any step fails (e.g., inserting an order item), the entire operation rolls back, leaving the database in a clean state.

### 9. Error Handling
The PDO connection is configured with `PDO::ERRMODE_EXCEPTION`. All database exceptions are caught in try/catch blocks. Error details are **never shown to users** — only generic messages are displayed.

### 10. Audit Logging
Key administrative and staff actions (e.g., updating an order status) are logged to the `audit_logs` table via the `logAction()` helper, recording the user ID, action name, and timestamp.

---

## 🔄 Complete Workflow

```
[ADMIN]
  ↓ Creates Categories (Burgers, Pizzas, Drinks...)
  ↓ Adds Food Items with prices and images
  ↓ Promotes registered users to Kitchen/Cashier roles

[CUSTOMER]
  ↓ Browses menu, searches/filters food
  ↓ Adds items to session cart
  ↓ Goes to checkout, confirms delivery info
  ↓ Places order → ORDER STATUS: Pending

[KITCHEN STAFF]
  ↓ Sees Pending order on their queue
  ↓ Clicks "Accept" → ORDER STATUS: Accepted
  ↓ Clicks "Start Preparing" → ORDER STATUS: Preparing
  ↓ Clicks "Mark Ready" → ORDER STATUS: Ready

[CASHIER]
  ↓ Sees Ready order on their register
  ↓ Collects payment from customer
  ↓ Clicks "Process Payment" → ORDER STATUS: Completed
  ↓ Payment record created in DB
  ↓ Printable receipt is generated

[CUSTOMER]
  ↓ Sees order marked as Completed in order history
  ↓ Submits a star rating and review for the food items
```

---

## 🔑 Default Credentials

| Role | Email | Password |
|------|-------|----------|
| **Admin** | admin@admin.com | password |

> **To create Kitchen/Cashier accounts:** Register a new account normally as a Customer, then log in as Admin, go to **Manage Users**, and update that user's role.

---

## 📄 File Reference Guide

| File | Purpose |
|------|---------|
| `index.php` | The only entry point to the app. Routes all GET `?page=` requests to the correct view/controller. |
| `setup.sql` | Run once in phpMyAdmin to build the entire database. |
| `fix_admin.php` | One-time utility to set the admin password. Can be deleted after use. |
| `seed_menu.php` | Populates the database with 5 categories and 20 food items. Safe to run multiple times. |
| `config/database.php` | PDO connection. Change `$user`/`$pass` here if your MySQL credentials differ. |
| `config/constants.php` | Change `APP_NAME` here to rebrand the restaurant. |
| `includes/security.php` | The `escape()` and `csrfField()` functions used globally. |
| `includes/session.php` | `requireLogin()`, `requireRole()`, `hasRole()` used on every protected page. |
| `views/cashier/receipt.php` | The printable receipt — uses standalone HTML, not the shared header/footer layout. |

---

## 📝 Notes

- The project uses **Bootstrap's JS bundle** only for UI components like dropdowns and modals. Zero custom JavaScript logic was written.
- The `BASE_URL` constant in `constants.php` is dynamically calculated, meaning the app works regardless of the folder name or server port.
- The `price_at_time` column in `order_items` locks in the price at the moment of ordering, so historical order totals remain accurate even if an admin later changes a food item's price.