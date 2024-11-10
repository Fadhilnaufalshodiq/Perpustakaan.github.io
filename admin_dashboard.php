<?php
// admin_dashboard.php
include 'includes/header.php';
include 'includes/db.php';

// Cek apakah yang login adalah admin
if ($_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

// Menampilkan daftar buku untuk admin
$sql = "SELECT id_buku, judul, pengarang, penerbit, tahun_terbit, kategori, jumlah FROM buku";
$result = $conn->query($sql);

// Hapus buku jika tombol delete diklik
if (isset($_GET['delete'])) {
    $id_buku = $_GET['delete'];
    $delete_sql = "DELETE FROM buku WHERE id_buku = $id_buku";
    if ($conn->query($delete_sql)) {
        echo "<script>alert('Buku berhasil dihapus'); window.location='admin_dashboard.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus buku');</script>";
    }
}
?>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title"><span class="fa fa-book"></span>Data Buku</h3>
                </div>
                <div class="panel-body">

                    <!-- Tabel Daftar Buku -->
                    <table id="deskripsi" class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Judul Buku</th>
                                <th>Nama Pengarang</th>
                                <th>Penerbit</th>
                                <th>Tahun Terbit</th>
                                <th>Kategori</th>
                                <th>Jumlah</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Ambil data dari database dan tampilkan di tabel -->
                            <?php
                            $nomor = 0;
                            while ($row = $result->fetch_assoc()) {
                                $nomor++;
                                ?>
                            <tr>
                                <td><?= $nomor ?></td>
                                <td><?= $row['judul'] ?></td>
                                <td><?= $row['pengarang'] ?></td>
                                <td><?= $row['penerbit'] ?></td>
                                <td><?= $row['tahun_terbit'] ?></td>
                                <td><?= $row['kategori'] ?></td>
                                <td><?= $row['jumlah'] ?></td>
                                <td>
                                    <!-- Tombol Tambah Buku (mengarah ke halaman add_book.php) -->
                                    <a href="add_book.php" class="btn btn-primary btn-xs">Tambah</a>

                                    <!-- Tombol Edit -->
                                    <a href="edit_book.php?id_buku=<?= $row['id_buku'] ?>"
                                        class="btn btn-warning btn-xs">Edit</a>


                                    <!-- Tombol Delete -->
                                    <a href="?delete=<?= $row['id_buku'] ?>" class="btn btn-danger btn-xs"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus buku ini?');">Delete</a>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                        <!--<tfoot>
                            <tr>
                                <td colspan="8">
                                    Tombol Cetak Semua Buku
                                    <a href="report/buku_semua.php" target="_blank" class="btn btn-info btn-sm">
                                        <span class="fa fa-print"></span> Cetak Semua Buku
                                    </a>

                                    Tombol Cetak Per Bulan
                                    <a href="#cetak_perbulan" class="btn btn-info btn-sm">
                                        <span class="fa fa-print"></span> Cetak Per Bulan
                                    </a>

                                    Tombol Cetak Per Tahun
                                    <a href="#cetak_pertahun" class="btn btn-info btn-sm">
                                        <span class="fa fa-print"></span> Cetak Per Tahun
                                    </a>
                                </td>
                            </tr>
                        </tfoot>-->
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cetak Per Bulan -->
<!--<div id="cetak_perbulan" class="modalDialog">
    <div>
        <a href="#" title="Close" class="close">X</a>
        <form target="_blank" action="report/buku_perbulan.php" method="post">
            <h4>Pilih Bulan</h4>
            <select name="bulan" class="form-control">
                <option value="12">Desember</option>
                <option value="11">November</option>
                <option value="10">Oktober</option>
                <option value="09">September</option>
                <option value="08">Agustus</option>
                <option value="07">Juli</option>
                <option value="06">Juni</option>
                <option value="05">Mei</option>
                <option value="04">April</option>
                <option value="03">Maret</option>
                <option value="02">Februari</option>
                <option value="01">Januari</option>
            </select>

            <h4>Pilih Tahun</h4>
            <select name="tahun" class="form-control">
                <=?php
                for ($i = substr(date("d-m-Y"), 6, 4); $i > substr(date("d-m-Y"), 6, 4) - 5; $i--) {
                    echo "<option value='$i'>$i</option>";
                }
                ?>
            </select>

            <button type="submit" class="btn btn-primary">OK</button>
        </form>
    </div>
</div>-->

<!-- Modal Cetak Per Tahun -->
<!--div id="cetak_pertahun" class="modalDialog">
    <div>
        <a href="#" title="Close" class="close">X</a>
        <form target="_blank" action="report/buku_pertahun.php" method="post">
            <h4>Pilih Tahun</h4>
            <select name="tahun" class="form-control">
                <=?php
                for ($i = substr(date("d-m-Y"), 6, 4); $i > substr(date("d-m-Y"), 6, 4) - 5; $i--) {
                    echo "<option value='$i'>$i</option>";
                }
                ?>
            </select>

            <button type="submit" class="btn btn-primary">OK</button>
        </form>
    </div>
</!--div>

<?php include 'includes/footer.php'; ?>