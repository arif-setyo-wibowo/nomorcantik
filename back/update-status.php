<?php

include('../koneksi.php'); 
session_start(); 

$response = array();

if (isset($_POST['id_operator']) && isset($_POST['status'])) {
    $id_operator = $_POST['id_operator'];
    $status = $_POST['status'];

    $query = "UPDATE operator SET status = '$status' WHERE id_operator = '$id_operator'";
    if (mysqli_query($koneksi, $query)) {
        $response['status'] = 'success';
        $response['message'] = 'Status berhasil diupdate!';
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Status gagal diupdate!';
    }

    echo json_encode($response);
    exit(); 
}
?>
