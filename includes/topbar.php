<nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">

    <!-- Navbar Brand-->

    <a class="navbar-brand ps-3" href="dashboard.php"><?= getSettingValue('admin_settings', 'admin') ?></a>

    <!-- Sidebar Toggle-->

    <button class="btn shadow-none btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>

    <!-- Navbar Search-->

    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">



    </form>

    <!-- Navbar-->

    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">

        <li class="nav-item dropdown">

            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">

                <li><a class="dropdown-item" href="settings.php">Settings</a></li>

                <li><a class="dropdown-item" href="profile.php">Admin Panel Settings</a></li>

                <li>
                    <hr class="dropdown-divider" />
                </li>

                <form action="<?php echo $_SERVER['localhost']; ?>" method="POST">

                    <button class="dropdown-item" name="logout" type="submit">

                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>

                        Logout

                    </button>

                </form>

            </ul>

        </li>

    </ul>

</nav>







<div id="layoutSidenav">





    <?php



    if (isset($_POST['logout'])) {

        session_destroy();



        header("Location: index.php");
    }
