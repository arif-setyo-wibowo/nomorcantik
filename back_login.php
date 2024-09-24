<?php
session_start();
include 'koneksi.php'; // Include your database connection file

// Proses login jika metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $errors = [];

    // Validasi input
    if (empty($username)) {
        $errors[] = 'Username tidak boleh kosong.';
    }
    if (empty($password)) {
        $errors[] = 'Password tidak boleh kosong.';
    }

    if (empty($errors)) {
        // Prepare SQL query to check the username and password
        $stmt = $koneksi->prepare('SELECT * FROM admin WHERE username = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            // Assuming password is stored hashed
            if (password_verify($password, $user['password'])) {
                // Login berhasil
                $_SESSION['admin'] = true;
                $_SESSION['user'] = $username;
                $_SESSION['nama'] = $user['nama']; 
                $_SESSION['msg'] = 'Login berhasil!';
                header('Location: back/index.php'); 
                exit();
            } else {
                $_SESSION['error'] = 'Username atau password salah.';
            }
        } else {
            $_SESSION['error'] = 'Username atau password salah.';
        }

        $stmt->close();
    } else {
        $_SESSION['errors'] = $errors;
    }

    header('Location: back_login.php');
    exit();
}
?>


<!doctype html>

<html
    lang="en"
    class="light-style layout-wide customizer-hide"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="assets/"
    data-template="horizontal-menu-template">
<head>
    <meta charset="utf-8" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>pedagangnomor | Login Admin</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/favicon/favicon.ico" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="assets/back/vendor/fonts/materialdesignicons.css" />
    <link rel="stylesheet" href="assets/back/vendor/fonts/flag-icons.css" />

    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="assets/back/vendor/libs/node-waves/node-waves.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/back/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/back/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/back/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="assets/back/vendor/libs/typeahead-js/typeahead.css" />
    <!-- Vendor -->
    <link rel="stylesheet" href="assets/back/vendor/libs/@form-validation/form-validation.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="assets/back/vendor/css/pages/page-auth.css" />

    <!-- Helpers -->
    <script src="assets/back/vendor/js/helpers.js"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
    <script src="assets/back/vendor/js/template-customizer.js"></script>
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="assets/js/config.js"></script>
</head>

<body>
<!-- Content -->

<div class="position-relative">
    <div class="authentication-wrapper authentication-basic container-p-y">
        <div class="authentication-inner py-4">
            <!-- Login -->
            <div class="card p-2">
                <div class="card-body mt-2">
                    <h4 class="mb-2">Selamat Datang! 👋</h4>
                    <p class="mb-4">Login dengan user anda untuk masuk ke sistem</p>
                    <?php

                    // Display success message if it exists
                    if (isset($_SESSION['msg'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert" id="autoDismissAlert" style="margin:5px;">
                            <?php echo $_SESSION['msg']; ?>
                        </div>
                        <?php unset($_SESSION['msg']); // Clear the message after displaying it
                    endif; ?>

                    <?php
                    // Display error message if it exists
                    if (isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="autoDismissAlert" style="margin:5px;">
                            <?php echo $_SESSION['error']; ?>
                        </div>
                        <?php unset($_SESSION['error']); // Clear the error after displaying it
                    endif; ?>

                    <?php
                    // Display validation errors if they exist
                    if (!empty($_SESSION['errors'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="autoDismissAlert" style="margin:5px;">
                            <?php foreach ($_SESSION['errors'] as $error): ?>
                                <?php echo $error; ?><br>
                            <?php endforeach; ?>
                        </div>
                        <?php unset($_SESSION['errors']); // Clear the errors after displaying them
                    endif; ?>


                        <form class="mb-3" action="" method="post">
                            <div class="form-floating form-floating-outline mb-3">
                                <input type="text" class="form-control" name="username" placeholder="Masukkan Username" required />
                                <label for="username">Username</label>
                            </div>
                            <div class="mb-3">
                                <div class="form-password-toggle">
                                    <div class="input-group input-group-merge">
                                        <div class="form-floating form-floating-outline">
                                            <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" required />
                                            <label for="password">Password</label>
                                        </div>
                                        <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
                            </div>
                        </form>

                </div>
            </div>
            <!-- /Login -->
            <img
            alt="mask"
            src="assets/back/img/illustrations/auth-basic-login-mask-light.png"
            class="authentication-image d-none d-lg-block"
            data-app-light-img="illustrations/auth-basic-login-mask-light.png"
            data-app-dark-img="illustrations/auth-basic-login-mask-dark.png" />
        </div>
    </div>
</div>

    <!-- / Content -->

    <!-- Core JS -->
    <!-- build:js assets/back/vendor/js/core.js -->
    <script src="assets/back/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/back/vendor/libs/popper/popper.js"></script>
    <script src="assets/back/vendor/js/bootstrap.js"></script>
    <script src="assets/back/vendor/libs/node-waves/node-waves.js"></script>
    <script src="assets/back/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="assets/back/vendor/libs/hammer/hammer.js"></script>
    <script src="assets/back/vendor/libs/i18n/i18n.js"></script>
    <script src="assets/back/vendor/libs/typeahead-js/typeahead.js"></script>
    <script src="assets/back/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="assets/back/vendor/libs/@form-validation/popular.js"></script>
    <script src="assets/back/vendor/libs/@form-validation/bootstrap5.js"></script>
    <script src="assets/back/vendor/libs/@form-validation/auto-focus.js"></script>

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="assets/js/pages-auth.js"></script>
</body>

</html>

