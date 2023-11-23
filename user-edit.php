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
        <div class="heading-contianer d-flex justify-content-between align-items-center">
            <div class="page-details">
                <h1 class="mt-4 dash-heading">User</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">User / Edit</li>
                </ol>
            </div>
            <a href="users.php" class="back-btn">Go Back</a>
        </div>

        <div class="user-edit-container">
            <?php
            if (isset($_GET['user_id'])) :
                $user_id = filter($_GET['user_id']);
            ?>
                <div class="row g-3">
                    <div class="col-4">
                        <div class="user-data-view">
                            <?php
                            $sql = "SELECT * FROM `users` WHERE `id`=$user_id";
                            $query_run = mysqli_query($conn, $sql);
                            if ($query_run) :
                                foreach ($query_run as $row) :
                                    if ($row['signed_in'] == 1) :
                                        $text = "Active";
                                        $style = "active";
                                    else :
                                        $text = "Inactive";
                                        $style = "banned";
                                    endif;
                                    if ($row['verified'] == 1) :
                                        $class = "fa-user-check";
                                        $bgClass = "active";
                                    else :
                                        $class = "fa-user-xmark";
                                        $bgClass = "banned";
                                    endif;
                                    if ($row['sign_up_via'] == "EMAIL") :
                                        $path = "fa-regular fa-envelope fa-lg ";
                                    else :
                                        $path = "fa-brands fa-google";
                                    endif;
                            ?>
                                    <div class="card-heading d-flex justify-content-between align-items-center">
                                        Edit - User
                                        <div>
                                            <span class="<?= $style; ?>">
                                                <?= $text ?>
                                            </span>
                                            <span class="mx-2 <?= $bgClass; ?>">
                                                <i class="fa-solid <?= $class; ?>"></i>
                                            </span>
                                            <span class="active">
                                                <i class="<?= $path; ?>"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="user-data-container d-flex justify-content-between flex-wrap">
                                        <div class="user-data">
                                            <form action="code.php" method="post">
                                                <div class="mb-3">
                                                    <label for="name" class="form-label">User Name</label>
                                                    <input type="text" value="<?= filter($row['user_name']); ?>" class="form-control" name='user_name'>
                                                    <input type="hidden" value="<?= filter($row['id']); ?>" class="form-control" id="id" name="id">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="email" class="form-label">Email</label>
                                                    <input type="email" value="<?= filter($row['email']); ?>" class="form-control" id="email" name="email">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="language" class="form-label">Language</label>
                                                    <input type="text" value="<?= filter($row['language']); ?>" class="form-control" id="language" name="language">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="accreated" class="form-label">Account Created</label>
                                                    <input type="datetime-local" value="<?= filter($row['account_created']); ?>" class="form-control" id="accreated" name="accreated">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="last-active" class="form-label">Last Active</label>
                                                    <input type="datetime-local" value="<?= filter($row['last_active']); ?>" class="form-control" id="last-active" name="last-active">
                                                </div>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center flex-wrap">
                                        <button type="submit" name="save_user" class="btn btn-primary save-user-btn">Submit</button>
                                    </div>
                                    </form>
                            <?php
                                    $uId = $row['uId'];
                                    $name = $row['user_name'];
                                endforeach;
                            else :
                                echo "Some Error Occurred";
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
        </div>
    </div>
</main>

<?php
            else :
                echo "Some error occurred!";
            endif;
?>

<?php
include("includes/script.php");
?>
<script>
    $(document).ready(function() {
        $("#example").DataTable();
    });
    $(document).ready(function() {
        $("#example2").DataTable();
    });
    $(document).ready(function() {
        $("#example3").DataTable();
    });
</script>

<?php
include("includes/footer.php");
?>