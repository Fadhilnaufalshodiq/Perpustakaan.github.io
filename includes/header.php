<?php
session_start(); // Pastikan session dimulai
$current_page = basename($_SERVER['PHP_SELF']); // Nama file halaman saat ini
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Online</title>
    <link rel="stylesheet" href="css/styles.css">
    <script src="js/script.js" defer></script> <!-- Pastikan JS dipanggil setelah halaman dimuat -->
</head>

<body>
    <nav>
        <div class="sidebar">
            <ul>
                <!-- Menampilkan nama pengguna jika sudah login -->
                <?php if (isset($_SESSION['user_id'])) { ?>
                <li class="user-profile">
                    <a href="#">
                        <?php echo $_SESSION['name']; ?>
                    </a>
                </li>
                <?php } ?>

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') { ?>
                <li class="<?php echo ($current_page == 'admin_dashboard.php') ? 'active' : ''; ?>"><a href="admin_dashboard.php">Administrator</a></li>
                <?php } ?>
                <li class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>"><a href="index.php">Beranda</a></li>
                <li class="<?php echo ($current_page == 'info.php') ? 'active' : ''; ?>"><a href="info.php">Info Perpustakaan</a></li>
                <?php if (isset($_SESSION['user_id'])) { ?>
                <li class="<?php echo ($current_page == 'stokbuku.php') ? 'active' : ''; ?>"><a href="stokbuku.php">Stok Buku</a></li>
                <li class="<?php echo ($current_page == 'peminjam.php') ? 'active' : ''; ?>"><a href="peminjam.php">Dashboard</a></li>
                <?php } ?>

                <?php if (!isset($_SESSION['user_id'])) { ?>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle">Login / Register</a>
                    <ul class="dropdown-menu">
                        <li><a href="login.php">Login</a></li>
                        <li><a href="register.php">Register</a></li>
                    </ul>
                </li>
                <?php } else { ?>
                <li><a href="logout.php">Logout</a></li>
                <?php } ?>
            </ul>
        </div>
    </nav>

    <header>
        <div class="header-content">
            <h1>Selamat datang di Perpustakaan Online</h1>
        </div>
    </header>

    <!-- Konten halaman berikutnya akan diletakkan di sini -->
