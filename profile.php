<?php
session_start();
if (!$_SESSION["id"]) {
    header("Location: login.php");
}

include 'database.php';
$userObj = new Database();

$user = $userObj->fetchOneRecordById($_SESSION['id']);
// $sql = "SELECT * FROM registration WHERE id='{$_SESSION['id']}'";
// $stmt = $pdo->prepare($sql);
// $stmt->execute();
// $result = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="profile.css">
</head>

<body>
    <?php
    include 'menu.php';
    ?>
    <h3 class="title">Your Account Info.</h3>

    <div class='form-section mb-3'>
        <h5> First Name: <?php echo $result['firstname'] ?> </h5>
        <h5> Middle Name: <?php echo $result['middlename'] ?> </h5>
        <h5> Last Name: <?php echo $result['lastname'] ?> </h5>
        <h5> Phone: <?php echo $result['phone'] ?> </h5>
        <h5> Class: <?php echo $result['class'] ?> </h5>
        <h5> Gender: <?php echo $result['gender'] ?> </h5>
        <h5> Email: <?php echo $result['email'] ?> </h5>
    </div>
    <div class='update-area'>
        <a href='update-profile-image.php'>
            <button class="btn btn-secondary">Update Profile Image</button>
        </a>
        <a href='update-password.php'>
            <button class="btn btn-primary">Update Password</button>
        </a>
    </div>
</body>

</html>