<?php
session_start();
$title = 'Pedagang Nomor Admin | Promo';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['admin'])) {
    header('Location: ../back_login.php');
    exit();
}

include '../koneksi.php';
$no = 1;
$data = mysqli_query(
    $koneksi,
    'SELECT promo.*,
                                    promo.id_promo, 
                                    promo.harga_promo, 
                                    nomor.nomor, 
                                    operator.nama_operator
                                FROM 
                                    promo
                                JOIN 
                                    nomor ON promo.id_nomor = nomor.id_nomor
                                JOIN 
                                    operator ON nomor.id_operator = operator.id_operator;
                                ',
);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];

    if ($action == 'delete') {
        $id_nomor = intval($_POST['id_nomor']);
        $id_promo = intval($_POST['id_promo']);

        $stmt = $koneksi->prepare('DELETE FROM promo WHERE id_promo = ?');
        $stmt->bind_param('i', $id_promo);
        if ($stmt->execute()) {
            unlink('../assets/uploads/' . $result);
            $_SESSION['msg'] = 'Promo berhasil dihapus!';
        } else {
            $_SESSION['error'] = 'Promo gagal dihapus!';
        }

        $stmt->close();
    } elseif ($action == 'insert') {
        $harga_promo = intval($_POST['harga_promo']);
        $id_nomor = intval($_POST['id_nomor']);

        $sql_promo = "INSERT INTO promo (id_nomor, harga_promo, status) VALUES ('$id_nomor', '$harga_promo','0')";
        if ($koneksi->query($sql_promo) === true) {
            $_SESSION['msg'] = ' Promo berhasil ditambahkan!';
        } else {
            $_SESSION['error'] = 'Promo gagal ditambahkan!';
        }

        header('Location: promo.php');
        exit();
    }

    header('Location:promo.php');
    exit();
}
?>
<?php include 'header.php'; ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Pedagang Nomor /</span> Promo</h4>

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
                            Promo
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-top-profile" aria-controls="navs-top-profile" aria-selected="true">
                            Tambah Data
                        </button>
                    </li>
                    <span class="tab-slider" style="left: 91.1528px; width: 107.111px; bottom: 0px;"></span>
                </ul>
            </div>
        </div>
        <div class="card-body">
            <div class="tab-content p-0">
                <div class="tab-pane fade active show" id="navs-top-home" role="tabpanel">
                    <table id="example1" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Operator</th>
                                <th>Nomor</th>
                                <th>Harga Promo</th>
                                <th>Tampilan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($d = mysqli_fetch_array($data)) : ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= $d['nama_operator'] ?></td>
                                <td><?= $d['nomor'] ?></td>
                                <td><?= $d['harga_promo'] ?></td>
                                <td>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox"
                                            id="flexSwitchCheckDefault-<?= $d['id_promo'] ?>" style="width:27%"
                                            data-id-promo="<?= $d['id_promo'] ?>"
                                            <?= $d['status'] == 1 ? 'checked' : '' ?>>
                                        <label class="form-check-label"
                                            for="flexSwitchCheckDefault-<?= $d['id_promo'] ?>">Tampil</label>
                                    </div>
                                </td>
                                <td>
                                    <a href="promo-edit.php?id=<?= $d['id_promo'] ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-pencil-alt"></i> Edit
                                    </a>
                                    <form action="promo.php" method="POST" id="delete-form-<?= $d['id_promo'] ?>"
                                        style="display: inline;">
                                        <input type="hidden" name="action" value="delete">
                                        <input type="hidden" name="id_promo" value="<?= $d['id_promo'] ?>">
                                        <button type="button" class="btn btn-danger btn-sm confirm-text"
                                            data-form-id="<?= $d['id_promo'] ?>">
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
                            <label>Nomor Telp</label>
                            <select class="selectpicker w-100" data-style="btn-default" name="id_nomor"
                                data-live-search="true" required>
                                <option selected disabled value="">Pilih Nomor</option>
                                <?php 
                                $nomorData = mysqli_query($koneksi, 'SELECT id_nomor, nomor FROM nomor');
                                while($row = mysqli_fetch_assoc($nomorData)) { ?>
                                <option value="<?= $row['id_nomor'] ?>"><?= $row['nomor'] ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="form-floating form-floating-outline mb-4">
                            <input type="text" class="form-control" name="harga_promo" required
                                placeholder="Masukkan Harga Promo">
                            <label for="harga_promo">Harga Promo</label>
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
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('.form-check-input').on('change', function() {
            var id_promo = $(this).data('id-promo');
            var status = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: 'update-status-promo.php',
                method: 'POST',
                data: {
                    id_promo: id_promo,
                    status: status
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        window.location.href =
                            'promo.php';
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
