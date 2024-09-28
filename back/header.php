<?php
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_unset(); 
    session_destroy(); 
    
    header("Location: ../back_login.php"); 
    exit();
}
?>
<!doctype html>
<html lang="en" class="light-style layout-navbar-fixed layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="../assets/back/" data-template="vertical-menu-template">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title><?= $title ?></title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="../assets/back/logo-atas.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="../assets/back/vendor/fonts/materialdesignicons.css" />
    <link rel="stylesheet" href="../assets/back/vendor/fonts/flag-icons.css" />

    <!-- Menu waves for no-customizer fix -->
    <link rel="stylesheet" href="../assets/back/vendor/libs/node-waves/node-waves.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="../assets/back/vendor/css/rtl/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="../assets/back/vendor/css/rtl/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="../assets/back/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="../assets/back/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    <link rel="stylesheet" href="../assets/back/vendor/libs/typeahead-js/typeahead.css" />
    <link rel="stylesheet" href="../assets/back/vendor/libs/quill/editor.css" />
    <link rel="stylesheet" href="../assets/back/vendor/libs/bootstrap-select/bootstrap-select.css" />
    <link rel="stylesheet" href="../assets/back/vendor/libs/apex-charts/apex-charts.css" />
    <link rel="stylesheet" href="../assets/back/vendor/libs/datatables-bs5/datatables.bootstrap5.css" />
    <link rel="stylesheet" href="../assets/back/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css" />
    <link rel="stylesheet" href="../assets/back/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css" />
    <link rel="stylesheet" href="../assets/back/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css" />
    <link rel="stylesheet" href="../assets/back/vendor/libs/select2/select2.css" />
    <link rel="stylesheet" href="../assets/back/vendor/libs/quill/katex.css" />
    <link rel="stylesheet" href="../assets/back/vendor/libs/toastr/toastr.css" />
    <link rel="stylesheet" href="../assets/back/vendor/libs/sweetalert2/sweetalert2.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <!-- Page CSS -->
    <link rel="stylesheet" href="../assets/back/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css" />
    <link rel="stylesheet" href="../assets/back/vendor/css/pages/app-logistics-dashboard.css" />

    <!-- Helpers -->
    <script src="../assets/back/vendor/js/helpers.js"></script>
    <script src="../assets/back/vendor/js/template-customizer.js"></script>
    <script src="../assets/back/js/config.js"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="" class="app-brand-link">
                        <span class="app-brand-text demo menu-text fw-bold ms-2 mt-2">pedagangnomor</span>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>
                <?php
                    $current_page = basename($_SERVER['PHP_SELF']);
                ?>
                <ul class="menu-inner py-1">
                    
                    <li class="menu-header fw-medium mt-4">
                        <span class="menu-header-text">Input Data Tampilan</span>
                    </li>
                    <li class="menu-item <?php echo ($current_page == 'logo-toko.php') ? 'active' : ''; ?>">
                        <a href="logo-toko.php " class="menu-link">
                            <i class="menu-icon tf-icons mdi mdi-shape"></i>
                            <div>Logo dan Toko</div>
                        </a>
                    </li>
                    <li class="menu-item <?php echo ($current_page == 'tokopedia-shopee.php') ? 'active' : ''; ?>">
                        <a href="tokopedia-shopee.php " class="menu-link">
                            <i class="menu-icon tf-icons mdi mdi-shape"></i>
                            <div>Shopee dan Tokopedia</div>
                        </a>
                    </li>
                    <li class="menu-item <?php echo ($current_page == 'kolom-kiri.php') ? 'active' : ''; ?>">
                        <a href="kolom-kiri.php " class="menu-link">
                            <i class="menu-icon tf-icons mdi mdi-shape"></i>
                            <div>Kolom Kiri</div>
                        </a>
                    </li>
                    <li class="menu-header fw-medium mt-4">
                        <span class="menu-header-text">Input Data</span>
                    </li>
                    <li class="menu-item <?php echo ($current_page == 'operator.php') ? 'active' : ''; ?>">
                        <a href="operator.php " class="menu-link">
                            <i class="menu-icon tf-icons mdi mdi-shape"></i>
                            <div>Operator</div>
                        </a>
                    </li>
                    <li class="menu-item <?php echo ($current_page == 'nomor.php') ? 'active' : ''; ?>">
                        <a href="nomor.php" class="menu-link">
                            <i class="menu-icon tf-icons mdi  mdi-card-account-phone"></i>
                            <div>Nomor</div>
                        </a>
                    </li>
                    <li class="menu-item <?php echo ($current_page == 'promo.php') ? 'active' : ''; ?>">
                        <a href="promo.php" class="menu-link">
                            <i class="menu-icon tf-icons mdi  mdi-sale"></i>
                            <div>Promo</div>
                        </a>
                    </li>
                    <li class="menu-item <?php echo ($current_page == 'wa-rek.php') ? 'active' : ''; ?>">
                        <a href="wa-rek.php" class="menu-link">
                            <i class="menu-icon tf-icons mdi  mdi-credit-card"></i>
                            <div>Kontak dan Rekening</div>
                        </a>
                    </li>
                    <li class="menu-item <?php echo ($current_page == 'user-admin.php') ? 'active' : ''; ?>">
                        <a href="user-admin.php" class="menu-link ">
                            <i class="menu-icon tf-icons mdi mdi-clipboard-account" ></i>
                            <div>Data Admin</div>
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="?action=logout" class="menu-link">
                            <i class="menu-icon tf-icons mdi mdi-logout"></i>
                            <div>Logout</div>
                        </a>
                    </li>
                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="mdi mdi-menu mdi-24px"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="../assets/back/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="pages-account-settings-account.html">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="../assets/back/img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="?action=logout">
                                            <i class="mdi mdi-logout me-2"></i>
                                            <span class="align-middle">Log Out</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <!-- Add your content here -->