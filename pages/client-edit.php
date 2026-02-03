<?php
  $pageTitle = "Client Management";
  $pageSubtitle = "Tuesday, February 3, 2026";
  $topActionLabel = "Edit Client";
  $activePage = "client-edit";
  require "../partials/head.php";
  require "../partials/sidebar.php";
?>
<main class="main page">
  <?php require "../partials/topbar.php"; ?>

  <section class="hero">
    <h2>Update borrower profiles with controlled edits.</h2>
    <p>Track edits, verify documents, and keep contact details current.</p>
    <div class="stats">
      <div class="stat">
        <strong>38</strong>
        <span>Edits today</span>
      </div>
      <div class="stat">
        <strong>9</strong>
        <span>Pending review</span>
      </div>
      <div class="stat">
        <strong>4</strong>
        <span>ID updates</span>
      </div>
      <div class="stat">
        <strong>1</strong>
        <span>Risk escalations</span>
      </div>
    </div>
  </section>

  <div class="grid grid-2" style="margin-top: 24px;">
    <section class="card">
      <div class="section-title">
        <h3>Edit Client Record</h3>
        <div>
          <button class="btn ghost">Save Changes</button>
          <button class="btn">Submit Review</button>
        </div>
      </div>

      <div class="form-grid">
        <div>
          <label>Client Name</label>
          <input type="text" value="Maria Dela Cruz" />
        </div>
        <div>
          <label>Borrower ID</label>
          <input type="text" value="BR-000245" />
        </div>
        <div>
          <label>Primary Phone</label>
          <input type="text" value="+63 917 555 2445" />
        </div>
        <div>
          <label>Email Address</label>
          <input type="email" value="maria@email.com" />
        </div>
        <div>
          <label>Risk Category</label>
          <select>
            <option>Undefined</option>
            <option>VIP</option>
            <option>DOSRI</option>
            <option>RPT</option>
            <option>PEP</option>
          </select>
        </div>
        <div>
          <label>Verification Status</label>
          <select>
            <option>Verified</option>
            <option>Needs Follow-up</option>
          </select>
        </div>
        <div>
          <label>Present Address</label>
          <input type="text" value="109 Rizal St., Cebu" />
        </div>
        <div>
          <label>Emergency Contact</label>
          <input type="text" value="Anna Dela Cruz" />
        </div>
      </div>

      <div class="divider"></div>

      <div class="form-grid">
        <div>
          <label>Last Review Date</label>
          <input type="date" value="2026-01-30" />
        </div>
        <div>
          <label>Assigned Officer</label>
          <input type="text" value="J. Ramirez" />
        </div>
      </div>
    </section>

    <section class="list-panel">
      <header>
        <strong>Recent Clients Needing Updates</strong>
        <a href="#">View Queue</a>
      </header>
      <ul>
        <li>
          <span>Joel Santos</span>
          <span class="status-pill warn">Missing IDs</span>
        </li>
        <li>
          <span>Grace Lim</span>
          <span class="status-pill">Email Update</span>
        </li>
        <li>
          <span>Marvin Cruz</span>
          <span class="status-pill">Address Review</span>
        </li>
        <li>
          <span>Celia Bautista</span>
          <span class="status-pill ok">Ready</span>
        </li>
      </ul>
    </section>
  </div>
</main>
<?php require "../partials/footer.php"; ?>
