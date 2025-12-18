<?php
include_once("../includes/db_config.php");

session_start();
if (!isset($_SESSION['email'])) {
  header("../Location: index.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <title>
    Material Dashboard 3 by Creative Tim
  </title>
  <!--     Fonts and icons     -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
  <!-- Nucleo Icons -->
  <link href="../assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="../assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />
</head>

<body class="g-sidenav-show  bg-gray-100">

  <!-- Sidebar -->
  <?php include_once("../includes/sidebar.php") ?>

  <!-- Main Content -->
  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <?php include_once("../includes/navbar.php") ?>
    <!-- End Navbar -->
    <div class="container-fluid py-2">
      <div class="row">
        <div class="col-12">
          <div class="card my-4">

            <!-- Card Header -->
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-dark shadow-dark border-radius-lg pt-4 pb-3">
                <h6 class="text-white text-capitalize ps-3">Category Table</h6>
              </div>
            </div>

            <!-- Card Body -->
            <div class="d-flex justify-content-end align-items-center flex-wrap gap-2 mb-3 px-3">
              <a href="entry.php" class="btn btn-success my-2">+ Add Category</a>
            </div>

            <!-- Card Body -->
            <div class="card-body px-0 pb-2 px-3">
              <div class="table-responsive p-0">

                <?php if (!empty($_SESSION['success'])): ?>
                  <div class="alert alert-success text-white">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                  </div>
                  <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (!empty($_SESSION['error'])): ?>
                  <div class="alert alert-danger text-white">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                  </div>
                  <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <div class="table-responsive">
                  <table class="table align-items-center mb-0">
                    <thead>
                      <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th colspan="3">Action</th>
                      </tr>
                    </thead>

                    <?php
                    $res = $db->query("SELECT * FROM categories");
                    while ($row = $res->fetch_object()):
                    ?>
                      <tr>
                        <td><?= $row->id ?></td>
                        <td><?= $row->name ?></td>
                        <td><?= $row->description ?></td>
                        <td><?= $row->status ?></td>
                        <td>
                          <a class="btn btn-sm btn-primary" href="edit.php?id=<?= $row->id ?>">Edit</a>
                          <a class="btn btn-sm btn-success" href="toggle.php?id=<?= $row->id ?>">Toggle</a>
                          <a class="btn btn-sm btn-danger" href="delete.php?id=<?= $row->id ?>">Delete</a>
                        </td>
                      </tr>
                    <?php endwhile; ?>
                  </table>

                  <a href="entry.php">Add Category</a>
                  <?php $db->close(); ?>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <?php include_once("includes/footer.php") ?>
      </div>
  </main>


  <!--   Core JS Files   -->
  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/core/bootstrap.min.js"></script>
  <script src="assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Material Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="assets/js/material-dashboard.min.js?v=3.2.0"></script>
</body>

</html>