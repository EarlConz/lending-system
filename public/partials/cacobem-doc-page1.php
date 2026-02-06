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
