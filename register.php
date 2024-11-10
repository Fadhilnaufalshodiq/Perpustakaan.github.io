<?php
include 'includes/header.php';
include 'includes/db.php';  // Koneksi database

// Menangani form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Query untuk memasukkan data pengguna
    $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'peminjam')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $hashed_password);

    if ($stmt->execute()) {
        echo "<div class='success-message'>Pendaftaran berhasil! Silakan login.</div>";
    } else {
        echo "<div class='error-message'>Terjadi kesalahan: " . $stmt->error . "</div>";
    }
    $stmt->close();
}
?>

<div class="form-container">
    <div class="form-wrapper">
        <h2>Daftar Akun</h2>
        <form method="POST" action="register.php">
            <div class="form-group">
                <label for="name">Nama:</label>
                <input type="text" id="name" name="name" placeholder="Masukkan nama lengkap" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Masukkan email" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required>
            </div>

            <button type="submit" class="btn btn-primary">Daftar</button>

            <div class="form-footer">
                <p>Sudah punya akun? <a href="login.php">Login disini</a></p>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
