<?php
session_start();
require_once 'config/connection.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM photos WHERE user_id = '$user_id' ORDER BY created_at DESC";
$result = mysqli_query($conn, $query);

include 'includes/header.php';
?>

<div class="container mt-4">
    <h2>My Gallery</h2>
    <div class="row">
        <?php while($photo = mysqli_fetch_assoc($result)): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="uploads/<?php echo $photo['image_path']; ?>" class="card-img-top" alt="<?php echo $photo['title']; ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $photo['title']; ?></h5>
                        <p class="card-text"><?php echo $photo['description']; ?></p>
                        <a href="edit.php?id=<?php echo $photo['id']; ?>" class="btn btn-primary">Edit</a>
                        <a href="delete.php?id=<?php echo $photo['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?> 