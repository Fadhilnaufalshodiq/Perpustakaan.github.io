<?php
// index.php
include 'includes/header.php';
include 'includes/db.php';

// Ambil data buku dari database
$sql = "SELECT * FROM buku WHERE jumlah > 0"; // Menampilkan hanya buku yang memiliki jumlah > 0
$result = $conn->query($sql);
?>

<div class="container mt-5">
    <div class="book-list">
        <h2 class="text-center mb-4">Daftar Buku yang Tersedia</h2>
        
        <?php if ($result->num_rows > 0) { ?>
        <!-- Tabel Daftar Buku -->
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Judul Buku</th>
                    <th>Pengarang</th>
                    <th>Penerbit</th>
                    <th>Tahun Terbit</th>
                    <th>Kategori</th>
                    <th>Jumlah Tersedia</th>
                    <?php if (isset($_SESSION['user_id'])) { ?>
                    <th>Pinjam Buku</th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php
                $nomor = 1;
                while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $nomor++; ?></td>
                    <td><?php echo $row['judul']; ?></td>
                    <td><?php echo $row['pengarang']; ?></td>
                    <td><?php echo $row['penerbit']; ?></td>
                    <td><?php echo $row['tahun_terbit']; ?></td>
                    <td><?php echo $row['kategori']; ?></td>
                    <td><?php echo $row['jumlah']; ?> Buku</td>
                    <?php if (isset($_SESSION['user_id'])) { ?>
                    <td>
                        <a href="pinjam_buku.php?borrow=<?php echo $row['id_buku']; ?>" class="btn btn-success btn-sm">
                            Pinjam Buku
                        </a>
                        <!-- Cek apakah user meminjam buku ini -->
                        <?php
                        $user_id = $_SESSION['user_id'];
                        $id_buku = $row['id_buku'];
                        $sql_check_pinjam = "SELECT * FROM peminjaman WHERE user_id = ? AND id_buku = ? AND status = 'pinjam'";
                        $stmt_check_pinjam = $conn->prepare($sql_check_pinjam);
                        $stmt_check_pinjam->bind_param("ii", $user_id, $id_buku);
                        $stmt_check_pinjam->execute();
                        $result_check_pinjam = $stmt_check_pinjam->get_result();

                        if ($result_check_pinjam->num_rows > 0) { 
                            // Jika buku dipinjam, tampilkan tombol balikan buku
                            echo '<a href="kembalikan_buku.php?id_peminjaman=' . $result_check_pinjam->fetch_assoc()['id_peminjaman'] . '" class="btn btn-danger btn-sm">Kembalikan Buku</a>';
                        }
                        ?>
                    </td>
                    <?php } ?>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } else { ?>
        <p class="text-center">Tidak ada buku yang tersedia.</p>
        <?php } ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
