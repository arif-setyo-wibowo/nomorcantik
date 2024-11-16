<?php
session_start();
$title = 'pedagangnomor Admin | Nomor';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['admin'])) {
    header('Location: ../back_login.php');
    exit();
}

include '../koneksi.php';

// Ambil halaman saat ini dari URL (default halaman pertama)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; // Jumlah data per halaman
$offset = ($page - 1) * $limit;

// Query data untuk DataTable
$dataQuery = "SELECT n.*, o.nama_operator FROM nomor n 
              LEFT JOIN operator o ON n.id_operator = o.id_operator 
              LIMIT $limit OFFSET $offset";
$data = mysqli_query($koneksi, $dataQuery);

// Hitung total data
$totalData = mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(*) AS total FROM nomor"))['total'];
$totalPages = ceil($totalData / $limit);

// Data Operator
$dataOperator = mysqli_query($koneksi, "SELECT * FROM operator");

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'delete') {
        $id_nomor = intval($_POST['id_nomor']);

        $stmt = $koneksi->prepare("DELETE FROM nomor WHERE id_nomor = ?");
        $stmt->bind_param('i', $id_nomor);

        if ($stmt->execute()) {
            $_SESSION['msg'] = 'Nomor berhasil dihapus!';
        } else {
            $_SESSION['error'] = 'Gagal menghapus nomor!';
        }

        $stmt->close();
    } elseif ($action == 'insert') {
        $id_operator = intval($_POST['id_operator']);
        $nomor = $_POST['nomor'];
        $harga = intval($_POST['harga']);
        $kode = $_POST['kode'];
        $tipe = $_POST['tipe'];

        $insertQuery = "INSERT INTO nomor (id_operator, nomor, harga, tipe, kode) 
                        VALUES (?, ?, ?, ?, ?)";
        $stmt = $koneksi->prepare($insertQuery);
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

    <div class="card mb-4">
        <div class="card-header p-0">
            <!-- Alerts -->
            <?php if (isset($_SESSION['msg']) || isset($_SESSION['error'])): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        Swal.fire({
                            title: '<?php echo isset($_SESSION['msg']) ? 'Success!' : 'Error!'; ?>',
                            text: '<?php echo isset($_SESSION['msg']) ? $_SESSION['msg'] : $_SESSION['error']; ?>',
                            icon: '<?php echo isset($_SESSION['msg']) ? 'success' : 'error'; ?>',
                            customClass: { confirmButton: 'btn btn-primary' },
                            buttonsStyling: false
                        });
                    });
                </script>
            <?php unset($_SESSION['msg'], $_SESSION['error']); endif; ?>

            <!-- Navigation Tabs -->
            <div class="nav-align-top">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-home">Nomor</button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-add">Tambah Data</button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-csv">Import CSV</button>
                    </li>
                </ul>
            </div>
        </div>

        <div class="card-body">
            <div class="tab-content">
                <!-- DataTable Tab -->
                <div class="tab-pane fade show active" id="navs-top-home">
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
                        <?php $no = $offset + 1; while ($d = mysqli_fetch_array($data)) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($d['kode']) ?></td>
                                <td><?= htmlspecialchars($d['nama_operator']) ?: 'Tidak Diketahui' ?></td>
                                <td><?= htmlspecialchars($d['nomor']) ?></td>
                                <td><?= htmlspecialchars($d['harga']) ?></td>
                                <td><?= htmlspecialchars($d['tipe']) ?></td>
                                <td>
                                    <a href="nomor-edit.php?id=<?= $d['id_nomor'] ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-pencil-alt"></i> Edit
                                    </a>
                                    <form action="nomor.php" method="POST" style="display:inline;" id="delete-form-<?= $d['id_nomor'] ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id_nomor" value="<?= $d['id_nomor'] ?>">
                                        <button type="button" class="btn btn-danger btn-sm confirm-delete" data-id="<?= $d['id_nomor'] ?>">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Add Data Tab -->
                <div class="tab-pane fade" id="navs-top-add">
                    <form action="nomor.php" method="POST">
                        <div class="mb-3">
                            <label for="operator">Operator</label>
                            <select id="operator" name="id_operator" class="form-control" required>
                                <option value="" disabled selected>Pilih Operator</option>
                                <?php while ($op = mysqli_fetch_assoc($dataOperator)): ?>
                                    <option value="<?= $op['id_operator'] ?>"><?= htmlspecialchars($op['nama_operator']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="nomor">Nomor</label>
                            <input type="text" id="nomor" name="nomor" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="harga">Harga</label>
                            <input type="number" id="harga" name="harga" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="kode">Kode</label>
                            <input type="text" id="kode" name="kode" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="tipe">Tipe</label>
                            <select id="tipe" name="tipe" class="form-control" required>
                                <option value="" disabled selected>Pilih Tipe</option>
                                <option value="stok">Stok</option>
                                <option value="supplier">Supplier</option>
                            </select>
                        </div>
                        <input type="hidden" name="action" value="insert">
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </form>
                </div>

                <!-- Import CSV Tab -->
                <div class="tab-pane fade" id="navs-top-csv">
                    <form action="import-nomor-csv.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="csv">Upload File CSV</label>
                            <input type="file" id="csv" name="csv_file" class="form-control" accept=".csv" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'footer.php'; ?>
<script src="path/to/sweetalert.js"></script>
<script>
    document.querySelectorAll('.confirm-delete').forEach(button => {
        button.addEventListener('click', function () {
            const id = this.getAttribute('data-id');
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menghapus data ini?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-form-${id}`).submit();
                }
            });
        });
    });
</script>
