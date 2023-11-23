<div id="layoutSidenav_nav">

    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">

        <div class="sb-sidenav-menu">

            <div class="nav">

                <div class="sb-sidenav-menu-heading">Core</div>

                <a class="nav-link" href="dashboard.php">

                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>

                    Dashboard

                </a>

                <div class="sb-sidenav-menu-heading">Interface</div>

                <a class="nav-link" href="users.php">

                    <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>

                    Users

                </a>

                <a class="nav-link" href="category.php">

                    <div class="sb-nav-link-icon"><i class="fa-brands fa-quinscape"></i></div>

                    Category

                </a>

                <a class="nav-link" href="articles.php">

                    <div class="sb-nav-link-icon"><i class="fa-solid fa-q"></i></div>

                    Articles

                </a>

                <a class="nav-link" href="video-articles.php">

                    <div class="sb-nav-link-icon"><i class="fa-solid fa-question"></i></div>

                    Video Articles

                </a>

                <a class="nav-link" href="notifications.php">

                    <div class="sb-nav-link-icon"><i class="fa-solid fa-bell"></i></div>

                    Notification

                </a>

                <div class="sb-sidenav-menu-heading">Settings</div>

                <a class="nav-link" href="settings.php">

                    <div class="sb-nav-link-icon"><i class="fa-solid fa-gear"></i></div>

                    Settings

                </a>

                <a class="nav-link" href="appinfo.php">

                    <div class="sb-nav-link-icon"><i class="fa-solid fa-circle-info"></i></div>

                    App Info

                </a>

            </div>

        </div>

        <div class="sb-sidenav-footer">

            <div class="small">Logged in as:</div>

            <?= $_SESSION['AdminLoginId']; ?>

        </div>

    </nav>

</div>



<div id="layoutSidenav_content">