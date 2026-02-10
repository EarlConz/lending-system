<?php
declare(strict_types=1);

final class SqlQueries
{
  private const QUERIES = [
    // BackupRepository
    "backup.create" => "INSERT INTO db_backups (label, created_by) VALUES (:label, :created_by)",
    "backup.list" => <<<'SQL'
SELECT b.id, b.label, b.created_at, u.username AS created_by
FROM db_backups b
LEFT JOIN users u ON u.id = b.created_by
ORDER BY b.created_at DESC
SQL,

    // BranchRepository
    "branch.all" => <<<'SQL'
SELECT id, code, name
FROM branches
ORDER BY name ASC
SQL,

    // ClientRepository
    "client.dashboard_stats" => <<<'SQL'
SELECT
  COUNT(*) AS active,
  SUM(CASE WHEN verification_status = 'Needs Follow-up' THEN 1 ELSE 0 END) AS pending_verification,
  SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) AS new_applications,
  SUM(CASE WHEN risk_category IN ('PEP', 'DOSRI', 'RPT') THEN 1 ELSE 0 END) AS high_risk
FROM clients
SQL,
    "client.recent" => <<<'SQL'
SELECT id, first_name, middle_name, last_name
FROM clients
ORDER BY created_at DESC
LIMIT %d
SQL,
    "client.edit_stats" => <<<'SQL'
SELECT
  SUM(CASE WHEN last_review_date = CURDATE() THEN 1 ELSE 0 END) AS edits_today,
  SUM(CASE WHEN verification_status = 'Needs Follow-up' THEN 1 ELSE 0 END) AS pending_review,
  SUM(CASE WHEN secondary_id IS NOT NULL AND secondary_id <> '' THEN 1 ELSE 0 END) AS id_updates,
  SUM(CASE WHEN risk_category IN ('PEP', 'DOSRI', 'RPT') THEN 1 ELSE 0 END) AS risk_escalations
FROM clients
SQL,
    "client.needing_updates" => <<<'SQL'
SELECT id, first_name, last_name, verification_status, last_review_date
FROM clients
ORDER BY
  CASE WHEN last_review_date IS NULL THEN 0 ELSE 1 END,
  last_review_date ASC,
  created_at DESC
LIMIT %d
SQL,
    "client.picklist" => <<<'SQL'
SELECT borrower_id, first_name, middle_name, last_name, phone_primary
FROM clients
WHERE borrower_id IS NOT NULL AND borrower_id <> ''
ORDER BY last_name ASC, first_name ASC, middle_name ASC
SQL,
    "client.beneficiaries" => <<<'SQL'
SELECT id, relation, first_name, middle_name, last_name, birthdate, gender
FROM client_beneficiaries
WHERE client_id = :client_id
ORDER BY created_at DESC
SQL,
    "client.by_id" => <<<'SQL'
SELECT
  branch_id,
  borrower_id,
  first_name,
  middle_name,
  last_name,
  phone_primary,
  email,
  risk_category,
  verification_status,
  present_address,
  emergency_contact,
  last_review_date,
  assigned_officer
FROM clients
WHERE id = :id
LIMIT 1
SQL,
    "client.insert" => <<<'SQL'
INSERT INTO clients (
  branch_id,
  client_type,
  borrower_id,
  last_name,
  first_name,
  middle_name,
  birthdate,
  birthplace,
  nationality,
  gender,
  civil_status,
  email,
  facebook,
  source_of_fund,
  employment_occupation,
  employer_name,
  employment_address,
  employment_barangay,
  employment_position,
  employment_contact,
  employment_year_started,
  employment_gross_monthly_income,
  business_name,
  business_address,
  business_barangay,
  business_contact,
  business_year_started,
  business_gross_monthly_income,
  other_occupation,
  other_source_of_income,
  other_gross_monthly_income,
  phone_primary,
  phone_secondary,
  landline_primary,
  landline_secondary,
  present_address,
  permanent_address,
  emergency_contact,
  emergency_phone,
  id_number,
  secondary_id,
  secondary_id_expiry
) VALUES (
  :branch_id,
  :client_type,
  :borrower_id,
  :last_name,
  :first_name,
  :middle_name,
  :birthdate,
  :birthplace,
  :nationality,
  :gender,
  :civil_status,
  :email,
  :facebook,
  :source_of_fund,
  :employment_occupation,
  :employer_name,
  :employment_address,
  :employment_barangay,
  :employment_position,
  :employment_contact,
  :employment_year_started,
  :employment_gross_monthly_income,
  :business_name,
  :business_address,
  :business_barangay,
  :business_contact,
  :business_year_started,
  :business_gross_monthly_income,
  :other_occupation,
  :other_source_of_income,
  :other_gross_monthly_income,
  :phone_primary,
  :phone_secondary,
  :landline_primary,
  :landline_secondary,
  :present_address,
  :permanent_address,
  :emergency_contact,
  :emergency_phone,
  :id_number,
  :secondary_id,
  :secondary_id_expiry
)
SQL,
    "client.update" => "UPDATE clients SET %s WHERE id = :id",
    "client.delete" => "DELETE FROM clients WHERE id = :id",
    "client.borrower_id_exists" => "SELECT id FROM clients WHERE borrower_id = :borrower_id LIMIT 1",
    "client.borrower_id_exists_excluding" => "SELECT id FROM clients WHERE borrower_id = :borrower_id AND id <> :exclude_id LIMIT 1",
    "client.max_borrower_id" => <<<'SQL'
SELECT MAX(CAST(SUBSTRING(borrower_id, 4) AS UNSIGNED)) AS max_id
FROM clients
WHERE borrower_id LIKE 'BR-%'
SQL,
    "client.beneficiary_insert" => <<<'SQL'
INSERT INTO client_beneficiaries (
  client_id,
  relation,
  first_name,
  middle_name,
  last_name,
  birthdate,
  gender
) VALUES (
  :client_id,
  :relation,
  :first_name,
  :middle_name,
  :last_name,
  :birthdate,
  :gender
)
SQL,
    "client.beneficiary_delete" => "DELETE FROM client_beneficiaries WHERE id = :id AND client_id = :client_id",
    "client.find_by_borrower_id" => <<<'SQL'
SELECT id, borrower_id, first_name, last_name
FROM clients
WHERE borrower_id = :borrower_id
LIMIT 1
SQL,

