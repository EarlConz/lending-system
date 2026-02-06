-- Create database
CREATE DATABASE IF NOT EXISTS lending_systeme
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE lending_systeme;

-- Users (login is passwordless for now; password_hash can be NULL)
CREATE TABLE users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  username VARCHAR(80) NOT NULL UNIQUE,
  role ENUM('Administrator', 'Staff', 'Viewer') NOT NULL DEFAULT 'Staff',
  password_hash VARCHAR(255) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

-- Branches
CREATE TABLE branches (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  code VARCHAR(10) NOT NULL UNIQUE,
  name VARCHAR(120) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

-- Clients
CREATE TABLE clients (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  branch_id INT UNSIGNED NULL,
  client_type ENUM('Individual', 'Business') NOT NULL DEFAULT 'Individual',
  borrower_id VARCHAR(30) NOT NULL UNIQUE,
  last_name VARCHAR(80) NOT NULL,
  first_name VARCHAR(80) NOT NULL,
  middle_name VARCHAR(80) NULL,
  birthdate DATE NULL,
  birthplace VARCHAR(120) NULL,
  nationality VARCHAR(80) NULL,
  gender ENUM('Female', 'Male', 'Other') NULL,
  civil_status ENUM('Single', 'Married', 'Separated', 'Widowed') NULL,
  email VARCHAR(120) NULL,
  facebook VARCHAR(120) NULL,
  source_of_fund ENUM('None', 'Employment', 'Business', 'Others') NOT NULL DEFAULT 'None',
  employment_occupation ENUM('Self-Employed', 'Employed', 'Professional Practitioner', 'Private Employee', 'Politician') NULL,
  employer_name VARCHAR(120) NULL,
  employment_address VARCHAR(200) NULL,
  employment_barangay VARCHAR(120) NULL,
  employment_position VARCHAR(120) NULL,
  employment_contact VARCHAR(30) NULL,
  employment_year_started YEAR NULL,
  employment_gross_monthly_income DECIMAL(12,2) NULL,
  business_name VARCHAR(120) NULL,
  business_address VARCHAR(200) NULL,
  business_barangay VARCHAR(120) NULL,
  business_contact VARCHAR(30) NULL,
  business_year_started YEAR NULL,
  business_gross_monthly_income DECIMAL(12,2) NULL,
  other_occupation VARCHAR(120) NULL,
  other_source_of_income VARCHAR(120) NULL,
  other_gross_monthly_income DECIMAL(12,2) NULL,
  phone_primary VARCHAR(30) NULL,
  phone_secondary VARCHAR(30) NULL,
  landline_primary VARCHAR(30) NULL,
  landline_secondary VARCHAR(30) NULL,
  present_address VARCHAR(200) NULL,
  permanent_address VARCHAR(200) NULL,
  emergency_contact VARCHAR(120) NULL,
  emergency_phone VARCHAR(30) NULL,
  id_number VARCHAR(50) NULL,
  secondary_id VARCHAR(50) NULL,
  secondary_id_expiry DATE NULL,
  risk_category ENUM('Undefined', 'VIP', 'DOSRI', 'RPT', 'PEP') NOT NULL DEFAULT 'Undefined',
  verification_status ENUM('Verified', 'Needs Follow-up') NOT NULL DEFAULT 'Needs Follow-up',
  last_review_date DATE NULL,
  assigned_officer VARCHAR(80) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT fk_clients_branch
    FOREIGN KEY (branch_id) REFERENCES branches(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

-- Client Beneficiaries
CREATE TABLE client_beneficiaries (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  client_id INT UNSIGNED NOT NULL,
  relation VARCHAR(50) NOT NULL,
  first_name VARCHAR(80) NOT NULL,
  middle_name VARCHAR(80) NULL,
  last_name VARCHAR(80) NOT NULL,
  birthdate DATE NULL,
  gender ENUM('Female', 'Male', 'Other') NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT fk_beneficiaries_client
    FOREIGN KEY (client_id) REFERENCES clients(id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- Loan Products
CREATE TABLE loan_products (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(120) NOT NULL UNIQUE,
  interest_rate DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  service_charge DECIMAL(5,2) NOT NULL DEFAULT 0.00,
  status ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Active',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

-- Loan Applications
CREATE TABLE loan_applications (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  application_id VARCHAR(30) NOT NULL UNIQUE,
  client_id INT UNSIGNED NOT NULL,
  product_id INT UNSIGNED NULL,
  requested_amount DECIMAL(12,2) NOT NULL,
  monthly_income DECIMAL(12,2) NULL,
  employment_info VARCHAR(160) NULL,
  terms_months INT UNSIGNED NULL,
  term_unit VARCHAR(30) NULL,
  term_fixed TINYINT(1) NOT NULL DEFAULT 0,
  savings_account VARCHAR(60) NULL,
  collateral VARCHAR(120) NULL,
  guarantor VARCHAR(120) NULL,
  interest_rate DECIMAL(6,3) NULL,
  interest_type VARCHAR(40) NULL,
  equal_principal TINYINT(1) NOT NULL DEFAULT 0,
  release_date DATE NULL,
  maturity_date DATE NULL,
  deduction_interest DECIMAL(12,2) NULL,
  deduction_service_charge DECIMAL(12,2) NULL,
  deduction_climbs DECIMAL(12,2) NULL,
  deduction_notarial_fee DECIMAL(12,2) NULL,
  total_deductions DECIMAL(12,2) NULL,
  net_proceeds DECIMAL(12,2) NULL,
  amortization_days VARCHAR(40) NULL,
  principal_interval VARCHAR(40) NULL,
  interval_adjustment VARCHAR(40) NULL,
  fixed_amortization DECIMAL(12,2) NULL,
  irregular_amortization DECIMAL(12,2) NULL,
  insurance_amount DECIMAL(12,2) NULL,
  insurance_basis VARCHAR(40) NULL,
  interest_amortized VARCHAR(20) NULL,
  service_charge_amortized VARCHAR(20) NULL,
  client_photo_path VARCHAR(255) NULL,
  status ENUM('Pending', 'Approved', 'Rejected') NOT NULL DEFAULT 'Pending',
  priority ENUM('Normal', 'Medium', 'High') NOT NULL DEFAULT 'Normal',
  submitted_date DATE NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT fk_applications_client
    FOREIGN KEY (client_id) REFERENCES clients(id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_applications_product
    FOREIGN KEY (product_id) REFERENCES loan_products(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

-- Loan Application Schedules
CREATE TABLE loan_application_schedules (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  loan_application_id INT UNSIGNED NOT NULL,
  installment_no INT UNSIGNED NOT NULL,
  due_date DATE NOT NULL,
  principal DECIMAL(12,2) NOT NULL,
  interest DECIMAL(12,2) NOT NULL,
  total DECIMAL(12,2) NOT NULL,
  balance DECIMAL(12,2) NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT fk_application_schedules_application
    FOREIGN KEY (loan_application_id) REFERENCES loan_applications(id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- Loans (approved/released)
CREATE TABLE loans (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  loan_id VARCHAR(30) NOT NULL UNIQUE,
  client_id INT UNSIGNED NOT NULL,
  product_id INT UNSIGNED NOT NULL,
  amount DECIMAL(12,2) NOT NULL,
  balance DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  term_months INT UNSIGNED NOT NULL,
  approval_date DATE NOT NULL,
  status ENUM('Active', 'Closed', 'Delinquent') NOT NULL DEFAULT 'Active',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT fk_loans_client
    FOREIGN KEY (client_id) REFERENCES clients(id)
    ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT fk_loans_product
    FOREIGN KEY (product_id) REFERENCES loan_products(id)
    ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB;

-- Loan Releases
CREATE TABLE loan_releases (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  release_id VARCHAR(30) NOT NULL UNIQUE,
  loan_id INT UNSIGNED NOT NULL,
  amount DECIMAL(12,2) NOT NULL,
  release_date DATE NOT NULL,
  status ENUM('Ready', 'Scheduled', 'Hold') NOT NULL DEFAULT 'Ready',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT fk_releases_loan
    FOREIGN KEY (loan_id) REFERENCES loans(id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- CACOBEM Applications
CREATE TABLE cacobem_applications (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  client_id INT UNSIGNED NULL,
  borrower_name VARCHAR(120) NULL,
  application_date DATE NULL,
  amount_applied DECIMAL(12,2) NULL,
  data_json LONGTEXT NOT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT fk_cacobem_client
    FOREIGN KEY (client_id) REFERENCES clients(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

-- Payments
CREATE TABLE payments (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  payment_id VARCHAR(30) NOT NULL UNIQUE,
  loan_id INT UNSIGNED NOT NULL,
  amount DECIMAL(12,2) NOT NULL,
  payment_date DATE NOT NULL,
  method ENUM('Cash', 'Bank Transfer', 'Auto Debit') NOT NULL DEFAULT 'Cash',
  reference VARCHAR(50) NULL,
  processed_by VARCHAR(80) NULL,
  status ENUM('Posted', 'Pending', 'Reversed') NOT NULL DEFAULT 'Posted',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT fk_payments_loan
    FOREIGN KEY (loan_id) REFERENCES loans(id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- Amortization Schedule
CREATE TABLE amortizations (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  loan_id INT UNSIGNED NOT NULL,
  due_date DATE NOT NULL,
  principal DECIMAL(12,2) NOT NULL,
  interest DECIMAL(12,2) NOT NULL,
  penalty DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  total DECIMAL(12,2) NOT NULL,
  note VARCHAR(120) NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT fk_amortizations_loan
    FOREIGN KEY (loan_id) REFERENCES loans(id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

-- Saved Report Listings
CREATE TABLE reports_saved_listings (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(120) NOT NULL,
  status ENUM('Shared', 'Private', 'Scheduled') NOT NULL DEFAULT 'Private',
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB;

-- DB Backups (for Admin page list)
CREATE TABLE db_backups (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  label VARCHAR(120) NOT NULL,
  created_by INT UNSIGNED NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  CONSTRAINT fk_backups_user
    FOREIGN KEY (created_by) REFERENCES users(id)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB;

-- Helpful indexes
CREATE INDEX idx_clients_name ON clients(last_name, first_name);
CREATE INDEX idx_loans_status ON loans(status);
CREATE INDEX idx_payments_date ON payments(payment_date);
CREATE INDEX idx_applications_status ON loan_applications(status);
