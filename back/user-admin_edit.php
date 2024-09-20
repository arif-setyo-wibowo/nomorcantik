<?php
session_start();
include '../koneksi.php';
$title = 'NOMORCANTIK Admin | Ubah Data Admin';

// Cek apakah pengguna sudah login
// if (!isset($_SESSION['admin'])) {
//     header('Location: ../login_admin.php'); 
//     exit();
// }


// Validate admin ID
if (!isset($_GET['id_admin']) || empty($_GET['id_admin'])) {
    die('ID Admin tidak ditemukan!');
}

$id_admin = intval($_GET['id_admin']);

// Fetch current admin data
$stmt = $koneksi->prepare('SELECT * FROM admin WHERE id_admin = ?');
$stmt->bind_param('i', $id_admin);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = trim($_POST['nama']);
    $username = trim($_POST['username']);

    $errors = [];

    // Validate inputs
    if (empty($nama)) {
        $errors[] = 'Nama tidak boleh kosong.';
    }
    if (empty($username)) {
        $errors[] = 'Username tidak boleh kosong.';
    }

    // If no errors, proceed to update
    if (empty($errors)) {
        $stmt = $koneksi->prepare('UPDATE admin SET nama = ?, username = ? WHERE id_admin = ?');
        $stmt->bind_param('ssi', $nama, $username, $id_admin);

        if ($stmt->execute()) {
            $_SESSION['msg'] = 'Data berhasil diubah!';
        } else {
            $_SESSION['error'] = 'Gagal mengubah data!';
        }

        $stmt->close();
        header('Location: user-admin.php');
        exit();
    } else {
        $_SESSION['error'] = implode('<br>', $errors);
    }
}
?>

<?php include 'header.php'; ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">NOMORCANTIK /</span> Admin</h4>

    <div class="card mb-4">
        <div class="card-header p-0">
            <div class="nav-align-top">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link waves-effect active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-profile" aria-controls="navs-top-profile" aria-selected="true">
                            Edit Data
                        </button>
                    </li>
                    <span class="tab-slider" style="left: 91.1528px; width: 107.111px; bottom: 0px;"></span>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content p-0">
                <div class="tab-pane fade active show" id="navs-top-profile" role="tabpanel">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" value="<?php echo htmlspecialchars($admin['nama']); ?>" required />
                            <label for="nama">Nama</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" id="username" name="username" placeholder="Username" value="<?php echo htmlspecialchars($admin['username']); ?>" required />
                            <label for="username">Username</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Ubah</button>
                        <a href="user-admin.php" class="btn btn-danger">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