    // LoanRepository
    "loan.pending_stats" => <<<'SQL'
SELECT
  SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending_review,
  SUM(CASE WHEN status = 'Pending' AND (collateral IS NULL OR collateral = '') THEN 1 ELSE 0 END) AS needs_documents,
  SUM(CASE WHEN status = 'Pending' AND priority = 'High' THEN 1 ELSE 0 END) AS supervisor_review,
  SUM(CASE WHEN status = 'Pending' AND submitted_date < DATE_SUB(CURDATE(), INTERVAL 14 DAY) THEN 1 ELSE 0 END) AS overdue
FROM loan_applications
SQL,
    "loan.application_stats" => <<<'SQL'
SELECT
  SUM(CASE WHEN submitted_date = CURDATE() THEN 1 ELSE 0 END) AS applications_today,
  SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS waiting_approval,
  SUM(CASE WHEN status = 'Approved' THEN 1 ELSE 0 END) AS auto_approved,
  SUM(CASE WHEN priority = 'High' THEN 1 ELSE 0 END) AS high_risk
FROM loan_applications
SQL,
    "loan.pending_list" => <<<'SQL'
SELECT
  a.id,
  a.application_id,
  a.requested_amount,
  a.submitted_date,
  a.priority,
  c.first_name,
  c.last_name
FROM loan_applications a
LEFT JOIN clients c ON c.id = a.client_id
WHERE a.status = 'Pending'
ORDER BY a.submitted_date DESC
SQL,
    "loan.application_by_id" => <<<'SQL'
SELECT
  id,
  application_id,
  client_id,
  product_id,
  requested_amount,
  terms_months,
  status
FROM loan_applications
WHERE id = :id
LIMIT 1
SQL,
    "loan.release_stats" => <<<'SQL'
SELECT
  SUM(CASE WHEN status = 'Ready' THEN 1 ELSE 0 END) AS ready_for_release,
  SUM(CASE WHEN release_date = CURDATE() THEN 1 ELSE 0 END) AS released_today,
  SUM(CASE WHEN status = 'Scheduled' THEN 1 ELSE 0 END) AS scheduled_releases,
  SUM(CASE WHEN status = 'Hold' THEN 1 ELSE 0 END) AS on_hold
FROM loan_releases
SQL,
    "loan.approved_releases" => <<<'SQL'
SELECT
  l.loan_id,
  l.amount,
  l.term_months,
  l.approval_date,
  r.status,
  c.first_name,
  c.last_name
FROM loans l
LEFT JOIN loan_releases r ON r.loan_id = l.id
LEFT JOIN clients c ON c.id = l.client_id
WHERE l.status IN ('Active', 'Delinquent')
ORDER BY l.approval_date DESC
SQL,
    "loan.approved_unreleased_list" => <<<'SQL'
SELECT
  l.id AS loan_pk,
  l.loan_id,
  l.amount,
  l.balance,
  l.term_months,
  l.approval_date,
  l.status,
  c.first_name,
  c.last_name,
  p.name AS product_name
FROM loans l
JOIN clients c ON c.id = l.client_id
JOIN loan_products p ON p.id = l.product_id
LEFT JOIN loan_releases r ON r.loan_id = l.id
WHERE r.id IS NULL
  AND l.status = 'Active'
ORDER BY l.approval_date DESC
SQL,
    "loan.approved_applications_list" => <<<'SQL'
SELECT
  a.id AS application_pk,
  a.application_id,
  a.requested_amount,
  a.terms_months,
  a.submitted_date,
  a.status,
  c.first_name,
  c.last_name,
  p.name AS product_name
FROM loan_applications a
JOIN clients c ON c.id = a.client_id
JOIN loan_products p ON p.id = a.product_id
WHERE a.status = 'Approved'
ORDER BY a.submitted_date DESC
SQL,
    "loan.released_list" => <<<'SQL'
SELECT
  r.id AS release_pk,
  r.release_id,
  r.amount,
  r.release_date,
  r.status,
  l.id AS loan_pk,
  l.loan_id,
  l.term_months,
  l.approval_date,
  c.first_name,
  c.last_name
FROM loan_releases r
JOIN loans l ON l.id = r.loan_id
JOIN clients c ON c.id = l.client_id
WHERE r.status = 'Released'
ORDER BY r.release_date DESC
SQL,
    "loan.application_view_by_id" => <<<'SQL'
SELECT
  a.id,
  a.application_id,
  a.client_id,
  a.product_id,
  a.requested_amount,
  a.monthly_income,
  a.employment_info,
  a.terms_months,
  a.term_unit,
  a.term_fixed,
  a.savings_account,
  a.collateral,
  a.guarantor,
  a.interest_rate,
  a.interest_type,
  a.equal_principal,
  a.release_date,
  a.maturity_date,
  a.deduction_interest,
  a.deduction_service_charge,
  a.deduction_climbs,
  a.deduction_notarial_fee,
  a.total_deductions,
  a.net_proceeds,
  a.amortization_days,
  a.principal_interval,
  a.interval_adjustment,
  a.fixed_amortization,
  a.irregular_amortization,
  a.insurance_amount,
  a.insurance_basis,
  a.interest_amortized,
  a.service_charge_amortized,
  a.client_photo_path,
  a.submitted_date,
  a.status,
  a.priority,
  c.borrower_id,
  c.phone_primary,
  c.first_name,
  c.last_name,
  p.name AS product_name
FROM loan_applications a
LEFT JOIN clients c ON c.id = a.client_id
LEFT JOIN loan_products p ON p.id = a.product_id
WHERE a.id = :id
LIMIT 1
SQL,
    "loan.by_id" => <<<'SQL'
SELECT
  l.id AS loan_pk,
  l.loan_id,
  l.amount,
  l.balance,
  l.term_months,
  l.approval_date,
  l.status,
  c.borrower_id,
  c.phone_primary,
  c.first_name,
  c.last_name,
  p.name AS product_name
FROM loans l
JOIN clients c ON c.id = l.client_id
JOIN loan_products p ON p.id = l.product_id
WHERE l.id = :id
LIMIT 1
SQL,
    "loan.release_by_id" => <<<'SQL'
SELECT
  r.id AS release_pk,
  r.release_id,
  r.amount,
  r.release_date,
  r.status,
  l.id AS loan_pk,
  l.loan_id,
  l.amount AS loan_amount,
  l.term_months,
  l.approval_date,
  l.status AS loan_status,
  c.borrower_id,
  c.phone_primary,
  c.first_name,
  c.last_name,
  p.name AS product_name
FROM loan_releases r
JOIN loans l ON l.id = r.loan_id
JOIN clients c ON c.id = l.client_id
JOIN loan_products p ON p.id = l.product_id
WHERE r.id = :id
LIMIT 1
SQL,
    "loan.release_candidates" => <<<'SQL'
SELECT
  l.id AS loan_pk,
  l.loan_id,
  l.amount,
  l.balance,
  l.term_months,
  l.approval_date,
  p.name AS product_name,
  p.interest_rate,
  c.first_name,
  c.last_name,
  r.id AS release_pk
FROM loans l
JOIN clients c ON c.id = l.client_id
JOIN loan_products p ON p.id = l.product_id
LEFT JOIN loan_releases r ON r.loan_id = l.id
WHERE r.id IS NULL
ORDER BY l.approval_date DESC
SQL,

