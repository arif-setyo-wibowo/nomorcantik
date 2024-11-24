<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../back_login.php');
    exit();
}

include('../koneksi.php'); // Sesuaikan dengan file koneksi Anda

if (isset($_POST['id_informasi']) && isset($_POST['status'])) {
    $id_informasi = $_POST['id_informasi'];
    $status = $_POST['status'];

    $query = "UPDATE informasi SET status = '$status' WHERE id_informasi = '$id_informasi'";
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['msg'] = 'Status berhasil diupdate!';
        echo json_encode(['status' => 'success']);
    } else {
        $_SESSION['error'] = 'Status gagal diupdate!';
        echo json_encode(['status' => 'error']);
    }

}

?>