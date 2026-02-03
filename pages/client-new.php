<?php
  require "../bootstrap.php";

  $pageTitle = "Client Management";
  $pageSubtitle = date("l, F j, Y");
  $topActionLabel = "New Client";
  $activePage = "client-new";

  $clientRepo = new ClientRepository();
  $settingsRepo = new SettingsRepository();

  $clientStats = $clientRepo->getDashboardStats();
  $recentClients = $clientRepo->getRecentClients();
  $beneficiaries = $clientRepo->getBeneficiariesForClient(null);
  $loanProducts = $settingsRepo->getLoanProducts();

  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="hero">
    <h2>Keep every borrower profile clean and auditable.</h2>
    <p>
      Review identity, contact details, and risk tags in one flow.
      All changes are tracked by branch.
    </p>
    <div class="stats">
      <div class="stat">
        <strong><?php echo (int) $clientStats["active"]; ?></strong>
        <span>Active borrowers</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $clientStats["pending_verification"]; ?></strong>
        <span>Pending verification</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $clientStats["new_applications"]; ?></strong>
        <span>New applications</span>
      </div>
      <div class="stat">
        <strong><?php echo (int) $clientStats["high_risk"]; ?></strong>
        <span>High-risk flags</span>
      </div>
    </div>
  </section>

  <div class="grid grid-2" style="margin-top: 24px;">
    <section class="card">
      <div class="section-title">
        <h3>Client Intake</h3>
        <div>
          <button class="btn ghost">Save Draft</button>
          <button class="btn">Create Client</button>
        </div>
      </div>

      <div class="tabs">
        <div class="tab active">Basic Info</div>
        <div class="tab">Other Info</div>
        <div class="tab">Beneficiaries</div>
      </div>

      <div class="form-grid">
        <div>
          <label>Client Type</label>
          <select>
            <option>Individual</option>
            <option>Business</option>
          </select>
        </div>
        <div>
          <label>Branch</label>
          <input type="text" />
        </div>
        <div>
          <label>Last Name</label>
          <input type="text" placeholder="Dela Cruz" />
        </div>
        <div>
          <label>First Name</label>
          <input type="text" placeholder="Maria" />
        </div>
        <div>
          <label>Middle Name</label>
          <input type="text" placeholder="G." />
        </div>
        <div>
          <label>Birthdate</label>
          <input type="date" />
        </div>
        <div>
          <label>Birthplace</label>
          <input type="text" placeholder="Cebu City" />
        </div>
        <div>
          <label>Nationality</label>
          <input type="text" placeholder="Filipino" />
        </div>
        <div>
          <label>Gender</label>
          <select>
            <option>Female</option>
            <option>Male</option>
          </select>
        </div>
        <div>
          <label>Civil Status</label>
          <select>
            <option>Single</option>
            <option>Married</option>
          </select>
        </div>
        <div>
          <label>Email Address</label>
          <input type="email" placeholder="name@email.com" />
        </div>
        <div>
          <label>Facebook Account</label>
          <input type="text" placeholder="facebook.com/profile" />
        </div>
      </div>

      <div class="divider"></div>

      <div class="form-grid">
        <div>
          <label>Cellphone No. 1</label>
          <input type="text" placeholder="+63 9xx xxx xxxx" />
        </div>
        <div>
          <label>Cellphone No. 2</label>
          <input type="text" placeholder="+63 9xx xxx xxxx" />
        </div>
        <div>
          <label>Landline No. 1</label>
          <input type="text" placeholder="(02) 8xxx xxxx" />
        </div>
        <div>
          <label>Landline No. 2</label>
          <input type="text" placeholder="(02) 8xxx xxxx" />
        </div>
        <div>
          <label>Present Address</label>
          <input type="text" placeholder="Street, Barangay" />
        </div>
        <div>
          <label>Permanent Address</label>
          <input type="text" placeholder="Street, Barangay" />
        </div>
        <div>
          <label>Emergency Contact</label>
          <input type="text" placeholder="Contact person" />
        </div>
        <div>
          <label>Contact No.</label>
          <input type="text" placeholder="09xx xxx xxxx" />
        </div>
      </div>

      <div class="divider"></div>

      <div class="form-grid">
        <div>
          <label>Borrower ID</label>
          <input type="text" placeholder="BR-000245" />
        </div>
        <div>
          <label>ID Number</label>
          <input type="text" placeholder="0000-0000-0000" />
        </div>
        <div>
          <label>Secondary ID</label>
          <input type="text" placeholder="Driver License" />
        </div>
        <div>
          <label>Secondary ID Expiry</label>
          <input type="date" />
        </div>
      </div>

      <div class="divider"></div>

      <div>
        <label>Risk Category</label>
        <div class="tag-row" style="margin-top: 8px;">
          <span class="tag">Undefined</span>
          <span class="tag">VIP</span>
          <span class="tag">DOSRI</span>
          <span class="tag">RPT</span>
          <span class="tag">PEP</span>
        </div>
      </div>
    </section>

    <div class="grid" style="gap: 16px;">
      <section class="card soft">
        <div class="section-title">
          <h3>Other Info</h3>
          <button class="btn ghost">Edit</button>
        </div>
        <div class="form-grid">
          <div>
            <label>Height (Meters)</label>
            <input type="text" placeholder="1.62" />
          </div>
          <div>
            <label>Height (ft/in)</label>
            <input type="text" placeholder="5'4" />
          </div>
          <div>
            <label>Weight (Kg)</label>
            <input type="text" placeholder="52" />
          </div>
          <div>
            <label>Weight (Lbs)</label>
            <input type="text" placeholder="115" />
          </div>
          <div>
            <label>Source of Fund</label>
            <input type="text" placeholder="Employment" />
          </div>
          <div>
            <label>Employment Details</label>
            <input type="text" placeholder="Branch Manager, 4 yrs" />
          </div>
          <div>
            <label>Mother's Maiden Last Name</label>
            <input type="text" placeholder="Santos" />
          </div>
          <div>
            <label>Mother's Maiden First Name</label>
            <input type="text" placeholder="Lourdes" />
          </div>
        </div>
      </section>

      <section class="card soft beneficiaries">
        <div class="section-title">
          <h3>Beneficiaries</h3>
          <button class="btn ghost">Add Existing Client</button>
        </div>
        <table>
          <thead>
            <tr>
              <th>#</th>
              <th>Relation</th>
              <th>First Name</th>
              <th>Middle Name</th>
              <th>Last Name</th>
              <th>Birthdate</th>
              <th>Gender</th>
            </tr>
          </thead>
          <tbody>
            <?php if (empty($beneficiaries)) : ?>
              <tr>
                <td colspan="7" class="empty-row">No beneficiaries added yet.</td>
              </tr>
            <?php else : ?>
              <?php foreach ($beneficiaries as $beneficiary) : ?>
                <tr>
                  <td><?php echo htmlspecialchars((string) $beneficiary["index"]); ?></td>
                  <td><?php echo htmlspecialchars((string) $beneficiary["relation"]); ?></td>
                  <td><?php echo htmlspecialchars((string) $beneficiary["first_name"]); ?></td>
                  <td><?php echo htmlspecialchars((string) $beneficiary["middle_name"]); ?></td>
                  <td><?php echo htmlspecialchars((string) $beneficiary["last_name"]); ?></td>
                  <td><?php echo htmlspecialchars((string) $beneficiary["birthdate"]); ?></td>
                  <td><?php echo htmlspecialchars((string) $beneficiary["gender"]); ?></td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </section>
    </div>
  </div>

  <div class="grid grid-2" style="margin-top: 24px;">
    <section class="card">
      <div class="section-title">
        <h3>Loan Products</h3>
        <button class="btn ghost">Manage Products</button>
      </div>
      <?php if (empty($loanProducts)) : ?>
        <div class="empty-row">No loan products configured yet.</div>
      <?php else : ?>
        <div class="product-grid">
          <?php foreach ($loanProducts as $product) : ?>
            <div class="product">
              <div class="badge"></div>
              <strong><?php echo htmlspecialchars((string) $product["name"]); ?></strong>
              <span>Interest Rate: <?php echo htmlspecialchars((string) $product["interest_rate"]); ?></span>
              <span>Service Charge: <?php echo htmlspecialchars((string) $product["service_charge"]); ?></span>
              <span class="status"><?php echo htmlspecialchars((string) $product["status"]); ?></span>
              <button class="cta">Select</button>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>

    <section class="list-panel">
      <header>
        <strong>Recent Clients</strong>
        <a href="#">View All</a>
      </header>
      <ul>
        <?php if (empty($recentClients)) : ?>
          <li class="empty-row">No recent clients yet.</li>
        <?php else : ?>
          <?php foreach ($recentClients as $client) : ?>
            <li>
              <span><?php echo htmlspecialchars((string) $client["name"]); ?></span>
              <a href="<?php echo htmlspecialchars((string) $client["edit_url"]); ?>">Edit</a>
            </li>
          <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </section>
  </div>
</main>
<?php require "../partials/footer.php"; ?>