    // CACOBEM
    "cacobem.insert" => <<<'SQL'
INSERT INTO cacobem_applications (
  client_id,
  borrower_name,
  application_date,
  amount_applied,
  data_json
) VALUES (
  :client_id,
  :borrower_name,
  :application_date,
  :amount_applied,
  :data_json
)
SQL,
    "cacobem.update" => <<<'SQL'
UPDATE cacobem_applications
SET
  client_id = :client_id,
  borrower_name = :borrower_name,
  application_date = :application_date,
  amount_applied = :amount_applied,
  data_json = :data_json
WHERE id = :id
SQL,
    "cacobem.by_id" => <<<'SQL'
SELECT id, client_id, borrower_name, application_date, amount_applied, data_json, created_at, updated_at
FROM cacobem_applications
WHERE id = :id
LIMIT 1
SQL,
    "cacobem.list" => <<<'SQL'
SELECT id, borrower_name, application_date, amount_applied, created_at
FROM cacobem_applications
ORDER BY created_at DESC
SQL,
    "loan.release_deletion_stats" => <<<'SQL'
SELECT
  SUM(CASE WHEN status = 'Hold' THEN 1 ELSE 0 END) AS deletes_pending,
  SUM(CASE WHEN status = 'Scheduled' THEN 1 ELSE 0 END) AS supervisor_approvals
FROM loan_releases
SQL,
    "loan.release_deletions" => <<<'SQL'
SELECT
  r.id AS release_pk,
  r.release_id,
  r.amount,
  r.release_date,
  c.first_name,
  c.last_name
FROM loan_releases r
LEFT JOIN loans l ON l.id = r.loan_id
LEFT JOIN clients c ON c.id = l.client_id
WHERE r.status = 'Hold'
ORDER BY r.release_date DESC
SQL,
    "loan.application_insert" => <<<'SQL'
INSERT INTO loan_applications (
  application_id,
  client_id,
  product_id,
  requested_amount,
  monthly_income,
  employment_info,
  terms_months,
  term_unit,
  term_fixed,
  savings_account,
  collateral,
  guarantor,
  interest_rate,
  interest_type,
  equal_principal,
  release_date,
  maturity_date,
  deduction_interest,
  deduction_service_charge,
  deduction_climbs,
  deduction_notarial_fee,
  total_deductions,
  net_proceeds,
  amortization_days,
  principal_interval,
  interval_adjustment,
  fixed_amortization,
  irregular_amortization,
  insurance_amount,
  insurance_basis,
  interest_amortized,
  service_charge_amortized,
  client_photo_path,
  status,
  priority,
  submitted_date
) VALUES (
  :application_id,
  :client_id,
  :product_id,
  :requested_amount,
  :monthly_income,
  :employment_info,
  :terms_months,
  :term_unit,
  :term_fixed,
  :savings_account,
  :collateral,
  :guarantor,
  :interest_rate,
  :interest_type,
  :equal_principal,
  :release_date,
  :maturity_date,
  :deduction_interest,
  :deduction_service_charge,
  :deduction_climbs,
  :deduction_notarial_fee,
  :total_deductions,
  :net_proceeds,
  :amortization_days,
  :principal_interval,
  :interval_adjustment,
  :fixed_amortization,
  :irregular_amortization,
  :insurance_amount,
  :insurance_basis,
  :interest_amortized,
  :service_charge_amortized,
  :client_photo_path,
  :status,
  :priority,
  :submitted_date
)
SQL,
    "loan.application_update" => "UPDATE loan_applications SET %s WHERE id = :id",
    "loan.application_max_id" => <<<'SQL'
SELECT MAX(CAST(SUBSTRING(application_id, 5) AS UNSIGNED)) AS max_id
FROM loan_applications
WHERE application_id LIKE 'APP-%'
SQL,
    "loan.application_schedule_insert" => <<<'SQL'
INSERT INTO loan_application_schedules (
  loan_application_id,
  installment_no,
  due_date,
  principal,
  interest,
  total,
  balance
) VALUES (
  :loan_application_id,
  :installment_no,
  :due_date,
  :principal,
  :interest,
  :total,
  :balance
)
SQL,
    "loan.application_schedule_delete_by_application" =>
      "DELETE FROM loan_application_schedules WHERE loan_application_id = :loan_application_id",
    "loan.insert" => <<<'SQL'
INSERT INTO loans (
  loan_id,
  client_id,
  product_id,
  amount,
  balance,
  term_months,
  approval_date,
  status
) VALUES (
  :loan_id,
  :client_id,
  :product_id,
  :amount,
  :balance,
  :term_months,
  :approval_date,
  :status
)
SQL,
    "loan.update" => "UPDATE loans SET %s WHERE id = :id",
    "loan.delete" => "DELETE FROM loans WHERE id = :id",
    "loan.max_id" => <<<'SQL'
SELECT MAX(CAST(SUBSTRING(loan_id, 4) AS UNSIGNED)) AS max_id
FROM loans
WHERE loan_id LIKE 'LN-%'
SQL,
    "loan.release_max_id" => <<<'SQL'
SELECT MAX(CAST(SUBSTRING(release_id, 4) AS UNSIGNED)) AS max_id
FROM loan_releases
WHERE release_id LIKE 'RL-%'
SQL,
    "loan.release_insert" => <<<'SQL'
INSERT INTO loan_releases (
  release_id,
  loan_id,
  amount,
  release_date,
  status
) VALUES (
  :release_id,
  :loan_id,
  :amount,
  :release_date,
  :status
)
SQL,

