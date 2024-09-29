<?php
session_start();
$title = 'pedagangnomor Admin | Kolom Kiri';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['admin'])) {
    header('Location: ../back_login.php'); 
    exit();
}

include '../koneksi.php';

$no = 1;
$data = mysqli_query($koneksi, 'SELECT * FROM kolom');

// Handle insert action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'insert') {
    $judul = $_POST['judul'];
    $data = $_POST['data'];
    $isiData = $_POST['isi_data'];
    
    $insertQuery = "INSERT INTO kolom (judul, data, isi_data";
    
    // Check if user wants to upload an image
    if (isset($_POST['upload_logo'])) {
        $logo = $_FILES['gambar']['name'];
        $tmp_name = $_FILES['gambar']['tmp_name'];
        $target_dir = '../assets/uploads/';
        $target_file = $target_dir . basename($logo);
        
        // Attempt to upload the file
        if (move_uploaded_file($tmp_name, $target_file)) {
            $insertQuery .= ", logo) VALUES ('$judul', '$data', '$isiData', '$logo')";
        } else {
            $_SESSION['error'] = 'Gagal mengupload logo!';
            header('Location: '.$_SERVER['PHP_SELF']);
            exit();
        }
    } else {
        $insertQuery .= ") VALUES ('$judul', '$data', '$isiData')";
    }
    
    // Execute the insert query
    if (mysqli_query($koneksi, $insertQuery)) {
        $_SESSION['msg'] = 'Data berhasil ditambahkan!';
    } else {
        $_SESSION['error'] = 'Gagal menambahkan data!';
    }
    
    header('Location: '.$_SERVER['PHP_SELF']);
    exit();
}

// Handle delete action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $idkolom = $_POST['id_kolom'];
    
    $deleteQuery = "DELETE FROM kolom WHERE id_kolom = '$idkolom'";
    if (mysqli_query($koneksi, $deleteQuery)) {
        $_SESSION['msg'] = 'Data kolom berhasil dihapus!';
    } else {
        $_SESSION['error'] = 'Gagal menghapus data kolom!';
    }
    
    header('Location: '.$_SERVER['PHP_SELF']);
    exit();
}
?>

<?php include 'header.php'; ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">pedagangnomor /</span> Kolom Kiri</h4>
    <div class="row">
        <!-- kolom Section -->
        <div class="col-md-12">
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
                    
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-top-kolom" aria-selected="true">
                                Kolom
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-top-add" aria-selected="false">
                                Tambah Data
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content p-0">
                        <!-- kolom Table -->
                        <div class="tab-pane fade show active" id="navs-top-kolom">
                            <table id="example2" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <th>Data</th>
                                        <th>Isi Data</th>
                                        <th>Logo</th>
                                        <th>Tampilan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($d = mysqli_fetch_array($data)): ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo $d['judul']; ?></td>
                                        <td><?php echo $d['data']; ?></td>
                                        <td><?php echo $d['isi_data']; ?></td>
                                        <td>
                                            <?php if (!empty($d['logo'])): ?>
                                                <img src="../assets/uploads/<?php echo $d['logo']; ?>" width="100">
                                            <?php else: ?>
                                                Tidak ada logo
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch mb-2">
                                                <input class="form-check-input" type="checkbox"
                                                    id="flexSwitchCheckDefault-<?= $d['id_kolom'] ?>" style="width:37%"
                                                    data-id-kolom="<?= $d['id_kolom'] ?>"
                                                    <?= $d['status'] == 1 ? 'checked' : '' ?>>
                                                <label class="form-check-label"
                                                    for="flexSwitchCheckDefault-<?= $d['id_kolom'] ?>">Tampil</label>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="kolom-kiri-edit.php?id_kolom=<?php echo $d['id_kolom']; ?>" class="btn btn-info btn-sm">
                                                Edit
                                            </a>
                                            <form action="" method="POST" id="delete-form-<?php echo $d['id_kolom']; ?>" style="display:inline;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id_kolom" value="<?php echo $d['id_kolom']; ?>">
                                                <button type="button" class="btn btn-danger btn-sm confirm-text" data-form-id="<?php echo $d['id_kolom']; ?>">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Tambah kolom Form -->
                        <div class="tab-pane fade" id="navs-top-add">
                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="mb-4">
                                    <label for="nama_kolom">Judul</label>
                                    <input type="text" name="judul" class="form-control" required>
                                </div>
                                <div class="mb-4">
                                    <label for="nama_kolom">Data</label>
                                    <input type="text" name="data" class="form-control" required>
                                </div>
                                <div class="mb-4">
                                    <label for="nomor_kolom">Isi Data</label>
                                    <input type="text" name="isi_data" class="form-control" required>
                                </div>
                                <div class="form-check mb-4">
                                    <input type="checkbox" name="upload_logo" id="upload_logo" class="form-check-input">
                                    <label for="upload_logo" class="form-check-label">Tambahkan Gambar</label>
                                </div>
                                <div class="mb-4" id="gambar-input" style="display:none;">
                                    <label for="logo">Gambar</label>
                                    <input type="file" name="gambar" class="form-control">
                                </div>
                                <input type="hidden" name="action" value="insert">
                                <button type="submit" class="btn btn-primary">Tambah</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- / Content -->
<script>
    $(document).ready(function() {
     $('.form-check-input').on('change', function() {
        var id_kolom = $(this).data('id-kolom');
        var status = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: 'update-status-kolom.php',
                method: 'POST',
                data: {
                    id_kolom: id_kolom,
                    status: status
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        console.log('Session msg set successfully.');
                        window.location.href =
                        'kolom-kiri.php';
                    } else {
                        console.log('Error occurred.');
                    }
                },
                error: function(xhr, status, error) {
                    console.log('Error:', error);
                }
            });
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Show or hide image upload input based on checkbox
        document.getElementById('upload_logo').addEventListener('change', function() {
            const logoInput = document.getElementById('gambar-input');
            if (this.checked) {
                logoInput.style.display = 'block';
            } else {
                logoInput.style.display = 'none';
            }
        });

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
