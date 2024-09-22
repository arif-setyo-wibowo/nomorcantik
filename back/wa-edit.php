<?php
session_start();
$title = 'NOMORCANTIK | Wa dan Rekening';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['admin'])) {
    header('Location: ../back_login.php'); 
    exit();
}

include '../koneksi.php';

// Initialize error array
$errors = [];

$id_wa = 1; // Assuming you have only one record for wa, or modify it to fetch based on a specific condition
$waData = mysqli_query($koneksi, "SELECT * FROM wa WHERE id_wa = $id_wa");
$currentWa = mysqli_fetch_assoc($waData);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $wa = mysqli_real_escape_string($koneksi, trim($_POST['wa']));

    if (empty($wa)) {
        $errors[] = 'Nomor Wa tidak boleh kosong.';
    }

    // If no errors, process the update
    if (empty($errors)) {
        $stmt = mysqli_prepare($koneksi, "UPDATE wa SET wa = ? WHERE id_wa = ?");
        mysqli_stmt_bind_param($stmt, 'si', $wa, $id_wa);

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['msg'] = 'Wa berhasil diubah!';
        } else {
            $_SESSION['error'] = 'Gagal mengubah Wa!';
        }

        mysqli_stmt_close($stmt);

        // Redirect to prevent form resubmission
        header('Location: wa_rek.php');
        exit();
    }
}

?>
<?php include 'header.php'; ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">NOMORCANTIK /</span> Wa dan Rekening</h4>
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
                                    Ubah Wa
                                </button>
                            </li>
                            <span class="tab-slider" style="left: 91.1528px; width: 107.111px; bottom: 0px;"></span>
                        </ul>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content p-0">
                        <div class="tab-pane fade active show" id="navs-top-home" role="tabpanel">
                            <form action="" method="POST">
                                <div class="form-floating form-floating-outline mb-4">
                                    <input type="text" class="form-control" id="basic-default-fullname" name="wa"
                                        value="<?= htmlspecialchars($currentWa['wa']) ?>" placeholder="Nomor Wa" required />
                                    <label for="basic-default-fullname">Wa</label>
                                </div>
                                <input type="hidden" name="action" value="update">
                                <button type="submit" class="btn btn-primary">Ubah</button>
                                <a href="wa-rek.php"><button type="button" class="btn btn-danger">Batal</button></a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
