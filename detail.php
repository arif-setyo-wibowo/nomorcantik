<?php

include './koneksi.php';
$rekening = mysqli_query($koneksi, 'SELECT * FROM rekening');
$baseUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') . "://$_SERVER[HTTP_HOST]" . rtrim(dirname($_SERVER['PHP_SELF']), '/\\') . '/';
$idOperator = isset($_GET['ByOperator']) ? mysqli_real_escape_string($koneksi, $_GET['ByOperator']) : null;
$nomor = isset($_GET['nomor']) ? mysqli_real_escape_string($koneksi, $_GET['nomor']) : null;
$cek = 0;
$searchNomor = isset($_GET['nomor']) ? mysqli_real_escape_string($koneksi, $_GET['nomor']) : null;
$byOperator = isset($_GET['ByOperator']) ? $_GET['ByOperator'] : null;
$byPrice = isset($_GET['ByPrice']) ? mysqli_real_escape_string($koneksi, $_GET['ByPrice']) : null;

if ($idOperator && str_contains($idOperator, '-digit') == false && !$nomor) {
    $stmt = $koneksi->prepare('SELECT * FROM operator WHERE id_operator = ?');
    $stmt->bind_param('i', $idOperator);
    $stmt->execute();
    $sql = $stmt->get_result();
    $stmt->close();
} elseif ($idOperator && $nomor && $idOperator != 'all') {
    $stmt = $koneksi->prepare('SELECT * FROM operator WHERE id_operator = ?');
    $stmt->bind_param('i', $idOperator);
    $stmt->execute();
    $sql = $stmt->get_result();
    $stmt->close();
} else {
    $sql = mysqli_query($koneksi, 'SELECT * FROM operator WHERE status = 1');
}
$data = mysqli_fetch_all($sql, MYSQLI_ASSOC);
$operatorData = mysqli_fetch_all(mysqli_query($koneksi, 'SELECT * FROM operator WHERE status = 1'), MYSQLI_ASSOC);

