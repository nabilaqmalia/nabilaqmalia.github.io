<?php
session_start();
require_once 'config/connection.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if(isset($_POST['upload'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $user_id = $_SESSION['user_id'];
    
    $image = $_FILES['image'];
    $image_name = time() . '_' . $image['name'];
    $target = "uploads/" . $image_name;
    
    if(move_uploaded_file($image['tmp_name'], $target)) {
        $query = "INSERT INTO photos (user_id, title, description, image_path) 
                  VALUES ('$user_id', '$title', '$description', '$image_name')";
        
        if(mysqli_query($conn, $query)) {
            header("Location: gallery.php");
            exit();
        }
    }
    $error = "Upload gagal!";
}

include 'includes/header.php';
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">Upload Photo</div>
                <div class="card-body">
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Description</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                        </div>
                        <button type="submit" name="upload" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>