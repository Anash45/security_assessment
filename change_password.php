<?php
require_once './db_conn.php';
require './functions.php';

if (!isAdmin()) {
    header('location: login.php');
}
$info = '';
if (isset($_REQUEST['change'])) {
    $password = $_REQUEST['password'];
    $new_password = $_REQUEST['new_password'];
    $cnew_password = $_REQUEST['cnew_password'];
    $admin_id = $_SESSION['admin_id'];
    $check = mysqli_query($conn, "SELECT * FROM `admin` WHERE admin_id = $admin_id");
    if (mysqli_num_rows($check) > 0) {
        $row = mysqli_fetch_assoc($check);
        $pass = $row['password'];
        if (password_verify($password, $pass)) {
            if ($new_password == $cnew_password) {
                $new_password = password_hash($new_password, PASSWORD_DEFAULT);
                // update password
                $update = mysqli_query($conn, "UPDATE `admin` SET password = '$new_password' WHERE admin_id = $admin_id");
                if ($update) {
                    $info = '<div class="alert alert-success">Password changed successfully</div>';
                } else {
                    $info = '<div class="alert alert-danger">Failed to change password</div>';
                }
            } else {
                $info = '<div class="alert alert-danger">New password and confirm password must match</div>';
            }
        }else{
            $info = '<div class="alert alert-danger">Current password is incorrect</div>';
        }
    } else {
        $info = '<div class="alert alert-danger">Current password is incorrect</div>';
    }
}


$page = 'change_password';
?>
<!doctype html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
        <meta name="generator" content="Hugo 0.84.0">
        <title>Dashboard - Security Assessment</title>
        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="./assets/fontawesome/css/all.css">
        <link rel="stylesheet" href="./assets/css/style.css?v=2">
    </head>

    <body>
        <?php include './header.php'; ?>
        <div class="container-fluid">
            <div class="row">
                <?php include './sidebar.php'; ?>
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                    <div class="container">
                        <div class="row">
                            <main class="col-lg-4 col-md-6 col-sm-8 col-12 px-md-4 mx-auto py-5">
                                <div class="card">
                                    <div class="card-header text-center">
                                        <h5 class="card-title fw-bold">
                                            <span class="lang-en">Change Password</span>
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <form class="needs-validation" novalidate method="POST" action="">
                                            <?php echo $info; ?>
                                            <div class="mb-3">
                                                <label for="password" class="form-label">
                                                    <span class="lang-en">Current Password</span>
                                                </label>
                                                <input type="password" class="form-control bg-transparent" id="password"
                                                    name="password" required>
                                                <p class="invalid-feedback mb-0">
                                                    <span class="lang-en">Current Password is required!</span>
                                                </p>
                                            </div>
                                            <div class="mb-3">
                                                <label for="password" class="form-label">
                                                    <span class="lang-en">New Password</span>
                                                </label>
                                                <input type="password" class="form-control bg-transparent" id="password"
                                                    name="new_password" required>
                                                <p class="invalid-feedback mb-0">
                                                    <span class="lang-en">New Password is required!</span>
                                                </p>
                                            </div>
                                            <div class="mb-3">
                                                <label for="password" class="form-label">
                                                    <span class="lang-en">Confirm New Password</span>
                                                </label>
                                                <input type="password" class="form-control bg-transparent" id="password"
                                                    name="cnew_password" required>
                                                <p class="invalid-feedback mb-0">
                                                    <span class="lang-en">Confirm New Password is required!</span>
                                                </p>
                                            </div>
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-primary mx-auto" name="change">
                                                    <span class="lang-en">Change</span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </main>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="./assets/js/jquery-3.6.1.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <script src="./assets/js/bootstrap.bundle.min.js"></script>
        <!-- Modal -->
        <script src="./assets/js/script.js?v=2"></script>
    </body>

</html>