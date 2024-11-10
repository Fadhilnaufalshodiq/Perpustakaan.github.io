<?php
// Mulai sesi dan koneksi ke database
session_start();
include 'includes/db.php';
include 'includes/header.php';

// Pastikan hanya admin yang dapat mengakses halaman ini
if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Proses ketika form disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $judul = $_POST['judul'];
    $pengarang = $_POST['pengarang'];
    $penerbit = $_POST['penerbit'];
    $tahun_terbit = $_POST['tahun_terbit'];
    $kategori = $_POST['kategori'];
    $jumlah = $_POST['jumlah'];

    // Validasi input: pastikan semua kolom terisi
    if (empty($judul) || empty($pengarang) || empty($penerbit) || empty($tahun_terbit) || empty($kategori) || empty($jumlah)) {
        echo "<p>Semua kolom harus diisi.</p>";
        exit();
    }

    // Query untuk menambahkan buku dengan prepared statements (untuk mencegah SQL injection)
    $sql = "INSERT INTO buku (judul, pengarang, penerbit, tahun_terbit, kategori, jumlah) 
            VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Binding parameter
        $stmt->bind_param("sssssi", $judul, $pengarang, $penerbit, $tahun_terbit, $kategori, $jumlah);

        // Eksekusi query
        if ($stmt->execute()) {
            echo "<p>Buku berhasil ditambahkan.</p>";
            // Redirect setelah berhasil menambah buku
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

// Tutup koneksi database
$conn->close();
?>

<!-- Form HTML untuk Menambahkan Buku -->
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Buku - Perpustakaan</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <nav>
        <!-- Sidebar dan menu navigasi, sesuaikan dengan halaman ini -->
    </nav>

    <div class="add-book">
        <h2>Tambah Buku</h2>
        <form method="POST">
            <!-- Input untuk Judul Buku -->
            <div class="input-group">
                <label for="judul">Judul Buku:</label>
                <input type="text" name="judul" id="judul" required>
            </div>

            <!-- Input untuk Pengarang -->
            <div class="input-group">
                <label for="pengarang">Pengarang:</label>
                <input type="text" name="pengarang" id="pengarang" required>
            </div>

            <!-- Input untuk Penerbit -->
            <div class="input-group">
                <label for="penerbit">Penerbit:</label>
                <input type="text" name="penerbit" id="penerbit" required>
            </div>

            <!-- Input untuk Tahun Terbit -->
            <div class="input-group">
                <label for="tahun_terbit">Tahun Terbit:</label>
                <input type="number" name="tahun_terbit" id="tahun_terbit" required>
            </div>

            <!-- Input untuk Kategori -->
            <div class="input-group">
                <label for="kategori">Kategori:</label>
                <input type="text" name="kategori" id="kategori" required>
            </div>

            <!-- Input untuk Jumlah Buku -->
            <div class="input-group">
                <label for="jumlah">Jumlah Buku:</label>
                <input type="number" name="jumlah" id="jumlah" required>
            </div>

            <!-- Tombol Submit -->
            <button type="submit" class="btn-submit">Tambah Buku</button>
        </form>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>

</html>