    // PaymentRepository
    "payment.delete_stats" => <<<'SQL'
SELECT
  SUM(CASE WHEN status = 'Reversed' AND payment_date = CURDATE() THEN 1 ELSE 0 END) AS reversals_today,
  SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) AS pending_approval,
  SUM(CASE WHEN status = 'Posted' THEN 1 ELSE 0 END) AS completed,
  SUM(CASE WHEN status = 'Reversed' AND payment_date < CURDATE() THEN 1 ELSE 0 END) AS rejected
FROM payments
SQL,
    "payment.post_stats" => <<<'SQL'
SELECT
  SUM(CASE WHEN payment_date = CURDATE() THEN 1 ELSE 0 END) AS payments_today,
  SUM(CASE WHEN method = 'Cash' THEN 1 ELSE 0 END) AS cash,
  SUM(CASE WHEN method = 'Bank Transfer' THEN 1 ELSE 0 END) AS bank_transfer,
  SUM(CASE WHEN method = 'Auto Debit' THEN 1 ELSE 0 END) AS auto_debit
FROM payments
SQL,
    "payment.recent" => <<<'SQL'
SELECT
  p.payment_id,
  p.status,
  c.first_name,
  c.last_name
FROM payments p
LEFT JOIN loans l ON l.id = p.loan_id
LEFT JOIN clients c ON c.id = l.client_id
ORDER BY p.created_at DESC
LIMIT %d
SQL,
    "payment.posted" => <<<'SQL'
SELECT
  p.payment_id,
  p.amount,
  p.payment_date,
  p.method,
  p.status,
  c.first_name,
  c.last_name
