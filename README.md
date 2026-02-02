# Misa Cinema - Premium Cinema Management System

**Misa Cinema** is a comprehensive cinema booking and management platform built with the CakePHP 4 framework. This project features a modern, premium dark-themed UI, interactive seat selection, and a robust analytics dashboard.

---

## 🚀 Installation & Setup Guide

Follow these steps to set up the project on your local machine:

### 1. Prerequisites
Ensure you have the following installed:
- **PHP 7.4** or higher
- **Composer** (PHP Dependency Manager)
- **MySQL / MariaDB**
- A local server environment like **Laragon** (recommended), XAMPP, or WAMP.

### 2. Install Dependencies
Clone or download the repository, navigate to the project root directory in your terminal, and run:
```bash
composer install
```
*Note: This will download all required framework libraries and plugins.*

### 3. Create Database
1. Open your database management tool (e.g., HeidiSQL, phpMyAdmin).
2. Create a new database named: `misacinema`

### 4. Configuration Setup
1. Navigate to the `config/` directory.
2. Copy `app_local.example.php` and rename it to `app_local.php`.
3. Open `app_local.php` and update the **Datasources** section with your database credentials.
4. **Email Configuration (Gmail SMTP):** To enable email notifications (Booking receipts, OTPs), configure the `EmailTransport` section:
   - **Host:** `smtp.gmail.com`
   - **Port:** `587`
   - **Username:** Your Gmail address.
   - **Password:** Your 16-digit **Google App Password** (Do NOT use your regular Gmail password).
   - **TLS:** `true`

5. **Security Salt:** Ensure a unique string is set in the `'Security' => ['salt' => '...']` section of the same file.

### 5. Run Database Migrations
Misa Cinema uses CakePHP Migrations to build the database schema. Run the following command to create the tables automatically:
```bash
bin/cake migrations migrate
```

### 6. Initial Data Seeding
To populate the system with essential base data (e.g., seat types, initial admin), run:
```bash
bin/cake migrations seed
```

### 8. Full Data Transfer (Exporting Your Data)
If you want to transfer **all data** (Movies, Bookings, Halls, etc.) from one machine to another, do not rely on seeds alone. Use a **SQL Export**:
1.  **Export:** In HeidiSQL/phpMyAdmin, right-click the `misacinema` database -> **Export database as SQL**.
2.  **Settings:** Ensure **Table(s):** is set to `Create` and **Data:** is set to `Insert`.
3.  **Import:** On the new machine, create an empty `misacinema` database, open it, and **Run SQL file** to import the generated `.sql` file.

### 9. Accessing the System
Visit: `http://localhost:8765` after starting the server via `bin/cake server`.

**Default Admin Credentials:**
- **Email:** `marzkryy@gmail.com`
- **Password:** `123abc`

---

## 🔒 Security Features: Email Change Flow
Misa Cinema implements a secure **2-Step Verification** for email changes to prevent unauthorized account takeovers:
1.  **Old Email Confirmation:** A 4-digit OTP is sent to the *current* email address. The user must verify this code first.
2.  **New Email Validation:** Once confirmed, the user enters the new email address. A second OTP is sent to the *new* address.
3.  **Finalization:** Only after both codes are verified will the system update the database with the new email.

---

## ✨ Key Features
- **Dynamic Homepage:** Video backgrounds and featured movie carousels.
- **Intelligent Scheduling:** Automatic showtime conflict detection to prevent overlapping sessions in the same hall.
- **Real-time Seat Locking:** Instant "Reserved" status visibility for all customers once a seat is selected (prevents double bookings).
- **Auto-Scale Seating Engine:** High-performance seat map scaling for a perfect fit on any screen size.
- **Student Discounts:** Automated price calculation based on time and day (Mon-Fri before 6 PM).
- **Booking Management:** Digital tickets with QR placeholders, PDF generation, and email receipts.
- **Analytics Dashboard:** Visual performance tracking for revenue and movie popularity.

## 🛠 Tech Stack
- **Backend:** CakePHP 4.x
- **UI/UX:** Bootstrap 5, Custom CSS, FontAwesome
- **Visuals:** Chart.js, SweetAlert2
- **Database:** MySQL

---

**Misa Cinema: Experience the Best, Forget the Rest!** 🎬🍿🔴✨🤘
