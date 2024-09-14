<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require_once 'db_connect.php';

// Veritabanı sorgularını tek bir bağlantı üzerinden yapalım
$sql_personal = "SELECT * FROM personal_info WHERE id=1";
$result_personal = $conn->query($sql_personal);
$personal_info = $result_personal->fetch_assoc();

$sql_skills = "SELECT COUNT(*) as skill_count FROM skills";
$result_skills = $conn->query($sql_skills);
$skill_count = $result_skills->fetch_assoc()['skill_count'];

$sql_projects = "SELECT COUNT(*) as project_count FROM projects";
$result_projects = $conn->query($sql_projects);
$project_count = $result_projects->fetch_assoc()['project_count'];

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <?php include 'sidebar.php'; ?>
        <div class="flex-1 p-10 ml-64">
            <h1 class="text-3xl font-bold mb-8 text-gray-800">Hoş Geldiniz, Admin!</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Kişisel Bilgiler Card -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-5 bg-blue-500 text-white">
                        <h2 class="text-xl font-semibold">Kişisel Bilgiler</h2>
                    </div>
                    <div class="p-5">
                        <p class="mb-2"><strong>Ad Soyad:</strong> <?php echo htmlspecialchars($personal_info['name'] ?? ''); ?></p>
                        <p class="mb-4"><strong>Unvan:</strong> <?php echo htmlspecialchars($personal_info['title'] ?? ''); ?></p>
                        <a href="edit_personal_info.php" class="text-blue-500 hover:underline">Düzenle <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                </div>

                <!-- Yetenekler Card -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-5 bg-green-500 text-white">
                        <h2 class="text-xl font-semibold">Yetenekler</h2>
                    </div>
                    <div class="p-5">
                        <p class="text-3xl font-bold mb-4"><?php echo $skill_count; ?></p>
                        <p class="mb-4">Toplam yetenek</p>
                        <a href="edit_skills.php" class="text-green-500 hover:underline">Düzenle <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                </div>

                <!-- Projeler Card -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-5 bg-purple-500 text-white">
                        <h2 class="text-xl font-semibold">Projeler</h2>
                    </div>
                    <div class="p-5">
                        <p class="text-3xl font-bold mb-4"><?php echo $project_count; ?></p>
                        <p class="mb-4">Toplam proje</p>
                        <a href="edit_projects.php" class="text-purple-500 hover:underline">Düzenle <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                </div>

                <!-- Güncel Proje Card -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-5 bg-yellow-500 text-white">
                        <h2 class="text-xl font-semibold">Güncel Proje</h2>
                    </div>
                    <div class="p-5">
                        <p class="mb-4">Şu anki projenizi güncelleyin</p>
                        <a href="edit_current_project.php" class="text-yellow-500 hover:underline">Düzenle <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                </div>

                <!-- İletişim Bilgileri Card -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-5 bg-red-500 text-white">
                        <h2 class="text-xl font-semibold">İletişim Bilgileri</h2>
                    </div>
                    <div class="p-5">
                        <p class="mb-4">İletişim bilgilerinizi güncelleyin</p>
                        <a href="edit_contact_info.php" class="text-red-500 hover:underline">Düzenle <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                </div>

                <!-- Güncel Müzik Card -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-5 bg-indigo-500 text-white">
                        <h2 class="text-xl font-semibold">Güncel Müzik</h2>
                    </div>
                    <div class="p-5">
                        <p class="mb-4">Şu an dinlediğiniz müziği güncelleyin</p>
                        <a href="edit_current_music.php" class="text-indigo-500 hover:underline">Düzenle <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>