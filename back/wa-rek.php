<?php
session_start();
$title = 'pedagangnomor Admin | Wa dan Rekening';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['admin'])) {
    header('Location: ../back_login.php');
    exit();
}

include '../koneksi.php';

$no = 1;
$data = mysqli_query($koneksi, 'SELECT * FROM wa');
$dataRekening = mysqli_query($koneksi, 'SELECT * FROM rekening');

// Handle insert action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'insert') {
    $namaRekening = $_POST['nama_rekening'];
    $nomorRekening = $_POST['nomor_rekening'];

    // Handle file upload for the logo
    if (isset($_FILES['logo'])) {
        $logo = $_FILES['logo']['name'];
        $tmp_name = $_FILES['logo']['tmp_name'];
        $target_dir = '../assets/uploads/';
        $target_file = $target_dir . basename($logo);
    } else {
        $logo = '';
    }
    move_uploaded_file($tmp_name, $target_file);

    $insertQuery = "INSERT INTO rekening (nama_rekening, nomor_rekening, logo_rekening) 
                        VALUES ('$namaRekening', '$nomorRekening', '$logo')";
    if (mysqli_query($koneksi, $insertQuery)) {
        $_SESSION['msg'] = 'Data rekening berhasil ditambahkan!';
    } else {
        $_SESSION['error'] = 'Gagal menambahkan data rekening!';
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

// Handle delete action
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $idRekening = $_POST['id_rekening'];

    $deleteQuery = "DELETE FROM rekening WHERE id_rekening = '$idRekening'";
    if (mysqli_query($koneksi, $deleteQuery)) {
        $_SESSION['msg'] = 'Data rekening berhasil dihapus!';
    } else {
        $_SESSION['error'] = 'Gagal menghapus data rekening!';
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit();
}

?>
<?php include 'header.php'; ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">pedagangnomor /</span> Kontak dan Rekening</h4>
    <div class="row">
        <!-- Wa Section -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header p-0">
                    <!-- Success & Error Messages -->
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
                            <button type="button" class="nav-link waves-effect active" role="tab"
                                data-bs-toggle="tab" data-bs-target="#navs-top-home" aria-selected="false">
                                Kontak
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <table id="example1" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Wa</th>
                                <th>Tampilan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($d = mysqli_fetch_array($data)): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $d['wa']; ?></td>
                                <td><div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox"
                                            id="flexSwitchCheckDefault-<?= $d['id_wa'] ?>" style="width:87%"
                                            data-id-wa="<?= $d['id_wa'] ?>"
                                            <?= $d['status'] == 1 ? 'checked' : '' ?>>
                                        <label class="form-check-label"
                                            for="flexSwitchCheckDefault-<?= $d['id_wa'] ?>">Tampil</label>
                                    </div>
                                </td>
                                <td>
                                    <a href="wa-edit.php?id_wa=<?php echo $d['id_wa']; ?>" class="btn btn-info btn-sm">
                                        Edit
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Rekening Section -->
        <div class="col-md-6">
            <div class="card mb-4">
                <div class="card-header p-0">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                data-bs-target="#navs-top-rekening" aria-selected="true">
                                Rekening
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
                        <!-- Rekening Table -->
                        <div class="tab-pane fade show active" id="navs-top-rekening">
                            <table id="example2" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Rekening</th>
                                        <th>Logo</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($d = mysqli_fetch_array($dataRekening)): ?>
                                    <tr>
                                        <td><?php echo $no++; ?></td>
                                        <td><?php echo $d['nama_rekening']; ?></td>
                                        <td><?php echo $d['nomor_rekening']; ?></td>
                                        <td><img src="../assets/uploads/<?php echo $d['logo_rekening']; ?>" width="100"></td>
                                        <td>
                                            <a href="rek-edit.php?id=<?php echo $d['id_rekening']; ?>" class="btn btn-info btn-sm">
                                                Edit
                                            </a>
                                            <form action="" method="POST" id="delete-form-<?php echo $d['id_rekening']; ?>"
                                                style="display:inline;">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id_rekening" value="<?php echo $d['id_rekening']; ?>">
                                                <button type="button" class="btn btn-danger btn-sm confirm-text"
                                                    data-form-id="<?php echo $d['id_rekening']; ?>">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Tambah Rekening Form -->
                        <div class="tab-pane fade" id="navs-top-add">
                            <form action="" method="POST" enctype="multipart/form-data">
                                <div class="mb-4">
                                    <label for="nama_rekening">Nama Rekening</label>
                                    <input type="text" name="nama_rekening" class="form-control" required>
                                </div>
                                <div class="mb-4">
                                    <label for="nomor_rekening">Nomor Rekening</label>
                                    <input type="text" name="nomor_rekening" class="form-control" required>
                                </div>
                                <div class="mb-4">
                                    <label for="logo">Logo Rekening</label>
                                    <input type="file" name="logo" class="form-control">
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
        var id_wa = $(this).data('id-wa');
        var status = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: 'wa-update-status.php',
                method: 'POST',
                data: {
                    id_wa: id_wa,
                    status: status
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        console.log('Session msg set successfully.');
                        window.location.href =
                        'wa-rek.php';
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
</script>
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
