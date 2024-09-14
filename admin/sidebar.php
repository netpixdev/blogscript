<div class="bg-gradient-to-b from-gray-900 to-gray-800 text-gray-300 w-64 min-h-screen fixed left-0 top-0 overflow-y-auto transition-all duration-300 ease-in-out shadow-lg" id="sidebar">
    <div class="p-6 border-b border-gray-700">
        <h2 class="text-2xl font-bold text-white">Admin Panel</h2>
    </div>
    <nav class="mt-6">
        <?php
        $menu_items = [
            ['icon' => 'fas fa-tachometer-alt', 'text' => 'Dashboard', 'link' => 'index.php'],
            ['icon' => 'fas fa-user', 'text' => 'Kişisel Bilgiler', 'link' => 'edit_personal_info.php'],
            ['icon' => 'fas fa-cogs', 'text' => 'Yetenekler', 'link' => 'edit_skills.php'],
            ['icon' => 'fas fa-project-diagram', 'text' => 'Projeler', 'link' => 'edit_projects.php'],
            ['icon' => 'fas fa-tasks', 'text' => 'Güncel Proje', 'link' => 'edit_current_project.php'],
            ['icon' => 'fas fa-address-book', 'text' => 'İletişim Bilgileri', 'link' => 'edit_contact_info.php'],
            ['icon' => 'fas fa-music', 'text' => 'Güncel Müzik', 'link' => 'edit_current_music.php'],
            ['icon' => 'fas fa-key', 'text' => 'Şifre Değiştir', 'link' => 'change_password.php'],
        ];

        $current_page = basename($_SERVER['PHP_SELF']);

        foreach ($menu_items as $item):
            $is_active = $current_page === $item['link'];
            $active_class = $is_active ? 'bg-blue-600 text-white' : 'hover:bg-gray-700';
        ?>
            <a href="<?php echo $item['link']; ?>" class="block py-3 px-6 mb-1 rounded-lg transition duration-200 <?php echo $active_class; ?>">
                <div class="flex items-center">
                    <i class="<?php echo $item['icon']; ?> mr-3 text-lg <?php echo $is_active ? 'text-white' : 'text-gray-400'; ?>"></i>
                    <span class="font-medium"><?php echo $item['text']; ?></span>
                </div>
            </a>
        <?php endforeach; ?>
    </nav>
    <div class="absolute bottom-0 w-full p-6">
        <a href="logout.php" class="block py-2 px-4 bg-red-500 text-white rounded-lg hover:bg-red-600 transition duration-200 text-center shadow-md">
            <i class="fas fa-sign-out-alt mr-2"></i> Çıkış Yap
        </a>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', (event) => {
    const sidebarLinks = document.querySelectorAll('#sidebar a');
    
    sidebarLinks.forEach(link => {
        link.addEventListener('click', function() {
            sidebarLinks.forEach(l => l.classList.remove('bg-blue-600', 'text-white'));
            this.classList.add('bg-blue-600', 'text-white');
        });
    });
});
</script>