FROM payments p
LEFT JOIN loans l ON l.id = p.loan_id
LEFT JOIN clients c ON c.id = l.client_id
WHERE p.status = 'Posted'
ORDER BY p.payment_date DESC
SQL,
    "payment.edit_amort_stats" => <<<'SQL'
SELECT
  COUNT(DISTINCT loan_id) AS accounts_reviewed,
  SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) AS new_schedules
FROM amortizations
SQL,
    "payment.amortization_schedule" => <<<'SQL'
SELECT due_date, principal, interest, penalty, total, note
FROM amortizations
WHERE loan_id = :loan_id
ORDER BY due_date ASC
SQL,

    // ReportRepository
    "report.listing_stats" => <<<'SQL'
SELECT
  COUNT(*) AS saved_templates,
  SUM(CASE WHEN created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) THEN 1 ELSE 0 END) AS exports_week,
  SUM(CASE WHEN status = 'Shared' THEN 1 ELSE 0 END) AS shared,
  SUM(CASE WHEN status = 'Private' THEN 1 ELSE 0 END) AS drafts
FROM reports_saved_listings
SQL,
    "report.saved_listings" => <<<'SQL'
SELECT name, status
FROM reports_saved_listings
ORDER BY created_at DESC
SQL,
    "report.loan_listing_stats" => <<<'SQL'
SELECT
  COUNT(*) AS total_loans,
  SUM(CASE WHEN status = 'Active' THEN 1 ELSE 0 END) AS active,
  SUM(CASE WHEN status = 'Closed' THEN 1 ELSE 0 END) AS closed,
  SUM(CASE WHEN status = 'Delinquent' THEN 1 ELSE 0 END) AS delinquent
FROM loans
SQL,
    "report.loan_listing" => <<<'SQL'
SELECT
  l.loan_id,
  l.amount,
  l.balance,
  l.status,
  c.first_name,
  c.last_name
FROM loans l
LEFT JOIN clients c ON c.id = l.client_id
ORDER BY l.created_at DESC
SQL,
    "report.loan_payment_stats" => <<<'SQL'
SELECT
  COUNT(*) AS payments,
  SUM(CASE WHEN status = 'Posted' THEN 1 ELSE 0 END) AS posted
FROM payments
SQL,
    "report.delinquent_count" => <<<'SQL'
SELECT SUM(CASE WHEN status = 'Delinquent' THEN 1 ELSE 0 END) AS delinquent
FROM loans
SQL,
    "report.loan_payments" => <<<'SQL'
SELECT
  l.loan_id,
  c.first_name,
  c.last_name,
  a.total AS amount,
  a.due_date,
  IFNULL(SUM(p.amount), 0) AS paid_amount
FROM amortizations a
LEFT JOIN loans l ON l.id = a.loan_id
LEFT JOIN clients c ON c.id = l.client_id
LEFT JOIN payments p ON p.loan_id = l.id
GROUP BY a.id, l.loan_id, c.first_name, c.last_name, a.total, a.due_date
ORDER BY a.due_date DESC
SQL,
    "report.loan_release_stats" => <<<'SQL'
SELECT
  COUNT(*) AS releases,
  IFNULL(SUM(r.amount), 0) AS total_value,
  COUNT(DISTINCT c.branch_id) AS branches,
  COUNT(DISTINCT l.product_id) AS products
FROM loan_releases r
LEFT JOIN loans l ON l.id = r.loan_id
LEFT JOIN clients c ON c.id = l.client_id
SQL,
    "report.loan_releases" => <<<'SQL'
SELECT
  r.release_id,
  r.amount,
  r.release_date,
  p.name AS product,
  c.first_name,
  c.last_name
FROM loan_releases r
LEFT JOIN loans l ON l.id = r.loan_id
LEFT JOIN loan_products p ON p.id = l.product_id
LEFT JOIN clients c ON c.id = l.client_id
ORDER BY r.release_date DESC
SQL,
    "report.paid_loan_stats" => <<<'SQL'
SELECT
  SUM(CASE WHEN status = 'Closed' AND YEAR(approval_date) = YEAR(CURDATE()) THEN 1 ELSE 0 END) AS paid_year
FROM loans
SQL,
    "report.paid_loans" => <<<'SQL'
SELECT
  l.loan_id,
  l.amount,
  l.approval_date,
  c.first_name,
  c.last_name
FROM loans l
LEFT JOIN clients c ON c.id = l.client_id
WHERE l.status = 'Closed'
ORDER BY l.approval_date DESC
SQL,
    "report.transaction_payments" => <<<'SQL'
SELECT COUNT(*) AS payments, IFNULL(SUM(amount), 0) AS total
FROM payments
WHERE payment_date = CURDATE()
SQL,
    "report.transaction_releases" => <<<'SQL'
SELECT COUNT(*) AS releases, IFNULL(SUM(amount), 0) AS total
FROM loan_releases
WHERE release_date = CURDATE()
SQL,
    "report.transaction_summary" => <<<'SQL'
SELECT 'Payments' AS type,
        COUNT(*) AS count,
        IFNULL(SUM(amount), 0) AS total
FROM payments
WHERE payment_date = CURDATE()
UNION ALL
SELECT 'Releases' AS type,
        COUNT(*) AS count,
        IFNULL(SUM(amount), 0) AS total
