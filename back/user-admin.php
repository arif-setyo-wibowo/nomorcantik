<?php
session_start();
$title = 'NOMORCANTIK Admin | Data Tim Admin';

// Cek apakah pengguna sudah login
// if (!isset($_SESSION['admin'])) {
//     header('Location: ../back_login.php'); 
//     exit();
// }


include '../koneksi.php';

$no = 1;
$data = mysqli_query($koneksi, 'SELECT * FROM admin');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'delete') {
        $id_admin = intval($_POST['id_admin']);

        $stmt = $koneksi->prepare('DELETE FROM admin WHERE id_admin = ?');
        $stmt->bind_param('i', $id_admin);

        if ($stmt->execute()) {
            $_SESSION['msg'] = 'Admin berhasil dihapus!';
        } else {
            $_SESSION['error'] = 'Admin gagal dihapus!';
        }

        $stmt->close();
    } elseif ($action == 'insert') {
        $nama = $koneksi->real_escape_string($_POST['nama']);
        $username = $koneksi->real_escape_string($_POST['username']);
        $password = password_hash($koneksi->real_escape_string($_POST['password']), PASSWORD_DEFAULT);

        // Check if username already exists
        $stmt = $koneksi->prepare('SELECT COUNT(*) FROM admin WHERE username = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            $_SESSION['error'] = 'Username sudah terdaftar!';
        } else {
            // Handle file upload
            if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
                $foto = basename($_FILES['foto']['name']);
                $targetDir = "../uploads/";
                $targetFile = $targetDir . $foto;

                if (move_uploaded_file($_FILES['foto']['tmp_name'], $targetFile)) {
                    $stmt = $koneksi->prepare("INSERT INTO admin (nama, username, password, foto) VALUES (?, ?, ?, ?)");
                    $stmt->bind_param('ssss', $nama, $username, $password, $foto);

                    if ($stmt->execute()) {
                        $_SESSION['msg'] = 'Admin berhasil ditambahkan!';
                    } else {
                        $_SESSION['error'] = 'Admin gagal ditambahkan!';
                    }

                    $stmt->close();
                } else {
                    $_SESSION['error'] = 'Gagal mengupload foto!';
                }
            } else {
                $_SESSION['error'] = 'Foto wajib diupload!';
            }
        }
    }

    header('Location:user-admin.php');
    exit();
}
?>


<?php include 'header.php'; ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">SMART PPA /</span> Admin</h4>

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
                        <button type="button" class="nav-link waves-effect active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-home" aria-controls="navs-top-home" aria-selected="false" tabindex="-1">
                            Admin
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-profile" aria-controls="navs-top-profile" aria-selected="true">
                            Tambah Data
                        </button>
                    </li>
                <span class="tab-slider" style="left: 91.1528px; width: 107.111px; bottom: 0px;"></span></ul>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content p-0">
                <div class="tab-pane fade active show" id="navs-top-home" role="tabpanel">
                    <table id="example1" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>Username</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($d = mysqli_fetch_array($data)): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $d['nama']; ?></td>
                                <td><?php echo $d['username']; ?></td>

                                <td>
                                    <a href="user-admin_edit.php?id_admin=<?php echo $d['id_admin']; ;  ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-pencil-alt"></i>
                                        Edit
                                    </a>
                                    <a href="user-admin_edit_password.php?id_admin=<?php echo $d['id_admin']; ?>" class="btn btn-warning btn-sm">
                                        <i class="fas fa-pencil-alt"></i>
                                        Ganti Password
                                    </a>
                                    <form action="" method="POST" style="display: inline;" id="delete-form-<?php echo $d['id_admin']; ?>">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id_admin" value="<?php echo $d['id_admin']; ?>">
                                        <button type="button" class="btn btn-danger btn-sm confirm-text" data-form-id="<?php echo $d['id_admin']; ?>">
                                            <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="navs-top-profile" role="tabpanel">
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="nama" placeholder="Nama" required />
                            <label for="nama">Nama</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="username" placeholder="Username" required />
                            <label for="username">Username</label>
                        </div>
                        <div class="form-password-toggle mb-4">
                            <div class="input-group input-group-merge">
                                <div class="form-floating form-floating-outline">
                                    <input
                                        type="password"
                                        name="password"
                                        class="form-control"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        minlength="6"
                                        required />
                                    <label for="password">Password</label>
                                </div>
                                <span class="input-group-text cursor-pointer"><i class="mdi mdi-eye-off-outline"></i></span>
                            </div>
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
