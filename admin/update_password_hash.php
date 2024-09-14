<?php
require_once 'db_connect.php';  // "admin/" kısmını kaldırdık

$current_password = 'yourpasswordhere'; // Mevcut şifreniz
$hashed_password = password_hash($current_password, PASSWORD_DEFAULT);

$sql = "UPDATE admin_users SET password = ? WHERE id = 1"; // id = 1 varsayımı ile
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $hashed_password);

if ($stmt->execute()) {
    echo "Şifre başarıyla hash'lendi ve güncellendi.";
} else {
    echo "Hata: " . $conn->error;
}

$stmt->close();
$conn->close();
?>