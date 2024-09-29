<?php
session_start();
$title = 'pedagangnomor Admin | Edit Online Shop';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['admin'])) {
    header('Location: ../back_login.php');
    exit();
}

include '../koneksi.php';

// Initialize error array
$errors = [];

// Get the id of the online shop
$id_online_shop = intval($_GET['id_online_shop']);
$waData = mysqli_query($koneksi, "SELECT * FROM online_shop WHERE id_online_shop = $id_online_shop");
$currentWa = mysqli_fetch_assoc($waData);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and sanitize the input data
    $link = mysqli_real_escape_string($koneksi, $_POST['link']);

    // Validate input
    if (empty($link)) {
        $errors[] = 'Link Toko harus diisi.';
    }

    // If there are no errors, proceed with the update
    if (empty($errors)) {
        // Update the online_shop table
        $query = "UPDATE online_shop SET link = '$link' WHERE id_online_shop = $id_online_shop";
        if (mysqli_query($koneksi, $query)) {
            // Set success message and redirect back to the listing page
            $_SESSION['msg'] = 'Data online shop berhasil diubah.';
            header('Location: tokopedia-shopee.php');
            exit();
        } else {
            // Set error message in case of query failure
            $_SESSION['error'] = 'Gagal mengubah data online shop.';
        }
    }
}
?>

<?php include 'header.php'; ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">pedagangnomor /</span> Online Shop</h4>
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
                                    Ubah Online Shop
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
                                    <input type="text" class="form-control" id="basic-default-fullname" name="link"
                                        value="<?= htmlspecialchars($currentWa['link']) ?>" placeholder="Link Toko" required />
                                    <label for="basic-default-fullname">Link Toko</label>
                                </div>
                                <input type="hidden" name="action" value="update">
                                <button type="submit" class="btn btn-primary">Ubah</button>
                                <a href="tokopedia-shopee.php"><button type="button" class="btn btn-danger">Batal</button></a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
