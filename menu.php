<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark d-flex align-items-center text-white">
        <a class="navbar-brand" href="http://localhost/student-form-oop/dashboard.php">
            <?php
            if ($_SESSION['user_type']) {
                echo 'Admin';
            } else {
                echo 'Student';
            }
            ?>
            Dashboard</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active mt-3 mx-3">
                    <a class="nav-link" href="http://localhost/student-form-oop/profile.php">
                        <?php
                        $userObj = new Database();
                        $result = $userObj->fetchOneRecordById($_SESSION['id']);
                        // $sql = "SELECT * FROM registration WHERE id='{$_SESSION['id']}'";
                        // $stmt = $pdo->prepare($sql);
                        // $stmt->execute();
                        // $result = $stmt->fetch(PDO::FETCH_ASSOC);
                        echo "<p>" . $result['firstname'] . " " . $result['lastname'] . "</p>";
                        ?>
                    </a>
                </li>
                <li class="nav-item mb-3">
                    <a class="nav-link disabled" href="#">
                        <a href="logout.php">
                            <button class="btn btn-primary">Logout </button>
                        </a>
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</body>

</html>