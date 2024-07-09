<?php
// Include database connection
include 'db_conn.php';

// Initialize $info variable
$info = "";

// Check if the login form is submitted
if (isset($_POST['login'])) {
    // Retrieve form data
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Retrieve user data from database based on email
    $query = "SELECT * FROM `admin` WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['admin_id'] = $user['admin_id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = 'admin';
            // Redirect to dashboard or any other page
            header("Location: index.php");
            exit();
        } else {
            $info = "<p class='alert alert-danger'>Incorrect password.</p>";
        }
    } else {
        $info = "<p class='alert alert-danger'>User with this email does not exist.</p>";
    }
}
?>
<!doctype html>
<html lang="en">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
        <meta name="generator" content="Hugo 0.84.0">
        <title>Login - Security Assessment</title>
        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="./assets/css/bootstrap.min.css">
        <link rel="stylesheet" href="./assets/fontawesome/css/all.css">
        <link rel="stylesheet" href="./assets/css/style.css?v=2">
    </head>

    <body>
        <div class="container">
            <div class="row">
                <main class="col-lg-4 col-md-6 col-sm-8 col-12 px-md-4 mx-auto py-5">
                    <div class="card">
                        <div class="card-header text-center">
                            <h5 class="card-title fw-bold">
                                <span class="lang-en">Login</span>
                            </h5>
                        </div>
                        <div class="card-body">
                            <form class="needs-validation" novalidate method="POST" action="">
                                <?php echo $info; ?>
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        <span class="lang-en">Email</span>
                                    </label>
                                    <input type="email" class="form-control bg-transparent" id="email" name="email"
                                        required>
                                    <p class="invalid-feedback mb-0">
                                        <span class="lang-en">Enter a valid Email!</span>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">
                                        <span class="lang-en">Password</span>
                                    </label>
                                    <input type="password" class="form-control bg-transparent" id="password"
                                        name="password" required>
                                    <p class="invalid-feedback mb-0">
                                        <span class="lang-en">Password is required!</span>
                                    </p>
                                </div>
                                <!-- <div class="mb-3">
                                    <p>
                                        <span class="lang-en">Doesn't have an account?</span>
                                        <a href="./signup.php" class="text-primary text-decoration-none">
                                            <span class="lang-en">Signup Here</span>
                                        </a>
                                    </p>
                                </div> -->
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary mx-auto" name="login">
                                        <span class="lang-en">Login</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <script src="./assets/js/jquery-3.6.1.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <script src="./assets/js/bootstrap.bundle.min.js"></script>
        <script src="./assets/js/script.js?v=2"></script>
    </body>

</html>