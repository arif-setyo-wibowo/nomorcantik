<?php
session_start();
$title = 'pedagangnomor Admin | Nomor';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['admin'])) {
    header('Location: ../back_login.php');
    exit();
}


include '../koneksi.php';
$no=1;
$limit = 10; // Jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Total data
$total_data = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM nomor"))['total'];
$total_pages = ceil($total_data / $limit);

// Query untuk data nomor dengan paginasi
$data = mysqli_query($koneksi, "SELECT n.*, o.nama_operator 
                                FROM nomor n 
                                LEFT JOIN operator o ON n.id_operator = o.id_operator 
                                LIMIT $limit OFFSET $offset");

$dataOperator = mysqli_query($koneksi, 'SELECT * FROM operator');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'delete') {
        $id_nomor = intval($_POST['id_nomor']);
        
        $stmt = $koneksi->prepare('DELETE FROM nomor WHERE id_nomor = ?');
        $stmt->bind_param('i', $id_nomor);
        if ($stmt->execute()) {
            unlink("../assets/uploads/" . $result);
            $_SESSION['msg'] = 'Nomor berhasil dihapus!';
        } else {
            $_SESSION['error'] = 'Nomor gagal dihapus!';
        }

        $stmt->close();
    } elseif ($action == 'insert') {
        $id_operator = intval($_POST['id_operator']);
        $nomor = $_POST['nomor'];
        $harga = intval($_POST['harga']);
        $kode = $_POST['kode'];

        $sql = "INSERT INTO nomor (id_operator, nomor, harga, tipe, kode) VALUES ('$id_operator', '$nomor', '$harga','$tipe','$kode')";
        if ($koneksi->query($sql) === true) {
            $_SESSION['msg'] = 'Berhasil Menambahkan Nomor!';
        } else {
            $_SESSION['error'] = 'Gagal Menambahkan Nomor!';
        }
    }

    header('Location:nomor.php');
    exit();
}

?>
<?php include 'header.php'; ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">pedagangnomor /</span> Nomor</h4>

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
                            Import dari CSV
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
                    <table  class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Operator</th>
                                <th>Nomor</th>
                                <th>Harga</th>
                                <th>Tipe</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php $no = $offset + 1; ?>
                        <?php while($d = mysqli_fetch_array($data)) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $d['kode'] ?></td>
                                <td><?= $d['nama_operator'] ?? 'Tidak Diketahui' ?></td>
                                <td><?= $d['nomor'] ?></td>
                                <td><?= $d['harga'] ?></td>
                                <td><?= $d['tipe'] ?></td>
                                <td>
                                    <a href="nomor-edit.php?id=<?= $d['id_nomor'] ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-pencil-alt"></i> Edit
                                    </a>
                                    <form action="nomor.php" method="POST" id="delete-form-<?= $d['id_nomor'] ?>" style="display: inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id_nomor" value="<?= $d['id_nomor'] ?>">
                                        <button type="button" class="btn btn-danger btn-sm confirm-text" data-form-id="<?= $d['id_nomor'] ?>">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>

                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <!-- Tombol First -->
                            <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=1" aria-label="First">
                                    <span aria-hidden="true">&laquo;&laquo;</span>
                                </a>
                            </li>

                            <!-- Tombol Previous -->
                            <li class="page-item <?= $page == 1 ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>

                            <!-- Pagination Numbers -->
                            <?php 
                            // Menentukan halaman sekitar yang ditampilkan
                            $start_page = max(1, $page - 2);
                            $end_page = min($total_pages, $page + 2);

                            // Menampilkan halaman
                            for ($i = $start_page; $i <= $end_page; $i++): ?>
                                <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                                    <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <!-- Tombol Next -->
                            <li class="page-item <?= $page == $total_pages ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $page + 1 ?>" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>

                            <!-- Tombol Last -->
                            <li class="page-item <?= $page == $total_pages ? 'disabled' : '' ?>">
                                <a class="page-link" href="?page=<?= $total_pages ?>" aria-label="Last">
                                    <span aria-hidden="true">&raquo;&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>

                <!-- Tab for inserting data manually or via CSV -->
                <div class="tab-pane fade" id="navs-top-profile" role="tabpanel">
                    <!-- Manual Form -->
                    <form action="" method="POST">
                        <div class="form-floating form-floating-outline mb-4">
                            <label>Nama Operator</label>
                            <select class="selectpicker w-100" data-style="btn-default" name="id_operator" data-live-search="true" required>
                                <option selected disabled value="">Pilih Operator</option>
                                <?php while($d = mysqli_fetch_array($dataOperator)) : ?>
                                    <option value="<?= $d['id_operator'] ?>"><?= $d['nama_operator'] ?></option>
                                <?php endwhile;?>
                            </select>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" id="basic-default-fullname" name="nomor" placeholder="Nomor" required />
                            <label for="basic-default-fullname">Nomor</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="number" class="form-control" id="basic-default-fullname" name="harga" placeholder="Harga" required />
                            <label for="basic-default-fullname">Harga</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" id="basic-default-fullname" name="kode" placeholder="Kode" />
                            <label for="basic-default-fullname">Kode</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                            <label>Tipe</label>
                            <select class="selectpicker w-100" data-style="btn-default" name="tipe" data-live-search="true">
                                <option selected disabled value="">Pilih Tipe</option>
                                <option value="stok">Stok</option>
                                <option value="supplier">Supplier</option>
                            </select>
                        </div>
                        <input type="hidden" name="action" value="insert">
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </form>
                </div>

                <div class="tab-pane fade" id="navs-top-csv" role="tabpanel">
                    <form action="import-nomor-csv.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="csvFile" class="form-label">Tambah Data dari CSV</label>
                            <input type="file" class="form-control" id="csvFile" name="csv" accept=".csv" required />
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
