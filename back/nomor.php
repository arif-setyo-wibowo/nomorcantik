<?php
session_start();
$title = 'pedagangnomor Admin | Nomor';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['admin'])) {
    header('Location: ../back_login.php');
    exit();
}

include '../koneksi.php';
$limit = 50; // Batasi jumlah data yang ditampilkan per halaman
$offset = isset($_GET['page']) ? ($_GET['page'] - 1) * $limit : 0;

// Query data utama dengan pagination
$stmt = $koneksi->prepare('SELECT n.*, o.nama_operator 
                           FROM nomor n 
                           LEFT JOIN operator o ON n.id_operator = o.id_operator 
                           LIMIT ? OFFSET ?');
$stmt->bind_param('ii', $limit, $offset);
$stmt->execute();
$data = $stmt->get_result();

// Query untuk mendapatkan operator
$dataOperator = $koneksi->query('SELECT * FROM operator');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'];

    if ($action === 'delete') {
        $id_nomor = intval($_POST['id_nomor']);
        
        $stmt = $koneksi->prepare('DELETE FROM nomor WHERE id_nomor = ?');
        $stmt->bind_param('i', $id_nomor);
        if ($stmt->execute()) {
            $_SESSION['msg'] = 'Nomor berhasil dihapus!';
        } else {
            $_SESSION['error'] = 'Nomor gagal dihapus!';
        }
        $stmt->close();
    } elseif ($action === 'insert') {
        $id_operator = intval($_POST['id_operator']);
        $nomor = $_POST['nomor'];
        $harga = intval($_POST['harga']);
        $kode = $_POST['kode'];
        $tipe = $_POST['tipe'];

        $stmt = $koneksi->prepare('INSERT INTO nomor (id_operator, nomor, harga, tipe, kode) VALUES (?, ?, ?, ?, ?)');
        $stmt->bind_param('isiss', $id_operator, $nomor, $harga, $tipe, $kode);
        if ($stmt->execute()) {
            $_SESSION['msg'] = 'Berhasil menambahkan nomor!';
        } else {
            $_SESSION['error'] = 'Gagal menambahkan nomor!';
        }
        $stmt->close();
    }

    header('Location: nomor.php');
    exit();
}

?>
<?php include 'header.php'; ?>

<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">pedagangnomor /</span> Nomor</h4>

    <!-- Alerts -->
    <?php if (isset($_SESSION['msg'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Success!',
                    text: '<?php echo $_SESSION['msg']; ?>',
                    icon: 'success',
                    customClass: { confirmButton: 'btn btn-primary waves-effect waves-light' },
                    buttonsStyling: false
                });
            });
        </script>
        <?php unset($_SESSION['msg']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    title: 'Error!',
                    text: '<?php echo $_SESSION['error']; ?>',
                    icon: 'error',
                    customClass: { confirmButton: 'btn btn-primary waves-effect waves-light' },
                    buttonsStyling: false
                });
            });
        </script>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header p-0">
            <div class="nav-align-top">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link waves-effect active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-home" aria-controls="navs-top-home" aria-selected="false">
                            Nomor
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-profile" aria-controls="navs-top-profile" aria-selected="true">
                            Tambah Data
                        </button>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content p-0">
                <!-- Tab Data -->
                <div class="tab-pane fade active show" id="navs-top-home" role="tabpanel">
                    <table id="example1" class="table table-striped table-bordered">
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
                        <?php $no = $offset + 1; while ($d = $data->fetch_assoc()): ?>
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
                </div>

                <!-- Tab Tambah Data -->
                <div class="tab-pane fade" id="navs-top-profile" role="tabpanel">
                    <form action="" method="POST">
                        <div class="form-floating mb-4">
                            <label>Nama Operator</label>
                            <select class="selectpicker w-100" name="id_operator" required>
                                <option disabled selected value="">Pilih Operator</option>
                                <?php while ($d = $dataOperator->fetch_assoc()): ?>
                                    <option value="<?= $d['id_operator'] ?>"><?= $d['nama_operator'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-floating mb-4">
                            <input type="text" class="form-control" name="nomor" placeholder="Nomor" required>
                            <label>Nomor</label>
                        </div>
                        <div class="form-floating mb-4">
                            <input type="number" class="form-control" name="harga" placeholder="Harga" required>
                            <label>Harga</label>
                        </div>
                        <div class="form-floating mb-4">
                            <input type="text" class="form-control" name="kode" placeholder="Kode">
                            <label>Kode</label>
                        </div>
                        <div class="form-floating mb-4">
                            <select class="selectpicker w-100" name="tipe" required>
                                <option disabled selected value="">Pilih Tipe</option>
                                <option value="stok">Stok</option>
                                <option value="supplier">Supplier</option>
                            </select>
                        </div>
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
