<?php
include_once 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'] ?? null;
if (!$admin_id) {
    header('location:login.php');
    exit;
}

// Shto produkt
if (isset($_POST['add_product'])) {
    $emri = mysqli_real_escape_string($conn, $_POST['emri']);
    $cmimi = floatval($_POST['cmimi']);
    $image = $_FILES['image']['name'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_size = $_FILES['image']['size'];
    $image_folder = 'uploaded_img/' . $image;

    $check = mysqli_query($conn, "SELECT emri FROM produkt WHERE emri = '$emri'");
    if (mysqli_num_rows($check) > 0) {
        $message[] = 'Ky produkt ekziston tashmë!';
    } elseif ($image_size > 2 * 1024 * 1024) {
        $message[] = 'Imazhi është shumë i madh!';
    } else {
        $insert = mysqli_query($conn, "INSERT INTO produkt (emri, cmimi, image) VALUES ('$emri', '$cmimi', '$image')");
        if ($insert) {
            move_uploaded_file($image_tmp_name, $image_folder);
            $message[] = 'Produkti u shtua me sukses!';
        } else {
            $message[] = 'Shtimi dështoi.';
        }
    }
}

// Fshi produkt
if (isset($_GET['delete'])) {
    $delete_id = intval($_GET['delete']);
    $select_image = mysqli_query($conn, "SELECT image FROM produkt WHERE id = $delete_id");
    if ($row = mysqli_fetch_assoc($select_image)) {
        $image_path = 'uploaded_img/' . $row['image'];
        if (file_exists($image_path)) unlink($image_path);
    }
    mysqli_query($conn, "DELETE FROM produkt WHERE id = $delete_id");
    header('location:admin_products.php');
    exit;
}

// Përditëso produkt
if (isset($_POST['update_product'])) {
    $id = intval($_POST['update_p_id']);
    $emri = mysqli_real_escape_string($conn, $_POST['update_emri']);
    $cmimi = floatval($_POST['update_cmimi']);
    $old_image = $_POST['update_old_image'];

    mysqli_query($conn, "UPDATE produkt SET emri = '$emri', cmimi = '$cmimi' WHERE id = $id");

    if (!empty($_FILES['update_image']['name'])) {
        $new_image = $_FILES['update_image']['name'];
        $tmp_name = $_FILES['update_image']['tmp_name'];
        $size = $_FILES['update_image']['size'];
        $folder = 'uploaded_img/' . $new_image;

        if ($size <= 2 * 1024 * 1024) {
            mysqli_query($conn, "UPDATE produkt SET image = '$new_image' WHERE id = $id");
            move_uploaded_file($tmp_name, $folder);
            $old_path = 'uploaded_img/' . $old_image;
            if (file_exists($old_path)) unlink($old_path);
        } else {
            $message[] = 'Imazhi i ri është shumë i madh!';
        }
    }

    header('location:admin_products.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Produktet</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/admin_style.css">
</head>
<body>

<?php include_once 'admin_header.php'; ?>

<section class="add-products">
    <h1 class="title">Shto Produkt</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="text" name="emri" class="box" placeholder="Emri i produktit" required>
        <input type="number" name="cmimi" step="0.01" class="box" placeholder="Çmimi" required>
        <input type="file" name="image" accept="image/*" class="box" required>
        <input type="submit" value="Shto produkt" name="add_product" class="btn">
    </form>
</section>

<section class="show-products">
    <div class="box-container">
        <?php
        $result = mysqli_query($conn, "SELECT * FROM produkt");
        if (mysqli_num_rows($result) > 0):
            while ($row = mysqli_fetch_assoc($result)):
        ?>
        <div class="box">
            <img src="uploaded_img/<?= htmlspecialchars($row['image']) ?>" alt="">
            <div class="name"><?= htmlspecialchars($row['emri']) ?></div>
            <div class="price">$<?= number_format($row['cmimi'], 2) ?>/-</div>
            <a href="admin_products.php?update=<?= $row['id'] ?>" class="option-btn">Përditëso</a>
            <a href="admin_products.php?delete=<?= $row['id'] ?>" class="delete-btn" onclick="return confirm('Fshi produktin?');">Fshi</a>
        </div>
        <?php endwhile; else: ?>
            <p class="empty">Nuk ka produkte të shtuar!</p>
        <?php endif; ?>
    </div>
</section>

<section class="edit-product-form">
<?php if (isset($_GET['update'])):
    $id = intval($_GET['update']);
    $query = mysqli_query($conn, "SELECT * FROM produkt WHERE id = $id");
    if (mysqli_num_rows($query) > 0):
        $row = mysqli_fetch_assoc($query);
?>
<form action="" method="post" enctype="multipart/form-data">
    <input type="hidden" name="update_p_id" value="<?= $row['id'] ?>">
    <input type="hidden" name="update_old_image" value="<?= $row['image'] ?>">
    <img src="uploaded_img/<?= htmlspecialchars($row['image']) ?>" alt="">
    <input type="text" name="update_emri" value="<?= htmlspecialchars($row['emri']) ?>" class="box" required>
    <input type="number" name="update_cmimi" value="<?= $row['cmimi'] ?>" step="0.01" class="box" required>
    <input type="file" name="update_image" accept="image/*" class="box">
    <input type="submit" name="update_product" value="Përditëso" class="btn">
    <input type="reset" value="Anulo" id="close-update" class="option-btn">
</form>
<?php endif; else: ?>
<script>document.querySelector(".edit-product-form").style.display = "none";</script>
<?php endif; ?>
</section>

<script src="jscript/admin_script.js"></script>
</body>
</html>
