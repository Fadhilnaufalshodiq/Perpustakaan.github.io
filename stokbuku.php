<?php
// includes/header.php
include 'includes/header.php';
include 'includes/db.php';

// Memeriksa apakah pengguna telah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Arahkan ke halaman login jika belum login
    exit();
}

// Query untuk mengambil buku yang tersedia untuk dipinjam
$sql = "SELECT * FROM buku WHERE jumlah > 0"; // Buku yang jumlahnya lebih dari 0
$result = $conn->query($sql);
?>

<div class="book-list-container">
    <h2 class="section-title">Daftar Buku yang Tersedia untuk Dipinjam</h2>

    <?php if ($result->num_rows > 0) { ?>
    <table class="book-list-table">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Pengarang</th>
                <th>Penerbit</th>
                <th>Tahun Terbit</th>
                <th>Kategori</th>
                <th>Jumlah Tersedia</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
                <td><?php echo $row['judul']; ?></td>
                <td><?php echo $row['pengarang']; ?></td>
                <td><?php echo $row['penerbit']; ?></td>
                <td><?php echo $row['tahun_terbit']; ?></td>
                <td><?php echo $row['kategori']; ?></td>
                <td><?php echo $row['jumlah']; ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
    <?php } else { ?>
    <p class="no-books-message">Tidak ada buku yang tersedia untuk dipinjam saat ini.</p>
    <?php } ?>
</div>

<?php include 'includes/footer.php'; ?>
