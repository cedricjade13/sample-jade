<?php
include_once "templates/header.php";
?>

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Dashboard</h1>
  </div><!-- End Page Title -->

  <section class="section dashboard">
    <div class="row">

      <!-- Card -->
      <div class="col-lg-4">
        <div class="card info-card">
          <div class="card-body">
            <h5 class="card-title">Admin Panel <span>| Access</span></h5>
            <p class="card-text">Click the button below to go to the admin dashboard.</p>
            <a href="admin/index.php" class="btn btn-primary">Go to Admin</a>
          </div>
        </div>
      </div><!-- End Card -->

    </div>
  </section>

</main><!-- End #main -->

<?php
include_once "templates/footer.php";
?>
