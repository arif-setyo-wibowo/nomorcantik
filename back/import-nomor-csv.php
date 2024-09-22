<?php
session_start();
include '../koneksi.php';

if (!isset($_SESSION['admin'])) {
    header('Location: ../back_login.php');
    exit();
}


function filter($type, $phone)
{
    $change = '';
    if (!preg_match('/[^+0-9]/', $phone)) {
        if (substr($phone, 0, 3) == '+62') {
            $change = '0' . substr($phone, 3);
        } else if (substr($phone, 0, 2) == '62') {
            $change = '0' . substr($phone, 2);
        } else if (substr($phone, 0, 1) == '0') {
            $change = $phone;
        }
    }
    $data = ['full' => $change, 'code' => substr($change, 0, 4)];
    return $data[$type];
}

function operator($number)
{
    $hlr = filter('code', $number);
    $data = [
        ['name' => 'Telkomsel', 'code' => ['0811', '0812', '0813', '0821', '0822', '0823', '0852', '0853']],
        ['name' => 'By.U', 'code' => ['0851']],
        ['name' => 'Indosat', 'code' => ['0814', '0815', '0816', '0855', '0856', '0857', '0858']],
        ['name' => 'XL', 'code' => ['0817', '0818', '0819', '0859', '0877', '0878', '0879']],
        ['name' => 'Axis', 'code' => ['0831', '0832', '0833', '0838']],
        ['name' => 'Smartfren', 'code' => ['0881', '0882', '0883', '0887', '0888', '0889']],
        ['name' => 'Three', 'code' => ['0895', '0896', '0897', '0898', '0899']]
    ];
    $result = 'unknown';
    foreach ($data as $r) {
        if (in_array($hlr, $r['code'])) {
            $result = $r['name'];
            break;
        }
    }
    return $result;
}

if ($_FILES['csv']['size'] > 0) {
    $file = $_FILES['csv']['tmp_name'];
    $handle = fopen($file, "r");
    $i = 0;
    while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
        if ($i > 0) {
            $nama_operator = operator(preg_replace('/\s+/', '', $data[0]));
            $sql = "SELECT id_operator FROM operator WHERE nama_operator LIKE '$nama_operator' OR nama_operator LIKE '" . $nama_operator . " Prabayar'";
            $getId = mysqli_fetch_assoc(mysqli_query($koneksi, $sql));
            $id_operator = $getId['id_operator'] ?? null;

            $checkQuery = "SELECT id_nomor FROM nomor WHERE nomor = ?";
            $stmtCheck = $koneksi->prepare($checkQuery);
            $stmtCheck->bind_param("s", $data[0]);
            $stmtCheck->execute();
            $stmtCheck->store_result();

            if ($stmtCheck->num_rows > 0) {
                $import = "UPDATE nomor SET id_operator = ?, harga = ? WHERE nomor = ?";
                $stmtImport = $koneksi->prepare($import);
                $stmtImport->bind_param("iss", $id_operator, $data[1], $data[0]);
            } else {
                $import = "INSERT INTO nomor (id_operator, nomor, harga, tipe) VALUES (?, ?, ?, 'reseller')";
                $stmtImport = $koneksi->prepare($import);
                $stmtImport->bind_param("iss", $id_operator, $data[0], $data[1]);
            }

            $stmtImport->execute();
            $stmtImport->close();
            $stmtCheck->close();

        }
        $i++;
    }

    fclose($handle);
    $_SESSION['msg'] = 'Berhasil Mengimport Nomor!';
    header('Location:nomor.php');
    exit();
}
