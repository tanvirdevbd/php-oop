<?php
session_start();
if (!$_SESSION["id"]) {
    header("Location: login.php");
}
include 'database.php';
$userObj = new Database();

$successValue = 0;
$error = 0;
$errorMessage = "";

$sessionId = $_SESSION['id'];
$sessionUser = $_SESSION['user_type'];

// if (
//     !isset($sessionId) || ($sessionId !== $_GET['id'] && $sessionUser != 1)
// ) {
//     header("Location: dashboard.php");
//     die();
// }

$result = $userObj->fetchOneRecordById($_SESSION['id']);
// $sql = "SELECT * FROM registration WHERE id='$sessionId'";
// $stmt = $pdo->prepare($sql);
// $stmt->execute();
// $result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $filename = $_FILES["uploadfile"]["name"];
    $tempname = $_FILES["uploadfile"]["tmp_name"];
    $folder = "images/" . $filename;
    if ($folder == "images/") {
        $folder = $result['std_img'];
    }
    move_uploaded_file($tempname, $folder);

    $std_img = $folder;
    $sql = "UPDATE `registration`
                SET std_img=:std_img
                WHERE id=$sessionId";

    $stmt = $pdo->prepare($sql);

    $res = $stmt->execute(['std_img' => $folder]);

    if ($res) {
        $successValue = "Profile Picture Updated Successfully";
    } else {
        $errorMessage = "Profile Picture Update Failed";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="update.css">
</head>

<body>

    <?php
    if ($successValue) {
        echo "<div class='alert alert-success' role='alert'>" . $successValue . "</div>";
        echo "<meta http-equiv='refresh' content='1;url=dashboard.php'>";
    } else if ($errorMessage) {
        echo '<div class="alert alert-danger" role="alert">' . $errorMessage . '</div>';
    }
    ?>

    <div class='container'>
        <div class='title'>
            <h1>Update Your Image</h1>
        </div>
        <div class='form-section'>
            <form method='POST' enctype="multipart/form-data">
                <div class='left'>
                    <!-- image  -->
                    <?php
                    if ($result['std_img']) { ?>
                        <img src="<?php echo $result['std_img'] ?>" alt="profile_image">
                    <?php
                    }
                    ?>
                    <div class="mb-2 me-2">
                        <label for="image" class="form-label name">Profile Picture: </label>
                        <input type="file" name="uploadfile" id="">
                    </div>
                    <!-- image update button  -->
                    <button type="submit" class="reg-btn w-100">Update Image </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>