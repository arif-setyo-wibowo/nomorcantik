<?php
session_start();
$title = 'pedagangnomor Admin | Edit Logo dan Toko';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['admin'])) {
    header('Location: ../back_login.php'); 
    exit();
}

include '../koneksi.php';

// Initialize error array
$errors = [];

$id_logo = intval($_GET['id_logo']); 
$waData = mysqli_query($koneksi, "SELECT * FROM logo_toko WHERE id_logo = $id_logo");
$currentWa = mysqli_fetch_assoc($waData);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    // Proses Update
    $ukuran_nama = intval($_POST['ukuran']);
    
    // Cek apakah logo di-upload
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $logo = $_FILES['logo'];
        $target_dir = "../assets/uploads/";
        $file_extension = pathinfo($logo['name'], PATHINFO_EXTENSION);
        $new_file_name = uniqid() . '.' . strtolower($file_extension);
        $target_file = $target_dir . $new_file_name;
        
        // Validasi ekstensi file (opsional)
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        if (!in_array($file_type, $allowed_types)) {
            $errors[] = "Tipe file tidak valid. Hanya diperbolehkan: " . implode(", ", $allowed_types);
        }

        // Hapus gambar lama jika ada
        if ($currentKolom && !empty($currentKolom['logo'])) {
            $old_logo_file = $target_dir . $currentKolom['logo'];
            if (file_exists($old_logo_file)) {
                unlink($old_logo_file);
            }
        }
        
        // Jika tidak ada error, lakukan upload file
        if (empty($errors)) {
            if (move_uploaded_file($logo["tmp_name"], $target_file)) {
                // Proses update ke database
                $updateLogo = "UPDATE logo_toko SET logo = '$new_file_name', ukuran_nama = $ukuran_nama WHERE id_logo = $id_logo";
                if (mysqli_query($koneksi, $updateLogo)) {
                    $_SESSION['msg'] = "Logo dan ukuran nama berhasil diperbarui!";
                } else {
                    $_SESSION['error'] = "Gagal memperbarui data.";
                }
            } else {
                $errors[] = "Gagal mengupload logo.";
            }
        }
    } else {
        // Jika logo tidak di-upload, hanya update ukuran_nama
        $updateUkuran = "UPDATE logo_toko SET ukuran_nama = $ukuran_nama WHERE id_logo = $id_logo";
        if (mysqli_query($koneksi, $updateUkuran)) {
            $_SESSION['msg'] = "Ukuran nama berhasil diperbarui!";
        } else {
            $_SESSION['error'] = "Gagal memperbarui ukuran nama.";
        }
    }

    // Redirect setelah submit
    header("Location: logo-toko.php");
    exit();
}
?>

<?php include 'header.php'; ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">pedagangnomor /</span> Logo atau Ukuran Nama Toko</h4>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header p-0">
                    <!-- Success Alert -->
                    <?php if (isset($_SESSION['msg'])): ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                title: 'Success!',
                                text: '<?php echo $_SESSION['msg']; ?>',
                                icon: 'success',
                                customClass: {
                                    confirmButton: 'btn btn-primary waves-effect waves-light'
                                },
                                buttonsStyling: false
                            });
                        });
                    </script>
                    <?php unset($_SESSION['msg']); endif; ?>

                    <!-- Error Alert -->
                    <?php if (isset($_SESSION['error'])): ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                title: 'Error!',
                                text: '<?php echo $_SESSION['error']; ?>',
                                icon: 'error',
                                customClass: {
                                    confirmButton: 'btn btn-primary waves-effect waves-light'
                                },
                                buttonsStyling: false
                            });
                        });
                    </script>
                    <?php unset($_SESSION['error']); endif; ?>

                    <!-- Validation Errors Alert -->
                    <?php if (!empty($errors)): ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                title: 'Error!',
                                html: '<?php echo implode('<br>', $errors); ?>',
                                icon: 'error',
                                customClass: {
                                    confirmButton: 'btn btn-primary waves-effect waves-light'
                                },
                                buttonsStyling: false
                            });
                        });
                    </script>
                    <?php endif; ?>
                    
                    <div class="nav-align-top">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button type="button" class="nav-link waves-effect active" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-top-home" aria-controls="navs-top-home" aria-selected="false"
                                    tabindex="-1">
                                    Ubah Logo atau Ukuran Nama Toko
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content p-0">
                        <div class="tab-pane fade active show" id="navs-top-home" role="tabpanel">
                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="form-floating form-floating-outline mb-4">
                                    <input type="file" class="form-control" name="logo" />
                                    <label for="logo">Logo Rekening</label>
                                    <p class="text-danger">* kosongkan jika tidak ingin mengubah gambar</p>
                                </div>
                                <div class="form-floating form-floating-outline mb-4">
                                    <input type="number" class="form-control" id="basic-default-fullname" name="ukuran"
                                        value="<?= htmlspecialchars($currentWa['ukuran_nama']) ?>" placeholder="Ukuran Nama" required />
                                    <label for="basic-default-fullname">Ukuran</label>
                                </div>
                                <input type="hidden" name="action" value="update">
                                <button type="submit" class="btn btn-primary">Ubah</button>
                                <a href="logo-toko.php"><button type="button" class="btn btn-danger">Batal</button></a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
