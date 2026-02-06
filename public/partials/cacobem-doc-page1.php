<?php
  if (!isset($cacobemValues) || !is_array($cacobemValues)) {
    $cacobemValues = [];
  }
  $value = static function (string $key) use ($cacobemValues): string {
    return htmlspecialchars((string) ($cacobemValues[$key] ?? ""));
  };
?>
<div class="cacobem-sheet">
  <?php require __DIR__ . "/cacobem-doc-header.php"; ?>

  <div class="cacobem-title-doc">LOAN APPLICATION</div>

  <div class="cacobem-line no-wrap">
    <span class="cacobem-label-inline">Application Date:</span>
    <input class="cacobem-input-inline" data-underline="27" type="text" name="cacobem[application_date]" value="<?php echo $value("application_date"); ?>" />
  </div>

  <div class="cacobem-line no-wrap tight spaced">
    <span class="cacobem-label-inline">Name:</span>
    <input class="cacobem-input-inline" data-underline="20" type="text" name="cacobem[borrower_name]" value="<?php echo $value("borrower_name"); ?>" />
    <span class="cacobem-label-inline">Age:</span>
    <input class="cacobem-input-inline" data-underline="7" type="text" name="cacobem[borrower_age]" value="<?php echo $value("borrower_age"); ?>" />
    <span class="cacobem-label-inline">CTC No.:</span>
    <input class="cacobem-input-inline" data-underline="10" type="text" name="cacobem[ctc_no]" value="<?php echo $value("ctc_no"); ?>" />
    <span class="cacobem-label-inline">Date issued:</span>
    <input class="cacobem-input-inline" data-underline="7" type="text" name="cacobem[ctc_date_issued]" value="<?php echo $value("ctc_date_issued"); ?>" />
  </div>

  <div class="cacobem-line no-wrap tight spaced">
    <span class="cacobem-label-inline">Birthdate:</span>
    <input class="cacobem-input-inline" data-underline="13" type="text" name="cacobem[birthdate]" value="<?php echo $value("birthdate"); ?>" />
    <span class="cacobem-label-inline">Place of Birth:</span>
    <input class="cacobem-input-inline" data-underline="19" type="text" name="cacobem[birth_place]" value="<?php echo $value("birth_place"); ?>" />
    <span class="cacobem-label-inline">Place Issued:</span>
    <input class="cacobem-input-inline" data-underline="12" type="text" name="cacobem[place_issued]" value="<?php echo $value("place_issued"); ?>" />
  </div>

  <div class="cacobem-line no-wrap tight spaced">
    <span class="cacobem-label-inline">Name of Spouse:</span>
    <input class="cacobem-input-inline" data-underline="26" type="text" name="cacobem[spouse_name]" value="<?php echo $value("spouse_name"); ?>" />
    <span class="cacobem-label-inline">Age:</span>
    <input class="cacobem-input-inline" data-underline="13" type="text" name="cacobem[spouse_age]" value="<?php echo $value("spouse_age"); ?>" />
    <span class="cacobem-label-inline">No of Children:</span>
    <input class="cacobem-input-inline" data-underline="6" type="text" name="cacobem[children_count]" value="<?php echo $value("children_count"); ?>" />
  </div>

  <div class="cacobem-line spaced">
    <span class="cacobem-label-inline">Address:</span>
    <input class="cacobem-input-inline" data-underline="67" type="text" name="cacobem[address]" value="<?php echo $value("address"); ?>" />
  </div>

  <div class="cacobem-line no-wrap tight">
    <span class="cacobem-label-inline">Amount applied for:</span>
    <input class="cacobem-input-inline" data-underline="12" type="text" name="cacobem[amount_applied]" value="<?php echo $value("amount_applied"); ?>" />
    <span class="cacobem-label-inline">Interest Rate:</span>
    <span class="cacobem-inline-text">Nine (9%) percent per annum</span>
  </div>

  <div class="cacobem-line">
    <span class="cacobem-label-inline">Specific Purpose:</span>
    <input class="cacobem-input-inline" data-underline="54" type="text" name="cacobem[specific_purpose]" value="<?php echo $value("specific_purpose"); ?>" />
  </div>

  <div class="cacobem-sign-row">
    <div class="cacobem-sign-block">
      <input class="cacobem-input-inline" data-underline="30" type="text" name="cacobem[borrower_signature]" value="<?php echo $value("borrower_signature"); ?>" />
      <div class="cacobem-sign-label">Name and Signature of Borrower</div>
    </div>
    <div class="cacobem-sign-block">
      <input class="cacobem-input-inline" data-underline="30" type="text" name="cacobem[spouse_signature]" value="<?php echo $value("spouse_signature"); ?>" />
      <div class="cacobem-sign-label">Name and Signature of Spouse</div>
    </div>
  </div>

  <div class="cacobem-sign-row">
    <div class="cacobem-sign-block">
      <input class="cacobem-input-inline" data-underline="30" type="text" name="cacobem[comaker1_signature]" value="<?php echo $value("comaker1_signature"); ?>" />
      <div class="cacobem-sign-label">Name and Signature of Co-maker</div>
    </div>
    <div class="cacobem-sign-block">
      <input class="cacobem-input-inline" data-underline="30" type="text" name="cacobem[comaker2_signature]" value="<?php echo $value("comaker2_signature"); ?>" />
      <div class="cacobem-sign-label">Name and Signature of Co-maker</div>
    </div>
  </div>

  <div class="cacobem-columns">
    <div class="cacobem-box action">
      <div class="cacobem-box-title">Action to Loan Application</div>
      <div class="cacobem-line no-wrap tight">
        <span class="cacobem-label-inline cacobem-bold">Loan Ceiling:</span>
        <input class="cacobem-input-inline fill" type="text" name="cacobem[action_loan_ceiling]" value="<?php echo $value("action_loan_ceiling"); ?>" />
      </div>
      <div class="cacobem-line no-wrap tight">
        <span class="cacobem-label-inline">Share Capital:</span>
        <input class="cacobem-input-inline" data-underline="13" type="text" name="cacobem[action_share_capital]" value="<?php echo $value("action_share_capital"); ?>" />
      </div>
      <div class="cacobem-line no-wrap tight">
        <span class="cacobem-label-inline">Loan Balance:</span>
        <input class="cacobem-input-inline" data-underline="13" type="text" name="cacobem[action_loan_balance]" value="<?php echo $value("action_loan_balance"); ?>" />
      </div>
      <div class="cacobem-line no-wrap tight">
        <span class="cacobem-label-inline">Interest Due:</span>
        <input class="cacobem-input-inline" data-underline="13" type="text" name="cacobem[action_interest_due]" value="<?php echo $value("action_interest_due"); ?>" />
      </div>
      <div class="cacobem-line">
        <span class="cacobem-label-inline">Remark:</span>
        <input class="cacobem-input-inline fill" type="text" name="cacobem[action_remark]" value="<?php echo $value("action_remark"); ?>" />
      </div>
      <div class="cacobem-line no-wrap tight">
        <span class="cacobem-label-inline">Certified by:</span>
        <span class="cacobem-label-inline">Date</span>
      </div>
      <div class="cacobem-line no-wrap tight">
        <div class="cacobem-underline-field">
          <input class="cacobem-input-inline" data-underline="18" type="text" name="cacobem[action_certified_by]" value="<?php echo $value("action_certified_by"); ?>" />
        </div>
        <div class="cacobem-underline-field">
          <input class="cacobem-input-inline" data-underline="7" type="text" name="cacobem[action_certified_date]" value="<?php echo $value("action_certified_date"); ?>" />
        </div>
      </div>
      <div class="cacobem-line no-wrap tight">
        <span class="cacobem-inline-text">Bookkeeper</span>
        <span class="cacobem-inline-text">Date</span>
      </div>
      <div class="cacobem-line no-wrap tight">
        <span class="cacobem-label-inline">Security:</span>
        <span class="cacobem-radio-group">
          <label><input class="cacobem-radio" type="radio" name="cacobem[action_security]" value="Secured" <?php echo $value("action_security") === "Secured" ? "checked" : ""; ?> /> Secured</label>
          <label><input class="cacobem-radio" type="radio" name="cacobem[action_security]" value="Unsecured" <?php echo $value("action_security") === "Unsecured" ? "checked" : ""; ?> /> Unsecured</label>
        </span>
      </div>
      <div class="cacobem-line no-wrap tight">
        <span class="cacobem-label-inline">Share Capital:</span>
        <input class="cacobem-input-inline" data-underline="8" type="text" name="cacobem[action_share_capital_security]" value="<?php echo $value("action_share_capital_security"); ?>" />
      </div>
      <div class="cacobem-line no-wrap tight">
        <span class="cacobem-label-inline">REM TCT No.:</span>
        <input class="cacobem-input-inline" data-underline="13" type="text" name="cacobem[action_rem_tct_no]" value="<?php echo $value("action_rem_tct_no"); ?>" />
      </div>
      <div class="cacobem-line no-wrap tight">
        <span class="cacobem-label-inline">Chattel Mortgage:</span>
        <input class="cacobem-input-inline" data-underline="13" type="text" name="cacobem[action_chattel_mortgage]" value="<?php echo $value("action_chattel_mortgage"); ?>" />
      </div>
      <div class="cacobem-line">
        <span class="cacobem-label-inline">Approval</span>
      </div>
      <div class="cacobem-line no-wrap tight">
        <span class="cacobem-label-inline">Approved amount:</span>
        <input class="cacobem-input-inline" data-underline="13" type="text" name="cacobem[action_approved_amount]" value="<?php echo $value("action_approved_amount"); ?>" />
      </div>

      <div class="cacobem-approval">
        <div class="cacobem-approval-row">
          <div class="cacobem-approval-name">STEVEN B. BANEZ</div>
          <div class="cacobem-approval-name">MARILYN L. SAGALABOD</div>
        </div>
        <div class="cacobem-approval-row">
          <div class="cacobem-approval-role">BOD Chairperson</div>
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

    <div class="cacobem-box discount">
      <div class="cacobem-box-title">Discount Statement</div>
      <div class="cacobem-line no-wrap tight">
        <span class="cacobem-label-inline">Loan Type:</span>
        <input class="cacobem-input-inline fill" type="text" name="cacobem[discount_loan_type]" value="<?php echo $value("discount_loan_type"); ?>" />
      </div>
      <div class="cacobem-line no-wrap tight">
        <span class="cacobem-label-inline">Term:</span>
        <input class="cacobem-input-inline" data-underline="9" type="text" name="cacobem[discount_term_days]" value="<?php echo $value("discount_term_days"); ?>" />
        <span class="cacobem-inline-text">Days</span>
      </div>
      <div class="cacobem-line no-wrap tight">
        <span class="cacobem-label-inline">Date Granted:</span>
        <input class="cacobem-input-inline" data-underline="15" type="text" name="cacobem[discount_date_granted]" value="<?php echo $value("discount_date_granted"); ?>" />
      </div>
      <div class="cacobem-line no-wrap tight">
        <span class="cacobem-label-inline">Maturity Date:</span>
        <input class="cacobem-input-inline" data-underline="15" type="text" name="cacobem[discount_maturity_date]" value="<?php echo $value("discount_maturity_date"); ?>" />
      </div>
      <div class="cacobem-line no-wrap tight">
        <span class="cacobem-label-inline">Amount of Loan:</span>
        <input class="cacobem-input-inline" data-underline="12" type="text" name="cacobem[discount_amount_loan]" value="<?php echo $value("discount_amount_loan"); ?>" />
      </div>
      <div class="cacobem-line no-wrap tight">
        <span class="cacobem-label-inline">LB/Int.:</span>
        <input class="cacobem-input-inline fill" type="text" name="cacobem[discount_lb_int]" value="<?php echo $value("discount_lb_int"); ?>" />
      </div>
      <div class="cacobem-line no-wrap tight">
        <span class="cacobem-label-inline">Notarial Fee:</span>
        <input class="cacobem-input-inline fill" type="text" name="cacobem[discount_notarial_fee]" value="<?php echo $value("discount_notarial_fee"); ?>" />
      </div>
      <div class="cacobem-line no-wrap tight">
        <span class="cacobem-label-inline">MRI (Insurance):</span>
        <input class="cacobem-input-inline fill" type="text" name="cacobem[discount_mri_insurance]" value="<?php echo $value("discount_mri_insurance"); ?>" />
      </div>
      <div class="cacobem-line no-wrap tight">
        <span class="cacobem-label-inline">Total Deductions:</span>
        <input class="cacobem-input-inline fill" type="text" name="cacobem[discount_total_deductions]" value="<?php echo $value("discount_total_deductions"); ?>" />
      </div>
      <div class="cacobem-line no-wrap tight">
        <span class="cacobem-label-inline">Net Proceeds:</span>
      </div>
      <div class="cacobem-line no-wrap tight">
        <span class="cacobem-label-inline">Prepared by:</span>
        <span class="cacobem-label-inline">Checked By:</span>
      </div>
      <div class="cacobem-line no-wrap tight">
        <div class="cacobem-underline-field">
          <input class="cacobem-input-inline" data-underline="17" type="text" name="cacobem[discount_prepared_by]" value="<?php echo $value("discount_prepared_by"); ?>" />
          <span class="cacobem-inline-text">Bookkeeper</span>
        </div>
        <div class="cacobem-underline-field">
          <input class="cacobem-input-inline" data-underline="16" type="text" name="cacobem[discount_checked_by]" value="<?php echo $value("discount_checked_by"); ?>" />
          <span class="cacobem-inline-text">Cashier</span>
        </div>
      </div>
      <div class="cacobem-line">
        The Net Proceeds amounting to
        <input class="cacobem-input-inline" data-underline="11" type="text" name="cacobem[discount_net_proceeds]" value="<?php echo $value("discount_net_proceeds"); ?>" />
        <input class="cacobem-input-inline" data-underline="41" type="text" name="cacobem[discount_net_proceeds_words]" value="<?php echo $value("discount_net_proceeds_words"); ?>" />
        (Php
        <input class="cacobem-input-inline" data-underline="18" type="text" name="cacobem[discount_net_proceeds_php]" value="<?php echo $value("discount_net_proceeds_php"); ?>" />
        )
        shall be credited to:
        <input class="cacobem-input-inline" data-underline="37" type="text" name="cacobem[discount_bank_account]" value="<?php echo $value("discount_bank_account"); ?>" />
        <span class="cacobem-inline-text">(Bank and Account no.)</span>
      </div>
      <div class="cacobem-line no-wrap tight">
        Conformed by:
        <input class="cacobem-input-inline" data-underline="19" type="text" name="cacobem[discount_conformed_by]" value="<?php echo $value("discount_conformed_by"); ?>" />
        <span class="cacobem-inline-text">Borrower</span>
      </div>
    </div>
  </div>

  <div class="cacobem-box cacobem-box-full">
    <div class="cacobem-box-title auth">A U T H O R I Z A T I O N</div>
    <div class="cacobem-line no-wrap">
      <span class="cacobem-label-inline">Date:</span>
      <input class="cacobem-input-inline" data-underline="22" type="text" name="cacobem[authorization_date]" value="<?php echo $value("authorization_date"); ?>" />
    </div>
    <div class="cacobem-line">
      I hereby authorize Cooperative Bank of Cagayan to collect from my salary every
      <span class="cacobem-radio-group">
        <label><input class="cacobem-radio" type="radio" name="cacobem[authorization_schedule]" value="15th" <?php echo $value("authorization_schedule") === "15th" ? "checked" : ""; ?> /> 15th</label>
        <label><input class="cacobem-radio" type="radio" name="cacobem[authorization_schedule]" value="30th" <?php echo $value("authorization_schedule") === "30th" ? "checked" : ""; ?> /> 30th</label>
        <label><input class="cacobem-radio" type="radio" name="cacobem[authorization_schedule]" value="15/30th" <?php echo $value("authorization_schedule") === "15/30th" ? "checked" : ""; ?> /> 15/30th</label>
      </span>
      the sum of
      <input class="cacobem-input-inline" data-underline="33" type="text" name="cacobem[authorization_amount_words]" value="<?php echo $value("authorization_amount_words"); ?>" />
      (Php
      <input class="cacobem-input-inline" data-underline="11" type="text" name="cacobem[authorization_amount_php]" value="<?php echo $value("authorization_amount_php"); ?>" />
      )
      plus interest and insurance if any, in case the loan is long term, until fully paid.
    </div>
    <div class="cacobem-line no-wrap">
      <input class="cacobem-input-inline" data-underline="30" type="text" name="cacobem[authorization_borrower_signature]" value="<?php echo $value("authorization_borrower_signature"); ?>" />
      <span class="cacobem-sign-label">Borrower</span>
    </div>
  </div>

</div>
