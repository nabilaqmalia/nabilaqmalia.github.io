<?php
session_start();
require_once 'config/connection.php';

if(isset($_GET['id'])) {
    $photo_id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Mengambil detail foto dan informasi user
    $query = "SELECT p.*, u.username, u.email 
              FROM photos p 
              JOIN users u ON p.user_id = u.id 
              WHERE p.id = '$photo_id'";
    $result = mysqli_query($conn, $query);
    $photo = mysqli_fetch_assoc($result);
    
    if(!$photo) {
        header("Location: index.php");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}

include 'includes/header.php';
?>

<div class="container-fluid p-0">
    <div class="row g-0">
        <!-- Kolom Foto -->
        <div class="col-md-8 bg-black">
            <div class="photo-container d-flex align-items-center justify-content-center" style="height: calc(100vh - 56px);">
                <a href="uploads/<?php echo $photo['image_path']; ?>" target="_blank">
                    <img src="uploads/<?php echo $photo['image_path']; ?>" 
                         class="detail-image" 
                         alt="<?php echo htmlspecialchars($photo['title']); ?>">
                </a>
            </div>
        </div>
        
        <!-- Kolom Informasi -->
        <div class="col-md-4 bg-light">
            <div class="p-4">
                <h2><?php echo htmlspecialchars($photo['title']); ?></h2>
                
                <!-- Informasi Photographer -->
                <div class="photographer-info mb-4">
                    <h5>Photographer</h5>
                    <p class="mb-1">
                        <i class="fas fa-user"></i> 
                        <?php echo htmlspecialchars($photo['username']); ?>
                    </p>
                    <?php if(isset($_SESSION['user_id']) && $_SESSION['user_id'] == $photo['user_id']): ?>
                        <div class="mt-2">
                            <a href="edit.php?id=<?php echo $photo['id']; ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="delete.php?id=<?php echo $photo['id']; ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('Are you sure you want to delete this photo?')">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Deskripsi Foto -->
                <div class="photo-description mb-4">
                    <h5>Description</h5>
                    <p><?php echo nl2br(htmlspecialchars($photo['description'])); ?></p>
                </div>
                
                <!-- Detail Foto -->
                <div class="photo-details mb-4">
                    <h5>Details</h5>
                    <p class="mb-1">
                        <strong>Uploaded:</strong> 
                        <?php echo date('F j, Y', strtotime($photo['created_at'])); ?>
                    </p>
                    <p class="mb-1">
                        <strong>Resolution:</strong> 
                        <?php 
                            $image_path = 'uploads/' . $photo['image_path'];
                            if(file_exists($image_path)) {
                                $image_info = getimagesize($image_path);
                                echo $image_info[0] . ' x ' . $image_info[1] . ' pixels';
                            }
                        ?>
                    </p>
                </div>
                
                <!-- Download Button -->
                <div class="download-section">
                    <a href="uploads/<?php echo $photo['image_path']; ?>" 
                       class="btn btn-primary btn-lg w-100" 
                       download="<?php echo $photo['title']; ?>">
                        <i class="fas fa-download"></i> Download
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>