function formatHarga($nilai)
{
    if ($nilai >= 100000000) {
        // If value is 100 million or more, display in "M" format with one decimal place
        return number_format($nilai / 1000000, 1, ',', '.') . ' M';
    } elseif ($nilai >= 1000000) {
        // If value is in millions, display in "M" format with one decimal place
        return number_format($nilai / 1000000, 1, ',', '.') . ' M';
    } elseif ($nilai >= 1000) {
        // For values in thousands
        // Check if the last two digits are 00
        if ($nilai % 100 == 0) {
            // Round to 1 decimal place if last two digits are 00
            return number_format($nilai / 1000, 1, ',', '.') . ' jt';
        } else {
            // Otherwise, show with 3 decimal places
            return number_format($nilai / 1000, 3, ',', '.') . ' jt';
        }
    } else {
        // For values less than 1000, just display the value
        return number_format($nilai, 0, ',', '.');
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>pedagangnomor</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

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

    .no-logo {
        display: none;
    }

    @media (max-width: 1200px) {
        .no-logo {
            display: inline-block !important;
        }
    }
</style>

<body>
    <!-- Topbar Start -->
    <div class="container-fluid">
        <div class="row align-items-center bg-light py-3 px-xl-5 d-none d-lg-flex">
            <div class="col-lg-4 d-flex align-items-center">

                <?php
            $logoQuery = "SELECT logo, status, ukuran_nama FROM logo_toko LIMIT 1";  
            $logoResult = mysqli_query($koneksi, $logoQuery);

            if ($logoResult && mysqli_num_rows($logoResult) > 0) {
                $logoData = mysqli_fetch_assoc($logoResult);
                $logoUrl = 'assets/uploads/' . $logoData['logo'];
                $status = $logoData['status']; 
                $ukuranNama = $logoData['ukuran_nama'].'px'; 
            }
            if (isset($status) && $status == 1): ?>
                <img class="px-2" src="<?= htmlspecialchars($logoUrl) ?>"
                    style="display:inline-block; max-width:90px; max-height:90px;" alt="Logo">
                <?php else: ?>
                <img class="px-2" src="<?= htmlspecialchars($logoUrl) ?>"
                    style="display:none; max-width:90px; max-height:90px;" alt="Logo">
                <?php endif; ?>

                <a href="" class="text-decoration-none">
                    <strong>
                        <span class="text-uppercase text-light bg-primary px-2"
                            style="font-size: <?= isset($ukuranNama) ? htmlspecialchars($ukuranNama) : '25px' ?>;">
                            PEDAGANGNOMOR
                        </span>
                    </strong>
                </a>

            </div>
        </div>
    </div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <div class="container-fluid bg-light mb-15">
        <div class="row px-xl-5">
            <div class="col-lg-12">
                <nav class="navbar navbar-expand-lg bg-ligt navbar-dark py-3 py-lg-0 px-0">
                    <?php
                $logoQuery = "SELECT logo, status, ukuran_nama FROM logo_toko LIMIT 1";  
                $logoResult = mysqli_query($koneksi, $logoQuery);

                if ($logoResult && mysqli_num_rows($logoResult) > 0) {
                    $logoData = mysqli_fetch_assoc($logoResult);
                    $logoUrl = 'assets/uploads/' . $logoData['logo'];
                    $status = $logoData['status']; 
                    $ukuranNama = $logoData['ukuran_nama'].'px'; 
                }
                if (isset($status) && $status == 1): ?>
                    <img class="px-2 d-block d-lg-none" src="<?= htmlspecialchars($logoUrl) ?>"
                        style=" max-width:90px; max-height:90px;" alt="Logo">
                    <?php else: ?>
                    <img class="px-2" src="<?= htmlspecialchars($logoUrl) ?>"
                        style="display:none; max-width:90px; max-height:90px;" alt="Logo">
                    <?php endif; ?>

                    <a href="" class="text-decoration-none d-block d-lg-none">
                        <strong>
                            <span class="text-uppercase text-light bg-primary px-2"
                                style="font-size: <?= isset($ukuranNama) ? htmlspecialchars($ukuranNama) : '25px' ?>;">
                                PEDAGANGNOMOR
                            </span>
                        </strong>
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
                    <a class="breadcrumb-item text-dark" style="text-decoration:none;" href="<?= $baseUrl ?>">
                        <button class="btn btn-secondary">Halaman Utama</button>
                    </a>
                </nav>
            </div>
            <div class="col-6 ">
                <nav class="breadcrumb bg-light mb-0 d-none d-lg-block">
                    <form method="GET" action="index.php">
                        <div class="input-group">
                            <select class="custom-select" id="search-category" style="max-width: 200px;"
                                name="ByOperator">
                                <option value="all"
                                    <?= !isset($_GET['ByOperator']) || $_GET['ByOperator'] === 'all' ? 'selected' : '' ?>>
                                    Semua Operator</option>
                                <?php foreach ($operatorData as $operator): ?>
                                <option value="<?= $operator['id_operator'] ?>"
                                    <?= isset($_GET['ByOperator']) && $_GET['ByOperator'] == $operator['id_operator'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($operator['nama_operator']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>

                            <!-- Dropdown untuk posisi -->
                            <select class="custom-select" id="position-select" style="max-width:150px;" name="position">
                                <option value="all"
                                    <?= !isset($_GET['position']) || $_GET['position'] === 'all' ? 'selected' : '' ?>>
                                    Semua Posisi</option>
                                <option value="tengah"
                                    <?= isset($_GET['position']) && $_GET['position'] === 'tengah' ? 'selected' : '' ?>>
                                    Tengah</option>
                                <option value="belakang"
                                    <?= isset($_GET['position']) && $_GET['position'] === 'belakang' ? 'selected' : '' ?>>
                                    Belakang</option>
                            </select>

                            <input type="number" name="nomor" class="form-control" placeholder="Cari Nomor" required
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
            <div class="row justify-content-center mx-xl-5">
                <div class="col-12 p-4">
                    <form method="GET" action="index.php">
                        <div class="text-center">
                            <div class="row m-2 text-center">
                                <div class="col-md-6">
                                    <select class="custom-select" id="search-category" name="byOperator">
                                        <option value="all"
                                            <?= !isset($_GET['byOperator']) || $_GET['byOperator'] === 'all' ? 'selected' : '' ?>>
                                            Semua Operator</option>
                                        <?php foreach ($operatorData as $operator): ?>
                                        <option value="<?= $operator['id_operator'] ?>"
                                            <?= isset($_GET['byOperator']) && $_GET['byOperator'] == $operator['id_operator'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($operator['nama_operator']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select class="custom-select" id="position-select" name="position">
                                        <option value="all"
                                            <?= !isset($_GET['position']) || $_GET['position'] === 'all' ? 'selected' : '' ?>>
                                            Semua Posisi</option>
                                        <option value="tengah"
                                            <?= isset($_GET['position']) && $_GET['position'] === 'tengah' ? 'selected' : '' ?>>
                                            Tengah</option>
                                        <option value="belakang"
                                            <?= isset($_GET['position']) && $_GET['position'] === 'belakang' ? 'selected' : '' ?>>
                                            Belakang</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <div class="input-group">
                                        <input type="number" name="nomor" class="form-control"
                                            placeholder="Cari Nomor"
                                            value="<?= isset($_GET['nomor']) ? htmlspecialchars($_GET['nomor']) : '' ?>">
                                        <div class="input-group-append">
                                            <button class="input-group-text bg-transparent text-primary"
                                                type="submit">
                                                <span><i class="fa fa-search"></i></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Shop Detail Start -->
    <div class="container-fluid mt-4">
        <div class="row px-xl-5">
            <div class="col">
                <div class="bg-light p-30">
                    <div class="nav nav-tabs mb-4">
                        <a class="nav-item nav-link text-dark active" data-toggle="tab"
                            href="#tab-pane-1">Informasi</a>
                    </div>
                    <?php
                    // Periksa apakah id_promo dan id_nomor ada di URL
                    $id_promo = isset($_GET['id_pro']) ? intval($_GET['id_pro']) : null;
                    $id_nomor = isset($_GET['id_no']) ? intval($_GET['id_no']) : null;
                    ?>

                    <?php if ($id_promo != null) : ?>
                    <div class="tab-content">
                        <?php
                        // Ambil data promo dan nomor berdasarkan id_promo
                        $stmt = $koneksi->prepare('SELECT * FROM promo JOIN nomor ON promo.id_nomor = nomor.id_nomor WHERE id_promo = ?');
                        $stmt->bind_param('i', $id_promo);
                        $stmt->execute();
                        $promo = $stmt->get_result()->fetch_assoc(); // Simpan hasil query promo
                        $stmt->close();
                        
                        // Cek apakah data ditemukan
                        if (!$promo) {
                            echo 'Promo tidak ditemukan.';
                            exit();
                        }
                        ?>

                        <?php
                        // Ambil semua data dari tabel wa
                        $stmt = $koneksi->prepare('SELECT * FROM wa');
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        $wa = []; // Inisialisasi array untuk menyimpan data wa
                        while ($row = $result->fetch_assoc()) {
                            $wa[] = $row; // Masukkan data wa ke dalam array
                        }
                        
                        $stmt->close();
                        ?>

                        <div class="tab-pane fade show active" id="tab-pane-1">
                            <h4 class="mb-3">Pesan Nomor <?php echo htmlspecialchars($promo['nomor']); ?> -
                                (<?= formatHarga($promo['harga_promo']) ?>)</h4>
                            <p><strong>Cek Ketersediaan Nomor</strong></p>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-group list-group-flush">
                                        <?php for ($i = 0; $i < 2; $i++): ?>
                                        <?php if (isset($wa[$i])): ?>
                                        <li class="list-group-item px-0">
                                            <a href="https://api.whatsapp.com/send?phone=<?= htmlspecialchars($wa[$i]['wa']) ?>&amp;text=Halo,%20apakah%20Nomor%20<?= urlencode($promo['nomor']) ?>%20<?= urlencode($promo['kode']) ?>%20harga%20<?= formatHarga($promo['harga_promo']) ?>%20Tersedia?"
                                                target="_blank" style="text-decoration:none;">
                                                <h4 class="text-danger m-0" style="display:inline-block;">
                                                    +<?= htmlspecialchars($wa[$i]['wa']) ?>
                                                </h4>
                                            </a>
                                        </li>
                                        <?php endif; ?>
                                        <?php endfor; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php elseif ($id_nomor != null) : ?>
                    <div class="tab-content">
                        <?php
                        // Ambil data berdasarkan id_nomor dari URL
                        $stmt = $koneksi->prepare('SELECT * FROM nomor WHERE id_nomor = ?');
                        $stmt->bind_param('i', $id_nomor);
                        $stmt->execute();
                        $nomor = $stmt->get_result()->fetch_assoc(); // Simpan hasil query nomor
                        $stmt->close();
                        
                        // Cek apakah data ditemukan
                        if (!$nomor) {
                            echo 'Nomor tidak ditemukan.';
                            exit();
                        }
                        ?>

                        <?php
                        // Ambil semua data dari tabel wa
                        $stmt = $koneksi->prepare('SELECT * FROM wa');
                        $stmt->execute();
                        $result = $stmt->get_result();
                        
                        $wa = []; // Inisialisasi array untuk menyimpan data wa
                        while ($row = $result->fetch_assoc()) {
                            $wa[] = $row; // Masukkan data wa ke dalam array
                        }
                        
                        $stmt->close();
                        ?>

                        <div class="tab-pane fade show active" id="tab-pane-1">
                            <h4 class="mb-3">Pesan Nomor  <?php echo htmlspecialchars($nomor['nomor']); ?> -
                                (<?= formatHarga($nomor['harga']) ?>)</h4>
                            <p><strong>Cek Ketersediaan Nomor</strong></p>
                            <div class="row">
                                <div class="col-md-6">
                                    <ul class="list-group list-group-flush">
                                        <?php for ($i = 0; $i < 2; $i++): ?>
                                        <?php if (isset($wa[$i])): ?>
                                        <li class="list-group-item px-0">
                                            <a href="https://api.whatsapp.com/send?phone=<?= htmlspecialchars($wa[$i]['wa']) ?>&amp;text=Halo,%20apakah%20Nomor%20<?= urlencode($nomor['nomor']) ?>%20<?= urlencode($nomor['kode']) ?>%20harga%20<?= formatHarga($nomor['harga']) ?>%20Tersedia?"
                                                target="_blank" style="text-decoration:none;">
                                                <h4 class="text-danger m-0" style="display:inline-block;">
                                                    +<?= htmlspecialchars($wa[$i]['wa']) ?>
                                                </h4>
                                            </a>
                                        </li>
                                        <?php endif; ?>
                                        <?php endfor; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php else : ?>
                    <p>Data nomor atau promo tidak ditemukan. Pastikan URL yang Anda akses benar.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <!-- Shop Detail End -->


    <!-- Footer Start -->
    <div class="container-fluid bg-danger text-secondary mt-5 pt-5">
        <div class="row px-xl-5 pt-5">
            <div class="col-lg-4 col-md-12 mb-5 pr-3 pr-xl-5">
                <h5 class="text-secondary text-uppercase mb-4">PedagangNomor</h5>
                <p class="mb-4">Menyediakan Nomor Terbaik untuk Anda.</p>
                <p class="mb-2"><i class="fa fa-envelope text-white mr-3"></i>pedagangnomor@gmail.com</p>
                <?php
                $stmt = $koneksi->prepare('SELECT * FROM wa');
                $stmt->execute();
                $result = $stmt->get_result();
                
                $wa = [];
                while ($row = $result->fetch_assoc()) {
                    $wa[] = $row;
                }
                
                $stmt->close();
                ?>
                <?php for ($i = 0; $i < 2; $i++): ?>
                <?php if (isset($wa[$i])): ?>
                <p class="mb-0">
                    <a
                        href="https://api.whatsapp.com/send?phone=<?= htmlspecialchars($wa[$i]['wa']) ?>&amp;text=Halo,%20pedagangnomor ">
                        <strong class="text-white" style="font-size:20px;">+<?= htmlspecialchars($wa[$i]['wa']) ?>
                        </strong>
                    </a>
                </p>
                <?php endif; ?>
                <?php endfor; ?>
            </div>

            <div class="col-lg-8 col-md-12">
                <div class="row">
                    <div class="col col-md-2 mb-5">
                        <h6 class="text-secondary text-uppercase mt-4 mb-3">Instagram</h6>
                        <div class="d-flex">
                            <!-- <a class="btn btn-primary btn-square" href="https://instagram.com/pedagangnomor"
                                target="_blank">
                                <i class="fab fa-instagram text-white" style="font-size:34px;"></i>
                            </a> -->
                            <a href="https://instagram.com/pedagangnomor" target="_blank"
                                style="background-color: #f12c2c; display: inline-block; border-radius: 8px; padding:12px;">
                                <i class="fab fa-instagram text-white" style="font-size:34px; height:34px;"></i>
                            </a>
                        </div>
                    </div>
                    <?php
                    $shopQuery = 'SELECT * FROM online_shop WHERE id_online_shop IN (1, 2)';
                    $shopResult = mysqli_query($koneksi, $shopQuery);
                    
                    $shops = [];
                    if ($shopResult && mysqli_num_rows($shopResult) > 0) {
                        while ($row = mysqli_fetch_assoc($shopResult)) {
                            $shops[$row['id_online_shop']] = $row; // Store shops by id_online_shop
                        }
                    }
                    ?>

                    <div class="col col-md-2 mb-5">
                        <?php if (isset($shops[1]) && $shops[1]['status'] == 1): ?>
                        <h6 class="text-secondary text-uppercase mt-4 mb-3">Shopee</h6>
                        <a href="<?= htmlspecialchars($shops[1]['link']) ?>" target="_blank"
                            style="background-color: #f1582c; display: inline-block; border-radius: 8px; padding:12px;">
                            <img src="images/logo/shopee.png" alt="Shopee" class="img-fluid rounded"
                                style="max-width: 34px; max-height: 250px; object-fit: cover;">
                        </a>
                        <?php endif; ?>
                    </div>

                    <div class="col col-md-2 mb-5">
                        <?php if (isset($shops[2]) && $shops[2]['status'] == 1): ?>
                        <h6 class="text-secondary text-uppercase mt-4 mb-3">Tokopedia</h6>
                        <a href="<?= htmlspecialchars($shops[2]['link']) ?>" target="_blank"
                            style="background-color: #d4f4c6; display: inline-block; border-radius: 8px; padding:12px;">
                            <img src="images/logo/tokopedia.png" alt="Tokopedia" class="img-fluid rounded"
                                style="max-width: 34px; max-height: 250px; object-fit: cover;">
                        </a>
                        <?php endif; ?>
                    </div>


                </div>
            </div>
        </div>
        <div class="row border-top mx-xl-5 py-4" style="border-color: #ffffff !important;">
            <div class="col-md-6 px-xl-0">
                <p class="mb-md-0 text-center text-md-left text-secondary">
                    © <a class="text-light" href="https://itboy.my.id/">ITBOY</a>
                </p>
            </div>
        </div>
    </div>

    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up text-light"></i></a>


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
</body>

</html>
