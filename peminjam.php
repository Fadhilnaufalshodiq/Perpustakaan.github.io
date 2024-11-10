<?php
// peminjam.php
include 'includes/header.php';
include 'includes/db.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); // Arahkan ke halaman login jika belum login
    exit();
}

// Ambil data buku dari database yang tersedia
$sql = "SELECT * FROM buku WHERE jumlah > 0";
$result = $conn->query($sql);
?>

<div class="book-list">
    <h2 class="section-title">Daftar Buku Tersedia untuk Dipinjam</h2>

    <?php if ($result->num_rows > 0) { ?>
    <div class="book-cards">
        <?php while($row = $result->fetch_assoc()) { ?>
        <div class="book-card">
            <div class="book-card-header">
                <h3><?php echo $row['judul']; ?></h3>
            </div>
            <div class="book-card-body">
                <p><strong>Penulis:</strong> <?php echo $row['pengarang']; ?></p>
                <p><strong>Penerbit:</strong> <?php echo $row['penerbit']; ?></p>
                <p><strong>Tahun Terbit:</strong> <?php echo $row['tahun_terbit']; ?></p>
                <p><strong>Kategori:</strong> <?php echo $row['kategori']; ?></p>
                <p><strong>Jumlah Tersedia:</strong> <?php echo $row['jumlah']; ?></p>
            </div>
            <div class="book-card-footer">
                <a href="#" class="btn btn-primary">Pinjam Buku</a>
            </div>
        </div>
        <?php } ?>
    </div>
    <?php } else { ?>
    <p class="no-books-message">Maaf, tidak ada buku yang tersedia untuk dipinjam saat ini.</p>
    <?php } ?>
</div>

<?php include 'includes/footer.php'; ?>
