<?php
session_start();
$title = 'NOMORCANTIK Admin | Edit Kategori';

// Cek apakah pengguna sudah login
// if (!isset($_SESSION['admin'])) {
//     header('Location: ../login_admin.php'); 
//     exit();
// }

include '../koneksi.php';
if (isset($_GET['id'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id_kategori = intval($_POST['id_kategori']);
        $nama_kategori = $koneksi->real_escape_string($_POST['nama_kategori']);
        $sql = "UPDATE kategori SET nama_kategori='$nama_kategori' WHERE id_kategori=$id_kategori";

        if ($koneksi->query($sql) === true) {
            $_SESSION['msg'] = 'Kategori berhasil diperbarui!';
        } else {
            $_SESSION['error'] = 'Kategori gagal diperbarui!';
        }

        header('Location: kategori.php');
        exit();
    } else {
        $id_kategori = intval($_GET['id']);
        $sql = "SELECT * FROM kategori WHERE id_kategori = $id_kategori";
        $result = mysqli_query($koneksi, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $kategori = mysqli_fetch_assoc($result);
        } else {
            $_SESSION['error'] = 'Kategori tidak ditemukan!';
            header('Location: kategori.php');
            exit();
        }
    }
} else {
    header('Location: kategori.php');
    exit();
}

?>

<?php include 'header.php'; ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">SMART PPA /</span> Kategori</h4>

    <div class="card mb-4">
        <div class="card-header p-0">
            <div class="nav-align-top">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button type="button" class="nav-link waves-effect active" role="tab" data-bs-toggle="tab"
                            data-bs-target="#navs-top-profile" aria-controls="navs-top-profile" aria-selected="true">
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
                    <form action="" method="POST">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="hidden" name="id_kategori" value="<?= $kategori['id_kategori'] ?>" hidden>
                            <input type="text" class="form-control" id="basic-default-fullname" name="nama_kategori"
                                placeholder="Kategori" value="<?= $kategori['nama_kategori'] ?>" required />
                            <label for="basic-default-fullname">Kategori</label>
                        </div>
                        <button type="submit" class="btn btn-primary">Ubah</button>
                        <a href="kategori.php"><button type="button" class="btn btn-danger">Batal</button></a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include 'footer.php'; ?>
