# Online Job Portal

A **web-based job portal system** developed using **PHP and MySQL**, designed to connect **job seekers**, **employers**, and **administrators** on a single platform.  
The project is run locally using **XAMPP**.

---

## Project Overview

The Online Job Portal allows:
- Job seekers to search and apply for jobs
- Employers to post and manage job listings
- Admins to manage users, jobs, and system data

This system helps automate the recruitment process and provides a centralized job management platform.

---

## Features

- User Registration & Login
- Role-based access (Admin / Employer / Job Seeker)
- Job Posting and Job Management
- Job Search and Apply functionality
- Admin Dashboard
- Profile Management
- Secure database interaction using MySQL

---

##  Technologies Used

- **Frontend:** HTML, CSS, Bootstrap, JavaScript  
- **Backend:** PHP  
- **Database:** MySQL  
- **Server:** Apache (XAMPP)

---

## Requirements

- XAMPP Server
- Web Browser (Chrome / Firefox)
- PHP 7+
- MySQL

---

##  Installation & Setup (XAMPP)

### 1️ Install XAMPP
Download and install XAMPP from:  
https://www.apachefriends.org/

Start **Apache** and **MySQL** from XAMPP Control Panel.

---

### 2️ Clone the Repository

```bash
git clone https://github.com/SaiRajesh01/Online-Job-Portal.git
```

---

### 3️ Move Project to htdocs

Copy the project folder to:

```
C:\xampp\htdocs\Online-Job-Portal
```

---

### 4️ Create Database

1. Open browser and go to:
```
http://localhost/phpmyadmin
```

2. Create a new database:
```
online_job_portal
```

3. Import the SQL file included in the project (e.g., `database.sql`).

---

### 5️ Configure Database Connection

Open the database configuration file (e.g., `config.php`) and update:

```php
$servername = "localhost";
$username = "root";
$password = "";
$database = "online_job_portal";
```

---

### 6️ Run the Project

Open your browser and navigate to:

```
http://localhost/online_job_portal/
```

---

## 📁 Project Structure

```
Online-Job-Portal/
│── admin/
│── employer/
│── jobseeker/
│── assets/
│── css/
│── js/
│── includes/
│── config.php
│── index.php
│── login.php
└── database.sql
```

---

##  User Roles

- **Admin:** Manages system data and users
- **Employer:** Posts and manages jobs
- **Job Seeker:** Searches and applies for jobs

---

##  Security Notes

- Validate user input
- Use password hashing
- Restrict unauthorized access to admin pages

---

## 📜 License

This project is for academic and learning purposes.

---

##  Author

**Sai Rajesh Mutte**  
GitHub: https://github.com/SaiRajesh01
