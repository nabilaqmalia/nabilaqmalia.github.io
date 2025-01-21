<?php
session_start();
require_once 'config/connection.php';

$search = '';
$photos = [];

if(isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    
    $query = "SELECT p.*, u.username 
              FROM photos p 
              JOIN users u ON p.user_id = u.id 
              WHERE p.title LIKE '%$search%' 
              OR p.description LIKE '%$search%' 
              ORDER BY p.created_at DESC";
              
    $result = mysqli_query($conn, $query);
}

include 'includes/header.php';
?>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-6 mx-auto">
            <form method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" 
                       placeholder="Search photos..." value="<?php echo htmlspecialchars($search); ?>">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>

    <?php if(isset($_GET['search'])): ?>
        <?php if(mysqli_num_rows($result) > 0): ?>
            <h4 class="mb-4">Search results for: "<?php echo htmlspecialchars($search); ?>"</h4>
            <div class="row">
                <?php while($photo = mysqli_fetch_assoc($result)): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="uploads/<?php echo $photo['image_path']; ?>" 
                                 class="card-img-top" 
                                 alt="<?php echo htmlspecialchars($photo['title']); ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($photo['title']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($photo['description']); ?></p>
                                <p class="card-text">
                                    <small class="text-muted">By <?php echo htmlspecialchars($photo['username']); ?></small>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="alert alert-info">
                No photos found for "<?php echo htmlspecialchars($search); ?>"
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?> 