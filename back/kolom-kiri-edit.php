<?php
session_start();
$title = 'pedagangnomor Admin | Edit Kolom Kiri';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['admin'])) {
    header('Location: ../back_login.php'); 
    exit();
}

include '../koneksi.php';

// Ambil id_kolom dari GET parameter
$id_kolom = intval($_GET['id_kolom']);  
$kolomData = mysqli_query($koneksi, "SELECT * FROM kolom WHERE id_kolom = $id_kolom"); 
$currentKolom = mysqli_fetch_assoc($kolomData);

// Handle update action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update') {
    $judul = $_POST['judul'];
    $data = $_POST['data'];
    $isiData = $_POST['isi_data'];
    
    $updateQuery = "UPDATE kolom SET judul='$judul', data='$data', isi_data='$isiData'";

    // Check if user wants to upload a new image
    if (isset($_POST['upload_logo'])) {
        $tmp_name = $_FILES['gambar']['tmp_name'];
        $logo = $_FILES['gambar']['name'];
        $target_dir = '../assets/uploads/';
        $file_extension = pathinfo($logo, PATHINFO_EXTENSION);
        $new_file_name = uniqid() . '.' . strtolower($file_extension);
        $target_file = $target_dir . $new_file_name;
        
        // Hapus gambar lama jika ada
        if ($currentKolom && !empty($currentKolom['logo'])) {
            $old_logo_file = $target_dir . $currentKolom['logo'];
            if (file_exists($old_logo_file)) {
                unlink($old_logo_file);
            }
        }
        
        // Unggah file gambar baru
        if (move_uploaded_file($tmp_name, $target_file)) {
            $updateQuery .= ", logo='$new_file_name'";
        } else {
            $_SESSION['error'] = 'Gagal mengupload logo!';
            header('Location: kolom-kiri.php');
            exit();
        }
    }

    $updateQuery .= " WHERE id_kolom='$id_kolom'";
    
    // Execute the update query
    if (mysqli_query($koneksi, $updateQuery)) {
        $_SESSION['msg'] = 'Data berhasil diperbarui!';
    } else {
        $_SESSION['error'] = 'Gagal memperbarui data!';
    }
    
    header('Location: kolom-kiri.php');
    exit();
}
?>

<?php include 'header.php'; ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">pedagangnomor /</span> Kolom Kiri</h4>
    <div class="row">
        <!-- Kolom Section -->
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header p-0">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-top-add" aria-selected="false">
                                Ubah Data
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content p-0">
                        <!-- Ubah Form -->
                        <div class="tab-pane fade show active" id="navs-top-add">
                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="mb-4">
                                    <label for="nama_rekening">Judul</label>
                                    <input type="text" name="judul" class="form-control" value="<?= $currentKolom['judul'] ?>" required>
                                </div>
                                <div class="mb-4">
                                    <label for="nama_rekening">Data</label>
                                    <input type="text" name="data" class="form-control" value="<?= $currentKolom['data'] ?>" required>
                                </div>
                                <div class="mb-4">
                                    <label for="nomor_rekening">Isi Data</label>
                                    <input type="text" name="isi_data" class="form-control" value="<?= $currentKolom['isi_data'] ?>" required>
                                </div>
                                <?php if (!empty($currentKolom['logo'])): ?>
                                <div class="mb-4">
                                    <img src="../assets/uploads/<?= $currentKolom['logo'] ?>" alt="Current Logo" width="100">
                                    <br><a href="hapus_logo.php?id_kolom=<?= $currentKolom['id_kolom'] ?>" class="btn btn-danger mt-2">Hapus Gambar</a>
                                </div>
                                <?php endif; ?>
                                <div class="form-check mb-4">
                                    <input type="checkbox" name="upload_logo" id="upload_logo" class="form-check-input">
                                    <label for="upload_logo" class="form-check-label">Tambahkan Gambar Baru</label>
                                </div>
                                <div class="mb-4" id="gambar-input" style="display:none;">
                                    <label for="logo">Gambar</label>
                                    <input type="file" name="gambar" class="form-control">
                                </div>
                                <input type="hidden" name="action" value="update">
                                <button type="submit" class="btn btn-primary">Ubah</button>
                                <a href="kolom-kiri.php"><button type="button" class="btn btn-danger">Batal</button></a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- / Content -->
<script>
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
