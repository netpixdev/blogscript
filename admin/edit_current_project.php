<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require_once 'db_connect.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $status = $_POST['status'];
    $icon_class = $_POST['icon_class'];

    $sql = "UPDATE current_project SET name = ?, status = ?, icon_class = ? WHERE id = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $status, $icon_class);

    if ($stmt->execute()) {
        $message = "Güncel proje başarıyla güncellendi.";
        $message_type = "success";
    } else {
        $message = "Hata: " . $conn->error;
        $message_type = "error";
    }

    $stmt->close();
}

$sql = "SELECT * FROM current_project WHERE id = 1";
$result = $conn->query($sql);
if (!$result) {
    die("Sorgu hatası: " . $conn->error);
}
$current_project = $result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Güncel Proje Düzenle</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <?php include 'sidebar.php'; ?>
        <div class="flex-1 p-10 ml-64">
            <div class="max-w-2xl mx-auto">
                <h1 class="text-3xl font-bold mb-8 text-center text-gray-800">Güncel Proje Düzenle</h1>
                
                <?php if ($message): ?>
                    <div class="<?php echo $message_type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'; ?> border px-4 py-3 rounded-lg mb-6" role="alert">
                        <p class="font-bold"><?php echo $message_type === 'success' ? 'Başarılı!' : 'Hata!'; ?></p>
                        <p class="text-sm"><?php echo $message; ?></p>
                    </div>
                <?php endif; ?>
                
                <div class="bg-white shadow-md rounded-lg overflow-hidden">
                    <div class="p-6 bg-gray-50 border-b border-gray-200">
                        <h2 class="text-xl font-semibold text-gray-700">Proje Detayları</h2>
                    </div>
                    <form method="POST" class="p-6">
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Proje Adı</label>
                            <input class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300" id="name" type="text" name="name" value="<?php echo htmlspecialchars($current_project['name']); ?>" required>
                        </div>
                        <div class="mb-6">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Durum</label>
                            <input class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300" id="status" type="text" name="status" value="<?php echo htmlspecialchars($current_project['status']); ?>" required>
                        </div>
                        <div class="mb-6">
                            <label for="icon_class" class="block text-sm font-medium text-gray-700 mb-2">İkon Sınıfı</label>
                            <div class="flex items-center">
                                <input class="flex-grow px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300" id="icon_class" type="text" name="icon_class" value="<?php echo htmlspecialchars($current_project['icon_class']); ?>" required>
                                <span class="bg-gray-100 px-3 py-2 border border-l-0 border-gray-300 rounded-r-md">
                                    <i class="<?php echo htmlspecialchars($current_project['icon_class']); ?>"></i>
                                </span>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Font Awesome ikon sınıfını girin (örn: fas fa-project-diagram)</p>
                        </div>
                        <div class="flex items-center justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:shadow-outline transition duration-300">
                                Güncelle
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>