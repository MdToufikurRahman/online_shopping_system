<?php
require_once("includes/db_config.php");
session_start();

/* 🔐 Auth check */
if (!isset($_SESSION['email'], $_SESSION['id'])) {
  header("Location: index.php");
  exit;
}

$error = '';
$success = '';

$user_id = (int) $_SESSION['id'];

/* 1️⃣ Fetch logged-in user */
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
  session_destroy();
  header("Location: index.php");
  exit;
}

/* 2️⃣ Update profile */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $firstname = trim($_POST['firstname']);
  $lastname  = trim($_POST['lastname']);
  $phone     = trim($_POST['phone']);
  $password  = trim($_POST['password']);

  try {

    if (!empty($password)) {
      $password = md5($password); // legacy
      $stmt = $db->prepare("
        UPDATE users 
        SET firstname=?, lastname=?, phone=?, password=?
        WHERE id=?
      ");
      $stmt->bind_param("ssssi", $firstname, $lastname, $phone, $password, $user_id);
    } else {
      $stmt = $db->prepare("
        UPDATE users 
        SET firstname=?, lastname=?, phone=?
        WHERE id=?
      ");
      $stmt->bind_param("sssi", $firstname, $lastname, $phone, $user_id);
    }

    $stmt->execute();
    $stmt->close();

    $success = "Profile updated successfully.";

    /* reload updated user */
    $stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();
  } catch (mysqli_sql_exception $e) {
    $error = "Update failed. Try again.";
  }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="assets/img/favicon.png">
  <title>Add User</title>

  <!-- Fonts and icons -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />

  <!-- Nucleo Icons -->
  <link href="assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="assets/css/nucleo-svg.css" rel="stylesheet" />

  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigi="anonymous"></script>

  <!-- Material Icons -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

  <!-- CSS Files -->
  <link id="pagestyle" href="assets/css/material-dashboard.css?v=3.2.0" rel="stylesheet" />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

  <!-- Custom CSS -->
  <style>
    /* Darker shadow and proper input borders */
    .custom-form {
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4);
      /* darker shadow */
      border-radius: 0.5rem;
      padding: 2rem;
      background-color: #fff;
    }

    .custom-form .form-control,
    .custom-form .form-select,
    .custom-form textarea {
      border: 1px solid #ced4da;
      /* ensure visible border */
      border-radius: 0.375rem;
      padding-left: 10px;
    }

    .custom-form .form-control:focus,
    .custom-form .form-select:focus,
    .custom-form textarea:focus {
      border-color: #495057;
      box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    /* Image preview */
    #imgPreview {
      display: block;
      max-width: 200px;
      max-height: 200px;
      margin-top: 10px;
      border: 1px solid #ced4da;
      border-radius: 0.375rem;
    }
  </style>
</head>

<body class="g-sidenav-show  bg-gray-100">

  <?php include_once("includes/sidebar.php") ?>

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
    <!-- Navbar -->
    <?php include_once("includes/navbar.php") ?>

    <!-- Content -->
    <div class="container-fluid py-2">
      <div class="row">
        <div class="col-12">
          <div class="card my-4">

            <!-- Card Header -->
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
              <div class="bg-gradient-dark shadow-dark border-radius-lg pt-3 pb-3 d-flex align-items-center">
                <!-- Page Title -->
                <h6 class="text-white text-capitalize ps-3">My Profile</h6>
              </div>
            </div>

            <!-- Error -->
            <?php if (!empty($error)): ?>
              <div class="alert alert-danger text-white">
                <?= htmlspecialchars($error) ?>
              </div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
              <div class="alert alert-success text-white">
                <?= htmlspecialchars($success) ?>
              </div>
            <?php endif; ?>



            <!-- Table -->
            <div class="card-body px-3 pb-4">
              <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">

                  <form method="post" class="custom-form">

                    <h5 class="mb-4 text-center fw-bold">My Profile</h5>

                    <div class="mb-3">
                      <label class="form-label">First Name</label>
                      <input type="text" name="firstname" class="form-control"
                        value="<?= htmlspecialchars($user['firstname']) ?>" required>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Last Name</label>
                      <input type="text" name="lastname" class="form-control"
                        value="<?= htmlspecialchars($user['lastname']) ?>" required>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Email</label>
                      <input type="email" class="form-control"
                        value="<?= htmlspecialchars($user['email']) ?>" disabled>
                      <small class="text-muted">Email cannot be changed</small>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Phone</label>
                      <input type="text" name="phone" class="form-control"
                        value="<?= htmlspecialchars($user['phone']) ?>">
                    </div>

                    <div class="mb-3">
                      <label class="form-label">New Password</label>
                      <input type="password" name="password" class="form-control"
                        placeholder="Leave blank to keep current password">
                    </div>

                    <div class="d-grid">
                      <button type="submit" class="btn btn-primary">
                        Update Profile
                      </button>
                    </div>

                  </form>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Footer -->
    <?php include_once("includes/footer.php") ?>
  </main>

  <!-- Core JS -->
  <script src="assets/js/core/popper.min.js"></script>
  <script src="assets/js/core/bootstrap.min.js"></script>
  <script src="assets/js/material-dashboard.min.js?v=3.2.0"></script>

</body>

</html>