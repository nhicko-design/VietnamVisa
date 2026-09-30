# Database setup - XAMPP

## 1. Start XAMPP

Start **Apache** and **MySQL** in XAMPP Control Panel.

## 2. Open phpMyAdmin

Open:

`http://localhost/phpmyadmin`

## 3. Import the SQL

Choose **Import** → select `database/VietnamVisa.sql` → click **Import**.

The script creates database `VietnamVisa`, all tables and sample content.

## 4. Local admin account

- Email: `admin@sunviet.local`
- Password: `ChangeMe123!`

Change the password when the real authentication screen is implemented.

## 5. Connection defaults

`common/database.php` assumes the normal XAMPP setup:

- Host: `127.0.0.1`
- Port: `3306`
- Database: `VietnamVisa`
- User: `root`
- Password: empty

If your XAMPP MySQL password is not empty, change `$pass` in `common/database.php`.
