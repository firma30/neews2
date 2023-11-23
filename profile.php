<?php

session_start();



require("config/conn.php");

require("functions.php");

include("includes/header.php");

include("includes/topbar.php");

include("includes/sidebar.php");





session_regenerate_id(true);



if (!isset($_SESSION['AdminLoginId'])) {

    header("Location: index.php");
}

?>







<main>

    <div class="container-fluid px-4">



        <h1 class="mt-4 dash-heading">Admin Panel Settings</h1>

        <ol class="breadcrumb mb-4">

            <li class="breadcrumb-item active">Admin Panel Settings</li>

        </ol>







        <div class="row my-4">

            <div class="col-12 add-category-box my-4">

                <h1 class="category-heading">Change Login Details</h1>

                <div class="categort-add-container">

                    <form action="code.php" method="POST" enctype="multipart/form-data">

                        <div class="d-flex align-items-start flex-wrap">

                            <div class="mb-3 mx-2">

                                <label for="username" class="form-label">Username</label>

                                <input type="text" value="<?= getAdminLoginDetails('admin_login', 'username'); ?>" class="form-control" id="username" name="username">

                            </div>

                            <div class="mb-3 mx-2">

                                <label for="password" class="form-label">Password</label>

                                <input type="text" value="<?= getAdminLoginDetails('admin_login', 'password'); ?>" class="form-control" id="password" name="password">

                            </div>



                        </div>

                        <button type="submit" name="save_admin_panel_login_details" class="add-category-btn  mb-3 mx-2">Save</button>

                    </form>

                </div>



            </div>

            <div class="col-12 add-category-box my-2">

                <h1 class="category-heading">Admin Panel Settings</h1>

                <div class="categort-add-container">

                    <form action="code.php" method="POST" enctype="multipart/form-data">

                        <div class="d-flex align-items-start flex-wrap">

                            <div class="mb-3 mx-2">

                                <label for="newDashboardIcon" class="form-label">Dashboard Icon</label>

                                <br>

                                <input type="file" class="form-control" id="newDashboardIcon" name="newDashboardIcon">



                                <input type="hidden" value="<?= getSettingValue('admin_settings', 'Dashboard Icon') ?>" class="form-control" id="oldDashboardImage" name="oldDashboardImage">



                                <label for="newDashboardIcon" class="form-label mt-2">Current Dashboard Icon</label>

                                <br>



                                <img src="<?= getSettingValue('admin_settings', 'Dashboard Icon') ?>" class="mx-4" style="width: 250px;" alt="Dashboard Icon">

                            </div>

                            <div class="mb-3 mx-2">

                                <label for="adminPanelSetting" class="form-label">Admin Panel Name</label>

                                <input type="text" value="<?= getSettingValue('admin_settings', 'Admin Panel Name') ?>" class="form-control" id="adminPanelSetting" name="adminPanelSetting">

                            </div>

                            <div class="mb-3 mx-2">

                                <label for="newLoginIconIcon" class="form-label">Login Image</label>

                                <br>

                                <input type="file" class="form-control" id="newLoginIconIcon" name="newLoginIconIcon">



                                <input type="hidden" value="<?= getSettingValue('admin_settings', 'Login Image') ?>" class="form-control" id="oldLoginImage" name="oldLoginImage">



                                <label for="currentLoginIcon" class="form-label mt-2">Current Login Icon</label>

                                <br>



                                <img src="<?= getSettingValue('admin_settings', 'Login Image') ?>" class="mx-4" style="width: 500px;" alt="Login Icon">

                            </div>



                        </div>

                        <button type="submit" name="save_admin_panel_settings" class="add-category-btn  mb-3 mx-2">Save</button>

                    </form>

                </div>



            </div>











        </div>









</main>



















<?php



include("includes/script.php");



?>





<script>
    <?php



    if (isset($_SESSION['status'])) {

    ?>

        swal("<?= $_SESSION['message']; ?>", {

                icon: "<?= $_SESSION['icon'] ?>",



            }

        );

    <?php

        unset($_SESSION['status']);

        unset($_SESSION['message']);

        unset($_SESSION['icon']);
    }



    ?>
</script>







<?php



include("includes/footer.php");





?>