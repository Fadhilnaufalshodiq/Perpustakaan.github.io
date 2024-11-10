<?php
session_start();
include 'includes/db.php';

// Pastikan pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Ambil id_peminjaman dari URL
if (isset($_GET['id_peminjaman'])) {
    $id_peminjaman = $_GET['id_peminjaman'];

    // Validasi ID Peminjaman (pastikan itu adalah angka)
    if (!is_numeric($id_peminjaman)) {
        echo "ID Peminjaman tidak valid.";
        exit();
    }

    // Query untuk mendapatkan data peminjaman dan id_buku yang dipinjam
    $sql = "SELECT p.id_buku, p.user_id, b.judul, p.status FROM peminjaman p
            JOIN buku b ON p.id_buku = b.id_buku
            WHERE p.id_peminjaman = ? AND p.status = 'pinjam'";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_peminjaman);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika peminjaman ditemukan
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $id_buku = $row['id_buku'];

        // Perbarui status peminjaman menjadi 'kembali'
        $update_peminjaman = "UPDATE peminjaman SET status = 'kembali' WHERE id_peminjaman = ?";
        $stmt_update = $conn->prepare($update_peminjaman);
        $stmt_update->bind_param("i", $id_peminjaman);
        $stmt_update->execute();

        // Tambahkan jumlah buku kembali ke tabel buku
        $update_buku = "UPDATE buku SET jumlah = jumlah + 1 WHERE id_buku = ?";
        $stmt_buku = $conn->prepare($update_buku);
        $stmt_buku->bind_param("i", $id_buku);
        $stmt_buku->execute();

        echo "Buku berhasil dikembalikan!";
        header("Location: index.php");  // Redirect ke halaman dashboard pengguna
        exit();
    } else {
        echo "Data peminjaman tidak ditemukan atau buku sudah dikembalikan.";
    }
} else {
    echo "ID peminjaman tidak ditemukan.";
}
?>