<?php
include './koneksi.php';


if (isset($_GET['ByOperator'])) {
    $idOperator = intval($_GET['ByOperator']);
    $stmt = $koneksi->prepare("SELECT * FROM operator WHERE id_operator = ?");
    $stmt->bind_param("i", $idOperator);
    $stmt->execute();
    $sql = $stmt->get_result();
    $stmt->close();
} else {
    $sql = mysqli_query($koneksi, 'SELECT * FROM operator WHERE status = 1');
}
$data = mysqli_fetch_all($sql, MYSQLI_ASSOC);

$dataOperator = mysqli_query($koneksi, 'SELECT * FROM operator WHERE status = 1');
$operatorData = mysqli_fetch_all($dataOperator, MYSQLI_ASSOC);

function formatHarga($nilai)
{
    if ($nilai >= 1000) {
        $nilai = $nilai / 1000;
        return number_format($nilai, 0, ',', '.') . ' Jt';
    } elseif ($nilai < 1000) {
        return number_format($nilai, 0, ',', '.');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Nomor Cantik</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="assets/lib/animate/animate.min.css" rel="stylesheet">
    <link href="assets/lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<style>
    .table {
        display: table;
        width: 100%;
    }

    .table .row {
        display: table-row;
    }

    .table .cell {
        display: table-cell;
        padding: 5px;
        border: 1px solid #000;
        /* Optional for border like a table */
        text-align: center;
        /* Center content */
        vertical-align: middle;
        /* Center vertically */
    }

    .cell img {
        max-width: 30%;
        /* Ensure image doesn't exceed its container width */
        height: auto;
        /* Maintain aspect ratio */
        object-fit: contain;
        /* Prevent distortion */
    }

    /* Make sure the image adjusts properly for smaller screens */
    @media (max-width: 600px) {
        .cell img {
            width: 50px;
            /* Adjust image width for smaller screens */
        }
    }

    /* By default, hide the search form */
    .search-mobile {
        display: none;
    }

    /* Show the search form only on screens smaller than 768px */
    @media (max-width: 767px) {
        .search-mobile {
            display: block;
        }
    }
</style>

<body>
    <!-- Topbar Start -->
    <div class="container-fluid">
        <div class="row align-items-center bg-light py-3 px-xl-5 d-none d-lg-flex">
            <div class="col-lg-4">
                <a href="" class="text-decoration-none">
                    <span class="h1 text-uppercase text-primary bg-light px-2">Nomor</span>
                    <span class="h1 text-uppercase text-light bg-primary px-2 ml-n1">cantik</span>
                </a>
            </div>
            <div class="col-lg-4 col-6 text-left">
            </div>
            <div class="col-lg-4 col-6 text-right">
                <p class="m-0">Customer Service</p>
                <h5 class="m-0"><a href="https://api.whatsapp.com/send?phone=&amp;text=Hallo Mau pesan nomer"><img
                            src="assets/img/wa-button.png" style=" max-width: 20%;"></a></h5>
            </div>
        </div>
    </div>
    <!-- Topbar End -->

    <!-- Navbar Start -->
    <div class="container-fluid bg-light mb-15">
        <div class="row px-xl-5">
            <div class="col-lg-12">
                <nav class="navbar navbar-expand-lg bg-ligt navbar-dark py-3 py-lg-0 px-0">
                    <a href="" class="text-decoration-none d-block d-lg-none">
                        <span class="h1 text-uppercase text-dark bg-light px-2">Nomor</span>
                        <span class="h1 text-uppercase text-light bg-primary px-2 ml-n1">Cantik</span>
                    </a>
                </nav>
            </div>
        </div>
    </div>
    <!-- Breadcrumb Start -->
    <div class="container-fluid mt-4">
        <div class="row mx-xl-5 bg-light">
            <div class="col-6 ">
                <nav class="breadcrumb bg-light mb-0">
                    <a class="breadcrumb-item text-dark" style="text-decoration:none;" href="index.php">
                        <button class="btn btn-secondary">Halaman Utama</button></a>
                </nav>
            </div>
            <div class="col-6 ">
                <nav class="breadcrumb bg-light mb-0 d-block">
                    <form method="GET">
                        <div class="input-group">
                            <select class="custom-select" id="search-category" style="max-width: 200px;"
                                name="ByOperator">
                                <option value="all" <?= (!isset($_GET['ByOperator']) || $_GET['ByOperator'] === 'all') ? 'selected' : '' ?>>Semua Operator</option>
                                <?php foreach ($operatorData as $operator): ?>
                                    <option value="<?= $operator['id_operator'] ?>" <?= (isset($_GET['ByOperator']) && $_GET['ByOperator'] == $operator['id_operator']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($operator['nama_operator']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="number" name="nomor" class="form-control"
                                placeholder="Masukan nomor cantik yang anda cari"
                                value="<?= isset($_GET['nomor']) ? htmlspecialchars($_GET['nomor']) : '' ?>">
                            <div class="input-group-append">
                                <button class="input-group-text bg-transparent text-primary" type="submit">
                                    <span><i class="fa fa-search"></i></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </nav>
            </div>
        </div>
    </div>
    <!-- Breadcrumb End -->

    <div class="row search-mobile mt-0 mb-1">
        <div class="container-fluid">
            <div class="row mx-xl-5">
                <div class="col-12 p-4">
                    <form method="GET">
                        <div class="input-group">
                            <select class="custom-select" id="search-category" style="max-width: 150px;"
                                name="byOperator">
                                <option value="all" <?= (!isset($_GET['byOperator']) || $_GET['byOperator'] === 'all') ? 'selected' : '' ?>>Semua Operator</option>
                                <?php foreach ($operatorData as $operator): ?>
                                    <option value="<?= $operator['id_operator'] ?>" <?= (isset($_GET['byOperator']) && $_GET['byOperator'] == $operator['id_operator']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($operator['nama_operator']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <input type="number" name="nomor" class="form-control"
                                placeholder="Masukan nomor cantik yang anda cari"
                                value="<?= isset($_GET['nomor']) ? htmlspecialchars($_GET['nomor']) : '' ?>">
                            <div class="input-group-append">
                                <button class="input-group-text bg-transparent text-primary" type="submit">
                                    <span><i class="fa fa-search"></i></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Shop Start -->
    <div class="container-fluid">
        <div class="row px-xl-5 mt-3">
            <!-- Shop Sidebar Start -->
            <div class="col-lg-3 col-md-4">
                <!-- Price Start -->
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Filter
                        dari harga</span></h5>
                <div class="bg-light p-4 mb-30">
                    <form>
                        <div
                            class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" checked id="price-all">
                            <label class="custom-control-label" for="price-all">All Price</label>
                            <span class="badge border font-weight-normal">1000</span>
                        </div>
                        <div
                            class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="price-1">
                            <label class="custom-control-label" for="price-1">$0 - $100</label>
                            <span class="badge border font-weight-normal">150</span>
                        </div>
                        <div
                            class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="price-2">
                            <label class="custom-control-label" for="price-2">$100 - $200</label>
                            <span class="badge border font-weight-normal">295</span>
                        </div>
                        <div
                            class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="price-3">
                            <label class="custom-control-label" for="price-3">$200 - $300</label>
                            <span class="badge border font-weight-normal">246</span>
                        </div>
                        <div
                            class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="price-4">
                            <label class="custom-control-label" for="price-4">$300 - $400</label>
                            <span class="badge border font-weight-normal">145</span>
                        </div>
                        <div class="custom-control custom-checkbox d-flex align-items-center justify-content-between">
                            <input type="checkbox" class="custom-control-input" id="price-5">
                            <label class="custom-control-label" for="price-5">$400 - $500</label>
                            <span class="badge border font-weight-normal">168</span>
                        </div>
                    </form>
                </div>
                <!-- Price End -->

                <!-- Color Start -->
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">Filter
                        dari operator</span></h5>
                <div class="bg-light p-4 mb-30">
                    <form id="byOperator" method="GET" action="">
                        <div
                            class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" <?= !isset($_GET['ByOperator']) ? 'checked' : '' ?> id="color-all" onclick="checkAll(this)">
                            <label class="custom-control-label" for="color-all">Semua Operator</label>
                            <?php
                            $dataNomor = mysqli_query($koneksi, "SELECT * FROM nomor");
                            if (!$dataNomor) {
                                die('Query Error: ' . mysqli_error($koneksi));
                            }
                            $totalNomor = mysqli_num_rows($dataNomor);
                            ?>
                            <span class="badge border font-weight-normal"><?= $totalNomor ?></span>
                        </div>
                        <?php foreach ($operatorData as $index => $operator): ?>
                            <div
                                class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                                <input type="checkbox" class="custom-control-input" id="color-<?= $index ?>"
                                    <?= (isset($_GET['ByOperator']) && $_GET['ByOperator'] == $operator['id_operator']) ? 'checked' : '' ?> onclick="handleCheckboxClick(this)" name="ByOperator"
                                    value="<?= $operator['id_operator'] ?>">
                                <label class="custom-control-label"
                                    for="color-<?= $index ?>"><?= $operator['nama_operator'] ?></label>
                                <?php
                                $id = $operator['id_operator'];
                                $dataNomor = mysqli_query($koneksi, "SELECT * FROM nomor WHERE id_operator = '$id'");
                                if (!$dataNomor) {
                                    die('Query Error: ' . mysqli_error($koneksi));
                                }
                                $totalNomor = mysqli_num_rows($dataNomor);
                                ?>
                                <span class="badge border font-weight-normal"><?= $totalNomor ?></span>
                            </div>
                        <?php endforeach ?>
                    </form>
                </div>
                <!-- Color End -->

            </div>
            <!-- Shop Sidebar End -->


            <!-- Shop Product Start -->
            <div class="col-lg-6 col-md-8 bg-light mt-2">
                <div class="row pb-3">
                    <div class="col-12 pb-1">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <div class="ml-2">
                            </div>
                        </div>
                    </div>

                    <?php foreach ($data as $operator): ?>
                        <?php
                        $id = $operator['id_operator'];

                        $dataNomor = mysqli_query($koneksi, "SELECT * FROM nomor WHERE id_operator = '$id' LIMIT 10");
                        $nomorData = mysqli_fetch_all($dataNomor, MYSQLI_ASSOC);
                        if (empty($nomorData) && !isset($_GET['ByOperator'])) {
                            continue;
                        } else if (empty($nomorData) && isset($_GET['ByOperator'])) {
                            echo "<h5 class='text-center mx-4'>Maaf, nomor yang anda cari tidak ditemukan, silahkan cari nomor yang lain.</h5>";
                            continue;
                        }

                        $no = 1;
                        ?>
                        <div class="col-md-6 col-sm-6 pb-1 bg-light">
                            <div class="h-2 rounded-pill mt-4 mb-3 d-flex justify-content-center align-items-center"
                                style="width: 100px; height: 60px; margin-left:auto; margin-right:auto;">
                                <img class="img-fluid" src="./assets/uploads/<?= htmlspecialchars($operator['logo']) ?>"
                                    style="max-width: 100%; max-height: 100%; object-fit: contain;">
                            </div>

                            <div class="product-item bg-light mb-4">
                                <div class="table">
                                    <?php foreach ($nomorData as $nomor): ?>
                                        <div class="row">
                                            <div class="cell"><?= $no++ ?></div>
                                            <div class="cell">
                                                <h4 class="text-danger m-0"><?= htmlspecialchars($nomor['nomor']) ?></h4>
                                            </div>
                                            <div class="cell">
                                                <h5 class="text-success m-0"><?= formatHarga($nomor['harga']) ?></h5>
                                            </div>
                                            <div class="cell"><a
                                                    href="https://api.whatsapp.com/send?phone=6288210001000&amp;text=saya%20ingin%20info%20lebih%20lanjut%20nomor%20<?= urlencode($nomor['nomor']) ?>"
                                                    target="_blank"><img src="assets/img/wa.png" alt="WhatsApp" width="50"></a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- Shop Product End -->

            <div class="col-lg-3 col-md-4">
                <!-- Price Start -->
                <h5 class="section-title position-relative text-uppercase mb-3"><span
                        class="bg-secondary pr-3">Informasi</span></h5>
                <div class="bg-light p-4 mb-30">

                </div>
                <!-- Price End -->

                <!-- Color Start -->
                <h5 class="section-title position-relative text-uppercase mb-3"><span class="bg-secondary pr-3">cara
                        pembayaran</span></h5>
                <div class="bg-light p-4 mb-30">
                    <!-- Color End -->

                </div>
                <!-- Shop Sidebar End -->
            </div>
        </div>
        <!-- Shop End -->


        <!-- Footer Start -->
        <div class="container-fluid bg-dark text-secondary mt-5 pt-5">
            <div class="row px-xl-5 pt-5">
                <div class="col-lg-4 col-md-12 mb-5 pr-3 pr-xl-5">
                    <h5 class="text-secondary text-uppercase mb-4">Get In Touch</h5>
                    <p class="mb-4">No dolore ipsum accusam no lorem. Invidunt sed clita kasd clita et et dolor sed
                        dolor. Rebum tempor no vero est magna amet no</p>
                    <p class="mb-2"><i class="fa fa-map-marker-alt text-primary mr-3"></i>123 Street, New York, USA</p>
                    <p class="mb-2"><i class="fa fa-envelope text-primary mr-3"></i>info@example.com</p>
                    <p class="mb-0"><i class="fa fa-phone-alt text-primary mr-3"></i><a
                            href="https://api.whatsapp.com/send?phone=6288210001000&amp;text=www.nomorcantik.com"><img
                                src="assets/img/wa-button.png" style=" max-width: 20%;"></a></p>
                </div>
            </div>
            <div class="row border-top mx-xl-5 py-4" style="border-color: rgba(256, 256, 256, .1) !important;">
                <div class="col-md-6 px-xl-0">
                    <p class="mb-md-0 text-center text-md-left text-secondary">
                        &copy; <a class="text-primary" href="#">ITBOY</a>. All Rights Reserved.
                    </p>
                </div>
            </div>
        </div>
        <!-- Footer End -->


        <!-- Back to Top -->
        <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up"></i></a>


        <!-- JavaScript Libraries -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
        <script src="assets/lib/easing/easing.min.js"></script>
        <script src="assets/lib/owlcarousel/owl.carousel.min.js"></script>

        <!-- Contact Javascript File -->
        <script src="assets/mail/jqBootstrapValidation.min.js"></script>
        <script src="assets/mail/contact.js"></script>

        <!-- Template Javascript -->
        <script src="assets/js/main.js"></script>

        <script>
            function handleCheckboxClick(checkbox) {
                const checkboxes = document.querySelectorAll('#byOperator input[type="checkbox"]');
                checkboxes.forEach(cb => {
                    if (cb !== checkbox && cb.checked) {
                        cb.checked = false;
                    }
                });
                document.getElementById('byOperator').submit();
            }

            function checkAll(checkbox) {
                if (checkbox.checked) {
                    const checkboxes = document.querySelectorAll('#byOperator input[type="checkbox"]');
                    checkboxes.forEach(cb => {
                        if (cb !== checkbox) {
                            cb.checked = false;
                        }
                    });
                }
                document.getElementById('byOperator').submit();
            }
        </script>
</body>

</html>