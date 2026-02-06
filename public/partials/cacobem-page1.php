<?php
  if (!isset($cacobemValues) || !is_array($cacobemValues)) {
    $cacobemValues = [];
  }
  $cacobemShowAuthorizationDuplicate = $cacobemShowAuthorizationDuplicate ?? false;
  $value = static function (string $key) use ($cacobemValues): string {
    return htmlspecialchars((string) ($cacobemValues[$key] ?? ""));
  };
?>
<div class="cacobem-page">
  <div class="cacobem-title">LOAN APPLICATION</div>

  <div class="cacobem-row">
    <span class="cacobem-label">Application Date:</span>
    <input class="cacobem-input cacobem-input-date" type="date" name="cacobem[application_date]" value="<?php echo $value("application_date"); ?>" />
  </div>

  <div class="cacobem-grid cols-4">
    <div class="cacobem-field">
      <label class="cacobem-label">Name:</label>
      <input class="cacobem-input" type="text" name="cacobem[borrower_name]" value="<?php echo $value("borrower_name"); ?>" />
    </div>
    <div class="cacobem-field">
      <label class="cacobem-label">Age:</label>
      <input class="cacobem-input small" type="text" name="cacobem[borrower_age]" value="<?php echo $value("borrower_age"); ?>" />
    </div>
    <div class="cacobem-field">
      <label class="cacobem-label">CTC No.:</label>
      <input class="cacobem-input" type="text" name="cacobem[ctc_no]" value="<?php echo $value("ctc_no"); ?>" />
    </div>
    <div class="cacobem-field">
      <label class="cacobem-label">Date issued:</label>
      <input class="cacobem-input cacobem-input-date" type="date" name="cacobem[ctc_date_issued]" value="<?php echo $value("ctc_date_issued"); ?>" />
    </div>
  </div>

  <div class="cacobem-grid cols-3">
    <div class="cacobem-field">
      <label class="cacobem-label">Birthdate:</label>
      <input class="cacobem-input cacobem-input-date" type="date" name="cacobem[birthdate]" value="<?php echo $value("birthdate"); ?>" />
    </div>
    <div class="cacobem-field">
      <label class="cacobem-label">Place of Birth:</label>
      <input class="cacobem-input" type="text" name="cacobem[birth_place]" value="<?php echo $value("birth_place"); ?>" />
    </div>
    <div class="cacobem-field">
      <label class="cacobem-label">Place Issued:</label>
      <input class="cacobem-input" type="text" name="cacobem[place_issued]" value="<?php echo $value("place_issued"); ?>" />
    </div>
  </div>

  <div class="cacobem-grid cols-3">
    <div class="cacobem-field">
      <label class="cacobem-label">Name of Spouse:</label>
      <input class="cacobem-input" type="text" name="cacobem[spouse_name]" value="<?php echo $value("spouse_name"); ?>" />
    </div>
    <div class="cacobem-field">
      <label class="cacobem-label">Age:</label>
      <input class="cacobem-input small" type="text" name="cacobem[spouse_age]" value="<?php echo $value("spouse_age"); ?>" />
    </div>
    <div class="cacobem-field">
      <label class="cacobem-label">No of Children:</label>
      <input class="cacobem-input small" type="text" name="cacobem[children_count]" value="<?php echo $value("children_count"); ?>" />
    </div>
  </div>

  <div class="cacobem-row">
    <span class="cacobem-label">Address:</span>
    <input class="cacobem-input wide" type="text" name="cacobem[address]" value="<?php echo $value("address"); ?>" />
  </div>

  <div class="cacobem-grid cols-2">
    <div class="cacobem-field">
      <label class="cacobem-label">Amount applied for:</label>
      <input class="cacobem-input" type="text" name="cacobem[amount_applied]" value="<?php echo $value("amount_applied"); ?>" />
    </div>
    <div class="cacobem-field">
      <label class="cacobem-label">Interest Rate:</label>
      <div class="cacobem-static">Nine (9%) percent per annum</div>
    </div>
  </div>

  <div class="cacobem-row">
    <span class="cacobem-label">Specific Purpose:</span>
    <input class="cacobem-input wide" type="text" name="cacobem[specific_purpose]" value="<?php echo $value("specific_purpose"); ?>" />
  </div>

  <div class="cacobem-signatures">
    <div class="cacobem-sign-field">
      <input class="cacobem-input" type="text" name="cacobem[borrower_signature]" value="<?php echo $value("borrower_signature"); ?>" />
      <div class="cacobem-sign-label">Name and Signature of Borrower</div>
    </div>
    <div class="cacobem-sign-field">
      <input class="cacobem-input" type="text" name="cacobem[spouse_signature]" value="<?php echo $value("spouse_signature"); ?>" />
      <div class="cacobem-sign-label">Name and Signature of Spouse</div>
    </div>
  </div>

  <div class="cacobem-signatures">
    <div class="cacobem-sign-field">
      <input class="cacobem-input" type="text" name="cacobem[comaker1_signature]" value="<?php echo $value("comaker1_signature"); ?>" />
      <div class="cacobem-sign-label">Name and Signature of Co-maker</div>
    </div>
    <div class="cacobem-sign-field">
      <input class="cacobem-input" type="text" name="cacobem[comaker2_signature]" value="<?php echo $value("comaker2_signature"); ?>" />
      <div class="cacobem-sign-label">Name and Signature of Co-maker</div>
    </div>
  </div>

  <div class="cacobem-authorization">
    <div class="cacobem-title small">A U T H O R I Z A T I O N</div>
    <div class="cacobem-row">
      <span class="cacobem-label">Date:</span>
      <input class="cacobem-input cacobem-input-date" type="date" name="cacobem[authorization_date]" value="<?php echo $value("authorization_date"); ?>" />
    </div>
    <div class="cacobem-row">
      I hereby authorize Cooperative Bank of Cagayan to collect from my salary every
      <span class="cacobem-inline">
        <label><input type="radio" name="cacobem[authorization_schedule]" value="15th" <?php echo $value("authorization_schedule") === "15th" ? "checked" : ""; ?> /> 15th</label>
        <label><input type="radio" name="cacobem[authorization_schedule]" value="30th" <?php echo $value("authorization_schedule") === "30th" ? "checked" : ""; ?> /> 30th</label>
        <label><input type="radio" name="cacobem[authorization_schedule]" value="15/30th" <?php echo $value("authorization_schedule") === "15/30th" ? "checked" : ""; ?> /> 15/30th</label>
      </span>
      the sum of
      <input class="cacobem-input" type="text" name="cacobem[authorization_amount_words]" value="<?php echo $value("authorization_amount_words"); ?>" />
      (Php
      <input class="cacobem-input small" type="text" name="cacobem[authorization_amount_php]" value="<?php echo $value("authorization_amount_php"); ?>" />
      )
      plus interest and insurance if any, in case the loan is long term, until fully paid.
    </div>
    <div class="cacobem-row">
      <input class="cacobem-input" type="text" name="cacobem[authorization_borrower_signature]" value="<?php echo $value("authorization_borrower_signature"); ?>" />
      <div class="cacobem-sign-label">Borrower</div>
    </div>
  </div>

  <?php if ($cacobemShowAuthorizationDuplicate) : ?>
    <div class="cacobem-authorization duplicate">
      <div class="cacobem-title small">A U T H O R I Z A T I O N</div>
      <div class="cacobem-row">
        <span class="cacobem-label">Date:</span>
        <span class="cacobem-value"><?php echo $value("authorization_date"); ?></span>
      </div>
      <div class="cacobem-row">
        I hereby authorize Cooperative Bank of Cagayan to collect from my salary every
        <span class="cacobem-value"><?php echo $value("authorization_schedule"); ?></span>
        the sum of
        <span class="cacobem-value"><?php echo $value("authorization_amount_words"); ?></span>
        (Php <span class="cacobem-value"><?php echo $value("authorization_amount_php"); ?></span>)
        plus interest and insurance if any, in case the loan is long term, until fully paid.
      </div>
      <div class="cacobem-row">
        <span class="cacobem-value"><?php echo $value("authorization_borrower_signature"); ?></span>
        <div class="cacobem-sign-label">Borrower</div>
      </div>
    </div>
  <?php endif; ?>
</div>
