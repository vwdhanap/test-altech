# Laravel Application Setup Guide

This document provides an overview of setting up the Laravel application, accessing API documentation, and running unit tests.

---

## **1. Laravel Installation**

Follow these steps to install the Laravel application:

### **Prerequisites**

Ensure you have the following installed:

-   PHP (>= 8.0)
-   Composer
-   A supported database (e.g., MySQL, PostgreSQL, SQLite)

### **Installation Steps**

1. Install dependencies using Composer:

    ```bash
    composer install
    ```

2. Create a `.env` file:

    ```bash
    cp .env.example .env
    ```

3. Configure the `.env` file:

    - Set the database credentials (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`). If you are using SQLite then you are ready to go
    - Set `APP_URL` to the application URL (e.g., `http://localhost`).

4. Generate the application key:

    ```bash
    php artisan key:generate
    ```

5. Run database migrations:

    ```bash
    php artisan migrate
    ```

    If you see the screen to create an SQLite database, then chose yes

6. Start the development server:

    ```bash
    php artisan serve
    ```

    Access the application at [http://localhost:8000](http://localhost:8000).

---

## **2. Accessing API Documentation**

This project uses **Scramble Dedoc** to generate and serve API documentation. Usually, I prefer the Scramble Dedoc because it is more clean.

### **Steps to Access the Documentation**

1. Make sure the application is running (`php artisan serve && php artisan queue:work`).
2. Visit the following URL in your browser:

    ```
    http://localhost:8000/docs/api
    ```

    (Adjust the URL if your application runs on a different port or subdomain.)

3. The documentation will display all the available API endpoints, request parameters, and response formats.

---

## **3. Running Unit Tests**

Unit tests ensure the functionality of individual components within the application. Follow these steps to execute the tests:

### **Run All Tests**

To run all tests, including unit and feature tests:

```bash
php artisan test
```

### **Run Specific Test Files**

You can also target specific test files:

```bash
php artisan test --filter=TestFileName
```

---

## **4. Additional Commands**

### **Clear Cache**

If you make changes to configurations or routes, clear the application cache:

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

### **Rebuild Routes Cache**

After modifying routes, you can rebuild the route cache:

```bash
php artisan route:cache
```
