<?php
session_start();
$title = 'Pedagang Nomor Admin | Wa dan Rekening';

if (!isset($_SESSION['admin'])) {
    header('Location: ../back_login.php'); 
    exit();
}

include '../koneksi.php';

$errors = [];
$id_rekening = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch rekening data
$rekeningData = mysqli_query($koneksi, "SELECT * FROM rekening WHERE id_rekening = $id_rekening");

if (!$rekeningData || mysqli_num_rows($rekeningData) === 0) {
    $errors[] = 'Data rekening tidak ditemukan.';
} else {
    $currentRekening = mysqli_fetch_assoc($rekeningData);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $nama_rekening = mysqli_real_escape_string($koneksi, trim($_POST['nama_rekening']));
    $nomor_rekening = mysqli_real_escape_string($koneksi, trim($_POST['nomor_rekening']));
    $logo = $_FILES['logo'];

    if (empty($nama_rekening) || empty($nomor_rekening)) {
        $errors[] = 'Nama Rekening dan Nomor Rekening tidak boleh kosong.';
    }

    // If no errors, process the update
    if (empty($errors)) {
        $updateQuery = "UPDATE rekening SET nama_rekening = ?, nomor_rekening = ?";
        $params = [$nama_rekening, $nomor_rekening];

        if (!empty($logo['name'])) {
            // Handle logo upload
            $file_extension = pathinfo($logo['name'], PATHINFO_EXTENSION);
            $new_file_name = uniqid() . '.' . strtolower($file_extension);
            $target_file = '../assets/uploads/' . $new_file_name;

            if (move_uploaded_file($logo['tmp_name'], $target_file)) {
                $updateQuery .= ", logo_rekening = ?";
                $params[] = $new_file_name;
            } else {
                $errors[] = 'Gagal mengupload logo.';
            }
        }

        $updateQuery .= " WHERE id_rekening = ?";
        $params[] = $id_rekening;

        $stmt = mysqli_prepare($koneksi, $updateQuery);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, str_repeat('s', count($params) - 1) . 'i', ...$params);
            if (mysqli_stmt_execute($stmt)) {
                $_SESSION['msg'] = 'Data Rekening berhasil diubah!';
            } else {
                $errors[] = 'Gagal mengubah data: ' . mysqli_error($koneksi);
            }
            mysqli_stmt_close($stmt);
        } else {
            $errors[] = 'Gagal menyiapkan pernyataan: ' . mysqli_error($koneksi);
        }

        if (empty($errors)) {
            header('Location: wa-rek.php');
            exit();
        }
    }
}
?>
<?php include 'header.php'; ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Pedagang Nomor /</span> Wa dan Rekening</h4>
    <div class="row">
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header p-0">
                    <!-- Success and Error Alerts -->
                    <?php if (isset($_SESSION['msg'])): ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                title: 'Success!',
                                text: '<?php echo $_SESSION['msg']; ?>',
                                icon: 'success',
                                customClass: { confirmButton: 'btn btn-primary' },
                                buttonsStyling: false
                            });
                        });
                    </script>
                    <?php unset($_SESSION['msg']); endif; ?>
                    <?php if (isset($_SESSION['error'])): ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                title: 'Error!',
                                text: '<?php echo $_SESSION['error']; ?>',
                                icon: 'error',
                                customClass: { confirmButton: 'btn btn-primary' },
                                buttonsStyling: false
                            });
                        });
                    </script>
                    <?php unset($_SESSION['error']); endif; ?>
                    <?php if (!empty($errors)): ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            Swal.fire({
                                title: 'Error!',
                                html: '<?php echo implode('<br>', $errors); ?>',
                                icon: 'error',
                                customClass: { confirmButton: 'btn btn-primary' },
                                buttonsStyling: false
                            });
                        });
                    </script>
                    <?php endif; ?>
                </div>
                <div class="card-body">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" id="wa" name="nama_rekening"
                                value="<?= htmlspecialchars($currentRekening['nama_rekening'] ?? '') ?>" placeholder="Nama Rekening" required />
                            <label for="wa">Nama Rekening</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" id="rekening" name="nomor_rekening"
                                value="<?= htmlspecialchars($currentRekening['nomor_rekening'] ?? '') ?>" placeholder="Nomor Rekening" required />
                            <label for="rekening">Nomor Rekening</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="file" class="form-control" name="logo" />
                            <label for="logo">Logo Rekening</label>
                            <p class="text-danger">* kosongkan jika tidak ingin mengubah gambar</p>
                        </div>
                        <input type="hidden" name="action" value="update">
                        <button type="submit" class="btn btn-primary">Ubah</button>
                        <a href="wa-rek.php" class="btn btn-danger">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
