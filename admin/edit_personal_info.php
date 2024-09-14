<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require_once 'db_connect.php';

$message = '';
$message_type = '';

// Mevcut bilgileri veritabanından çek
$sql = "SELECT * FROM personal_info WHERE id=1";
$result = $conn->query($sql);
$info = $result->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // ... (mevcut POST işleme kodu)
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kişisel Bilgileri Düzenle</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100" x-data="{ activeTab: 'basic' }">
    <div class="flex min-h-screen">
        <?php include 'sidebar.php'; ?>
        <div class="flex-1 p-10 ml-64">
            <h1 class="text-4xl font-bold mb-8 text-gray-800">Kişisel Bilgileri Düzenle</h1>
            
            <?php if ($message): ?>
                <div class="<?php echo $message_type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'; ?> border px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline"><?php echo $message; ?></span>
                </div>
            <?php endif; ?>
            
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="flex border-b">
                    <button @click="activeTab = 'basic'" :class="{ 'bg-blue-500 text-white': activeTab === 'basic', 'bg-gray-200': activeTab !== 'basic' }" class="flex-1 py-4 px-6 text-lg font-semibold focus:outline-none transition duration-300">
                        Temel Bilgiler
                    </button>
                    <button @click="activeTab = 'images'" :class="{ 'bg-blue-500 text-white': activeTab === 'images', 'bg-gray-200': activeTab !== 'images' }" class="flex-1 py-4 px-6 text-lg font-semibold focus:outline-none transition duration-300">
                        Görseller
                    </button>
                </div>
                
                <form method="POST" enctype="multipart/form-data" class="p-6">
                    <div x-show="activeTab === 'basic'">
                        <div class="mb-6">
                            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Ad Soyad</label>
                            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($info['name'] ?? ''); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300">
                        </div>
                        <div class="mb-6">
                            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Unvan</label>
                            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($info['title'] ?? ''); ?>" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300">
                        </div>
                        <div class="mb-6">
                            <label for="bio" class="block text-gray-700 text-sm font-bold mb-2">Biyografi</label>
                            <textarea id="bio" name="bio" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300 h-32"><?php echo htmlspecialchars($info['bio'] ?? ''); ?></textarea>
                        </div>
                    </div>
                    
                    <div x-show="activeTab === 'images'" class="space-y-6">
                        <div>
                            <label for="cover_image" class="block text-gray-700 text-sm font-bold mb-2">Kapak Fotoğrafı</label>
                            <input type="file" id="cover_image" name="cover_image" class="w-full">
                            <?php if (!empty($info['cover_image'])): ?>
                                <img src="../<?php echo htmlspecialchars($info['cover_image']); ?>" alt="Mevcut Kapak Fotoğrafı" class="mt-2 w-full h-48 object-cover rounded-lg shadow-md">
                            <?php endif; ?>
                        </div>
                        <div>
                            <label for="avatar" class="block text-gray-700 text-sm font-bold mb-2">Avatar</label>
                            <input type="file" id="avatar" name="avatar" class="w-full">
                            <?php if (!empty($info['avatar'])): ?>
                                <img src="../<?php echo htmlspecialchars($info['avatar']); ?>" alt="Mevcut Avatar" class="mt-2 w-32 h-32 object-cover rounded-full shadow-md">
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="mt-8">
                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-300 transform hover:scale-105">
                            Güncelle
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>