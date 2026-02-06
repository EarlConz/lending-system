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

  <div class="cacobem-title-doc">PROMISSORY NOTE</div>

  <div class="cacobem-line no-wrap">
    <span class="cacobem-label-inline">PN No.:</span>
    <input class="cacobem-input-inline" data-underline="16" type="text" name="cacobem[pn_no]" value="<?php echo $value("pn_no"); ?>" />
  </div>
  <div class="cacobem-line no-wrap">
    <span class="cacobem-label-inline">Date Granted:</span>
    <input class="cacobem-input-inline" data-underline="15" type="text" name="cacobem[pn_date_granted]" value="<?php echo $value("pn_date_granted"); ?>" />
  </div>
  <div class="cacobem-line no-wrap">
    <span class="cacobem-label-inline">Maturity Date:</span>
    <input class="cacobem-input-inline" data-underline="15" type="text" name="cacobem[pn_maturity_date]" value="<?php echo $value("pn_maturity_date"); ?>" />
  </div>
  <div class="cacobem-line no-wrap">
    <span class="cacobem-label-inline">Amount Granted:</span>
    <input class="cacobem-input-inline" data-underline="16" type="text" name="cacobem[pn_amount_granted]" value="<?php echo $value("pn_amount_granted"); ?>" />
  </div>

  <div class="cacobem-paragraph">
    <input class="cacobem-input-inline" data-underline="15" type="text" name="cacobem[pn_term_value]" value="<?php echo $value("pn_term_value"); ?>" />
    (<input class="cacobem-input-inline" data-underline="5" type="text" name="cacobem[pn_term_unit]" value="<?php echo $value("pn_term_unit"); ?>" />)
    <span class="cacobem-inline-text">days/years</span>
    after date for value received. I/we promise to pay jointly and severally to the order of the Cagayan Cooperative Bank
    Employees MPCI at its office at Diversion Road, San Gabriel Village, Tuguegarao City the Amount of
    <input class="cacobem-input-inline" data-underline="32" type="text" name="cacobem[pn_amount_words]" value="<?php echo $value("pn_amount_words"); ?>" />
    (Php <input class="cacobem-input-inline" data-underline="9" type="text" name="cacobem[pn_amount_php]" value="<?php echo $value("pn_amount_php"); ?>" />)
    Philippine Currency with interest rate of Nine (9%) percent per annum from date hereof until fully paid, according to the following schedule:
  </div>
  <div class="cacobem-line">See attached: (Annex A)</div>
  <div class="cacobem-paragraph indent">
    This Note is secured by
    <input class="cacobem-input-inline" data-underline="22" type="text" name="cacobem[pn_secured_by]" value="<?php echo $value("pn_secured_by"); ?>" />
    executed on
    <input class="cacobem-input-inline" data-underline="10" type="text" name="cacobem[pn_secured_date]" value="<?php echo $value("pn_secured_date"); ?>" />
    in favor of the aforementioned Cooperative Bank Employees MPCI under Doc No:
    <input class="cacobem-input-inline" data-underline="5" type="text" name="cacobem[pn_doc_no]" value="<?php echo $value("pn_doc_no"); ?>" />
    Page No.:
    <input class="cacobem-input-inline" data-underline="5" type="text" name="cacobem[pn_page_no]" value="<?php echo $value("pn_page_no"); ?>" />
    Book No.:
    <input class="cacobem-input-inline" data-underline="5" type="text" name="cacobem[pn_book_no]" value="<?php echo $value("pn_book_no"); ?>" />
    Series of
    <input class="cacobem-input-inline" data-underline="2" type="text" name="cacobem[pn_series_year]" value="<?php echo $value("pn_series_year"); ?>" />.
  </div>

  <div class="cacobem-paragraph indent">
    In case of my default in payments as herein agreed, the entire balance of this note shall become due and demandable, at the option of the holder, and I/We jointly and severally empower and authorize the Cagayan Cooperative Bank Employees MPC (CACOBEM) at their option and without notice to set off or apply to the payment of this note all funds which may be in their hands on deposit or otherwise that belong to me or anymore of us or any other property/ies which may be in their possession or control by virtue of any contract. In order for the CACOBEM to effect the immediately stated action, I/We furthermore state that we have freely and voluntarily waive any and all rights under the provisions of the Philippine Law.
  </div>
  <div class="cacobem-paragraph indent">
    It is further agreed by party hereto, that in case payment shall not be made at maturity he/she shall pay the costs of collection which include penalty and other charges, and attorneys fee in the amount of TEN(10%) PERCENT of the Principal Balance and interest due on this note, but such charge in no event to be less than TEN (10) Pesos.
  </div>
  <div class="cacobem-paragraph indent">
    In case of Judicial Execution of this obligation or any part of it, the debtor waives all his/her rights under the provision of rule 3 section 13 and rule 39 section 39 section 12 of the rule of court.
  </div>

  <div class="cacobem-sign-row">
    <div class="cacobem-sign-block">
      <input class="cacobem-input-inline" data-underline="30" type="text" name="cacobem[pn_borrower_signature]" value="<?php echo $value("pn_borrower_signature"); ?>" />
      <div class="cacobem-sign-label">Name and Signature of Borrower</div>
    </div>
    <div class="cacobem-sign-block">
      <input class="cacobem-input-inline" data-underline="30" type="text" name="cacobem[pn_spouse_signature]" value="<?php echo $value("pn_spouse_signature"); ?>" />
      <div class="cacobem-sign-label">Name and Signature of Spouse</div>
    </div>
  </div>

  <div class="cacobem-sign-row">
    <div class="cacobem-sign-block">
      <input class="cacobem-input-inline" data-underline="30" type="text" name="cacobem[pn_comaker1_signature]" value="<?php echo $value("pn_comaker1_signature"); ?>" />
      <div class="cacobem-sign-label">Name and Signature of Co-maker</div>
    </div>
    <div class="cacobem-sign-block">
      <input class="cacobem-input-inline" data-underline="30" type="text" name="cacobem[pn_comaker2_signature]" value="<?php echo $value("pn_comaker2_signature"); ?>" />
      <div class="cacobem-sign-label">Name and Signature of Co-maker</div>
    </div>
  </div>

  <div class="cacobem-section-title">WITNESSES</div>
  <div class="cacobem-line witness no-wrap">
    <input class="cacobem-input-inline" data-underline="30" type="text" name="cacobem[witness_1]" value="<?php echo $value("witness_1"); ?>" />
    <input class="cacobem-input-inline" data-underline="30" type="text" name="cacobem[witness_2]" value="<?php echo $value("witness_2"); ?>" />
  </div>

  <div class="cacobem-line cacobem-sworn">
    <span class="cacobem-bold">SUBSCRIBED AND SWORN</span> to before me this _____day of _________ at Tuguegarao City, Cagayan.
  </div>
  <div class="cacobem-line cacobem-sworn">Doc. No. _____</div>
  <div class="cacobem-line cacobem-sworn">Page No. _____</div>
  <div class="cacobem-line cacobem-sworn">Book No. _____</div>
  <div class="cacobem-line cacobem-sworn">Series of 2026.</div>
</div>
