<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['admin'])) {
    header('Location: ../back_login.php'); 
    exit();
}

if (isset($_GET['id_kolom'])) {
    $id_kolom = intval($_GET['id_kolom']);

    // Fetch the current logo filename from the database
    $kolomData = mysqli_query($koneksi, "SELECT logo FROM kolom WHERE id_kolom = $id_kolom");
    $currentKolom = mysqli_fetch_assoc($kolomData);

    if ($currentKolom && !empty($currentKolom['logo'])) {
        $logoFile = '../assets/uploads/' . $currentKolom['logo'];

        // Attempt to delete the file from the server
        if (file_exists($logoFile)) {
            unlink($logoFile);
        }

        // Update the database to remove the logo
        $updateQuery = "UPDATE kolom SET logo=NULL WHERE id_kolom='$id_kolom'";
        if (mysqli_query($koneksi, $updateQuery)) {
            $_SESSION['msg'] = 'Logo berhasil dihapus!';
        } else {
            $_SESSION['error'] = 'Gagal menghapus logo dari database!';
        }
    } else {
        $_SESSION['error'] = 'Logo tidak ditemukan!';
    }
} else {
    $_SESSION['error'] = 'ID kolom tidak valid!';
}

header('Location: kolom-kiri.php?id_kolom=' . $id_kolom); // Ganti dengan halaman yang sesuai
exit();
?>
