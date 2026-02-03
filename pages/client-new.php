<?php
  $pageTitle = "Client Management";
  $pageSubtitle = "Tuesday, February 3, 2026";
  $topActionLabel = "New Client";
  $activePage = "client-new";
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
        <strong>124</strong>
        <span>Active borrowers</span>
      </div>
      <div class="stat">
        <strong>16</strong>
        <span>Pending verification</span>
      </div>
      <div class="stat">
        <strong>8</strong>
        <span>New applications</span>
      </div>
      <div class="stat">
        <strong>2</strong>
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
          <input type="text" value="002 - Main Branch" />
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
            <tr>
              <td>1</td>
              <td>Spouse</td>
              <td>Anna</td>
              <td>M.</td>
              <td>Dela Cruz</td>
              <td>1994-08-01</td>
              <td>Female</td>
            </tr>
            <tr>
              <td>2</td>
              <td>Child</td>
              <td>Marco</td>
              <td>R.</td>
              <td>Dela Cruz</td>
              <td>2018-11-21</td>
              <td>Male</td>
            </tr>
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
      <div class="product-grid">
        <div class="product">
          <div class="badge"></div>
          <strong>Salary Loan</strong>
          <span>Interest Rate: 1.8%</span>
          <span>Service Charge: 0.5%</span>
          <span class="status">Active</span>
          <button class="cta">Select</button>
        </div>
        <div class="product">
          <div class="badge"></div>
          <strong>Business Loan</strong>
          <span>Interest Rate: 2.1%</span>
          <span>Service Charge: 0.6%</span>
          <span class="status">Active</span>
          <button class="cta">Select</button>
        </div>
        <div class="product">
          <div class="badge"></div>
          <strong>Emergency Loan</strong>
          <span>Interest Rate: 1.5%</span>
          <span>Service Charge: 0.4%</span>
          <span class="status">Active</span>
          <button class="cta">Select</button>
        </div>
        <div class="product">
          <div class="badge"></div>
          <strong>Education Loan</strong>
          <span>Interest Rate: 1.2%</span>
          <span>Service Charge: 0.3%</span>
          <span class="status">Active</span>
          <button class="cta">Select</button>
        </div>
      </div>
    </section>

    <section class="list-panel">
      <header>
        <strong>Recent Clients</strong>
        <a href="#">View All</a>
      </header>
      <ul>
        <li>
          <span>Mariel Dela Cruz</span>
          <a href="#">Edit</a>
        </li>
        <li>
          <span>James Torres</span>
          <a href="#">Edit</a>
        </li>
        <li>
          <span>Lea Domingo</span>
          <a href="#">Edit</a>
        </li>
        <li>
          <span>Joan Reyes</span>
          <a href="#">Edit</a>
        </li>
      </ul>
    </section>
  </div>
</main>
<?php require "../partials/footer.php"; ?>
