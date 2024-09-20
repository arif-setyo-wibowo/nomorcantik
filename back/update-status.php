<?php
session_start();
// Koneksi ke database
include('../koneksi.php'); // Sesuaikan dengan file koneksi Anda

if (isset($_POST['id_operator']) && isset($_POST['status'])) {
    $id_operator = $_POST['id_operator'];
    $status = $_POST['status'];

    // Update status di database
    $query = "UPDATE operator SET status = '$status' WHERE id_operator = '$id_operator'";
    if (mysqli_query($koneksi, $query)) {
        $_SESSION['msg'] = 'Status berhasil diupdate!';
        echo json_encode(['status' => 'success']);
    } else {
        $_SESSION['error'] = 'Status gagal diupdate!';
        echo json_encode(['status' => 'error']);
    }

}

?>
