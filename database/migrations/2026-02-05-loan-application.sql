ALTER TABLE loan_applications
  ADD COLUMN product_id INT UNSIGNED NULL AFTER client_id,
  ADD COLUMN term_unit VARCHAR(30) NULL AFTER terms_months,
  ADD COLUMN term_fixed TINYINT(1) NOT NULL DEFAULT 0 AFTER term_unit,
  ADD COLUMN savings_account VARCHAR(60) NULL AFTER term_fixed,
  ADD COLUMN interest_rate DECIMAL(6,3) NULL AFTER guarantor,
  ADD COLUMN interest_type VARCHAR(40) NULL AFTER interest_rate,
  ADD COLUMN equal_principal TINYINT(1) NOT NULL DEFAULT 0 AFTER interest_type,
  ADD COLUMN release_date DATE NULL AFTER equal_principal,
  ADD COLUMN maturity_date DATE NULL AFTER release_date,
  ADD COLUMN deduction_interest DECIMAL(12,2) NULL AFTER maturity_date,
  ADD COLUMN deduction_service_charge DECIMAL(12,2) NULL AFTER deduction_interest,
  ADD COLUMN deduction_climbs DECIMAL(12,2) NULL AFTER deduction_service_charge,
  ADD COLUMN deduction_notarial_fee DECIMAL(12,2) NULL AFTER deduction_climbs,
  ADD COLUMN total_deductions DECIMAL(12,2) NULL AFTER deduction_notarial_fee,
  ADD COLUMN net_proceeds DECIMAL(12,2) NULL AFTER total_deductions,
  ADD COLUMN amortization_days VARCHAR(40) NULL AFTER net_proceeds,
  ADD COLUMN principal_interval VARCHAR(40) NULL AFTER amortization_days,
  ADD COLUMN interval_adjustment VARCHAR(40) NULL AFTER principal_interval,
  ADD COLUMN fixed_amortization DECIMAL(12,2) NULL AFTER interval_adjustment,
  ADD COLUMN irregular_amortization DECIMAL(12,2) NULL AFTER fixed_amortization,
  ADD COLUMN insurance_amount DECIMAL(12,2) NULL AFTER irregular_amortization,
  ADD COLUMN insurance_basis VARCHAR(40) NULL AFTER insurance_amount,
  ADD COLUMN interest_amortized VARCHAR(20) NULL AFTER insurance_basis,
  ADD COLUMN service_charge_amortized VARCHAR(20) NULL AFTER interest_amortized,
  ADD COLUMN client_photo_path VARCHAR(255) NULL AFTER service_charge_amortized,
  ADD CONSTRAINT fk_applications_product
    FOREIGN KEY (product_id) REFERENCES loan_products(id)
    ON UPDATE CASCADE ON DELETE SET NULL;

CREATE TABLE IF NOT EXISTS loan_application_schedules (
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
