<?php
session_start();
$title = 'pedagangnomor Admin | Nomor';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['admin'])) {
    header('Location: ../back_login.php');
    exit();
}

include '../koneksi.php';

// Variabel untuk paginasi
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

// Query untuk data operator
$dataOperator = mysqli_query($koneksi, 'SELECT * FROM operator');

// Proses form untuk insert dan delete
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'delete') {
        $id_nomor = intval($_POST['id_nomor']);
        $stmt = $koneksi->prepare('DELETE FROM nomor WHERE id_nomor = ?');
        $stmt->bind_param('i', $id_nomor);
        if ($stmt->execute()) {
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
        $tipe = $_POST['tipe'];

        $sql = "INSERT INTO nomor (id_operator, nomor, harga, tipe, kode) VALUES ('$id_operator', '$nomor', '$harga', '$tipe', '$kode')";
        if ($koneksi->query($sql) === true) {
            $_SESSION['msg'] = 'Berhasil Menambahkan Nomor!';
        } else {
            $_SESSION['error'] = 'Gagal Menambahkan Nomor!';
        }
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
            <!-- Notifikasi -->
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
            <table class="table table-striped table-bordered">
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

        </div>
    </div>
</div>

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
