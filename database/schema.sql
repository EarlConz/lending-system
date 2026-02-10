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
    code VARCHAR(60) NULL,
    description VARCHAR(255) NULL,
    status ENUM('Active', 'Inactive') NOT NULL DEFAULT 'Active',
    loan_type VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    promissory_note VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    max_loan_amount DECIMAL(12,2) NULL,
    ceiling_loan_product DECIMAL(12,2) NULL,
    max_loan_count INT UNSIGNED NULL,
    grouping VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    cost_center VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    borrower_type_default VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    require_security VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    default_security VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    proceeds_type_default VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    enable_deed_assignment VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    required_no_employees TINYINT(1) NOT NULL DEFAULT 0,
    required_coborrower TINYINT(1) NOT NULL DEFAULT 0,
    required_comakers INT UNSIGNED NULL,
    employee_loan VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    term_unit VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    term_unit_flexible TINYINT(1) NOT NULL DEFAULT 0,
    fixed_number_days INT UNSIGNED NULL,
    fixed_number_days_flexible TINYINT(1) NOT NULL DEFAULT 0,
    default_term INT UNSIGNED NULL,
    default_term_flexible TINYINT(1) NOT NULL DEFAULT 0,
    maximum_term INT UNSIGNED NULL,
    interest_rate DECIMAL(8,4) NOT NULL DEFAULT 0.0000,
    interest_rate_flexible TINYINT(1) NOT NULL DEFAULT 0,
    recompute_interest TINYINT(1) NOT NULL DEFAULT 0,
    interest_basis_computation VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    interest_basis_flexible TINYINT(1) NOT NULL DEFAULT 0,
    interest_computation VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    interest_computation_flexible TINYINT(1) NOT NULL DEFAULT 0,
    interest_rate_minimum DECIMAL(8,4) NOT NULL DEFAULT 0.0000,
    days_in_year INT UNSIGNED NOT NULL DEFAULT 360,
    penalty_per_amort_fixed_rate DECIMAL(8,4) NULL,
    penalty_per_amort_fixed_amount DECIMAL(12,2) NULL,
    penalty_per_amort_running_rate DECIMAL(8,4) NULL,
    penalty_per_amort_grace_days INT UNSIGNED NULL,
    penalty_per_amort_basis VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    penalty_after_maturity_fixed_rate DECIMAL(8,4) NULL,
    penalty_after_maturity_fixed_amount DECIMAL(12,2) NULL,
    penalty_after_maturity_running_rate DECIMAL(8,4) NULL,
    penalty_after_maturity_grace_days INT UNSIGNED NULL,
    penalty_after_maturity_basis VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    disregard_payments_after_maturity TINYINT(1) NOT NULL DEFAULT 0,
    include_amort_penalty TINYINT(1) NOT NULL DEFAULT 0,
    past_due_interest_rate DECIMAL(8,4) NULL,
    past_due_interest_basis VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    past_due_disregard_payments TINYINT(1) NOT NULL DEFAULT 0,
    penalty_gl_account VARCHAR(120) NULL,
    grace_period_option VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    secured_approval_min DECIMAL(12,2) NULL,
    secured_approval_max DECIMAL(12,2) NULL,
    secured_approver_count INT UNSIGNED NOT NULL DEFAULT 1,
    unsecured_approval_min DECIMAL(12,2) NULL,
    unsecured_approval_max DECIMAL(12,2) NULL,
    unsecured_approver_count INT UNSIGNED NOT NULL DEFAULT 1,
    service_charge_used VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    savings_discounted_used VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    grt_used VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    insurance_used VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    insurance_name VARCHAR(120) NULL,
    insurance_flexible TINYINT(1) NOT NULL DEFAULT 0,
    insurance_provider_default VARCHAR(120) NOT NULL DEFAULT 'ICISP',
    insurance_table VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    insurance_printing_form VARCHAR(120) NOT NULL DEFAULT 'Yes',
    insurance_gl_account VARCHAR(160) NULL,
    insurance_product VARCHAR(120) NOT NULL DEFAULT 'None',
    notarial_used VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    doc_stamp_used VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    inspection_fee_used VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    filing_fee_used VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    processing_fee_used VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    processing_fee_name VARCHAR(120) NULL,
    processing_fee_bracket_option VARCHAR(160) NOT NULL DEFAULT 'By Amount (PHP)',
    processing_fee_rate_option VARCHAR(120) NOT NULL DEFAULT 'Percent (%)',
    processing_fee_flexible TINYINT(1) NOT NULL DEFAULT 0,
    processing_fee_gl_account VARCHAR(160) NULL,
    ctr_fund_used VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    insurance2_used VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    deduction8_used VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    deduction9_used VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    service_charge_amortized VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    savings_amortized VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    amort1 VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    amort2 VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    amort_date_adjustment VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    amort_adjust_on_holidays VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    amortization_grace_period INT UNSIGNED NULL,
    auto_debit_amortization TINYINT(1) NOT NULL DEFAULT 0,
    savings_holdout_value DECIMAL(12,2) NULL,
    savings_holdout_basis VARCHAR(120) NOT NULL DEFAULT 'Percent',
    cure_period_daily INT UNSIGNED NOT NULL DEFAULT 0,
    cure_period_weekly INT UNSIGNED NOT NULL DEFAULT 0,
    cure_period_semi_monthly INT UNSIGNED NOT NULL DEFAULT 0,
    cure_period_monthly INT UNSIGNED NOT NULL DEFAULT 0,
    cure_period_quarterly INT UNSIGNED NOT NULL DEFAULT 0,
    cure_period_semi_annual INT UNSIGNED NOT NULL DEFAULT 0,
    cure_period_annual INT UNSIGNED NOT NULL DEFAULT 0,
    cure_period_lumpsum INT UNSIGNED NOT NULL DEFAULT 0,
    enable_individual_cure_period TINYINT(1) NOT NULL DEFAULT 0,
    enable_release_tagging TINYINT(1) NOT NULL DEFAULT 0,
    cash_disbursed_by_teller TINYINT(1) NOT NULL DEFAULT 0,
    security_dependent_pns TINYINT(1) NOT NULL DEFAULT 0,
    acl_exempted TINYINT(1) NOT NULL DEFAULT 0,
    acl_assessment VARCHAR(160) NULL,
    comakership_limit DECIMAL(12,2) NULL,
    collection_list_display VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    collection_list_orientation VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    balance_to_show VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    reflect_date_granted TINYINT(1) NOT NULL DEFAULT 0,
    reflect_loan_amount TINYINT(1) NOT NULL DEFAULT 0,
    reflect_savings_balance TINYINT(1) NOT NULL DEFAULT 0,
    reflect_duedate TINYINT(1) NOT NULL DEFAULT 0,
    signature_on_collection_list TINYINT(1) NOT NULL DEFAULT 0,
    sms_language VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    sms_free VARCHAR(120) NOT NULL DEFAULT 'Not Used',
    sms_show_unpaid_amorts TINYINT(1) NOT NULL DEFAULT 0,
    service_charge DECIMAL(8,4) NOT NULL DEFAULT 0.0000,
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
