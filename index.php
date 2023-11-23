<?php
require("config/conn.php");
require("functions.php");
session_start();
ob_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?= getSettingValue('admin_settings', 'admin'); ?></title>
    <link rel="icon" type="image/x-icon" href="<?= getSettingValue('admin_settings', 'Dashboard Icon'); ?>">
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link href="assets/css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" type="text/css" href="assets/datatables/datatables.min.css" />
    <style>
        .bg-login-image {
            background: url("<?= getSettingValue('admin_settings', 'Login Image'); ?>");
            background-position: center;
            /* background-size: cover; */
            background-repeat: no-repeat;
            height: 100%;
            width: 48%;
        }

        form.user .form-control-user {
            font-size: 1rem;
            border-radius: 10rem;
            padding: 1rem 1rem;
        }
    </style>
</head>

<body class="bg-primary">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row align-items-center justify-content-center" style="height: 30rem;">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Welcome To <?= getSettingValue('admin_settings', 'admin'); ?></h1>
                                    </div>
                                    <form class="user" method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                                        <div class="form-group mb-3">
                                            <input type="text" class="form-control form-control-user" id="username" name="username" placeholder="Username">
                                        </div>
                                        <div class="form-group mb-3">
                                            <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Password">
                                        </div>
                                        <button name="login" type="submit" style="width: 100%;" class="delete-user-btn">Login</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<?php
if (isset($_POST['login'])) {
    # filtering Data
    $username = filter($_POST['username']);
    $password = filter($_POST['password']);
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);
    $query = "SELECT * FROM `admin_login` WHERE `username`=? AND `password`=?";
    #prepared statement
    if ($stmt = mysqli_prepare($conn, $query)) {
        mysqli_stmt_bind_param($stmt, "ss", $username, $password);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) == 1) {
            $_SESSION['all'] = "ALL";
            $_SESSION['AdminLoginId'] = $username;
            $_SESSION['message'] = "Admin Login Successfull";
            $_SESSION['status'] = "success";
            $_SESSION['icon'] = 'success';
            header('Location: dashboard.php');
        } else {
?>
            <script>
                swal("Incorrect Username or Passwod", {
                    icon: "",
                });
            </script>
<?php
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "Error Occured";
    }
}
?>

</html>