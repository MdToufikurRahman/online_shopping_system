<?php
require_once("includes/db_config.php");
session_start();

if (!isset($_SESSION['email'])) {
  header("Location: index.php");
  exit;
}

$error = '';
$success = '';

/* 1️⃣ Get user ID */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  header("Location: users.php");
  exit;
}

$user_id = (int)$_GET['id'];

/* 2️⃣ Fetch existing user */
$stmt = $db->prepare("SELECT * FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
  header("Location: users.php");
  exit;
}

/* 3️⃣ Update user */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $firstname = trim($_POST['firstname']);
  $lastname  = trim($_POST['lastname']);
  $email     = trim($_POST['email']);
  $phone     = trim($_POST['phone']);
  $role      = trim($_POST['role']);
  $status    = (int) $_POST['status'];
  $password  = trim($_POST['password']);

  try {

    /* Password update only if entered */
    if (!empty($password)) {
      $password = md5($password); // legacy
      $stmt = $db->prepare("
        UPDATE users SET
          firstname=?, lastname=?, email=?, phone=?, password=?, role=?, status=?
        WHERE id=?
      ");
      $stmt->bind_param(
        "ssssssii",
        $firstname,
        $lastname,
        $email,
        $phone,
        $password,
        $role,
        $status,
        $user_id
      );
    } else {
      $stmt = $db->prepare("
        UPDATE users SET
          firstname=?, lastname=?, email=?, phone=?, role=?, status=?
        WHERE id=?
      ");
      $stmt->bind_param(
        "sssssii",
        $firstname,
        $lastname,
        $email,
        $phone,
        $role,
        $status,
        $user_id
      );
    }

    $stmt->execute();
    $stmt->close();

    header("Location: users.php");
    exit;
  } catch (mysqli_sql_exception $e) {
    if ($e->getCode() === 1062) {
      $error = "This email is already registered.";
    } else {
      $error = "Update failed. Try again.";
    }
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

                <!-- Back Button -->
                <a
                  href="users.php"
                  class="btn btn-sm btn-outline-light ms-3 me-2 mt-3">
                  <i class="fas fa-arrow-left me-1"></i> Back
                </a>

                <!-- Page Title -->
                <h6 class="text-white text-capitalize ps-3">Update User</h6>
              </div>
            </div>

            <!-- Error -->
            <?php if (!empty($error)): ?>
              <div class="alert alert-danger text-white">
                <?= htmlspecialchars($error) ?>
              </div>
            <?php endif; ?>


            <!-- Table -->
            <div class="card-body px-3 pb-4">
              <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">

                  <form method="post" class="custom-form">

                    <h5 class="mb-4 text-center fw-bold">Update User</h5>

                    <!-- First Name -->
                    <div class="mb-3">
                      <label class="form-label">First Name</label>
                      <input
                        type="text"
                        name="firstname"
                        class="form-control"
                        value="<?= htmlspecialchars($user['firstname']) ?>"
                        placeholder="Enter first name"
                        required>
                    </div>

                    <!-- Last Name -->
                    <div class="mb-3">
                      <label class="form-label">Last Name</label>
                      <input
                        type="text"
                        name="lastname"
                        class="form-control"
                        value="<?= htmlspecialchars($user['lastname']) ?>"
                        placeholder="Enter last name"
                        required>
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                      <label class="form-label">Email</label>
                      <input
                        type="email"
                        name="email"
                        class="form-control"
                        value="<?= htmlspecialchars($user['email']) ?>"
                        placeholder="Enter email address"
                        required>
                    </div>

                    <!-- Phone -->
                    <div class="mb-3">
                      <label class="form-label">Phone</label>
                      <input
                        type="text"
                        name="phone"
                        class="form-control"
                        value="<?= htmlspecialchars($user['phone']) ?>"
                        placeholder=" Enter phone number">
                    </div>

                    <!-- Role -->
                    <div class="mb-3">
                      <label class="form-label">Role</label>
                      <select name="role" class="form-select">
                        <option value="customer" <?= $user['role'] == 'customer' ? 'selected' : '' ?>>Customer</option>
                        <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
                      </select>

                    </div>

                    <!-- Status -->
                    <div class="mb-4">
                      <label class="form-label">Status</label>
                      <select name="status" class="form-select">
                        <option value="1" <?= $user['status'] == 1 ? 'selected' : '' ?>>Active</option>
                        <option value="0" <?= $user['status'] == 0 ? 'selected' : '' ?>>Inactive</option>
                      </select>
                    </div>

                    <div class="mb-3">
                      <label class="form-label">Password</label>
                      <input
                        type="password"
                        name="password"
                        class="form-control"
                        placeholder="Leave blank to keep current password">
                    </div>


                    <!-- Submit -->
                    <div class="d-grid">
                      <button type="submit" class="btn btn-primary">
                        Update User
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