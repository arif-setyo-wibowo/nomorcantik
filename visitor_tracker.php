<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$host = 'localhost';
$dbname = 'u339386215_pedagangnomor';
$username = 'Pedagangnomor_itboy123';
$password = 'u339386215_pedagangnomor';

$connection = new mysqli($host, $username, $password, $dbname);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Fungsi untuk mendapatkan IP pengunjung
function getVisitorIP() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        // Jika berada di behind proxy
        return $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // Jika berada di behind proxy atau load balancer
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}

// Track total visitors
function trackTotalVisitors($connection) {
    $query = "UPDATE total_visitors SET count = count + 1 WHERE id = 1";
    if (!$connection->query($query)) {
        die("Error updating total visitors: " . $connection->error);
    }
}

// Track active visitors
function trackActiveVisitors($connection, $visitorIP) {
    $timestamp = time();

    // Insert or update the active visitor record based on IP
    $query = "INSERT INTO active_visitors (ip_address, last_activity) VALUES ('$visitorIP', $timestamp)
              ON DUPLICATE KEY UPDATE last_activity = $timestamp";
    if (!$connection->query($query)) {
        die("Error updating active visitors: " . $connection->error);
    }

    // Remove inactive visitors (those who haven't interacted in the last 5 minutes)
    $expiration_time = time() - 5; // 300 detik = 5 menit
    $connection->query("DELETE FROM active_visitors WHERE last_activity < $expiration_time");
}

// Get active visitors count
function getActiveVisitorsCount($connection) {
    $query = "SELECT COUNT(*) as active_count FROM active_visitors";
    $result = $connection->query($query);
    return $result->fetch_assoc()['active_count'];
}

// Get total visitors count
function getTotalVisitorsCount($connection) {
    $query = "SELECT count FROM total_visitors WHERE id = 1";
    $result = $connection->query($query);
    return $result->fetch_assoc()['count'];
}

// Dapatkan IP pengunjung
$visitorIP = getVisitorIP();

// Track visitors
trackTotalVisitors($connection);
trackActiveVisitors($connection, $visitorIP);

// Return counts
$activeVisitors = getActiveVisitorsCount($connection);
$totalVisitors = getTotalVisitorsCount($connection);

// Return JSON response
echo json_encode(['active' => $activeVisitors, 'total' => $totalVisitors]);

$connection->close();
?>
