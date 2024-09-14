<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'admin/db_connect.php';

$sql_personal = "SELECT * FROM personal_info WHERE id=1";
$result_personal = $conn->query($sql_personal);
if (!$result_personal) {
    die("Sorgu hatası (personal_info): " . $conn->error);
}
$personal_info = $result_personal->fetch_assoc();

$sql_skills = "SELECT * FROM skills";
$result_skills = $conn->query($sql_skills);
if (!$result_skills) {
    die("Sorgu hatası (skills): " . $conn->error);
}

$sql_projects = "SELECT * FROM projects";
$result_projects = $conn->query($sql_projects);
if (!$result_projects) {
    die("Sorgu hatası (projects): " . $conn->error);
}

$sql_current_project = "SELECT * FROM current_project WHERE id=1";
$result_current_project = $conn->query($sql_current_project);
if (!$result_current_project) {
    die("Sorgu hatası (current_project): " . $conn->error);
}
$current_project = $result_current_project->fetch_assoc();

$sql_contact = "SELECT * FROM contact_info WHERE id=1";
$result_contact = $conn->query($sql_contact);
if (!$result_contact) {
    die("Sorgu hatası (contact_info): " . $conn->error);
}
$contact_info = $result_contact->fetch_assoc();

$sql_music = "SELECT * FROM current_music WHERE id=1";
$result_music = $conn->query($sql_music);
if (!$result_music) {
    die("Sorgu hatası (current_music): " . $conn->error);
}
$current_music = $result_music->fetch_assoc();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>netpixdev - Proje Blog</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['DM Sans', 'sans-serif'],
                    },
                }
            }
        }
    </script>
