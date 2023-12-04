<?php
session_start();
if (!$_SESSION["id"]) {
    header("Location: login.php");
}

include 'connect.php';

$successValue = 0;
$errorValue = 0;
$errorMessage = "";

$sessionId = $_SESSION['id'];
$sessionUser = $_SESSION['user_type'];

if (
    !isset($sessionId) || ($sessionId !== $_GET['id'] && $sessionUser != 1)
) {
    header("Location: dashboard.php");
    die();
}

$sql = "SELECT * FROM registration WHERE id='{$_GET['id']}'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $filename = $_FILES["uploadfile"]["name"];
    $tempname = $_FILES["uploadfile"]["tmp_name"];
    $folder = "images/" . $filename;
    if ($folder == "images/") {
        $folder = $result['std_img'];
    }
    move_uploaded_file($tempname, $folder);
    $std_img = $folder;

    $error = array();
    $extension = array("jpeg", "jpg", "png", "gif");
    $maxsize = 120 * 1024;
    $allImages = "";
    foreach ($_FILES["files"]["tmp_name"] as $key => $tmp_name) {
        $file_name = $_FILES["files"]["name"][$key];
        $file_tmp = $_FILES["files"]["tmp_name"][$key];
        $file_size = $_FILES["files"]["size"][$key];
        $ext = pathinfo($file_name, PATHINFO_EXTENSION);

        if (in_array($ext, $extension)) {
            if (count($_FILES["files"]["size"]) >= 4) {
                if ($file_size < $maxsize) {
                    if (!file_exists("photo_gallery/" . $file_name)) {
                        move_uploaded_file($file_tmp, "photo_gallery/" . $file_name);
                        if (strlen($allImages)) {
                            $allImages = "$allImages," . $file_name;
                        } else {
                            $allImages = $file_name;
                        }
                    } else {
                        $filename = basename($file_name, $ext);
                        $newFileName = $filename . time() . "." . $ext;
                        move_uploaded_file($file_tmp, "photo_gallery/" . $newFileName);
                        if (strlen($allImages)) {
                            $allImages = "$allImages," . $newFileName;
                        } else {
                            $allImages = $newFileName;
                        }
                    }
                } else {
                    $errorValue = 1;
                    $errorMessage = "File size is larger than 120KB. Uplaod size limit 120KB";
                }
            } else {
                $errorValue = 1;
                $errorMessage = "Less than 4 images selected";
            }
        } else {
            array_push($error, "$file_name, ");
        }
    }

    if ($allImages == $result['gallery_images'] || $allImages == '') {
        $gallery_images = $result['gallery_images'];
    } else {
        $gallery_images = $allImages;
    }

    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $phone = $_POST['phone'];
    $pattern = "/^(?:\+?88)?01[3-9$]\d{8}/";
    if (!preg_match($pattern, $phone)) {
        $errorValue = 1;
        $errorMessage = "Phone number is not valid BD number";
    }
    $email = $_POST['email'];
    $password = $_POST['password'];
    $retypepassword = $_POST['retypepassword'];
    $class = $_POST['class'];
    $gender = $_POST['gender'];
    $division = $_POST['division'];
    $district = "";
    $upazila = "";
    if (isset($_POST['district']) && isset($_POST['upazila'])) {
        $district = $_POST['district'];
        $upazila = $_POST['upazila'];
    } else {
        $errorValue = 1;
        $errorMessage = "Please select your district & upazila";
    }
    $address = $_POST['address'];
    if ($address == $result['address']) {
        $address == $result['address'];
    } else {
        $errorValue = 1;
        $errorMessage = "Please enter your address";
    }
    $user_type = "";
    if (isset($_POST['user_type'])) {
        $user_type = $_POST['user_type'];
    }
    if (!$errorValue) {
        $sql = "UPDATE `registration`
                    SET firstname=:firstname,
                    middlename=:middlename,
                    lastname=:lastname,
                    phone=:phone,
                    email=:email,
                    password=:password,
                    retypepassword=:retypepassword,
                    class=:class,
                    gender=:gender,
                    division=:division,
                    district=:district,
                    upazila=:upazila,
                    address=:address,
                    std_img=:std_img,
                    user_type=:user_type,
                    gallery_images=:gallery_images
                    WHERE id={$result['id']}";
        $stmt = $pdo->prepare($sql);

        $res = $stmt->execute(['firstname' => $firstname, 'middlename' => $middlename, 'lastname' => $lastname, 'phone' => $phone, 'email' => $email, 'password' => $password, 'retypepassword' => $retypepassword, 'class' => $class, 'gender' => $gender, 'division' => $division, 'district' => $district, 'upazila' => $upazila, 'address' => $address, 'std_img' => $std_img, 'user_type' => $user_type, 'gallery_images' => $gallery_images]);

        if ($res) {
            $successValue = "Updated Successfully";
        } else {
            $errorMessage = "Update Failed";
        }
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
    if ($successValue &&  isset($_GET['search'])) {
        $searchTerm = $_GET['search'];
        echo "<div class='alert alert-success' role='alert'>" . $successValue . "</div>";
        echo "<meta http-equiv='refresh' content='1;url=dashboard.php?search=$searchTerm'>";
    } else if ($successValue) {
        echo "<div class='alert alert-success' role='alert'>" . $successValue . "</div>";
        echo "<meta http-equiv='refresh' content='1;url=dashboard.php'>";
    } else if ($errorValue) {
        echo '<div class="alert alert-danger" role="alert">' . $errorMessage . '</div>';
    }
    ?>

    <div class='container'>
        <div class='title'>
            <h1>Update Student Reg. Info.</h1>
        </div>
        <div class='form-section'>
            <form method='POST' enctype="multipart/form-data">
                <div class='left'>
                    <!-- firstname  -->
                    <div class="mb-2 me-2">
                        <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter First Name" value="<?php echo $result['firstname'] ? $result['firstname'] : '' ?>">
                    </div>

                    <!-- middlename  -->
                    <div class="mb-2 me-2">
                        <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Enter Middle Name" value="<?php echo $result['middlename'] ?>">
                    </div>

                    <!-- lastname  -->
                    <div class="mb-2 me-2">
                        <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter Last Name" value="<?php echo $result['lastname'] ?>">
                    </div>

                    <!-- email -->
                    <div class="mb-2  me-2">
                        <input type="email" class="form-control" id="email" name="email" required placeholder="Enter Email" value="<?php echo $result['email'] ?>">
                    </div>

                    <!-- password -->
                    <div class="mb-2  me-2">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required value="<?php echo $result['password'] ?>">
                    </div>

                    <!-- re type password -->
                    <div class="mb-2 me-2">
                        <input type="password" class="form-control" id="retypepassword" name="retypepassword" required placeholder="Re Enter Password" value="<?php echo $result['retypepassword'] ?>">
                    </div>

                    <!-- phone  -->
                    <div class=" mb-2 me-2">
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Phone Number" value="<?php echo $result['phone'] ?>">
                    </div>
                    <!-- image  -->
                    <div class="mb-2 me-2">
                        <label for="image" class="form-label name">Profile Picture: </label>
                        <input type="file" name="uploadfile" id="">
                        <img class="ms-0" src="<?php echo $result['std_img'] ?>" alt="profile_image" width='40' height='40' style='border-radius: 50%;'>
                    </div>
                    <!-- gallery images  -->
                    <div class="mb-2 me-2">
                        <label for="gallery-images" class="form-label name">Gallery Images: </label>
                        <input type="file" name="files[]" multiple>
                        <div class="d-flex">
                            <?php
                            $gallery_imagesArr = [];
                            $gallery_imagesArr = explode(',', $result['gallery_images']);
                            if (count($gallery_imagesArr)) {
                                foreach ($gallery_imagesArr as $x => $singleImage) {
                                    echo "<img src='photo_gallery/{$singleImage}' alt='gallery_images'  width='40' height='40' style='border-radius: 50%;'>";
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class='right'>
                    <!-- gender  -->
                    <div class="mb-2">
                        <label for="gender" class="form-label me-3 name">Gender: </label>
                        <input type="radio" id="male" name="gender" value="MALE" <?php echo ($result['gender'] == 'MALE') ? 'checked' : '' ?>>
                        <label for="html" class='mx-1'>Male</label>
                        <input type="radio" id="female" name="gender" value="FEMALE" <?php echo ($result['gender'] == 'FEMALE') ? 'checked' : '' ?>>
                        <label for="html" class='mx-1'>Female</label>
                        <input type="radio" id="others" name="gender" value="OTHERS" <?php echo ($result['gender'] == 'OTHERS') ? 'checked' : '' ?>>
                        <label for="html" class='mx-1'>Others</label>
                    </div>
                    <!-- user  -->
                    <?php
                    if ($sessionUser) { ?>
                        <div class="mb-2">
                            <label for="user_type" class="form-label me-4 name">User: </label>
                            <select name="user_type" id="user_type" class="select-area">
                                <option value="1" <?php echo ($result['user_type'] == 1) ? "selected" : ""; ?>>Admin
                                </option>
                                <option value="0" <?php echo ($result['user_type'] == 0) ? "selected" : ""; ?>>Student
                                </option>
                            </select>
                        </div>
                    <?php
                    }
                    ?>
                    <!-- class  -->
                    <div class="mb-2">
                        <label for="class" class="form-label me-4 name">Class: </label>
                        <select name="class" id="class" class="select-area">
                            <?php
                            $sql = "SELECT * FROM class_tb";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute();
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                                <option value="<?php echo $row['id']; ?>" <?php echo ($result['class'] == $row['id']) ? "selected" : ""; ?>>
                                    <?php echo $row['name']; ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <!-- division  -->
                    <div class="mb-2">
                        <label for="division" class="form-label me-2 name">Division: </label>
                        <select name="division" id="division" class="select-area">
                            <option value="">Select Division</option>
                            <?php
                            $sql = "SELECT * FROM division_tb";
                            $stmt = $pdo->prepare($sql);
                            $stmt->execute();
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                                <option value="<?php echo $row['id']; ?>" <?php echo ($result['division'] == $row['id']) ? "selected" : ""; ?>>
                                    <?php echo $row['name']; ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <!-- district  -->
                    <div class="mb-2">
                        <label for="district" class="form-label me-3  name">District: </label>
                        <select name="district" id="district" class="select-area">
                            <?php
                            if ($result['division']) {
                                $sql = "SELECT * FROM district_tb WHERE division_id={$result['division']}";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute();
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                                    <option value="<?php echo $row['id']; ?>" <?php echo ($result['district'] == $row['id']) ? "selected" : ""; ?>>
                                        <?php echo $row['name']; ?>
                                    </option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <!-- upazila  -->
                    <div class="mb-2">
                        <label for="upazila" class="form-label me-3 name">Upazila: </label>
                        <select name="upazila" id="upazila" class="select-area">
                            <?php
                            if ($result['district']) {
                                $sql = "SELECT * FROM upazila_tb  WHERE district_id={$result['district']}";
                                $stmt = $pdo->prepare($sql);

                                $stmt->execute();
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                                    <option value="<?php echo $row['id']; ?>" <?php echo ($result['upazila'] == $row['id']) ? "selected" : ""; ?>>
                                        <?php echo $row['name']; ?>
                                    </option>
                            <?php
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <!-- address  -->
                    <div class="mb-2">
                        <textarea class="form-control me-2" id="address" name="address" rows="4" cols="50" placeholder="Enter Address" placeholder="Enter Address"><?php echo $result['address'] ? $result['address'] : '' ?></textarea>
                    </div>
                    <!-- register button  -->
                    <button type="submit" class="reg-btn w-100">Update </button>
                </div>
            </form>
        </div>
    </div>

    <script type="text/javascript" src="jquery.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            function loadData(type, category_id) {
                $.ajax({
                    url: 'load-cs.php',
                    type: 'POST',
                    data: {
                        type: type,
                        id: category_id
                    },
                    success: function(data) {
                        if (type === "upazilaData") {
                            $("#upazila").html(data)
                        } else if (type === "districtData") {
                            $("#district").html(data)
                        } else if (type === "") {
                            $("#division").append(data)
                        }
                    }
                });
            }
            loadData();

            $("#division").on("change", function() {
                var division = $("#division").val();
                if (division != "") {
                    loadData("districtData", division);
                } else {
                    $("#district").html("");
                }
            })

            $("#district").on("change", function() {
                var district = $("#district").val();
                if (district != "") {
                    loadData("upazilaData", district);
                } else {
                    $("#upazila").html("");
                }
            })


        })
    </script>
</body>

</html>