FROM loan_releases
WHERE release_date = CURDATE()
SQL,

    // SettingsRepository
    "settings.product_stats" => <<<'SQL'
SELECT
  SUM(CASE WHEN status = 'Active' THEN 1 ELSE 0 END) AS active,
  SUM(CASE WHEN status = 'Inactive' THEN 1 ELSE 0 END) AS archived
FROM loan_products
SQL,
    "settings.loan_products_all" => <<<'SQL'
SELECT
  id,
  name,
  code,
  description,
  term_unit,
  default_term,
  interest_rate,
  service_charge,
  status,
  notarial_used,
  notarial_rate_option,
  notarial_rate_value
FROM loan_products
ORDER BY created_at DESC
SQL,
    "settings.loan_products_by_status" => <<<'SQL'
SELECT
  id,
  name,
  code,
  description,
  term_unit,
  default_term,
  interest_rate,
  service_charge,
  status,
  notarial_used,
  notarial_rate_option,
  notarial_rate_value
FROM loan_products
WHERE status = :status
ORDER BY created_at DESC
SQL,
    "settings.loan_product_by_id" => <<<'SQL'
SELECT *
FROM loan_products
WHERE id = ?
SQL,
    "settings.loan_product_insert" => <<<'SQL'
INSERT INTO loan_products (
  name,
  code,
  description,
  status,
  loan_type,
  promissory_note,
  max_loan_amount,
  ceiling_loan_product,
  max_loan_count,
  grouping,
  cost_center,
  borrower_type_default,
  require_security,
  default_security,
  proceeds_type_default,
  enable_deed_assignment,
  required_no_employees,
  required_coborrower,
  required_comakers,
  employee_loan,
  term_unit,
  term_unit_flexible,
  fixed_number_days,
  fixed_number_days_flexible,
  default_term,
  default_term_flexible,
  maximum_term,
  interest_rate,
  interest_rate_flexible,
  recompute_interest,
  interest_basis_computation,
  interest_basis_flexible,
  interest_computation,
  interest_computation_flexible,
  interest_rate_minimum,
  days_in_year,
  penalty_per_amort_fixed_rate,
  penalty_per_amort_fixed_amount,
  penalty_per_amort_running_rate,
  penalty_per_amort_grace_days,
  penalty_per_amort_basis,
  penalty_after_maturity_fixed_rate,
  penalty_after_maturity_fixed_amount,
  penalty_after_maturity_running_rate,
  penalty_after_maturity_grace_days,
  penalty_after_maturity_basis,
  disregard_payments_after_maturity,
  include_amort_penalty,
  past_due_interest_rate,
  past_due_interest_basis,
  past_due_disregard_payments,
  penalty_gl_account,
  grace_period_option,
  secured_approval_min,
  secured_approval_max,
  secured_approver_count,
  unsecured_approval_min,
  unsecured_approval_max,
  unsecured_approver_count,
  service_charge_used,
  savings_discounted_used,
    grt_used,
    insurance_used,
    insurance_name,
    insurance_flexible,
    insurance_provider_default,
    insurance_table,
    insurance_printing_form,
    insurance_gl_account,
    insurance_product,
    notarial_used,
    notarial_rate_option,
    notarial_rate_value,
    doc_stamp_used,
    inspection_fee_used,
    filing_fee_used,
    processing_fee_used,
    processing_fee_name,
    processing_fee_bracket_option,
    processing_fee_rate_option,
    processing_fee_rate_value,
    processing_fee_flexible,
    processing_fee_gl_account,
    ctr_fund_used,
    insurance2_used,
    deduction8_used,
    deduction9_used,
  service_charge_amortized,
  savings_amortized,
  amort1,
  amort2,
  amort_date_adjustment,
  amort_adjust_on_holidays,
  amortization_grace_period,
  auto_debit_amortization,
  savings_holdout_value,
  savings_holdout_basis,
  cure_period_daily,
  cure_period_weekly,
  cure_period_semi_monthly,
  cure_period_monthly,
  cure_period_quarterly,
  cure_period_semi_annual,
  cure_period_annual,
  cure_period_lumpsum,
  enable_individual_cure_period,
  enable_release_tagging,
  cash_disbursed_by_teller,
  security_dependent_pns,
  acl_exempted,
  acl_assessment,
  comakership_limit,
  collection_list_display,
  collection_list_orientation,
  balance_to_show,
  reflect_date_granted,
  reflect_loan_amount,
  reflect_savings_balance,
  reflect_duedate,
  signature_on_collection_list,
  sms_language,
  sms_free,
  sms_show_unpaid_amorts,
  service_charge
) VALUES (
  :name,
  :code,
  :description,
  :status,
  :loan_type,
  :promissory_note,
  :max_loan_amount,
  :ceiling_loan_product,
  :max_loan_count,
  :grouping,
  :cost_center,
  :borrower_type_default,
  :require_security,
  :default_security,
  :proceeds_type_default,
  :enable_deed_assignment,
  :required_no_employees,
  :required_coborrower,
  :required_comakers,
  :employee_loan,
  :term_unit,
  :term_unit_flexible,
  :fixed_number_days,
  :fixed_number_days_flexible,
  :default_term,
  :default_term_flexible,
  :maximum_term,
  :interest_rate,
  :interest_rate_flexible,
  :recompute_interest,
  :interest_basis_computation,
  :interest_basis_flexible,
  :interest_computation,
  :interest_computation_flexible,
  :interest_rate_minimum,
  :days_in_year,
  :penalty_per_amort_fixed_rate,
  :penalty_per_amort_fixed_amount,
  :penalty_per_amort_running_rate,
  :penalty_per_amort_grace_days,
  :penalty_per_amort_basis,
  :penalty_after_maturity_fixed_rate,
  :penalty_after_maturity_fixed_amount,
  :penalty_after_maturity_running_rate,
  :penalty_after_maturity_grace_days,
  :penalty_after_maturity_basis,
  :disregard_payments_after_maturity,
  :include_amort_penalty,
  :past_due_interest_rate,
  :past_due_interest_basis,
  :past_due_disregard_payments,
  :penalty_gl_account,
  :grace_period_option,
  :secured_approval_min,
  :secured_approval_max,
  :secured_approver_count,
  :unsecured_approval_min,
  :unsecured_approval_max,
  :unsecured_approver_count,
  :service_charge_used,
  :savings_discounted_used,
    :grt_used,
    :insurance_used,
    :insurance_name,
    :insurance_flexible,
    :insurance_provider_default,
    :insurance_table,
    :insurance_printing_form,
    :insurance_gl_account,
    :insurance_product,
    :notarial_used,
    :notarial_rate_option,
    :notarial_rate_value,
    :doc_stamp_used,
    :inspection_fee_used,
    :filing_fee_used,
    :processing_fee_used,
    :processing_fee_name,
    :processing_fee_bracket_option,
    :processing_fee_rate_option,
    :processing_fee_rate_value,
    :processing_fee_flexible,
    :processing_fee_gl_account,
    :ctr_fund_used,
    :insurance2_used,
    :deduction8_used,
    :deduction9_used,
  :service_charge_amortized,
  :savings_amortized,
  :amort1,
  :amort2,
  :amort_date_adjustment,
  :amort_adjust_on_holidays,
  :amortization_grace_period,
  :auto_debit_amortization,
  :savings_holdout_value,
  :savings_holdout_basis,
  :cure_period_daily,
  :cure_period_weekly,
  :cure_period_semi_monthly,
  :cure_period_monthly,
  :cure_period_quarterly,
  :cure_period_semi_annual,
  :cure_period_annual,
  :cure_period_lumpsum,
  :enable_individual_cure_period,
  :enable_release_tagging,
  :cash_disbursed_by_teller,
  :security_dependent_pns,
  :acl_exempted,
  :acl_assessment,
  :comakership_limit,
  :collection_list_display,
  :collection_list_orientation,
  :balance_to_show,
  :reflect_date_granted,
  :reflect_loan_amount,
  :reflect_savings_balance,
  :reflect_duedate,
  :signature_on_collection_list,
  :sms_language,
  :sms_free,
  :sms_show_unpaid_amorts,
  :service_charge
)
SQL,
    "settings.loan_product_update" => <<<'SQL'
