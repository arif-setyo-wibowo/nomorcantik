<?php
session_start();
$title = 'NOMORCANTIK Admin | Sub Kategori';

// Cek apakah pengguna sudah login
// if (!isset($_SESSION['admin'])) {
//     header('Location: ../login_admin.php'); 
//     exit();
// }

include '../koneksi.php';


?>

<?php include 'header.php'; ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">NOMORCANTIK /</span> Sub Kategori</h4>

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
                            Sub Kategori
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-top-profile" aria-controls="navs-top-profile" aria-selected="true">
                            Tambah Data
                        </button>
                    </li>
                    <span class="tab-slider" style="left: 91.1528px; width: 107.111px; bottom: 0px;"></span>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content p-0">
                <div class="tab-pane fade active show" id="navs-top-home" role="tabpanel">
                    <table id="example1" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kategori</th>
                                <th>Operator</th>
                                <th>Nomor</th>
                                <th>Logo</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>Prabayar</td>
                                <td>Telkomsel</td>
                                <td>0813, 0821, 0822, 0823, 0852, 0853</td>
                                <td>logo</td>
                                <td>
                                    <a href="sub-kategori-edit.php?id=1" class="btn btn-info btn-sm">
                                        <i class="fas fa-pencil-alt"></i>
                                        Edit
                                    </a>
                                    <form action="project.php" method="POST" id="delete-form-1"
                                        style="display: inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id_project" value="1">
                                        <button type="button" class="btn btn-danger btn-sm confirm-text"
                                            data-form-id="1">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade " id="navs-top-profile" role="tabpanel">
                    <form action="" method="POST">
                        <div class="form-floating form-floating-outline mb-4">
                            <label>Kategori</label>
                            <select class="selectpicker w-100" data-style="btn-default" name="id_kategori" data-live-search="true" required>
                                <option selected disabled value="">Pilih Kategori</option>
                            </select>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" id="nama_operator" name="nama_operator" placeholder="Nama Operator" required />
                            <label for="nama_operator">Nama Operator</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="file" class="form-control" name="foto" required />
                            <label for="foto">Logo</label>
                        </div>
                        <div id="kode-nomor-section">
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" class="form-control" id="kode-nomor-1" name="kode_nomor[]" placeholder="Kode Nomor 1" required />
                                <label for="kode-nomor-1">Kode Nomor 1</label>
                            </div>
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" class="form-control" id="kode-nomor-2" name="kode_nomor[]" placeholder="Kode Nomor 2" required />
                                <label for="kode-nomor-2">Kode Nomor 2</label>
                            </div>
                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" class="form-control" id="kode-nomor-3" name="kode_nomor[]" placeholder="Kode Nomor 3" required />
                                <label for="kode-nomor-3">Kode Nomor 3</label>
                            </div>
                        </div>

                        <button type="button" id="add-kode-nomor" class="btn btn-secondary mb-4">Tambah Kode Nomor</button>
                        <br>
                        <input type="hidden" name="action" value="insert">
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </form>


                </div>
            </div>
        </div>
    </div>
</div>

<!-- / Content -->
<script>
     let kodeNomorCount = 3; 

    document.getElementById('add-kode-nomor').addEventListener('click', function () {
        kodeNomorCount++; 

        const newField = `
            <div class="form-floating form-floating-outline mb-4" id="kode-nomor-field-${kodeNomorCount}">
                <input type="text" class="form-control" id="kode-nomor-${kodeNomorCount}" name="kode_nomor[]" placeholder="Kode Nomor ${kodeNomorCount}" required />
                <label for="kode-nomor-${kodeNomorCount}">Kode Nomor ${kodeNomorCount}</label>
                <button type="button" class="btn btn-danger mt-2" onclick="removeField(${kodeNomorCount})">Hapus Kode Nomor ${kodeNomorCount}</button>
            </div>`;

        document.getElementById('kode-nomor-section').insertAdjacentHTML('beforeend', newField);
    });

    function removeField(id) {
        const field = document.getElementById(`kode-nomor-field-${id}`);
        field.remove(); 
    }

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
