<?php
session_start();
$title = 'NOMORCANTIK Admin | Nomor';

// Cek apakah pengguna sudah login
// if (!isset($_SESSION['admin'])) {
//     header('Location: ../login_admin.php'); 
//     exit();
// }

include '../koneksi.php';

?>
<?php include 'header.php'; ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">SMART PPA /</span> Nomor</h4>

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
                            Nomor
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-top-profile" aria-controls="navs-top-profile" aria-selected="true">
                            Tambah Data
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-top-csv" aria-controls="navs-top-csv" aria-selected="true">
                            Tambah dari CSV
                        </button>
                    </li>
                    <span class="tab-slider" style="left: 91.1528px; width: 107.111px; bottom: 0px;"></span>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content p-0">
                <!-- Tab for displaying data in a table -->
                <div class="tab-pane fade active show" id="navs-top-home" role="tabpanel">
                    <table id="example1" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kategori</th>
                                <th>Sub Kategori</th>
                                <th>Nomor</th>
                                <th>Harga</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Prabayar</td>
                                <td>Telkomsel</td>
                                <td>081278833</td>
                                <td>700</td>
                                <td>
                                    <a href="nomor-edit.php?id=1" class="btn btn-info btn-sm">
                                        <i class="fas fa-pencil-alt"></i> Edit
                                    </a>
                                    <form action="kategori.php" method="POST" id="delete-form-1" style="display: inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id_kategori" value="1">
                                        <button type="button" class="btn btn-danger btn-sm confirm-text" data-form-id="1">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Tab for inserting data manually or via CSV -->
                <div class="tab-pane fade" id="navs-top-profile" role="tabpanel">
                    <!-- Manual Form -->
                    <form action="insert.php" method="POST">
                        <div class="form-floating form-floating-outline mb-4">
                            <label>Kategori</label>
                            <select class="selectpicker w-100" data-style="btn-default" name="id_kategori" data-live-search="true" required>
                                <option selected disabled value="">Pilih Kategori</option>
                            </select>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                            <label>Sub Kategori</label>
                            <select class="selectpicker w-100" data-style="btn-default" name="id_subkategori" data-live-search="true" required>
                                <option selected disabled value="">Pilih Sub Kategori</option>
                            </select>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" id="basic-default-fullname" name="nomor" placeholder="Nomor" required />
                            <label for="basic-default-fullname">Nomor</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="number" class="form-control" id="basic-default-fullname" name="nomor" placeholder="Nomor" required />
                            <label for="basic-default-fullname">Harga</label>
                        </div>
                        <input type="hidden" name="action" value="insert">
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </form>
                </div>

                <div class="tab-pane fade" id="navs-top-csv" role="tabpanel">
                    <form action="upload_csv.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="csvFile" class="form-label">Tambah Data dari CSV</label>
                            <input type="file" class="form-control" id="csvFile" name="csv_file" accept=".csv" required />
                        </div>
                        <button type="submit" class="btn btn-success">Upload CSV</button>
                    </form>
                </div>

            </div>
        </div>

    </div>
</div>

<!-- / Content -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.addEventListener('click', function(event) {
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
                }).then(function(result) {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            }
        });
    });
</script>

<?php include 'footer.php'; ?>
