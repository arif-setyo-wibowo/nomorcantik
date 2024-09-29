<?php
session_start();
$title = 'pedagangnomor Admin | Toko Online';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['admin'])) {
    header('Location: ../back_login.php'); 
    exit();
}

include '../koneksi.php';

$no = 1;
$data = mysqli_query($koneksi, 'SELECT * FROM online_shop');

?>
<?php include 'header.php'; ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">pedagangnomor /</span> Toko Online</h4>
    <div class="row">
        <!-- Wa Section -->
        <div class="col-md-6">
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
                            <button type="button" class="nav-link waves-effect active" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-top-home" aria-selected="false">
                                    Toko Online
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Toko Online</th>
                                <th>Link</th>
                                <th>Tampilan</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($d = mysqli_fetch_array($data)): ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $d['nama_toko']; ?></td>
                                <td><?php echo $d['link']; ?></td>
                                <td>
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox"
                                            id="flexSwitchCheckDefault-<?= $d['id_online_shop'] ?>" style="width:67%"
                                            data-id-online="<?= $d['id_online_shop'] ?>"
                                            <?= $d['status'] == 1 ? 'checked' : '' ?>>
                                        <label class="form-check-label"
                                            for="flexSwitchCheckDefault-<?= $d['id_online_shop'] ?>">Tampil</label>
                                    </div>
                                </td>
                                <td>
                                    <a href="tokopedia-shopee-edit.php?id_online_shop=<?php echo $d['id_online_shop']; ?>" class="btn btn-info btn-sm">
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
        var id_online_shop = $(this).data('id-online');
        var status = $(this).is(':checked') ? 1 : 0;

            $.ajax({
                url: 'update-status-online.php',
                method: 'POST',
                data: {
                    id_online_shop: id_online_shop,
                    status: status
                },
                success: function(response) {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        console.log('Session msg set successfully.');
                        window.location.href =
                        'tokopedia-shopee.php';
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
