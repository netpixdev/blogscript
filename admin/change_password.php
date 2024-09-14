<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

require_once 'db_connect.php';

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Mevcut şifreyi kontrol et
    $sql = "SELECT password FROM admin_users WHERE id = 1"; // Varsayılan olarak ilk admin kullanıcısı
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if (password_verify($current_password, $row['password'])) {
        if ($new_password === $confirm_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            $update_sql = "UPDATE admin_users SET password = ? WHERE id = 1";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("s", $hashed_password);
            
            if ($stmt->execute()) {
                $message = "Şifreniz başarıyla güncellendi.";
                $message_type = "success";
            } else {
                $message = "Şifre güncellenirken bir hata oluştu.";
                $message_type = "error";
            }
            $stmt->close();
        } else {
            $message = "Yeni şifre ve onay şifresi eşleşmiyor.";
            $message_type = "error";
        }
    } else {
        $message = "Mevcut şifre yanlış.";
        $message_type = "error";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Şifre Değiştir</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <?php include 'sidebar.php'; ?>
        <div class="flex-1 p-10 ml-64">
            <h1 class="text-3xl font-bold mb-8 text-gray-800">Şifre Değiştir</h1>
            
            <?php if ($message): ?>
                <div class="<?php echo $message_type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'; ?> border px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline"><?php echo $message; ?></span>
                </div>
            <?php endif; ?>

            <div class="bg-white p-8 rounded-lg shadow-md max-w-md">
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="space-y-6">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Mevcut Şifre</label>
                        <input type="password" id="current_password" name="current_password" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">Yeni Şifre</label>
                        <input type="password" id="new_password" name="new_password" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-1">Yeni Şifre (Tekrar)</label>
                        <input type="password" id="confirm_password" name="confirm_password" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-300">
                        Şifreyi Değiştir
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>