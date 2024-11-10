<?php
// pinjam_buku.php
session_start();
include 'includes/db.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    echo "Anda harus login terlebih dahulu.";
    exit();
}

// Cek apakah ada parameter 'borrow' di URL
if (isset($_GET['borrow'])) {
    $id_buku = $_GET['borrow'];

    // Validasi ID Buku (pastikan itu adalah angka)
    if (!is_numeric($id_buku)) {
        echo "ID Buku tidak valid.";
        exit();
    }

    // Ambil data buku berdasarkan ID
    $sql = "SELECT * FROM buku WHERE id_buku = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_buku);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika buku ditemukan
    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();

        // Cek apakah jumlah buku masih tersedia
        if ($book['jumlah'] > 0) {
            // Kurangi jumlah buku yang tersedia
            $new_jumlah = $book['jumlah'] - 1;

            // Update jumlah buku di database
            $update_sql = "UPDATE buku SET jumlah = ? WHERE id_buku = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("ii", $new_jumlah, $id_buku);
            $update_stmt->execute();

            // Simpan peminjaman buku ke tabel peminjaman (bila perlu)
            $user_id = $_SESSION['user_id'];
            $borrow_sql = "INSERT INTO peminjaman (user_id, id_buku, tanggal_pinjam) VALUES (?, ?, NOW())";
            $borrow_stmt = $conn->prepare($borrow_sql);
            $borrow_stmt->bind_param("ii", $user_id, $id_buku);
            $borrow_stmt->execute();

            // Beri feedback ke pengguna
            echo "Buku berhasil dipinjam.";
            header("Location: index.php");  // Redirect ke halaman utama
            exit();

        } else {
            echo "Buku tidak tersedia, jumlah buku habis.";
        }
    } else {
        echo "Buku tidak ditemukan.";
    }
} else {
    echo "ID Buku tidak ditemukan.";
}
?>