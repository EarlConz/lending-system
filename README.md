# Lending System

This project is a web-based lending system built with PHP. It manages clients, loan applications, payments, and various financial reports.

## Features

- **Client Management:** Add new clients, edit existing client details.
- **Loan Management:** Process loan applications, manage pending loans, approve and release loans.
- **Payment Management:** Record payments, manage amortization, and track payment history.
- **Reporting:** Generate reports for loan listings, payments, releases, paid loans, and daily transactions.
- **Settings:** Configure loan products.

## Technologies Used

- PHP
- HTML
- CSS (main.css)
- JavaScript (main.js)

## Getting Started

### Prerequisites

- A web server environment (e.g., XAMPP, WAMP, LAMP) with PHP support.
- A web browser.

### Installation

1.  **Clone or download** the project files to your web server's document root (e.g., `htdocs` for XAMPP).
    If you are using XAMPP, you would place this folder in `d:\Main\XAMPP\htdocs\`.

2.  **Access the application** through your web browser.
    If you placed the project in `d:\Main\XAMPP\htdocs\lending_systeme`, you would navigate to `http://localhost/lending_systeme`.

    The `index.php` file will automatically redirect you to the client creation page.

## Project Structure

- `assets/`: Contains CSS and JavaScript files for styling and interactivity.
- `pages/`: Core application pages including client, loan, payment, and report management.
- `partials/`: Reusable HTML/PHP partials like header, footer, sidebar, and top bar.
- `index.php`: The entry point of the application, redirects to the default page.
