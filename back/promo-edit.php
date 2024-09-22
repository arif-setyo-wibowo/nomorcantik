<?php
session_start();
include '../koneksi.php';

$title = 'NOMORCANTIK | Edit Promo';

// Redirect if not logged in
if (!isset($_SESSION['admin'])) {
    header('Location: ../back_login.php');
    exit();
}

// Check if id_promo is passed and fetch data if available
$id_promo = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_promo > 0) {
    $promoQuery = mysqli_query($koneksi, "SELECT * FROM promo WHERE id_promo = $id_promo");
    $promoData = mysqli_fetch_assoc($promoQuery);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'update') {
    $id_promo = intval($_POST['id_promo']);
    $id_nomor = intval($_POST['id_nomor']);
    $harga_promo = $koneksi->real_escape_string($_POST['harga_promo']);

    // Update the promo in the database
    $stmt = $koneksi->prepare("UPDATE promo SET id_nomor = ?, harga_promo = ? WHERE id_promo = ?");
    $stmt->bind_param('isi', $id_nomor, $harga_promo, $id_promo);

    if ($stmt->execute()) {
        $_SESSION['msg'] = 'Promo berhasil diubah!';
    } else {
        $_SESSION['error'] = 'Promo gagal diubah!';
    }

    $stmt->close();

    // Redirect back to this page after update
    header('Location: promo.php?id=' . $id_promo);
    exit();
}
?>
<?php include 'header.php'; ?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">NOMORCANTIK /</span> Ubah Promo</h4>

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
        </div>
        <div class="card-body">
            <div class="tab-content p-0">
                <div class="tab-pane fade active show" id="navs-top-profile" role="tabpanel">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <!-- Hidden field to store the id_promo -->
                        <input type="hidden" name="id_promo" value="<?= $id_promo ?>">

                        <div class="form-floating form-floating-outline mb-4">
                            <label>Nomor Telp</label>
                            <select class="selectpicker w-100" data-style="btn-default" name="id_nomor" data-live-search="true" required>
                                <option selected disabled value="">Pilih Nomor</option>
                                <?php 
                                $nomorData = mysqli_query($koneksi, 'SELECT id_nomor, nomor FROM nomor');
                                while($row = mysqli_fetch_assoc($nomorData)) { ?>
                                    <option value="<?= $row['id_nomor'] ?>" 
                                    <?= $promoData['id_nomor'] == $row['id_nomor'] ? 'selected' : '' ?>><?= $row['nomor'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="harga_promo" required placeholder="Masukkan Harga Promo"
                                value="<?= $promoData['harga_promo'] ?>">
                            <label for="harga_promo">Harga Promo</label>
                        </div>

                        <input type="hidden" name="action" value="update">
                        <button type="submit" class="btn btn-primary">Ubah</button>
                        <a href="promo.php"><button type="button" class="btn btn-danger">Batal</button></a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
