<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../back_login.php');
    exit();
}

include('../koneksi.php'); // Sesuaikan dengan file koneksi Anda

if (isset($_POST['id_promo']) && isset($_POST['status'])) {
    $id_promo = $_POST['id_promo'];
    $status = $_POST['status'];

    $query = "UPDATE promo SET status = '$status' WHERE id_promo = '$id_promo'";
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['msg'] = 'Status berhasil diupdate!';
        echo json_encode(['status' => 'success']);
    } else {
        $_SESSION['error'] = 'Status gagal diupdate!';
        echo json_encode(['status' => 'error']);
    }

}

?>