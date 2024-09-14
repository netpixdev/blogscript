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
    if (isset($_POST['add_skill'])) {
        $name = $_POST['skill_name'];
        $color = $_POST['skill_color'];
        
        $sql = "INSERT INTO skills (name, color) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $name, $color);
        
        if ($stmt->execute()) {
            $message = "Yeni yetenek başarıyla eklendi!";
            $message_type = "success";
        } else {
            $message = "Hata: " . $conn->error;
            $message_type = "error";
        }
        $stmt->close();
    } elseif (isset($_POST['delete_skill'])) {
        $id = $_POST['skill_id'];
        
        $sql = "DELETE FROM skills WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id);
        
        if ($stmt->execute()) {
            $message = "Yetenek başarıyla silindi!";
            $message_type = "success";
        } else {
            $message = "Hata: " . $conn->error;
            $message_type = "error";
        }
        $stmt->close();
    }
}

$sql = "SELECT * FROM skills";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yetenekleri Düzenle</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex">
        <?php include 'sidebar.php'; ?>
        <div class="flex-1 p-10 ml-64">
            <h1 class="text-3xl font-bold mb-6">Yetenekleri Düzenle</h1>
            <?php if ($message): ?>
                <div class="<?php echo $message_type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700'; ?> border px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline"><?php echo $message; ?></span>
                </div>
            <?php endif; ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-2xl font-bold mb-4">Yeni Yetenek Ekle</h2>
                    <form method="POST" class="space-y-4">
                        <div>
                            <label for="skill_name" class="block text-gray-700 font-bold mb-2">Yetenek Adı</label>
                            <input type="text" id="skill_name" name="skill_name" required class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="skill_color" class="block text-gray-700 font-bold mb-2">Renk Sınıfı</label>
                            <input type="text" id="skill_color" name="skill_color" required class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="örn: bg-blue-500">
                        </div>
                        <button type="submit" name="add_skill" class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-300">Yetenek Ekle</button>
                    </form>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h2 class="text-2xl font-bold mb-4">Mevcut Yetenekler</h2>
                    <div class="space-y-2">
                        <?php while($row = $result->fetch_assoc()): ?>
                            <div class="flex items-center justify-between bg-gray-100 p-3 rounded-md">
                                <span class="<?php echo htmlspecialchars($row['color']); ?> text-white px-3 py-1 rounded-full text-sm font-semibold"><?php echo htmlspecialchars($row['name']); ?></span>
                                <form method="POST" class="inline">
                                    <input type="hidden" name="skill_id" value="<?php echo $row['id']; ?>">
                                    <button type="submit" name="delete_skill" class="text-red-500 hover:text-red-700 transition duration-300">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>