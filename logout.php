<?php
// logout.php
session_start();
session_unset(); // Menghapus semua sesi
session_destroy(); // Menghancurkan sesi

header("Location: index.php"); // Arahkan kembali ke halaman utama setelah logout
exit();
?>