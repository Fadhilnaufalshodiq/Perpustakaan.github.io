<?php
include 'includes/header.php';
include 'includes/db.php';  // Koneksi database

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirect ke login jika belum login
    exit;
}

// Ambil data pengguna dari database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

?>

<div class="profile">
    <h2>Profil Pengguna</h2>
    <p><strong>Nama:</strong> <?php echo $user['name']; ?></p>
    <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
    <p><strong>Role:</strong> <?php echo $user['role']; ?></p>
    <p><strong>Member Since:</strong> <?php echo $user['created_at']; ?></p>
</div>

<?php include 'includes/footer.php'; ?>