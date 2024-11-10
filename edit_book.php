<?php
session_start();
include 'includes/db.php';
include 'includes/header.php';

// Pastikan hanya admin yang bisa mengakses halaman ini
if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Cek apakah parameter 'id_buku' ada dalam URL untuk edit buku
if (isset($_GET['id_buku'])) {
    $id_buku = $_GET['id_buku'];

    // Validasi ID Buku (pastikan itu adalah angka)
    if (!is_numeric($id_buku)) {
        die("ID Buku tidak valid.");
    }

    // Query untuk mengambil data buku berdasarkan id_buku
    $sql = "SELECT * FROM buku WHERE id_buku = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_buku);
    $stmt->execute();
    $result = $stmt->get_result();

    // Jika buku ditemukan, tampilkan form untuk edit
    if ($result->num_rows > 0) {
        $book = $result->fetch_assoc();
    } else {
        die("Buku tidak ditemukan.");
    }
} else {
    die("ID Buku tidak ditemukan.");
}

// Proses saat form di-submit
if (isset($_POST['update'])) {
    // Validasi input dari form
    $judul = trim($_POST['judul']);
    $pengarang = trim($_POST['pengarang']);
    $penerbit = trim($_POST['penerbit']);
    $tahun_terbit = $_POST['tahun_terbit'];
    $kategori = trim($_POST['kategori']);
    $jumlah = $_POST['jumlah']; // Ambil jumlah buku yang baru

    // Pastikan input tidak kosong
    if (empty($judul) || empty($pengarang) || empty($penerbit) || empty($tahun_terbit) || empty($kategori) || empty($jumlah)) {
        die("Semua kolom harus diisi.");
    }

    // Cek jika ada perubahan dibandingkan dengan data yang sudah ada
    if ($judul === $book['judul'] && $pengarang === $book['pengarang'] && $penerbit === $book['penerbit'] && 
        $tahun_terbit == $book['tahun_terbit'] && $kategori === $book['kategori'] && $jumlah == $book['jumlah']) {
        echo "Tidak ada perubahan yang dilakukan. Data sudah sama.";
        exit();
    }

    // Query untuk memperbarui data buku
    $update_sql = "UPDATE buku SET judul = ?, pengarang = ?, penerbit = ?, tahun_terbit = ?, kategori = ?, jumlah = ? WHERE id_buku = ?";
    $update_stmt = $conn->prepare($update_sql);

    // Periksa apakah prepare berhasil
    if ($update_stmt === false) {
        die('Error prepare: ' . $conn->error);
    }

    // Bind parameter
    $update_stmt->bind_param("ssssssi", $judul, $pengarang, $penerbit, $tahun_terbit, $kategori, $jumlah, $id_buku);

    // Eksekusi query
    $update_success = $update_stmt->execute();

    // Cek apakah query update berhasil
    if ($update_success) {
        // Cek apakah ada baris yang diperbarui
        if ($update_stmt->affected_rows > 0) {
            echo "Buku berhasil diperbarui.";
            // Redirect setelah update (untuk menghindari reload form)
            header("Location: admin_dashboard.php");
            exit();
        } else {
            echo "Tidak ada perubahan yang dilakukan. Data sudah sama.";
        }
    } else {
        echo "Terjadi kesalahan saat memperbarui buku. Error: " . $update_stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku - Perpustakaan</title>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
    <div class="edit-book">
        <h2>Edit Buku</h2>
        <form method="POST">
            <!-- Input untuk Judul Buku -->
            <div class="input-group">
                <label for="judul">Judul Buku:</label>
                <input type="text" name="judul" id="judul" value="<?php echo htmlspecialchars($book['judul']); ?>" required>
            </div>

            <!-- Input untuk Pengarang -->
            <div class="input-group">
                <label for="pengarang">Pengarang:</label>
                <input type="text" name="pengarang" id="pengarang" value="<?php echo htmlspecialchars($book['pengarang']); ?>" required>
            </div>

            <!-- Input untuk Penerbit -->
            <div class="input-group">
                <label for="penerbit">Penerbit:</label>
                <input type="text" name="penerbit" id="penerbit" value="<?php echo htmlspecialchars($book['penerbit']); ?>" required>
            </div>

            <!-- Input untuk Tahun Terbit -->
            <div class="input-group">
                <label for="tahun_terbit">Tahun Terbit:</label>
                <input type="number" name="tahun_terbit" id="tahun_terbit" value="<?php echo htmlspecialchars($book['tahun_terbit']); ?>" required>
            </div>

            <!-- Input untuk Kategori -->
            <div class="input-group">
                <label for="kategori">Kategori:</label>
                <input type="text" name="kategori" id="kategori" value="<?php echo htmlspecialchars($book['kategori']); ?>" required>
            </div>

            <!-- Input untuk Jumlah Buku -->
            <div class="input-group">
                <label for="jumlah">Jumlah Buku:</label>
                <input type="number" name="jumlah" id="jumlah" value="<?php echo htmlspecialchars($book['jumlah']); ?>" required>
            </div>

            <!-- Tombol Submit -->
            <button type="submit" name="update" class="btn-submit">Update Buku</button>
        </form>
    </div>

    <?php include 'includes/footer.php'; ?>
</body>

</html>
