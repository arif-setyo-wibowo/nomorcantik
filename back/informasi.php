<?php
session_start();
$title = 'pedagangnomor Admin | Informasi';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['admin'])) {
    header('Location: ../back_login.php');
    exit();
}

include '../koneksi.php';

$no = 1;
$data = mysqli_query($koneksi, 'SELECT * FROM informasi');



?>
<?php include 'header.php'; ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">pedagangnomor /</span> Informasi</h4>
    <div class="row">
        <!-- Wa Section -->
        <div class="col-md-12">
            <div class="card mb-4">
                <div class="card-header p-0">
                    <!-- Success & Error Messages -->
                    <?php if (isset($_SESSION['msg'])): ?>
                    <script>
                        Swal.fire({
                            title: 'Success!',
                            text: '<?php echo $_SESSION['msg']; ?>',
                            icon: 'success',
                            confirmButtonClass: 'btn btn-primary'
                        });
                    </script>
                    <?php unset($_SESSION['msg']); endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                    <script>
                        Swal.fire({
                            title: 'Error!',
                            text: '<?php echo $_SESSION['error']; ?>',
                            icon: 'error',
                            confirmButtonClass: 'btn btn-primary'
                        });
                    </script>
                    <?php unset($_SESSION['error']); endif; ?>

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
                                <th>Informasi</th>
                                <th>Tampilan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($d = mysqli_fetch_array($data)): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $d['deskripsi']; ?></td>
                                <td><div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox"
                                            id="flexSwitchCheckDefault-<?= $d['id_informasi'] ?>" style="width:97%"
                                            data-id-informasi="<?= $d['id_informasi'] ?>"
                                            <?= $d['status'] == 1 ? 'checked' : '' ?>>
                                        <label class="form-check-label"
                                            for="flexSwitchCheckDefault-<?= $d['id_informasi'] ?>">Tampil</label>
                                    </div>
                                </td>
                                <td>
                                    <a href="informasi-edit.php?id_informasi=<?php echo $d['id_informasi']; ?>" class="btn btn-info btn-sm">
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

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<!-- / Content -->
<script>
     $(document).ready(function() {
     $('.form-check-input').on('change', function() {
        var id_informasi = $(this).data('id-informasi');
        var status = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: 'update-status-informasi.php',
                method: 'POST',
                data: {
                    id_informasi: id_informasi,
                    status: status
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        console.log('Session msg set successfully.');
                        window.location.href =
                        'informasi.php';
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

<?php include 'footer.php'; ?>
