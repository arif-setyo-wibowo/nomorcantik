<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../back_login.php');
    exit();
}

include('../koneksi.php'); // Sesuaikan dengan file koneksi Anda

if (isset($_POST['id_kolom']) && isset($_POST['status'])) {
    $id_kolom = $_POST['id_kolom'];
    $status = $_POST['status'];

    $query = "UPDATE kolom SET status = '$status' WHERE id_kolom = '$id_kolom'";
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['msg'] = 'Status berhasil diupdate!';
        echo json_encode(['status' => 'success']);
    } else {
        $_SESSION['error'] = 'Status gagal diupdate!';
        echo json_encode(['status' => 'error']);
    }

}

?>