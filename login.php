<?php
include 'includes/header.php';
include 'includes/db.php';  // Koneksi database

// Menangani form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query untuk mengambil data pengguna berdasarkan email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Verifikasi password
    if ($user && password_verify($password, $user['password'])) {
        // Simpan session pengguna
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        header('Location: index.php');  // Redirect ke halaman beranda
        exit;
    } else {
        echo "<div class='error-message'>Email atau password salah!</div>";
    }
    $stmt->close();
}
?>

<div class="form-container">
    <div class="form-wrapper">
        <h2>Login</h2>
        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Masukkan email" required>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required>
            </div>

            <button type="submit" class="btn btn-primary">Login</button>

            <div class="form-footer">
                <p>Belum punya akun? <a href="register.php">Daftar disini</a></p>
            </div>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
