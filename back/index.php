<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: ../back_login.php');
    exit();
} else {
    header('Location: operator.php');
    exit();
}