UPDATE loan_products SET
  name = :name,
  code = :code,
  description = :description,
  status = :status,
  loan_type = :loan_type,
  promissory_note = :promissory_note,
  max_loan_amount = :max_loan_amount,
  ceiling_loan_product = :ceiling_loan_product,
  max_loan_count = :max_loan_count,
  grouping = :grouping,
  cost_center = :cost_center,
  borrower_type_default = :borrower_type_default,
  require_security = :require_security,
  default_security = :default_security,
  proceeds_type_default = :proceeds_type_default,
  enable_deed_assignment = :enable_deed_assignment,
  required_no_employees = :required_no_employees,
  required_coborrower = :required_coborrower,
  required_comakers = :required_comakers,
  employee_loan = :employee_loan,
  term_unit = :term_unit,
  term_unit_flexible = :term_unit_flexible,
  fixed_number_days = :fixed_number_days,
  fixed_number_days_flexible = :fixed_number_days_flexible,
  default_term = :default_term,
  default_term_flexible = :default_term_flexible,
  maximum_term = :maximum_term,
  interest_rate = :interest_rate,
  interest_rate_flexible = :interest_rate_flexible,
  recompute_interest = :recompute_interest,
  interest_basis_computation = :interest_basis_computation,
  interest_basis_flexible = :interest_basis_flexible,
  interest_computation = :interest_computation,
  interest_computation_flexible = :interest_computation_flexible,
  interest_rate_minimum = :interest_rate_minimum,
  days_in_year = :days_in_year,
  penalty_per_amort_fixed_rate = :penalty_per_amort_fixed_rate,
  penalty_per_amort_fixed_amount = :penalty_per_amort_fixed_amount,
  penalty_per_amort_running_rate = :penalty_per_amort_running_rate,
  penalty_per_amort_grace_days = :penalty_per_amort_grace_days,
  penalty_per_amort_basis = :penalty_per_amort_basis,
  penalty_after_maturity_fixed_rate = :penalty_after_maturity_fixed_rate,
  penalty_after_maturity_fixed_amount = :penalty_after_maturity_fixed_amount,
  penalty_after_maturity_running_rate = :penalty_after_maturity_running_rate,
  penalty_after_maturity_grace_days = :penalty_after_maturity_grace_days,
  penalty_after_maturity_basis = :penalty_after_maturity_basis,
  disregard_payments_after_maturity = :disregard_payments_after_maturity,
  include_amort_penalty = :include_amort_penalty,
  past_due_interest_rate = :past_due_interest_rate,
  past_due_interest_basis = :past_due_interest_basis,
  past_due_disregard_payments = :past_due_disregard_payments,
  penalty_gl_account = :penalty_gl_account,
  grace_period_option = :grace_period_option,
  secured_approval_min = :secured_approval_min,
  secured_approval_max = :secured_approval_max,
  secured_approver_count = :secured_approver_count,
  unsecured_approval_min = :unsecured_approval_min,
  unsecured_approval_max = :unsecured_approval_max,
  unsecured_approver_count = :unsecured_approver_count,
  service_charge_used = :service_charge_used,
    savings_discounted_used = :savings_discounted_used,
    grt_used = :grt_used,
    insurance_used = :insurance_used,
    insurance_name = :insurance_name,
    insurance_flexible = :insurance_flexible,
    insurance_provider_default = :insurance_provider_default,
    insurance_table = :insurance_table,
    insurance_printing_form = :insurance_printing_form,
    insurance_gl_account = :insurance_gl_account,
    insurance_product = :insurance_product,
    notarial_used = :notarial_used,
    notarial_rate_option = :notarial_rate_option,
    notarial_rate_value = :notarial_rate_value,
    doc_stamp_used = :doc_stamp_used,
    inspection_fee_used = :inspection_fee_used,
    filing_fee_used = :filing_fee_used,
    processing_fee_used = :processing_fee_used,
    processing_fee_name = :processing_fee_name,
    processing_fee_bracket_option = :processing_fee_bracket_option,
    processing_fee_rate_option = :processing_fee_rate_option,
    processing_fee_rate_value = :processing_fee_rate_value,
    processing_fee_flexible = :processing_fee_flexible,
    processing_fee_gl_account = :processing_fee_gl_account,
    ctr_fund_used = :ctr_fund_used,
    insurance2_used = :insurance2_used,
    deduction8_used = :deduction8_used,
    deduction9_used = :deduction9_used,
  service_charge_amortized = :service_charge_amortized,
  savings_amortized = :savings_amortized,
  amort1 = :amort1,
  amort2 = :amort2,
  amort_date_adjustment = :amort_date_adjustment,
  amort_adjust_on_holidays = :amort_adjust_on_holidays,
  amortization_grace_period = :amortization_grace_period,
  auto_debit_amortization = :auto_debit_amortization,
  savings_holdout_value = :savings_holdout_value,
  savings_holdout_basis = :savings_holdout_basis,
  cure_period_daily = :cure_period_daily,
  cure_period_weekly = :cure_period_weekly,
  cure_period_semi_monthly = :cure_period_semi_monthly,
  cure_period_monthly = :cure_period_monthly,
  cure_period_quarterly = :cure_period_quarterly,
  cure_period_semi_annual = :cure_period_semi_annual,
  cure_period_annual = :cure_period_annual,
  cure_period_lumpsum = :cure_period_lumpsum,
  enable_individual_cure_period = :enable_individual_cure_period,
  enable_release_tagging = :enable_release_tagging,
  cash_disbursed_by_teller = :cash_disbursed_by_teller,
  security_dependent_pns = :security_dependent_pns,
  acl_exempted = :acl_exempted,
  acl_assessment = :acl_assessment,
  comakership_limit = :comakership_limit,
  collection_list_display = :collection_list_display,
  collection_list_orientation = :collection_list_orientation,
  balance_to_show = :balance_to_show,
  reflect_date_granted = :reflect_date_granted,
  reflect_loan_amount = :reflect_loan_amount,
  reflect_savings_balance = :reflect_savings_balance,
  reflect_duedate = :reflect_duedate,
  signature_on_collection_list = :signature_on_collection_list,
  sms_language = :sms_language,
  sms_free = :sms_free,
  sms_show_unpaid_amorts = :sms_show_unpaid_amorts,
  service_charge = :service_charge
WHERE id = :id
SQL,
    "settings.recommended_products" => <<<'SQL'
SELECT id, name, interest_rate, service_charge, status
FROM loan_products
WHERE status = 'Active'
ORDER BY created_at DESC
LIMIT %d
SQL,

    // UserRepository
    "user.all" => "SELECT id, username, role, created_at FROM users ORDER BY created_at DESC",
    "user.by_username" => "SELECT id, username, role, password_hash FROM users WHERE username = :username LIMIT 1",
    "user.by_id" => "SELECT id, username, role, password_hash FROM users WHERE id = :id LIMIT 1",
    "user.insert" => "INSERT INTO users (username, role) VALUES (:username, :role)",
    "user.update_role" => "UPDATE users SET role = :role WHERE id = :id",
    "user.update_password_hash" => "UPDATE users SET password_hash = :password_hash WHERE id = :id",
  ];

  public static function get(string $key): string
  {
    if (!isset(self::QUERIES[$key])) {
      throw new InvalidArgumentException("Unknown SQL query key: " . $key);
    }

    return self::QUERIES[$key];
  }
}
