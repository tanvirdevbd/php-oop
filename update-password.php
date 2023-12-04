<?php
session_start();
if (!$_SESSION["id"]) {
    header("Location: login.php");
}

include 'connect.php';

$successValue = 0;
$error = 0;
$errorMessage = "";

$sql = "SELECT * FROM registration WHERE id='{$_SESSION['id']}'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $oldPassword = $_POST['oldPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];

    if ($oldPassword == $result['password']) {
        if (strlen($newPassword) < 8) {
            $errorMessage = "New Password length less than 8";
        } else if (!preg_match('@[A-Z]@', $newPassword)) {
            $errorMessage = "Uppercase not included in your password";
        } else if (!preg_match('@[a-z]@', $newPassword)) {
            $errorMessage = "lowercase not included in your password";
        } else if (!preg_match('@[0-9]@', $newPassword)) {
            $errorMessage = "number not included in your password";
        } else if ($newPassword != $confirmNewPassword) {
            $errorMessage = "New Password & Confirmed password not matched";
        } else {
            $sql = "UPDATE `registration`
                        SET password=:password
                        WHERE id='{$_SESSION['id']}'";
            $stmt = $pdo->prepare($sql);
            $res = $stmt->execute(['password' => $newPassword]);
            if ($res) {
                $successValue = "New Password updated successfully";
            } else {
                $errorMessage = "New Password not updated";
            }
        }
    } else {
        $errorMessage = "Old Password is incorrect";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css" />
    <link rel="stylesheet" href="profile.css">
</head>

<body>

    <?php
    if ($successValue) {
        echo '<div class="alert alert-success" role="alert">'
            . $successValue .
            '</div>';
        echo "<meta http-equiv='refresh' content='0;url=dashboard.php'>";;
        die;
    } else if ($errorMessage) {
        echo "<div class='alert alert-danger' role='alert'>"
            . $errorMessage .
            "</div>";
    }
    ?>
    <div class='container'>
        <div class='title'>
            <h1 class='my-5'>Change Account Password</h1>
        </div>
        <div class='form-section'>
            <form method='post'>
                <!-- old password -->
                <div class="mb-2 me-2">
                    <label for="old-password"> Old Password </label>
                    <div class="d-flex">
                        <input type="password" class="form-control" id="oldPassword" name="oldPassword" placeholder="Enter Old Password" required><i class="bi bi-eye-slash mt-2 ms-0" id="toggleOldPassword"></i>
                    </div>
                </div>
                <!-- new password -->
                <div class="mb-2 me-2">
                    <label for="new-password"> New Password</label>
                    <div class="d-flex">
                        <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="Enter New Password"> <i class="bi bi-eye-slash mt-2 ms-0" id="toggleNewPassword"></i>
                    </div>
                </div>

                <!-- confirm new password -->
                <div class="mb-2 me-2">
                    <label for="confirm-new-password"> Confirm new Password</label>
                    <div class="d-flex">
                        <input type="password" class="form-control" id="confirmNewPassword" name="confirmNewPassword" placeholder="Confirm New Password"> <i class="bi bi-eye-slash mt-2 ms-0" id="toggleConfirmNewPassword"></i>
                    </div>
                </div>
                <!-- update button  -->
                <button type="submit" class="btn btn-primary w-100">Update </button>
        </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script>
        const toggleOldPassword = document.querySelector('#toggleOldPassword');
        const oldPassword = document.querySelector('#oldPassword');

        const toggleNewPassword = document.querySelector('#toggleNewPassword');
        const newPassword = document.querySelector('#newPassword');

        const toggleConfirmNewPassword = document.querySelector('#toggleConfirmNewPassword');
        const confirmNewPassword = document.querySelector('#confirmNewPassword');

        toggleOldPassword.addEventListener('click', function() {
            const type = oldPassword
                .getAttribute('type') === 'password' ?
                'text' : 'password';
            oldPassword.setAttribute('type', type);
            this.classList.toggle('bi-eye');
        });

        toggleNewPassword.addEventListener('click', function() {
            const type = newPassword
                .getAttribute('type') === 'password' ?
                'text' : 'password';
            newPassword.setAttribute('type', type);
            this.classList.toggle('bi-eye');
        });

        toggleConfirmNewPassword.addEventListener('click', function() {
            const type = confirmNewPassword
                .getAttribute('type') === 'password' ?
                'text' : 'password';
            confirmNewPassword.setAttribute('type', type);
            this.classList.toggle('bi-eye');
        });
    </script>
</body>

</html>