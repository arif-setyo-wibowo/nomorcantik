<?php
session_start();
$title = 'Pedagang Nomor Admin | Edit Operator';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['admin'])) {
    header('Location: ../back_login.php');
    exit();
}


include '../koneksi.php';
if (isset($_GET['id'])) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id_operator = intval($_POST['id_operator']);
        $nama_operator = $koneksi->real_escape_string($_POST['nama_operator']);

        $getlogo = "SELECT logo FROM operator WHERE id_operator = $id_operator";
        $result = mysqli_fetch_assoc(mysqli_query($koneksi, $getlogo))['logo'];

        if (isset($_FILES['logo']) && $_FILES['logo']['error'] != UPLOAD_ERR_NO_FILE) {
            unlink("../assets/uploads/" . $result);

            $file_extension = pathinfo($_FILES["logo"]["name"], PATHINFO_EXTENSION);
            $new_file_name = uniqid() . "." . strtolower($file_extension);
            $target_file = "../assets/uploads/" . $new_file_name;

            if (move_uploaded_file($_FILES["logo"]["tmp_name"], $target_file)) {
                $logo = $new_file_name;
            }

            $sql = "UPDATE operator SET nama_operator='$nama_operator', logo='$logo' WHERE id_operator=$id_operator";
        }else{
            $sql = "UPDATE operator SET nama_operator='$nama_operator' WHERE id_operator=$id_operator";
        }

        if ($koneksi->query($sql) === true) {
            $_SESSION['msg'] = 'Operator berhasil diperbarui!';
        } else {
            $_SESSION['error'] = 'Operator gagal diperbarui!';
        }

        header('Location: operator.php');
        exit();
    } else {
        $id_operator = intval($_GET['id']);
        $sql = "SELECT * FROM operator WHERE id_operator = $id_operator";
        $result = mysqli_query($koneksi, $sql);

        if ($result && mysqli_num_rows($result) > 0) {
            $operator = mysqli_fetch_assoc($result);
        } else {
            $_SESSION['error'] = 'Operator tidak ditemukan!';
            header('Location: operator.php');
            exit();
        }
    }
} else {
    header('Location: operator.php');
    exit();
}

?>

<?php include 'header.php'; ?>
<div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="py-3 mb-4"><span class="text-muted fw-light">Pedagang Nomor /</span> Operator</h4>

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
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="hidden" name="id_operator" value="<?= $operator['id_operator'] ?>" hidden>
                            <input type="text" class="form-control" id="basic-default-fullname" name="nama_operator"
                                placeholder="Operator" value="<?= $operator['nama_operator'] ?>" required />
                            <label for="basic-default-fullname">Nama Operator</label>
                        </div>
                        <div class="form-floating form-floating-outline mb-4">
                            <input type="file" class="form-control" name="logo" />
                            <label for="logo">Logo</label>
                            <p class="text-danger">* kosongkan jika tidak ingin mengubah gambar</p>
                        </div>
                        <button type="submit" class="btn btn-primary">Ubah</button>
                        <a href="operator.php"><button type="button" class="btn btn-danger">Batal</button></a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<?php include 'footer.php'; ?>
