<?php
session_start();
require_once 'config/connection.php';

// Redirect jika belum login
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil foto user
$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM photos WHERE user_id = ? ORDER BY created_at DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

include 'includes/header.php';
?>

<div class="container mt-5">
    <div class="my-photos-header">
        <h2>My Photos</h2>
        <a href="upload.php" class="btn btn-primary">
            <i class="fas fa-upload me-2"></i>Upload New Photo
        </a>
    </div>

    <div class="row mt-4">
        <?php if(mysqli_num_rows($result) > 0): ?>
            <?php while($photo = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-4 mb-4">
                    <div class="photo-card">
                        <img src="uploads/<?php echo $photo['image_path']; ?>" 
                             class="card-img-top" 
                             alt="<?php echo htmlspecialchars($photo['title']); ?>">
                        <div class="photo-card-overlay">
                            <h5 class="photo-title"><?php echo htmlspecialchars($photo['title']); ?></h5>
                            <div class="photo-actions">
                                <a href="edit_photo.php?id=<?php echo $photo['id']; ?>" 
                                   class="btn btn-sm btn-light">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete_photo.php?id=<?php echo $photo['id']; ?>" 
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Are you sure you want to delete this photo?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12 text-center mt-5">
                <div class="empty-state">
                    <i class="fas fa-images empty-icon"></i>
                    <h3>No Photos Yet</h3>
                    <p>Start sharing your moments by uploading your first photo!</p>
                    <a href="upload.php" class="btn btn-primary mt-3">
                        <i class="fas fa-upload me-2"></i>Upload Photo
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?> 