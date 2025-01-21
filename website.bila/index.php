<?php
session_start();
require_once 'config/connection.php';

$query = "SELECT p.*, u.username FROM photos p 
          JOIN users u ON p.user_id = u.id 
          ORDER BY p.created_at DESC";
$result = mysqli_query($conn, $query);

include 'includes/header.php';
?>

<!-- Welcome Section -->
<div class="welcome-section">
    <div class="welcome-overlay"></div>
    <div class="container">
        <div class="welcome-content">
            <h1 class="welcome-title">Welcome to Photo Gallery</h1>
            <p class="welcome-text">Discover and share beautiful moments through photography</p>
            <?php if(!isset($_SESSION['user_id'])): ?>
                <div class="welcome-buttons">
                    <a href="login.php" class="btn btn-light btn-lg me-3">Login</a>
                    <a href="register.php" class="btn btn-outline-light btn-lg">Register</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Search Section -->
<div class="search-section">
    <div class="container">
        <div class="search-wrapper">
            <div class="input-group">
                <input type="text" 
                       id="searchInput" 
                       class="form-control search-input" 
                       placeholder="Search photos..."
                       aria-label="Search photos"
                       onkeyup="searchPhotos(this.value)">
                <span class="clear-search" onclick="clearSearch()">
                    <i class="fas fa-times"></i>
                </span>
                <button class="btn btn-search" type="button">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk menampilkan gambar -->
<div class="modal fullscreen-modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <!-- Header Modal dengan tombol Exit -->
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"></h5>
                <button type="button" class="btn-exit" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <!-- Body Modal -->
            <div class="modal-body">
                <!-- Tombol Previous -->
                <a class="prev" onclick="changeSlide(-1)">&#10094;</a>
                
                <!-- Gambar -->
                <img class="modal-content" id="modalImage">
                
                <!-- Tombol Next -->
                <a class="next" onclick="changeSlide(1)">&#10095;</a>
            </div>
        </div>
    </div>
</div>

<div class="container mt-4">
    <div class="row">
        <?php while($photo = mysqli_fetch_assoc($result)): ?>
            <div class="col-md-4 mb-4" id="photo-<?php echo $photo['id']; ?>">
                <div class="card">
                    <img src="uploads/<?php echo $photo['image_path']; ?>" 
                         class="card-img-top" 
                         alt="<?php echo htmlspecialchars($photo['title']); ?>"
                         onclick="openImageModal('uploads/<?php echo $photo['image_path']; ?>', '<?php echo htmlspecialchars($photo['title']); ?>')"
                         id="photo-img-<?php echo $photo['id']; ?>">
                    <div class="card-body">
                        <h5 class="card-title" id="photo-title-<?php echo $photo['id']; ?>">
                            <?php echo htmlspecialchars($photo['title']); ?>
                        </h5>
                        <p class="card-text" id="photo-desc-<?php echo $photo['id']; ?>">
                            <?php echo htmlspecialchars($photo['description']); ?>
                        </p>
                        <p class="card-text">
                            <small class="text-muted" id="photo-author-<?php echo $photo['id']; ?>">
                                By <?php echo htmlspecialchars($photo['username']); ?>
                            </small>
                        </p>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>