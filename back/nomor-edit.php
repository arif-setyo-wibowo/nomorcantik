<?php
session_start();
$title = 'pedagangnomor Admin | Nomor';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['admin'])) {
    header('Location: ../back_login.php');
    exit();
}


include '../koneksi.php';
if (isset($_GET['id'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id_nomor = intval($_POST['id_nomor']);
        $id_operator = intval($_POST['id_operator']);
        $nomor = $_POST['nomor'];
        $harga = intval($_POST['harga']);
        $tipe = $_POST['tipe'];
        $kode = $_POST['kode'];

        $sql = "UPDATE nomor SET id_operator='$id_operator', nomor='$nomor', harga='$harga', tipe='$tipe', kode='$kode' WHERE id_nomor=$id_nomor";
        
        if ($koneksi->query($sql) === true) {
            $_SESSION['msg'] = 'Nomor berhasil diperbarui!';
        } else {
            $_SESSION['error'] = 'Nomor gagal diperbarui!';
        }

        header('Location: nomor.php');
        exit();
    } else {
        $id_nomor = intval($_GET['id']);
        $sql = "SELECT * FROM nomor WHERE id_nomor = $id_nomor";
        $result = mysqli_query($koneksi, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $nomor = mysqli_fetch_assoc($result);
            $dataOperator = mysqli_query($koneksi, 'SELECT * FROM operator');
        } else {
            $_SESSION['error'] = 'Nomor tidak ditemukan!';
            header('Location: nomor.php');
            exit();
        }
    }
} else {
    header('Location: nomor.php');
    exit();
}

?>
<?php include 'header.php'; ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">pedagangnomor /</span> Edit Nomor</h4>

    <div class="card mb-4">
        <div class="card-header p-0">
            <!-- Success Alert -->
            <?php if (isset($_SESSION['msg'])): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
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
                    document.addEventListener('DOMContentLoaded', function () {
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
                    document.addEventListener('DOMContentLoaded', function () {
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
                            data-bs-target="#navs-top-profile" aria-controls="navs-top-profile" aria-selected="true">
                            Ubah Data
                        </button>
                    </li>
                    <span class="tab-slider" style="left: 91.1528px; width: 107.111px; bottom: 0px;"></span>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content p-0">

                <!-- Tab for inserting data manually or via CSV -->
                <div class="tab-pane fade active show" id="navs-top-profile" role="tabpanel">
                    <!-- Manual Form -->
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-floating form-floating-outline mb-4">
                            <label>Nama Operator</label>
                            <select class="selectpicker w-100" data-style="btn-default" name="id_operator"
                                data-live-search="true" required>
                                <option selected disabled value="">Pilih Operator</option>
                                <?php while ($d = mysqli_fetch_array($dataOperator)): ?>
                                    <option value="<?= $d['id_operator'] ?>" <?= $d['id_operator'] == $nomor['id_operator'] ? 'selected' : '' ?> ><?= $d['nama_operator'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="hidden" name="id_nomor" value="<?= $nomor['id_nomor'] ?>" hidden>
                            <input type="text" class="form-control" id="basic-default-fullname" name="nomor"
                                placeholder="Nomor" value="<?= $nomor['nomor'] ?>" required />
                            <label for="basic-default-fullname">Nomor</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="number" class="form-control" id="basic-default-fullname" name="harga"
                                placeholder="Nomor" value="<?= $nomor['harga'] ?>" required />
                            <label for="basic-default-fullname">Harga</label>
                        </div>
                        
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" id="basic-default-fullname" name="kode"
                                placeholder="Kode" value="<?= $nomor['kode'] ?>" />
                            <label for="basic-default-fullname">Kode</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                            <label>Tipe</label>
                            <select class="selectpicker w-100" data-style="btn-default" name="tipe" data-live-search="true">
                                <option selected disabled value="">Pilih Tipe</option>
                                <option value="stok"  <?= $nomor['tipe'] == "stok" ? 'selected' : '' ?>>Stok</option>
                                <option value="supplier"  <?= $nomor['tipe'] == "supplier"  ? 'selected' : '' ?>>Supplier</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Ubah</button>
                        <a href="nomor.php"><button type="button" class="btn btn-danger">Batal</button></a>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- / Content -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.addEventListener('click', function (event) {
            if (event.target && event.target.classList.contains('confirm-text')) {
                event.preventDefault();

                const formId = event.target.getAttribute('data-form-id');
                const form = document.getElementById(`delete-form-${formId}`);

                Swal.fire({
                    title: 'Apakah Yakin ingin menghapus data?',
                    text: "Data yang dihapus akan hilang!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                        cancelButton: 'btn btn-outline-secondary waves-effect'
                    },
                    buttonsStyling: false
                }).then(function (result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });
    });
</script>

<?php include 'footer.php'; ?>