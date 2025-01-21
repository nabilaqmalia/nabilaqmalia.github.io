<?php
session_start();
require_once 'config/connection.php';

// Redirect jika belum login
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil data user
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

include 'includes/header.php';
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-4">
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <i class="fas fa-user-circle"></i>
                    </div>
                    <h3 class="profile-name"><?php echo htmlspecialchars($user['username']); ?></h3>
                    <p class="profile-email"><?php echo htmlspecialchars($user['email']); ?></p>
                </div>
                <div class="profile-stats">
                    <?php
                    // Hitung jumlah foto
                    $photo_query = "SELECT COUNT(*) as total FROM photos WHERE user_id = ?";
                    $stmt = mysqli_prepare($conn, $photo_query);
                    mysqli_stmt_bind_param($stmt, "i", $user_id);
                    mysqli_stmt_execute($stmt);
                    $photo_result = mysqli_stmt_get_result($stmt);
                    $photo_count = mysqli_fetch_assoc($photo_result)['total'];
                    ?>
                    <div class="stat-item">
                        <span class="stat-value"><?php echo $photo_count; ?></span>
                        <span class="stat-label">Photos</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="profile-content">
                <h4 class="content-title">Profile Settings</h4>
                <form action="update_profile.php" method="POST" class="profile-form">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" 
                               value="<?php echo htmlspecialchars($user['username']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" 
                               value="<?php echo htmlspecialchars($user['email']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password">
                    </div>
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?> 