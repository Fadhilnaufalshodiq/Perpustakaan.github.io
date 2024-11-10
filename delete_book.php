<?php
// delete_book.php
include 'includes/header.php';
include 'includes/db.php';

// Pastikan hanya admin yang bisa mengakses halaman ini
if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Cek jika id_buku ada di URL
if (isset($_GET['id_buku'])) {
    $id_buku = $_GET['id_buku'];

    // Validasi id_buku agar hanya angka yang diterima
    if (!is_numeric($id_buku)) {
        echo "<p>ID Buku tidak valid.</p>";
        exit();
    }

    // Query untuk menghapus buku berdasarkan id_buku
    $sql = "DELETE FROM buku WHERE id_buku = ?";
    if ($stmt = $conn->prepare($sql)) {
        // Binding parameter
        $stmt->bind_param("i", $id_buku);

        // Eksekusi query
        if ($stmt->execute()) {
            echo "<p>Buku berhasil dihapus.</p>";
            // Redirect setelah buku dihapus
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "<p>Terjadi kesalahan: " . $stmt->error . "</p>";
        }

        // Tutup statement
        $stmt->close();
    } else {
        echo "<p>Query gagal dipersiapkan.</p>";
    }
}

// Menampilkan daftar buku
$sql = "SELECT * FROM buku";
$result = $conn->query($sql);
?>

<div class="delete-book">
    <h2>Hapus Buku</h2>
    <h3>Daftar Buku</h3>
    <ul class="book-list">
        <?php while ($row = $result->fetch_assoc()) { ?>
        <li class="book-item">
            <span class="book-title"><?php echo htmlspecialchars($row['judul']); ?>
                (<?php echo htmlspecialchars($row['pengarang']); ?>)</span>
            <a class="delete-button" href="delete_book.php?id_buku=<?php echo $row['id_buku']; ?>"
                onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">Hapus</a>
        </li>
        <?php } ?>
    </ul>
</div>

<?php include 'includes/footer.php'; ?>