<?php
session_start();
require_once 'config/connection.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$id = $_GET['id'];
$user_id = $_SESSION['user_id'];

$query = "SELECT * FROM photos WHERE id = '$id' AND user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$photo = mysqli_fetch_assoc($result);

if(!$photo) {
    header("Location: gallery.php");
    exit();
}

if(isset($_POST['update'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    if($_FILES['image']['name']) {
        $image = $_FILES['image'];
        $image_name = time() . '_' . $image['name'];
        $target = "uploads/" . $image_name;
        
        if(move_uploaded_file($image['tmp_name'], $target)) {
            unlink("uploads/" . $photo['image_path']);
            $query = "UPDATE photos SET title = '$title', description = '$description', image_path = '$image_name' 
                      WHERE id = '$id'";
        }
    } else {
        $query = "UPDATE photos SET title = '$title', description = '$description' WHERE id = '$id'";
    }
    
    if(mysqli_query($conn, $query)) {
        header("Location: gallery.php");
        exit();
    }
    $error = "Update gagal!";
}

include 'includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Edit Photo</div>
                <div class="card-body">
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" value="<?php echo $photo['title']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3"><?php echo $photo['description']; ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Current Image</label>
                            <img src="uploads/<?php echo $photo['image_path']; ?>" class="img-thumbnail d-block" style="max-width: 200px">
                        </div>
                        <div class="mb-3">
                            <label>New Image (optional)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                        </div>
                        <button type="submit" name="update" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>