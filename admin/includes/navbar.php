<nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-sm border-radius-xl bg-white" id="navbarBlur" data-scroll="true">
  <div class="container-fluid py-2 px-3">

    <!-- Left: Breadcrumb + Page Title -->
    <div class="d-flex flex-column">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb bg-transparent mb-1 p-0">
          <li class="breadcrumb-item text-sm">
            <a class="opacity-5 text-dark" href="dashboard.php">Pages</a>
          </li>
          <li class="breadcrumb-item text-sm text-dark active" aria-current="page">
            Dashboard
          </li>
        </ol>
      </nav>

      <!-- Page Heading -->
      <h6 class="mb-0 fw-bold">Dashboard Overview</h6>
    </div>

    <!-- Right: Actions -->
    <div class="collapse navbar-collapse justify-content-end">

      <!-- Search -->
      <form class="d-flex me-3">
        <div class="input-group input-group-outline">
          <input type="text" class="form-control" placeholder="Search...">
        </div>
      </form>

      <!-- Icons -->
      <ul class="navbar-nav align-items-center">

        <!-- Notifications -->
        <li class="nav-item px-3">
          <a class="nav-link text-body p-0" href="#">
            <i class="fa-solid fa-bell cursor-pointer"></i>
          </a>
        </li>

        <!-- Profile -->
        <li class="nav-item dropdown pe-2">
          <a href="#" class="nav-link text-body p-0" data-bs-toggle="dropdown">
            <i class="fa-solid fa-user-circle cursor-pointer"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-end px-2 py-3">
            <li>
              <a class="dropdown-item border-radius-md" href="profile.php">Profile</a>
            </li>
            <li>
              <a class="dropdown-item border-radius-md text-danger" href="logout.php">Logout</a>
            </li>
          </ul>
        </li>

      </ul>
    </div>

  </div>
</nav>