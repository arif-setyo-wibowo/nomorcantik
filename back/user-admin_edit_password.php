<?php
session_start();
include '../koneksi.php';
$title = 'NOMORCANTIK Admin | Ubah Password Admin';

// Cek apakah pengguna sudah login
// if (!isset($_SESSION['admin'])) {
//     header('Location: ../login_admin.php'); 
//     exit();
// }


if (!isset($_GET['id_admin']) || empty($_GET['id_admin'])) {
    die('ID Admin tidak ditemukan!');
}

$id_admin = $_GET['id_admin']; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $errors = [];
    if (empty($password)) {
        $errors[] = 'Password tidak boleh kosong.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Password harus terdiri dari minimal 6 karakter.';
    }
    if ($password !== $confirm_password) {
        $errors[] = 'Password dan konfirmasi password tidak cocok.';
    }

    // Jika tidak ada error, lanjutkan ke update password
    if (empty($errors)) {
        // Hash password baru
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Update password di database
        $stmt = $koneksi->prepare('UPDATE admin SET password = ? WHERE id_admin = ?');
        $stmt->bind_param('si', $hashed_password, $id_admin);

        if ($stmt->execute()) {
            $_SESSION['msg'] = 'Password berhasil diubah!';
        } else {
            $_SESSION['error'] = 'Gagal mengubah password!';
        }

        $stmt->close();
        header('Location: user-admin.php');
        exit();
    } else {
        $_SESSION['error'] = implode('<br>', $errors);
    }
}

// Fetch current admin data (if needed)
$stmt = $koneksi->prepare('SELECT * FROM admin WHERE id_admin = ?');
$stmt->bind_param('i', $id_admin);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();
$stmt->close();
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
                            html: '<?php echo $_SESSION['error']; ?>',
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-primary waves-effect waves-light'
                            },
                            buttonsStyling: false
                        });
                    });
                </script>
            <?php unset($_SESSION['error']); endif; ?>

            <div class="tab-content p-0">
                <div class="tab-pane fade active show" id="navs-top-profile" role="tabpanel">
                <form action="" method="POST">
                    <div class="form-password-toggle mb-4">
                        <div class="input-group input-group-merge">
                            <div class="form-floating form-floating-outline">
                                <input
                                    type="password"
                                    id="password"
                                    class="form-control"
                                    name="password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="password"
                                    minlength="6"
                                    required />
                                <label for="password">Password</label>
                            </div>
                            <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                        </div>
                    </div>
                    <div class="form-password-toggle mb-4">
                        <div class="input-group input-group-merge">
                            <div class="form-floating form-floating-outline">
                                <input
                                    type="password"
                                    id="confirm_password"
                                    class="form-control"
                                    name="confirm_password"
                                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                    aria-describedby="confirm_password"
                                    minlength="6"
                                    required />
                                <label for="confirm_password">Konfirmasi Password</label>
                            </div>
                            <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                        </div>
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
