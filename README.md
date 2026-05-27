# 📄 Sumber Rezeki

A modern, web-based store management and cashier system designed for the daily operations of **Toko Sumber Rezeki**. Built with **Laravel 12**, **Tailwind CSS**, and **Alpine.js**, it offers a clean, intuitive, and efficient interface for managing inventory, transactions, and financial records.

## ✨ Key Features

- **Secured Authentication**: Built-in login and profile management using Laravel Breeze.
- **Role-Based Access Control (RBAC)**: Separate permissions for Admins and Cashiers powered by Spatie Permission.
- **Inventory & Category Management**: Seamless CRUD operations for goods, categories, and item codes.
- **Transactions & Cashier System**: Fast and efficient checkout flows to record sales, purchases, and other transactions.
- **Debt & Credit Tracking**: Comprehensive management of customer receivables (Piutang) and supplier payables (Hutang).
- **Reports & Analytics**: An interactive dashboard showing summary statistics, with support for exporting sales history to Excel spreadsheets.

## 🚀 Technologies Used

- **Framework**: Laravel 12 (PHP)
- **Database & ORM**: MySQL / SQLite (Eloquent ORM)
- **Frontend & Styling**: Tailwind CSS, Alpine.js
- **Access Control**: Spatie Laravel-Permission
- **Exports**: Maatwebsite Laravel Excel
- **Authentication**: Laravel Breeze

## 🔐 Access Control & Permissions (RBAC)

Below is the routing architecture and access permissions configured for each role:

| Route Path | Description | Access Role |
| :--- | :--- | :--- |
| `/login` | Access authentication screen | Guest (All) |
| `/dashboard` | View overview statistics & sales summaries | Admin, Cashier |
| `/profile` | View and edit user profile settings | Admin, Cashier |
| `/barang` | Manage item inventory | Admin (Full CRUD), Cashier (View Only) |
| `/transaksi` | Record & view transaction logs (in/out) | Admin, Cashier |
| `/piutang` | Monitor customer receivables / credit | Admin |
| `/hutang-supplier`| Monitor supplier payables / debt | Admin |
| `/kode-barang` | Manage category and item code settings | Admin |
| `/transaksi/export`| Export transaction logs to Excel spreadsheets | Admin, Cashier |

## 🛠️ Getting Started

### Prerequisites
Make sure you have PHP (>= 8.2), Composer, Node.js, and npm installed on your local machine.

### Installation

1. Clone the repository and navigate into the project directory:
   ```bash
   git clone https://github.com/liunadirizkyh/sistem-management-toko.git
   cd sistem-management-toko
   ```

2. Install the backend (Composer) and frontend (npm) dependencies:
   ```bash
   composer install
   npm install
   ```

3. Set up the environment configuration:
   ```bash
   cp .env.example .env
   # Configure database settings in .env if not using the default SQLite database
   ```

4. Generate the application security key and initialize the database:
   ```bash
   php artisan key:generate
   touch database/database.sqlite
   php artisan migrate --seed
   ```

5. Start the development server (runs both the PHP server and Vite bundler):
   ```bash
   composer dev
   ```

6. Open [http://localhost:8000](http://localhost:8000) in your browser to see the application in action.

## ⚙️ Environment Variables (`.env`)

Ensure the following essential environment variables are properly configured in your `.env` file:

```env
APP_NAME="Sumber Rezeki"
APP_URL=http://localhost:8000

DB_CONNECTION=sqlite
# Alternatively, to use MySQL:
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=sumber_rezeki
# DB_USERNAME=root
# DB_PASSWORD=
```

## 🤝 Contributing

Contributions, issues, and feature requests are welcome! Feel free to check the issues page if you want to contribute.

## 📝 License

This project is privately owned and developed. All rights reserved.
