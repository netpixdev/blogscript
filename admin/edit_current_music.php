<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

require_once 'db_connect.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $platform = $_POST['platform'];
    $artist = $_POST['artist'];
    $song = $_POST['song'];

    $sql = "UPDATE current_music SET platform = ?, artist = ?, song = ? WHERE id = 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $platform, $artist, $song);

    if ($stmt->execute()) {
        $message = "Güncel müzik bilgileri başarıyla güncellendi.";
    } else {
        $message = "Hata: " . $conn->error;
    }

    $stmt->close();
}

$sql = "SELECT * FROM current_music WHERE id = 1";
$result = $conn->query($sql);
$current_music = $result->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Güncel Müzik Düzenle</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex">
        <?php include 'sidebar.php'; ?>
        <div class="flex-1 p-10 ml-64">
            <h1 class="text-3xl font-bold mb-6">Güncel Müzik Düzenle</h1>
            <?php if ($message): ?>
                <p class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4"><?php echo $message; ?></p>
            <?php endif; ?>
            <form method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="platform">
                        Platform
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="platform" type="text" name="platform" value="<?php echo htmlspecialchars($current_music['platform']); ?>" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="artist">
                        Sanatçı
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="artist" type="text" name="artist" value="<?php echo htmlspecialchars($current_music['artist']); ?>" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="song">
                        Şarkı
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="song" type="text" name="song" value="<?php echo htmlspecialchars($current_music['song']); ?>" required>
                </div>
                <div class="flex items-center justify-between">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Güncelle
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>