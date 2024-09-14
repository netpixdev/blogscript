<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require_once 'db_connect.php';

$message = '';
$message_type = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add_project'])) {
        $title = $_POST['project_title'];
        $description = $_POST['project_description'];
        $icon = $_POST['project_icon'];
        $link = $_POST['project_link'];
        
        $sql = "INSERT INTO projects (title, description, icon, link) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $title, $description, $icon, $link);
        
        if ($stmt->execute()) {
            $message = "Yeni proje başarıyla eklendi!";
            $message_type = "success";
        } else {
            $message = "Hata: " . $conn->error;
            $message_type = "error";
        }
        $stmt->close();
    } elseif (isset($_POST['delete_project'])) {
        $id = $_POST['project_id'];
        
        $sql = "DELETE FROM projects WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $message = "Proje başarıyla silindi!";
            $message_type = "success";
        } else {
            $message = "Hata: " . $conn->error;
            $message_type = "error";
        }
        $stmt->close();
    }
}

$sql = "SELECT * FROM projects";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projeleri Düzenle</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</head>
<body class="bg-gray-100" x-data="{ showModal: false, projectToDelete: null }">
    <div class="flex min-h-screen">
        <?php include 'sidebar.php'; ?>
        <div class="flex-1 p-10 ml-64">
            <h1 class="text-4xl font-bold mb-8 text-gray-800">Projeleri Düzenle</h1>
            
            <?php if ($message): ?>
                <div class="<?php echo $message_type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'; ?> border px-4 py-3 rounded relative mb-6" role="alert">
                    <span class="block sm:inline"><?php echo $message; ?></span>
                </div>
            <?php endif; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">Yeni Proje Ekle</h2>
                    <form method="POST" class="space-y-4">
                        <div>
                            <label for="project_title" class="block text-gray-700 font-bold mb-2">Proje Başlığı</label>
                            <input type="text" id="project_title" name="project_title" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300">
                        </div>
                        <div>
                            <label for="project_description" class="block text-gray-700 font-bold mb-2">Proje Açıklaması</label>
                            <textarea id="project_description" name="project_description" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300 h-32"></textarea>
                        </div>
                        <div>
                            <label for="project_icon" class="block text-gray-700 font-bold mb-2">Proje İkonu (Font Awesome class)</label>
                            <input type="text" id="project_icon" name="project_icon" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300" placeholder="örn: fas fa-robot">
                        </div>
                        <div>
                            <label for="project_link" class="block text-gray-700 font-bold mb-2">Proje Linki</label>
                            <input type="url" id="project_link" name="project_link" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300" placeholder="https://example.com">
                        </div>
                        <button type="submit" name="add_project" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300 transform hover:scale-105">Proje Ekle</button>
                    </form>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <h2 class="text-2xl font-bold mb-6 text-gray-800">Mevcut Projeler</h2>
                    <div class="space-y-4">
                        <?php while($row = $result->fetch_assoc()): ?>
                            <div class="flex items-center justify-between bg-gray-50 p-4 rounded-md hover:shadow-md transition duration-300">
                                <div>
                                    <h3 class="font-bold text-lg"><?php echo htmlspecialchars($row['title']); ?></h3>
                                    <p class="text-sm text-gray-600"><?php echo htmlspecialchars($row['description']); ?></p>
                                    <div class="mt-2">
                                        <i class="<?php echo htmlspecialchars($row['icon']); ?> text-blue-500 mr-2"></i>
                                        <?php if (!empty($row['link'])): ?>
                                            <a href="<?php echo htmlspecialchars($row['link']); ?>" target="_blank" class="text-blue-500 hover:underline">Proje Linki</a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <button @click="showModal = true; projectToDelete = <?php echo $row['id']; ?>" class="text-red-500 hover:text-red-700 transition duration-300">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div x-show="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" x-cloak>
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Projeyi Sil</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Bu projeyi silmek istediğinizden emin misiniz? Bu işlem geri alınamaz.
                    </p>
                </div>
                <div class="items-center px-4 py-3">
                    <form method="POST">
                        <input type="hidden" name="project_id" :value="projectToDelete">
                        <button @click="showModal = false" type="button" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-24 mr-2 hover:bg-gray-600">İptal</button>
                        <button type="submit" name="delete_project" class="px-4 py-2 bg-red-500 text-white text-base font-medium rounded-md w-24 hover:bg-red-600">Sil</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>