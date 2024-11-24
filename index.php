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
$sortBy = isset($_GET['sortBy']) ? mysqli_real_escape_string($koneksi, $_GET['sortBy']) : null;

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
        // For values in millions or more, display in "M" format with 1 decimal place
        return number_format($nilai / 1000000, 1, ',', '.') . ' M';
    } elseif ($nilai >= 1000000) {
        // For values in millions, display in "M" format with 1 decimal place
        return number_format($nilai / 1000000, 1, ',', '.') . ' M';
    } elseif ($nilai >= 1000) {
        // For values in thousands
        
        // If the number has exactly 3 trailing zeros (e.g., 1000, 10000), show with no decimal
        if ($nilai % 1000 == 0) {
            return number_format($nilai / 1000, 0, ',', '.') . ' Jt'; // No decimal
        }
        
        // If the number has exactly 2 trailing zeros (e.g., 4500, 15000), show with 1 decimal
        if ($nilai % 100 == 0) {
            return number_format($nilai / 1000, 1, ',', '.') . ' Jt'; // 1 decimal
        }

        // For all other numbers, show with 3 decimals
        return number_format($nilai / 1000, 3, ',', '.') . ' Jt'; // 3 decimals
    } else {
        // For values less than 1000, display the number as is
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

    <!-- Favicon -->

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
        text-align: center;
        vertical-align: middle;
    }

    .cell img {
        max-width: 30%;
        height: auto;
        object-fit: contain;
    }

    .totlipharga h6 {
        position: relative;
        display: inline-block;
    }

    .totlipharga h6::after {
        content: attr(title);
        position: absolute;
        bottom: 125%;
        left: 50%;
        transform: translateX(-50%);
        background-color: #000;
        color: #fff;
        padding: 5px;
        border-radius: 5px;
        white-space: nowrap;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease-in-out;
        font-size: 12px;
    }

    .totlipharga h6:hover::after {
        opacity: 1;
        visibility: visible;
    }


    @media (max-width: 1200px) {
        .no-cell {
            display: none !important;
        }

        .cell img {
            width: 50px;
        }

        .cell h5 {
            font-size: 15px !important;
        }

        .cell h6 {
            display: none !important;
        }

        .totlipharga h6 {
            font-size: 14px;
            cursor: pointer;
        }

        .totlipharga h6::after {
            font-size: 12px;
        }
    }

    .search-mobile {
        display: none;
    }

    @media (max-width: 1200px) {
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
            <div class="col-3 ">
                <nav class="breadcrumb bg-light mb-0">
                    <a class="breadcrumb-item text-dark" style="text-decoration:none;" href="<?= $baseUrl ?>">
                        <button class="btn btn-secondary">Halaman Utama</button>
                    </a>
                </nav>
            </div>
            <div class="col-3">
                <div class="breadcrumb-container d-flex align-items-center bg-light">
                    <nav class="breadcrumb bg-light mb-0 me-3">
                        <a class="breadcrumb-item text-dark" style="text-decoration:none;">
                            <button class="btn btn-secondary"id="pengunjung">Pengunjung : 0</span></button>
                        </a>
                    </nav>
                    <nav class="breadcrumb bg-light mb-0">
                        <a class="breadcrumb-item text-dark" style="text-decoration:none;">
                            <button class="btn btn-secondary"id="total-pengunjung">Total Pengunjung: 0</span></button>
                        </a>
                    </nav>
                </div>
            </div>
            <div class="col-6 ">
                <nav class="breadcrumb bg-light mb-0 d-none d-lg-block">
                    <form method="GET">
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
                    <form method="GET">
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

    <!-- Shop Start -->
    <div class="container-fluid">
        <div class="row px-xl-5 mt-3">
            <!-- Shop Sidebar Start -->
            <div class="col-12 col-md-12 col-lg-3 ">
                <!-- Price Start -->
                <h5 class="section-title position-relative text-uppercase mb-3"><span
                        class="bg-secondary pr-3">HARGA</span></h5>
                <div class="bg-light p-4 mb-30">
                    <form id="byPrice" method="GET" action="">
                        <?php
                        $priceRanges = [
                            'all' => ['label' => 'Semua Harga', 'query' => 'SELECT * FROM nomor LEFT JOIN operator ON nomor.id_operator = operator.id_operator WHERE nomor.id_operator IS NOT NULL AND operator.status = 1'],
                            '0-500' => ['label' => '0 - 500', 'query' => 'SELECT * FROM nomor LEFT JOIN operator ON nomor.id_operator = operator.id_operator WHERE nomor.id_operator IS NOT NULL AND operator.status = 1 AND harga BETWEEN 0 AND 500'],
                            '500-1000' => ['label' => '500 - 1 Jt', 'query' => 'SELECT * FROM nomor LEFT JOIN operator ON nomor.id_operator = operator.id_operator WHERE nomor.id_operator IS NOT NULL AND operator.status = 1 AND harga BETWEEN 500 AND 1000'],
                            '1000-20000' => ['label' => '1 Jt - 20 Jt', 'query' => 'SELECT * FROM nomor LEFT JOIN operator ON nomor.id_operator = operator.id_operator WHERE nomor.id_operator IS NOT NULL AND operator.status = 1 AND harga BETWEEN 1000 AND 20000'],
                            '20000-100000' => ['label' => '20 Jt - 100 Jt', 'query' => 'SELECT * FROM nomor LEFT JOIN operator ON nomor.id_operator = operator.id_operator WHERE nomor.id_operator IS NOT NULL AND operator.status = 1 AND harga BETWEEN 20000 AND 100000'],
                            '100000' => ['label' => '100 Jt++', 'query' => 'SELECT * FROM nomor LEFT JOIN operator ON nomor.id_operator = operator.id_operator WHERE nomor.id_operator IS NOT NULL AND operator.status = 1 AND harga > 100000'],
                            'promo' => ['label' => 'Promo', 'query' => 'SELECT * FROM promo INNER JOIN nomor ON promo.id_nomor = nomor.id_nomor INNER JOIN operator ON nomor.id_operator = operator.id_operator WHERE nomor.id_operator IS NOT NULL AND operator.status = 1 AND promo.status = 1'],
                        ];
                        
                        function generatePriceCheckbox($id, $label, $value, $query, $selectedPrice)
                        {
                            global $koneksi;
                            $dataNomor = mysqli_query($koneksi, $query);
                            $totalNomorHarga = mysqli_num_rows($dataNomor);
                            $checked = isset($_GET['ByPrice']) && $_GET['ByPrice'] === (string) $value ? 'checked' : '';
                            echo "
                                                                                                                                <div class='custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3'>
                                                                                                                                    <input type='checkbox' class='custom-control-input' id='$id' name='ByPrice' value='$value' $checked onclick='submitPriceForm(this)'>
                                                                                                                                    <label class='custom-control-label' for='$id'>$label</label>
                                                                                                                                    <span class='badge border font-weight-normal'>$totalNomorHarga</span>
                                                                                                                                </div>
                                                                                                                                ";
                        }
                        
                        foreach ($priceRanges as $value => $details) {
                            generatePriceCheckbox("price-$value", $details['label'], $value, $details['query'], $_GET['ByPrice'] ?? '');
                        }
                        ?>
                    </form>
                </div>
                <!-- Price End -->

                <!-- Color Start -->
                <h5 class="section-title position-relative text-uppercase mb-3"><span
                        class="bg-secondary pr-3">operator</span></h5>
                <div class="bg-light p-4 mb-30">
                    <form id="byOperator" method="GET" action="">
                        <div
                            class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" name="ByOperator" value="10-digit"
                                <?= isset($_GET['ByOperator']) && $_GET['ByOperator'] === '10-digit' ? 'checked' : '' ?>
                                id="color-all" onclick="handleCheckboxClick(this)">
                            <label class="custom-control-label" for="color-all">10 Digit</label>
                            <?php
                            $dataNomor = mysqli_query($koneksi, "SELECT * FROM nomor WHERE LENGTH(REPLACE(nomor, ' ', '')) = 10");
                            $totalNomor = mysqli_num_rows($dataNomor);
                            ?>
                            <span class="badge border font-weight-normal"><?= $totalNomor ?></span>
                        </div>
                        <?php foreach ($operatorData as $index => $operator): ?>
                        <div
                            class="custom-control custom-checkbox d-flex align-items-center justify-content-between mb-3">
                            <input type="checkbox" class="custom-control-input" id="color-<?= $index ?>"
                                <?= isset($_GET['ByOperator']) && $_GET['ByOperator'] == $operator['id_operator'] ? 'checked' : '' ?>
                                onclick="handleCheckboxClick(this)" name="ByOperator"
                                value="<?= $operator['id_operator'] ?>">
                            <label class="custom-control-label"
                                for="color-<?= $index ?>"><?= $operator['nama_operator'] ?></label>
                            <?php
                            $id = $operator['id_operator'];
                            $dataNomor = mysqli_query($koneksi, "SELECT * FROM nomor WHERE id_operator = '$id'");
                            $totalNomor = mysqli_num_rows($dataNomor);
                            ?>
                            <span class="badge border font-weight-normal"><?= $totalNomor ?></span>
                        </div>
                        <?php endforeach ?>
                    </form>
                </div>
                <!-- Color End -->

                <!-- Color Start -->

                <?php
                $kolomQuery = "SELECT * FROM kolom";
                $kolom = mysqli_query($koneksi, $kolomQuery);

                // Check for query success
                if (!$kolom) {
                    echo "Query error: " . mysqli_error($koneksi);
                    exit; // Exit if there's a query error
                }

                // Store all rows in an array
                $kolomData = [];
                while ($row = mysqli_fetch_assoc($kolom)) {
                    if ($row['status'] == 1) { // Check if status is 1
                        $kolomData[] = $row; // Store only rows with status = 1
                    }
                }
                if (count($kolomData) > 0): ?>
                <?php foreach ($kolomData as $row): ?>
                <h5 class="section-title position-relative text-uppercase mb-3">
                    <span class="bg-secondary pr-3"><?= htmlspecialchars($row['judul']) ?></span>
                </h5>
                <div class="bg-light p-4 mb-30">
                    <ul class="list-unstyled">
                        <li class="mb-2 text-center">
                            <strong><?= htmlspecialchars($row['data']) ?></strong><br>
                            <?= htmlspecialchars($row['isi_data']) ?><br>
                            <?php if (!empty($row['logo'])): ?>
                            <img src="./assets/uploads/<?= htmlspecialchars($row['logo']) ?>" alt="Logo"
                                style="max-width: 100px;" class="my-3" />
                            <?php endif; ?>
                        </li>
                    </ul>
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <?php endif; ?>
            </div>
            <!-- Shop Sidebar End -->

            <!-- Shop Product Start -->
            <div class="col-12 col-md-12 col-lg-6 bg-light mt-2">
                <div class="row pb-3 mt-4">
                    <?php if($byOperator || $byPrice || $searchNomor): ?>
                    <div class="col-12 pb-1">
                        <hr>
                        <div class="d-flex align-items-center justify-content-center">
                            <?php
                            $arrowStyle = $sortBy == 'ASC' ? '' : 'transform: rotate(180deg);';
                            $buttonText = $sortBy == 'ASC' ? 'Tertinggi - Terendah' : 'Terendah - Tertinggi';
                            $nextSort = $sortBy == 'ASC' ? 'DESC' : 'ASC';
                            ?>

                            <img src="images/arrow.gif" class="mr-2" style="width:15px; <?= $arrowStyle ?>"
                                alt="" srcset="">

                            <a href="#" class="text-center"
                                style="text-decoration: none;font-weight: bold; font-size: 16px;"
                                onclick="submitSortForm(); return false;">
                                <?= $buttonText ?>
                            </a>

                            <form action="" method="get" id="sortForm">
                                <input type="hidden" name="sortBy" value="<?= $nextSort ?>">
                            </form>

                            <img src="images/arrow.gif" class="ml-2" style="width:15px; <?= $arrowStyle ?>"
                                alt="" srcset="">
                        </div>
                        <hr>
                    </div>
                    <?php endif; ?>
                    <?php if(!$byOperator && !$byPrice && !$searchNomor): ?>
                    <?php
                    $query = 'SELECT * FROM promo JOIN nomor ON promo.id_nomor = nomor.id_nomor JOIN operator ON nomor.id_operator = operator.id_operator WHERE operator.status = 1 AND promo.status = 1 LIMIT 10';
                    $dataPromo = mysqli_query($koneksi, $query);
                    $promoData = mysqli_fetch_all($dataPromo, MYSQLI_ASSOC); ?>
                    <?php if (mysqli_num_rows($dataPromo) > 0){ ?>
                    <div class="col-md-12 col-sm-6 col-sm-6 col-lg-6 pb-1 bg-light">
                        <div class="h-2 rounded-pill mt-4 mb-3 d-flex justify-content-center align-items-center"
                            style="width: 100px; height: 60px; margin-left:auto; margin-right:auto;">
                            <span class="h1 text-uppercase text-light bg-primary  px-2">PROMO</span>
                        </div>
                        <div class="product-item bg-light mb-4">
                            <div class="table">
                                <?php $no = 1; foreach ($promoData as $nomor): ?>
                                <div class="row">
                                    <div class="cell"><?= $no++ ?></div>
                                    <div class="cell">
                                        <a href="detail.php?id_pro=<?= $nomor['id_promo'] ?>"
                                            style="text-decoration:none;">
                                            <h5 class="text-danger m-0"><?= htmlspecialchars($nomor['nomor']) ?></h5>
                                        </a>
                                    </div>
                                    <div class="cell">
                                        <h5 class="text-success m-0"><?= formatHarga($nomor['harga_promo']) ?></h5>
                                    </div>
                                </div>
                                <?php endforeach ?>
                            </div>
                        </div>
                    </div>
                    <?php };?>
                    <?php endif;?>
                    <?php foreach ($data as $operator): ?>
                    <?php
                    $id = $operator['id_operator'];
                    $query = "SELECT * FROM nomor WHERE id_operator = '$id'";
                    
                    if ($byPrice && $byPrice !== 'all') {
                        if ($byPrice === 'promo') {
                            $query = "SELECT * FROM promo INNER JOIN nomor ON promo.id_nomor = nomor.id_nomor WHERE nomor.id_operator = '$id' AND promo.status = 1";
                        } elseif ($byPrice == 100000) {
                            $query .= " AND harga > '$byPrice'";
                        } else {
                            $rangeParts = explode('-', $byPrice);
                            if (count($rangeParts) === 2) {
                                $query .= " AND harga BETWEEN '$rangeParts[0]' AND '$rangeParts[1]'";
                            }
                        }
                    }
                    
                    
                    if ($searchNomor) {
                        $position = isset($_GET['position']) ? mysqli_real_escape_string($koneksi, $_GET['position']) : null;
                    
                        $cleanedSearchNomor = mysqli_real_escape_string($koneksi, str_replace(' ', '', $searchNomor));
                    
                        if ($position === 'belakang') {
                            $query2 = "SELECT * FROM nomor WHERE REPLACE(nomor, ' ', '') LIKE '%$cleanedSearchNomor' AND id_operator = '$id'";
                        } elseif ($position === 'tengah') {
                            $query2 = "SELECT * FROM nomor WHERE REPLACE(nomor, ' ', '') LIKE '____$cleanedSearchNomor%' AND id_operator = '$id'";
                        } else {
                            $query2 = "SELECT * FROM nomor WHERE REPLACE(nomor, ' ', '') LIKE '%$cleanedSearchNomor%' AND id_operator = '$id'";
                        }
                    
                        if ($sortBy) {
                            $query2 .= ' ORDER BY harga ' . ($sortBy === 'ASC' ? 'ASC' : 'DESC');
                        }
                    
                        $dataNomor = mysqli_query($koneksi, $query2);
                    } else {
                        if (isset($byOperator) && str_contains($byOperator, '-digit')) {
                            $angkaDepan = explode('-digit', $byOperator)[0];
                            $query .= " AND LENGTH(TRIM(REPLACE(nomor, ' ', ''))) = " . intval($angkaDepan);
                        } elseif (!$byOperator && !$byPrice && !$sortBy) {
                            $query .= ' LIMIT 10';
                        }

                        if ($sortBy) {
                            $query .= ' ORDER BY harga ' . ($sortBy === 'ASC' ? 'ASC' : 'DESC');
                        }
                    
                        $dataNomor = mysqli_query($koneksi, $query);
                    }

                    
                    
                    $nomorData = mysqli_fetch_all($dataNomor, MYSQLI_ASSOC);
                    
                    if (empty($nomorData)) {
                        $cek++;
                        if ($cek === count($data)) {
                            echo "<h5 class='text-center mx-4'>Maaf, nomor yang anda cari tidak ditemukan, silahkan cari nomor yang lain.</h5>";
                        }
                        continue;
                    }
                    
                    $nomorChunks = array_chunk($nomorData, 10);
                    ?>

                    <?php foreach ($nomorChunks as $chunk): ?>
                    <div class="col-6 col-sm-4 col-md-3 col-lg-6 pb-1 bg-light">
                        <div class="h-2 rounded-pill mt-4 mb-3 d-flex justify-content-center align-items-center"
                            style="width: 100px; height: 60px; margin-left:auto; margin-right:auto;">
                            <img class="img-fluid" src="./assets/uploads/<?= htmlspecialchars($operator['logo']) ?>"
                                style="max-width: 100%; max-height: 100%; object-fit: contain;">
                        </div>
                        <div class="product-item bg-light mb-4">
                            <div class="table">
                                <?php foreach ($chunk as $no => $nomor): ?>
                                <div class="row">
                                    <div class="cell no-cell"><?= $no + 1 ?></div>
                                    <div class="cell totlipharga">
                                        <a href="detail.php?id_no=<?= $nomor['id_nomor'] ?>"
                                            style="text-decoration:none;"
                                            title="<?= formatHarga($nomor['harga_promo'] ?? $nomor['harga']) ?>">
                                            <h5 class="text-danger m-0"><?= htmlspecialchars($nomor['nomor']) ?></h5>
                                        </a>
                                    </div>
                                    <div class="cell">
                                        <h5 class="text-success m-0"><?= formatHarga($nomor['harga']) ?></h5>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Shop Product End -->
            <?php
                $stmt = $koneksi->prepare('SELECT * FROM informasi where status = 1');
                $stmt->execute();
                $result = $stmt->get_result();
                
                $informasi = [];
                while ($row = $result->fetch_assoc()) {
                    $informasi[] = $row;
                }
                
                $stmt->close();
                ?>
                <?php for ($i = 0; $i < 1; $i++): ?>
                    <?php if (isset($informasi[$i])): ?>
                        <div class="col-12 col-md-12 col-lg-3 ">
                            <h5 class="section-title position-relative text-uppercase mb-3">
                                <span class="bg-secondary pr-3">Informasi</span>
                            </h5>
                            <div class="bg-light p-4 mb-30">
                                <p>Mendapatkan nomor yang terbaik merupakan sebuah kepuasan batin dan kebahagiaan tersendiri bagi
                                    sebagian orang. </p>
                                <p>Rangkaian kombinasi nomor yang tersedia hanya ada SATU saja di dunia. Sehingga bisa menjadikan
                                    beberapa nomor itu:
                                </p>
                                <ul class="list-unstyled">
                                    <li>Spesial</li>
                                    <li>Antik</li>
                                    <li>Langka</li>
                                    <li>Unik</li>
                                    <li>Tanda</li>
                                </ul>
                                <p class="mt-2">Semoga Anda menemukan nomor yang sesuai dengan kebutuhan dan kebahagiaan Anda.
                                </p>
                                <p class="text-center">🙏 Terima Kasih 🙏</p>
                            </div>

                            <h5 class="section-title position-relative text-uppercase mb-3">
                                <span class="bg-secondary pr-3">Nomor Rekening</span>
                            </h5>
                            <div class="bg-light p-4 mb-30">
                                <?php if (mysqli_num_rows($rekening) > 0): ?>
                                <ul class="list-unstyled">
                                    <?php while ($row = mysqli_fetch_assoc($rekening)): ?>
                                    <li class="mb-2 text-center">
                                        <strong><?= htmlspecialchars($row['nama_rekening']) ?></strong><br>
                                        <?= htmlspecialchars($row['nomor_rekening']) ?><br>
                                        <?php if (!empty($row['logo_rekening'])): ?>
                                        <img src="./assets/uploads/<?= htmlspecialchars($row['logo_rekening']) ?>" alt="Logo"
                                            style="max-width: 100px;" class="my-3" />
                                        <?php endif; ?>
                                    </li>
                                    <?php endwhile; ?>
                                </ul>
                                <?php else: ?>
                                <p>Tidak ada informasi rekening tersedia.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                
                    <?php endif; ?>
                <?php endfor; ?>
        </div>
    </div>
    <!-- Shop End -->


    <!-- Footer Start -->
    <div class="container-fluid bg-danger text-secondary mt-5 pt-5">
        <div class="row px-xl-5 pt-5">
            <div class="col-lg-4 col-md-12 mb-5 pr-3 pr-xl-5">
                <h5 class="text-secondary text-uppercase mb-4">PedagangNomor</h5>
                <p class="mb-4">Menyediakan Nomor Terbaik untuk Anda.</p>
                <p class="mb-2"><i class="fa fa-envelope text-white mr-3"></i>pedagangnomor@gmail.com</p>
                <?php
                $stmt = $koneksi->prepare('SELECT * FROM wa where status = 1');
                $stmt->execute();
                $result = $stmt->get_result();
                
                $wa = [];
                while ($row = $result->fetch_assoc()) {
                    $wa[] = $row;
                }
                
                $stmt->close();
                ?>
                <?php for ($i = 0; $i < 6; $i++): ?>
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
    <!-- Footer End -->


    <!-- Back to Top -->
    <a href="#" class="btn btn-primary back-to-top"><i class="fa fa-angle-double-up text-white"></i></a>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
    <script src="assets/lib/easing/easing.min.js"></script>
    <script src="assets/lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="assets/mail/contact.js"></script>
    <script src="assets/js/main.js"></script>

    <script>
        function submitPriceForm(checkbox) {
            var checkboxes = document.querySelectorAll('#byPrice input[type="checkbox"]');
            checkboxes.forEach(function(cb) {
                if (cb !== checkbox) cb.checked = false;
            });
            var operatorForm = document.getElementById('byOperator');
            var operatorFormData = new URLSearchParams(new FormData(operatorForm)).toString();
            var actionUrl = window.location.pathname + '?';
            if (operatorFormData) {
                actionUrl += operatorFormData + '&';
            }
            var priceForm = document.getElementById('byPrice');
            var priceFormData = new FormData(priceForm);
            actionUrl += new URLSearchParams(priceFormData).toString();
            window.location.href = actionUrl;
        }

        function submitSortForm() {
            var currentParams = new URLSearchParams(window.location.search);
            var sortForm = document.getElementById('sortForm');
            var formData = new FormData(sortForm);
            var formParams = new URLSearchParams(formData);
            formParams.forEach(function(value, key) {
                currentParams.set(key, value);
            });

            var actionUrl = window.location.pathname + '?' + currentParams.toString();
            window.location.href = actionUrl;
        }

        function handleCheckboxClick(checkbox) {
            var checkboxes = document.querySelectorAll('#byOperator input[type="checkbox"]');
            checkboxes.forEach(function(cb) {
                if (cb !== checkbox) cb.checked = false;
            });
            var priceForm = document.getElementById('byPrice');
            var priceFormData = new URLSearchParams(new FormData(priceForm)).toString();
            var actionUrl = window.location.pathname + '?';
            if (priceFormData) {
                actionUrl += priceFormData + '&';
            }
            var operatorForm = document.getElementById('byOperator');
            var operatorFormData = new FormData(operatorForm);
            actionUrl += new URLSearchParams(operatorFormData).toString();
            window.location.href = actionUrl;
        }
    </script>

    <script>
        function updateVisitorCounts() {
            fetch('visitor_tracker.php')
                .then(response => response.json())
                .then(data => {
                    document.getElementById('pengunjung').textContent = `Pengunjung: ${data.active}`;
                    document.getElementById('total-pengunjung').textContent = `Total Pengunjung: ${data.total}`;
                })
                .catch(error => console.error('Error fetching visitor data:', error));
            }

            // Update the visitor count every 30 seconds
            setInterval(updateVisitorCounts, 30000);

            // Initial load
            updateVisitorCounts();
    </script>

    <script>
        $(document).ready(function() {
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>

</body>

</html>
