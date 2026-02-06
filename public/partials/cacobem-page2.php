<?php
  if (!isset($cacobemValues) || !is_array($cacobemValues)) {
    $cacobemValues = [];
  }
  $cacobemShowDuplicates = $cacobemShowDuplicates ?? true;
  $value = static function (string $key) use ($cacobemValues): string {
    return htmlspecialchars((string) ($cacobemValues[$key] ?? ""));
  };
?>
<div class="cacobem-page">
  <div class="cacobem-section">
    <div class="cacobem-title">Discount Statement</div>
    <div class="cacobem-grid cols-3">
      <div class="cacobem-field">
        <label class="cacobem-label">Loan Type:</label>
        <input class="cacobem-input" type="text" name="cacobem[discount_loan_type]" value="<?php echo $value("discount_loan_type"); ?>" />
      </div>
      <div class="cacobem-field">
        <label class="cacobem-label">Term (Days):</label>
        <input class="cacobem-input small" type="text" name="cacobem[discount_term_days]" value="<?php echo $value("discount_term_days"); ?>" />
      </div>
      <div class="cacobem-field">
        <label class="cacobem-label">Date Granted:</label>
        <input class="cacobem-input cacobem-input-date" type="date" name="cacobem[discount_date_granted]" value="<?php echo $value("discount_date_granted"); ?>" />
      </div>
    </div>

    <div class="cacobem-grid cols-3">
      <div class="cacobem-field">
        <label class="cacobem-label">Maturity Date:</label>
        <input class="cacobem-input cacobem-input-date" type="date" name="cacobem[discount_maturity_date]" value="<?php echo $value("discount_maturity_date"); ?>" />
      </div>
      <div class="cacobem-field">
        <label class="cacobem-label">Amount of Loan:</label>
        <input class="cacobem-input" type="text" name="cacobem[discount_amount_loan]" value="<?php echo $value("discount_amount_loan"); ?>" />
      </div>
      <div class="cacobem-field">
        <label class="cacobem-label">LB/Int.:</label>
        <input class="cacobem-input" type="text" name="cacobem[discount_lb_int]" value="<?php echo $value("discount_lb_int"); ?>" />
      </div>
    </div>

    <div class="cacobem-grid cols-3">
      <div class="cacobem-field">
        <label class="cacobem-label">Notarial Fee:</label>
        <input class="cacobem-input" type="text" name="cacobem[discount_notarial_fee]" value="<?php echo $value("discount_notarial_fee"); ?>" />
      </div>
      <div class="cacobem-field">
        <label class="cacobem-label">MRI (Insurance):</label>
        <input class="cacobem-input" type="text" name="cacobem[discount_mri_insurance]" value="<?php echo $value("discount_mri_insurance"); ?>" />
      </div>
      <div class="cacobem-field">
        <label class="cacobem-label">Total Deductions:</label>
        <input class="cacobem-input" type="text" name="cacobem[discount_total_deductions]" value="<?php echo $value("discount_total_deductions"); ?>" />
      </div>
    </div>

    <div class="cacobem-grid cols-3">
      <div class="cacobem-field">
        <label class="cacobem-label">Net Proceeds:</label>
        <input class="cacobem-input" type="text" name="cacobem[discount_net_proceeds]" value="<?php echo $value("discount_net_proceeds"); ?>" />
      </div>
      <div class="cacobem-field">
        <label class="cacobem-label">Prepared by:</label>
        <input class="cacobem-input" type="text" name="cacobem[discount_prepared_by]" value="<?php echo $value("discount_prepared_by"); ?>" />
        <div class="cacobem-sign-label">Bookkeeper</div>
      </div>
      <div class="cacobem-field">
        <label class="cacobem-label">Checked By:</label>
        <input class="cacobem-input" type="text" name="cacobem[discount_checked_by]" value="<?php echo $value("discount_checked_by"); ?>" />
        <div class="cacobem-sign-label">Cashier</div>
      </div>
    </div>

    <div class="cacobem-row">
      The Net Proceeds amounting to
      <input class="cacobem-input" type="text" name="cacobem[discount_net_proceeds_words]" value="<?php echo $value("discount_net_proceeds_words"); ?>" />
      (Php
      <input class="cacobem-input small" type="text" name="cacobem[discount_net_proceeds_php]" value="<?php echo $value("discount_net_proceeds_php"); ?>" />
      ) shall be credited to:
      <input class="cacobem-input wide" type="text" name="cacobem[discount_bank_account]" value="<?php echo $value("discount_bank_account"); ?>" />
      <span class="cacobem-muted">(Bank and Account no.)</span>
    </div>

    <div class="cacobem-row">
      Conformed by:
      <input class="cacobem-input" type="text" name="cacobem[discount_conformed_by]" value="<?php echo $value("discount_conformed_by"); ?>" />
      <span class="cacobem-sign-label">Borrower</span>
    </div>
  </div>

  <?php if ($cacobemShowDuplicates) : ?>
    <div class="cacobem-section duplicate">
      <div class="cacobem-title">Discount Statement</div>
      <div class="cacobem-row">
        Loan Type: <span class="cacobem-value"><?php echo $value("discount_loan_type"); ?></span>
        Term (Days): <span class="cacobem-value"><?php echo $value("discount_term_days"); ?></span>
        Date Granted: <span class="cacobem-value"><?php echo $value("discount_date_granted"); ?></span>
        Maturity Date: <span class="cacobem-value"><?php echo $value("discount_maturity_date"); ?></span>
      </div>
      <div class="cacobem-row">
        Amount of Loan: <span class="cacobem-value"><?php echo $value("discount_amount_loan"); ?></span>
        LB/Int.: <span class="cacobem-value"><?php echo $value("discount_lb_int"); ?></span>
        Notarial Fee: <span class="cacobem-value"><?php echo $value("discount_notarial_fee"); ?></span>
        MRI (Insurance): <span class="cacobem-value"><?php echo $value("discount_mri_insurance"); ?></span>
      </div>
      <div class="cacobem-row">
        Total Deductions: <span class="cacobem-value"><?php echo $value("discount_total_deductions"); ?></span>
        Net Proceeds: <span class="cacobem-value"><?php echo $value("discount_net_proceeds"); ?></span>
        Prepared by: <span class="cacobem-value"><?php echo $value("discount_prepared_by"); ?></span>
        Checked By: <span class="cacobem-value"><?php echo $value("discount_checked_by"); ?></span>
      </div>
      <div class="cacobem-row">
        The Net Proceeds amounting to
        <span class="cacobem-value"><?php echo $value("discount_net_proceeds_words"); ?></span>
        (Php <span class="cacobem-value"><?php echo $value("discount_net_proceeds_php"); ?></span>)
        shall be credited to:
        <span class="cacobem-value"><?php echo $value("discount_bank_account"); ?></span>
      </div>
      <div class="cacobem-row">
        Conformed by: <span class="cacobem-value"><?php echo $value("discount_conformed_by"); ?></span>
        <span class="cacobem-sign-label">Borrower</span>
      </div>
    </div>
  <?php endif; ?>

  <div class="cacobem-section">
    <div class="cacobem-title">Action to Loan Application</div>
    <div class="cacobem-grid cols-3">
      <div class="cacobem-field">
        <label class="cacobem-label">Loan Ceiling:</label>
        <input class="cacobem-input" type="text" name="cacobem[action_loan_ceiling]" value="<?php echo $value("action_loan_ceiling"); ?>" />
      </div>
      <div class="cacobem-field">
        <label class="cacobem-label">Share Capital:</label>
        <input class="cacobem-input" type="text" name="cacobem[action_share_capital]" value="<?php echo $value("action_share_capital"); ?>" />
      </div>
      <div class="cacobem-field">
        <label class="cacobem-label">Loan Balance:</label>
        <input class="cacobem-input" type="text" name="cacobem[action_loan_balance]" value="<?php echo $value("action_loan_balance"); ?>" />
      </div>
    </div>

    <div class="cacobem-grid cols-3">
      <div class="cacobem-field">
        <label class="cacobem-label">Interest Due:</label>
        <input class="cacobem-input" type="text" name="cacobem[action_interest_due]" value="<?php echo $value("action_interest_due"); ?>" />
      </div>
      <div class="cacobem-field">
        <label class="cacobem-label">Remark:</label>
        <input class="cacobem-input" type="text" name="cacobem[action_remark]" value="<?php echo $value("action_remark"); ?>" />
      </div>
      <div class="cacobem-field">
        <label class="cacobem-label">Certified by:</label>
        <input class="cacobem-input" type="text" name="cacobem[action_certified_by]" value="<?php echo $value("action_certified_by"); ?>" />
      </div>
    </div>

    <div class="cacobem-grid cols-3">
      <div class="cacobem-field">
        <label class="cacobem-label">Date:</label>
        <input class="cacobem-input cacobem-input-date" type="date" name="cacobem[action_certified_date]" value="<?php echo $value("action_certified_date"); ?>" />
      </div>
      <div class="cacobem-field">
        <label class="cacobem-label">Security:</label>
        <div class="cacobem-inline">
          <label><input type="radio" name="cacobem[action_security]" value="Secured" <?php echo $value("action_security") === "Secured" ? "checked" : ""; ?> /> Secured</label>
          <label><input type="radio" name="cacobem[action_security]" value="Unsecured" <?php echo $value("action_security") === "Unsecured" ? "checked" : ""; ?> /> Unsecured</label>
        </div>
      </div>
      <div class="cacobem-field">
        <label class="cacobem-label">Share Capital:</label>
        <input class="cacobem-input" type="text" name="cacobem[action_share_capital_security]" value="<?php echo $value("action_share_capital_security"); ?>" />
      </div>
    </div>

    <div class="cacobem-grid cols-3">
      <div class="cacobem-field">
        <label class="cacobem-label">REM TCT No.:</label>
        <input class="cacobem-input" type="text" name="cacobem[action_rem_tct_no]" value="<?php echo $value("action_rem_tct_no"); ?>" />
      </div>
      <div class="cacobem-field">
        <label class="cacobem-label">Chattel Mortgage:</label>
        <input class="cacobem-input" type="text" name="cacobem[action_chattel_mortgage]" value="<?php echo $value("action_chattel_mortgage"); ?>" />
      </div>
      <div class="cacobem-field">
        <label class="cacobem-label">Approved amount:</label>
        <input class="cacobem-input" type="text" name="cacobem[action_approved_amount]" value="<?php echo $value("action_approved_amount"); ?>" />
      </div>
    </div>

    <div class="cacobem-approval">
      <div class="cacobem-approval-row">
        <div class="cacobem-approval-name">STEVEN B. BANEZ</div>
        <div class="cacobem-approval-name">MARILYN L. SAGALABOD</div>
      </div>
      <div class="cacobem-approval-row">
        <div class="cacobem-approval-role">Chairperson</div>
        <div class="cacobem-approval-role">BOD Vice Chairperson</div>
      </div>
      <div class="cacobem-approval-row">
        <div class="cacobem-approval-name">ROLDAN B. APACIBLE</div>
        <div class="cacobem-approval-name">KRISTINE JOYCE S. AGUSTIN</div>
        <div class="cacobem-approval-name">MELODY FAITH C. MACABBABAD</div>
      </div>
      <div class="cacobem-approval-row">
        <div class="cacobem-approval-role">BOD Member</div>
        <div class="cacobem-approval-role">BOD Member</div>
        <div class="cacobem-approval-role">BOD Member</div>
      </div>
    </div>
  </div>

  <?php if ($cacobemShowDuplicates) : ?>
    <div class="cacobem-section duplicate">
      <div class="cacobem-title">Action to Loan Application</div>
      <div class="cacobem-row">
        Loan Ceiling: <span class="cacobem-value"><?php echo $value("action_loan_ceiling"); ?></span>
        Share Capital: <span class="cacobem-value"><?php echo $value("action_share_capital"); ?></span>
        Loan Balance: <span class="cacobem-value"><?php echo $value("action_loan_balance"); ?></span>
        Interest Due: <span class="cacobem-value"><?php echo $value("action_interest_due"); ?></span>
      </div>
      <div class="cacobem-row">
        Remark: <span class="cacobem-value"><?php echo $value("action_remark"); ?></span>
        Certified by: <span class="cacobem-value"><?php echo $value("action_certified_by"); ?></span>
        Date: <span class="cacobem-value"><?php echo $value("action_certified_date"); ?></span>
      </div>
      <div class="cacobem-row">
        Security: <span class="cacobem-value"><?php echo $value("action_security"); ?></span>
        Share Capital: <span class="cacobem-value"><?php echo $value("action_share_capital_security"); ?></span>
        REM TCT No.: <span class="cacobem-value"><?php echo $value("action_rem_tct_no"); ?></span>
        Chattel Mortgage: <span class="cacobem-value"><?php echo $value("action_chattel_mortgage"); ?></span>
        Approved amount: <span class="cacobem-value"><?php echo $value("action_approved_amount"); ?></span>
      </div>
      <div class="cacobem-approval">
        <div class="cacobem-approval-row">
          <div class="cacobem-approval-name">STEVEN B. BANEZ</div>
          <div class="cacobem-approval-name">MARILYN L. SAGALABOD</div>
        </div>
        <div class="cacobem-approval-row">
          <div class="cacobem-approval-role">Chairperson</div>
          <div class="cacobem-approval-role">BOD Vice Chairperson</div>
        </div>
        <div class="cacobem-approval-row">
          <div class="cacobem-approval-name">ROLDAN B. APACIBLE</div>
          <div class="cacobem-approval-name">KRISTINE JOYCE S. AGUSTIN</div>
          <div class="cacobem-approval-name">MELODY FAITH C. MACABBABAD</div>
        </div>
        <div class="cacobem-approval-row">
          <div class="cacobem-approval-role">BOD Member</div>
          <div class="cacobem-approval-role">BOD Member</div>
          <div class="cacobem-approval-role">BOD Member</div>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <div class="cacobem-section">
    <div class="cacobem-title">PROMISSORY NOTE</div>
    <div class="cacobem-grid cols-4">
      <div class="cacobem-field">
        <label class="cacobem-label">PN No.:</label>
        <input class="cacobem-input" type="text" name="cacobem[pn_no]" value="<?php echo $value("pn_no"); ?>" />
      </div>
      <div class="cacobem-field">
        <label class="cacobem-label">Date Granted:</label>
        <input class="cacobem-input cacobem-input-date" type="date" name="cacobem[pn_date_granted]" value="<?php echo $value("pn_date_granted"); ?>" />
      </div>
      <div class="cacobem-field">
        <label class="cacobem-label">Maturity Date:</label>
        <input class="cacobem-input cacobem-input-date" type="date" name="cacobem[pn_maturity_date]" value="<?php echo $value("pn_maturity_date"); ?>" />
      </div>
      <div class="cacobem-field">
        <label class="cacobem-label">Amount Granted:</label>
        <input class="cacobem-input" type="text" name="cacobem[pn_amount_granted]" value="<?php echo $value("pn_amount_granted"); ?>" />
      </div>
    </div>

    <div class="cacobem-row">
      <input class="cacobem-input small" type="text" name="cacobem[pn_term_value]" value="<?php echo $value("pn_term_value"); ?>" />
      <span class="cacobem-inline">
        <label><input type="radio" name="cacobem[pn_term_unit]" value="days" <?php echo $value("pn_term_unit") === "days" ? "checked" : ""; ?> /> days</label>
        <label><input type="radio" name="cacobem[pn_term_unit]" value="years" <?php echo $value("pn_term_unit") === "years" ? "checked" : ""; ?> /> years</label>
      </span>
      after date for value received. I/we promise to pay jointly and severally to the order of the Cagayan Cooperative Bank
      Employees MPCI at its office at Diversion Road, San Gabriel Village, Tuguegarao City the Amount of
      <input class="cacobem-input wide" type="text" name="cacobem[pn_amount_words]" value="<?php echo $value("pn_amount_words"); ?>" />
      (Php <input class="cacobem-input small" type="text" name="cacobem[pn_amount_php]" value="<?php echo $value("pn_amount_php"); ?>" />)
      Philippine Currency with interest rate of Nine (9%) percent per annum from date hereof until fully paid, according to the following schedule:
      <span class="cacobem-muted">See attached (Annex A).</span>
    </div>

    <div class="cacobem-row">
      This Note is secured by
      <input class="cacobem-input" type="text" name="cacobem[pn_secured_by]" value="<?php echo $value("pn_secured_by"); ?>" />
      executed on
      <input class="cacobem-input cacobem-input-date" type="date" name="cacobem[pn_secured_date]" value="<?php echo $value("pn_secured_date"); ?>" />
      in favor of the aforementioned Cooperative Bank Employees MPCI under Doc No:
      <input class="cacobem-input small" type="text" name="cacobem[pn_doc_no]" value="<?php echo $value("pn_doc_no"); ?>" />
      Page No.:
      <input class="cacobem-input small" type="text" name="cacobem[pn_page_no]" value="<?php echo $value("pn_page_no"); ?>" />
      Book No.:
      <input class="cacobem-input small" type="text" name="cacobem[pn_book_no]" value="<?php echo $value("pn_book_no"); ?>" />
      Series of
      <input class="cacobem-input small" type="text" name="cacobem[pn_series_year]" value="<?php echo $value("pn_series_year"); ?>" />.
    </div>

    <div class="cacobem-signatures">
      <div class="cacobem-sign-field">
        <input class="cacobem-input" type="text" name="cacobem[pn_borrower_signature]" value="<?php echo $value("pn_borrower_signature"); ?>" />
        <div class="cacobem-sign-label">Name and Signature of Borrower</div>
      </div>
      <div class="cacobem-sign-field">
        <input class="cacobem-input" type="text" name="cacobem[pn_spouse_signature]" value="<?php echo $value("pn_spouse_signature"); ?>" />
        <div class="cacobem-sign-label">Name and Signature of Spouse</div>
      </div>
    </div>

    <div class="cacobem-signatures">
      <div class="cacobem-sign-field">
        <input class="cacobem-input" type="text" name="cacobem[pn_comaker1_signature]" value="<?php echo $value("pn_comaker1_signature"); ?>" />
        <div class="cacobem-sign-label">Name and Signature of Co-maker</div>
      </div>
      <div class="cacobem-sign-field">
        <input class="cacobem-input" type="text" name="cacobem[pn_comaker2_signature]" value="<?php echo $value("pn_comaker2_signature"); ?>" />
        <div class="cacobem-sign-label">Name and Signature of Co-maker</div>
      </div>
    </div>

    <div class="cacobem-section">
      <div class="cacobem-title">WITNESSES</div>
      <div class="cacobem-signatures">
        <div class="cacobem-sign-field">
          <input class="cacobem-input" type="text" name="cacobem[witness_1]" value="<?php echo $value("witness_1"); ?>" />
        </div>
        <div class="cacobem-sign-field">
          <input class="cacobem-input" type="text" name="cacobem[witness_2]" value="<?php echo $value("witness_2"); ?>" />
        </div>
      </div>
    </div>

    <div class="cacobem-section">
      <div class="cacobem-title">SUBSCRIBED AND SWORN</div>
      <div class="cacobem-row">
        SUBSCRIBED AND SWORN to before me this _____ day of _________ at Tuguegarao City, Cagayan.
      </div>
      <div class="cacobem-row">
        Doc. No. _____ &nbsp;&nbsp; Page No. _____ &nbsp;&nbsp; Book No. _____ &nbsp;&nbsp; Series of 2026.
      </div>
    </div>
  </div>
</div>
