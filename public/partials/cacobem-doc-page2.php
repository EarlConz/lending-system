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