</head>
<body class="animated-bg-main min-h-screen font-sans flex flex-col justify-between">
    <header class="sticky-nav w-full">
        <div class="content-width p-4">
            <nav class="w-full p-4 bg-black rounded-lg shadow-lg">
                <div class="flex flex-row items-center justify-between">
                    <div class="text-xl sm:text-2xl font-bold text-white flex items-center">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8 mr-2" viewBox="0 0 100 100">
                            <!-- SVG içeriği -->
                        </svg>
                        <span class="text-blue-400">netpix</span>dev
                    </div>
                    <button class="coffee-button bg-gradient-to-r from-yellow-600 to-yellow-500 text-black px-3 py-1 sm:px-4 sm:py-2 rounded-full flex items-center space-x-2 hover:from-yellow-500 hover:to-yellow-400 transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:ring-opacity-50">
                        <i class="fas fa-coffee text-lg sm:text-xl coffee-icon"></i>
                        <span class="text-sm sm:text-base">Kahve Ismarla</span>
                    </button>
                </div>
            </nav>
        </div>
    </header>

    <main class="flex-grow w-full">
        <div class="content-width p-4">
            <div class="flex flex-col lg:flex-row justify-between space-y-4 lg:space-y-0 lg:space-x-4">
                <!-- Sol Sütun: Profil Kartı -->
                <div class="custom-bg p-6 w-full lg:w-[60%] rounded-lg mb-4 lg:mb-0">
                    <div class="relative mb-16">
                        <img src="<?php echo htmlspecialchars($personal_info['cover_image'] ?? ''); ?>" alt="Kapak Fotoğrafı" class="w-full h-48 object-cover rounded-lg">
                        <div class="absolute bottom-0 left-0 w-full h-1/2 bg-gradient-to-t from-black to-transparent"></div>
                        <img src="<?php echo htmlspecialchars($personal_info['avatar'] ?? ''); ?>" alt="Avatar" class="avatar absolute bottom-0 left-6 transform translate-y-1/2 w-32 h-32 object-cover rounded-full border-4 border-black">
                    </div>
                    <div class="profile-content">
                        <div class="profile-info">
                            <h2 class="text-2xl font-bold text-white"><?php echo htmlspecialchars($personal_info['name'] ?? ''); ?></h2>
                            <p class="text-blue-400"><?php echo htmlspecialchars($personal_info['title'] ?? ''); ?></p>
                        </div>
                        <p class="text-gray-300 mb-4 mt-4">
                            <?php echo htmlspecialchars($personal_info['bio'] ?? ''); ?>
                        </p>
                    </div>

                    <!-- Yetenekler -->
                    <div class="skills-container flex flex-wrap gap-2 mb-4 px-2 sm:px-0">
                        <?php
                        while ($skill = $result_skills->fetch_assoc()) {
                            echo '<span class="skill-tag ' . htmlspecialchars($skill['color'] ?? '') . ' text-white px-2 py-1 rounded-full text-sm">' . htmlspecialchars($skill['name'] ?? '') . '</span>';
                        }
                        ?>
                    </div>
                </div>

                <!-- Sağ Sütun: Diğer Kartlar -->
                <div class="w-full lg:w-[38%] space-y-4">
                    <!-- Güncel Proje -->
                    <div class="custom-bg p-3 rounded-lg project-box transition-all duration-300">
                        <h3 class="text-lg font-semibold text-white mb-2">Güncel Proje</h3>
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center">
                                <i class="<?php echo htmlspecialchars($current_project['icon_class'] ?? ''); ?> text-white text-2xl"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-400"><?php echo htmlspecialchars($current_project['status'] ?? ''); ?></p>
                                <p class="text-white font-semibold"><?php echo htmlspecialchars($current_project['name'] ?? ''); ?></p>
                            </div>
                            <div class="pulsing-icon text-purple-500">
                                <i class="fas fa-code-branch text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Tanışalım -->
                    <div class="custom-bg p-3 rounded-lg chat-box transition-all duration-300">
                        <h3 class="text-lg font-semibold text-white mb-2">Tanışalım:</h3>
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-comments text-white text-2xl"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-400"><?php echo htmlspecialchars($contact_info['description'] ?? ''); ?></p>
                                <a href="mailto:<?php echo htmlspecialchars($contact_info['email'] ?? ''); ?>" class="text-white font-semibold hover:text-blue-300 transition-colors duration-300"><?php echo htmlspecialchars($contact_info['email'] ?? ''); ?></a>
                            </div>
                            <div class="floating-chat text-blue-500">
                                <i class="fas fa-comment-dots text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Dinliyorum -->
                    <div class="custom-bg p-3 rounded-lg music-box transition-all duration-300">
                        <h3 class="text-lg font-semibold text-white mb-2">Dinliyorum</h3>
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                                <i class="fab fa-spotify text-white text-2xl"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-400"><?php echo htmlspecialchars($current_music['platform'] ?? ''); ?></p>
                                <p class="text-white font-semibold"><?php echo htmlspecialchars($current_music['artist'] ?? '') . ' - ' . htmlspecialchars($current_music['song'] ?? ''); ?></p>
                            </div>
                            <div class="vibrating-icon text-green-500">
                                <i class="fas fa-volume-high text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Bülten Aboneliği -->
                    <div class="custom-bg p-3 rounded-lg transition-all duration-300">
                        <h3 class="text-lg font-semibold text-white mb-2">Bülten Aboneliği</h3>
                        <form class="flex flex-col space-y-2">
                            <input type="email" id="email-input" placeholder="E-posta adresiniz" class="bg-gray-800 text-white px-3 py-2 rounded-md">
                            <button type="submit" class="bg-black text-white px-4 py-2 rounded-md border border-gray-600 hover:bg-green-600 hover:border-green-600 transition-colors duration-300">
                                Abone Ol
                            </button>
                            <div id="privacy-policy-container" class="hidden">
                                <div class="flex items-center space-x-2 mt-2">
                                    <input type="checkbox" id="privacy-policy" class="form-checkbox h-4 w-4 text-blue-600 transition duration-150 ease-in-out" checked>
                                    <label for="privacy-policy" class="text-sm text-gray-300">
                                        Gizlilik politikasını kabul ediyorum
                                    </label>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Yeni AI ve Cursor butonu -->
            <div class="mt-4">
                <button class="ai-cursor-button w-full text-white font-bold py-3 px-4 rounded-lg shadow-lg transition-all duration-300 ease-in-out transform hover:-translate-y-1 hover:scale-105 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-opacity-50 flex items-center justify-center space-x-2">
                    <svg class="ai-cursor-icon w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    <span>AI & Cursor ile Geliştirildi</span>
                </button>
            </div>

            <!-- Portfolio section -->
            <div class="bg-black p-3 rounded-lg mt-8">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-white mb-2">Portfolyo</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php
                        while ($project = $result_projects->fetch_assoc()) {
                            echo '
                            <a href="' . htmlspecialchars($project['link'] ?? '#') . '" target="_blank" class="project-card p-4 rounded-lg shadow-lg transition-all duration-300 hover:shadow-xl">
                                <h3 class="text-lg font-semibold text-white mb-2">' . htmlspecialchars($project['title'] ?? '') . '</h3>
                                <p class="text-gray-300 text-sm mb-4">' . htmlspecialchars($project['description'] ?? '') . '</p>
                                <div class="vibrating-icon text-' . htmlspecialchars($project['icon_color'] ?? '') . '-500">
                                    <i class="' . htmlspecialchars($project['icon_class'] ?? '') . ' text-2xl"></i>
                                </div>
                            </a>';
                        }
                        ?>
                    </div>
                    
                    <!-- Daha fazlası butonu -->
                    <div class="mt-6">
                        <button class="more-button w-full px-6 py-3 rounded-lg text-white font-semibold transition-all duration-300 ease-in-out transform hover:-translate-y-1 flex items-center justify-center">
                            <span>Daha fazlası</span>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="w-full py-6">
        <div class="content-width mx-auto px-4">
            <div class="bg-black text-white p-4 rounded-lg text-center text-sm">
                <p>&copy; 2023 netpixdev. Tüm hakları saklıdır.</p>
            </div>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>