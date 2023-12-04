<?php
// Include database file
include 'database.php';
$userObj = new Database();

$successValue = 0;
$errorValue = 0;
$message = array("errorMessage" => "", "successMessage" => "");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstname = $_POST["firstname"];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];

    $email = $_POST['email'];
    $row = $userObj->fetchOneRecordByEmail($email);

    if ($row) {
        $errorValue = 1;
        $message["errorMessage"] = "Email already exists";
    } else {
        $password = $_POST['password'];
        if (strlen($password) < 8) {
            $errorValue = 1;
            $message["errorMessage"] = "New Password length less than 8";
        } else if (!preg_match('@[A-Z]@', $password)) {
            $errorValue = 1;
            $message["errorMessage"] = "Uppercase not included in your password";
        } else if (!preg_match('@[a-z]@', $password)) {
            $errorValue = 1;
            $message["errorMessage"] = "Lowercase not included in your password";
        } else if (!preg_match('@[0-9]@', $password)) {
            $errorValue = 1;
            $message["errorMessage"] = "Number not included in your password";
        }
        $retypepassword = $_POST['retypepassword'];
        if ($password != $retypepassword) {
            $message["errorMessage"] = "New Password & Confirmed password not matched";
        }

        $phone = $_POST['phone'];
        $pattern = "/^(?:\+88|88)?(01[3-9]\d{8})$/";
        if (!preg_match($pattern, $phone)) {
            $errorValue = 1;
            $message["errorMessage"] = "Phone number is not valid BD number";
        }

        // profile image 
        $std_img = "";
        $std_img_prof = "";
        $extension_prof = array("jpeg", "jpg", "png", "gif");
        $maxsize_prof = 120 * 1024;
        $file_name_prof = $_FILES["uploadfile"]["name"];
        if (strlen($file_name_prof)) {
            $file_tmp_prof = $_FILES["uploadfile"]["tmp_name"];
            $file_size_prof = $_FILES["uploadfile"]["size"];
            $ext_prof = pathinfo($file_name_prof, PATHINFO_EXTENSION);

            if (in_array($ext_prof, $extension_prof)) {
                if ($file_size_prof < $maxsize_prof) {
                    if (!file_exists("images/" . $file_name_prof)) {
                        $std_img_prof = "images/" . $file_name_prof;
                        move_uploaded_file($file_tmp_prof, $std_img_prof);
                    } else {
                        $filename_prof = basename($file_name_prof, $ext_prof);
                        $newFileName_prof = $filename_prof . time() . "." . $ext_prof;
                        $std_img_prof = "images/" . $newFileName_prof;
                        move_uploaded_file($file_tmp_prof, $std_img_prof);
                    }
                } else {
                    $errorValue = 1;
                    $message["errorMessage"] = "File size is larger than 120KB";
                }
            } else {
                $errorValue = 1;
                $message["errorMessage"] = "Only jpeg jpg png gif type image support for profile";
            }
            $std_img = $std_img_prof;
        }

        $gender = $_POST['gender'];
        $class = $_POST['class'];
        $division = $_POST['division'];
        $district = $_POST['district'];
        $upazila = $_POST['upazila'];
        $address = $_POST['address'];
        $user_type = 0;

        if (!$errorValue) {

            $regRes = $userObj->insertData($user_type, $std_img, $firstname, $middlename, $lastname, $phone, $email, $password, $retypepassword, $class, $gender, $division, $district, $upazila, $address);

            if ($regRes) {
                $successValue = 1;
                $message["successMessage"] = "Registration Successful";
            } else {
                $errorValue = 1;
                $message["errorMessage"] = "Registration Failed";
            }
        }
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Student Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php
    if ($successValue) {
        echo "<div class='alert alert-success' role='alert'>" . $message["successMessage"] . "</div>";
        echo "<meta http-equiv='refresh' content='1;url=login.php'>";
    } else if ($errorValue) {
        echo '<div class="alert alert-danger" role="alert">' . $message["errorMessage"] . '</div>';
    }
    ?>

    <div class='container'>
        <div class='title'>
            <h1>Student Registration</h1>
        </div>
        <div class='form-section'>
            <form action='registration.php' method='POST' enctype="multipart/form-data">
                <div class='left'>
                    <!-- firstname  -->
                    <div class="mb-2 me-2">
                        <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter First Name" required>
                    </div>
                    <!-- middlename  -->
                    <div class="mb-2 me-2">
                        <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Enter Middle Name">
                    </div>
                    <!-- lastname  -->
                    <div class="mb-2 me-2">
                        <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter Last Name" required>
                    </div>
                    <!-- email -->
                    <div class="mb-2  me-2">
                        <input type="email" class="form-control" id="email" name="email" required placeholder="Enter Email" required>
                    </div>
                    <!-- password -->
                    <div class="mb-2  me-2">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
                    </div>

                    <!-- re type password -->
                    <div class="mb-2 me-2">
                        <input type="password" class="form-control" id="retypepassword" name="retypepassword" placeholder="Re Enter Password" required>
                    </div>
                    <!-- phone  -->
                    <div class="mb-2 me-2">
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Phone Number" required>
                    </div>

                    <!-- image  -->
                    <div class="mb-4 me-2">
                        <label for="image" class="form-label name">Profile Picture: </label>
                        <input type="file" name="uploadfile" id="" class="ms-0">
                    </div>
                </div>
                <div class='right'>
                    <!-- gender  -->
                    <div class="mb-2">
                        <label for="gender" class="form-label me-3 name">Gender: </label>
                        <input type="radio" id="male" name="gender" value="MALE" required>
                        <label for="html" class='mx-1'>Male</label>
                        <input type="radio" id="female" name="gender" value="FEMALE" required>
                        <label for="html" class='mx-1'>Female</label>
                        <input type="radio" id="others" name="gender" value="OTHERS" required>
                        <label for="html" class='mx-1'>Others</label>
                    </div>
                    <!-- class  -->
                    <div class="mb-2">
                        <label for="class" class="form-label me-4 name">Class: </label>
                        <select name="class" id="class" class="select-area" required>
                            <option value="">Select Class</option>
                        </select>
                    </div>
                    <!-- division  -->
                    <div class="mb-2">
                        <label for="division" class="form-label me-2 name">Division: </label>
                        <select name="division" id="division" class="select-area">
                            <option value="">Select Division</option>
                        </select>
                    </div>
                    <!-- district  -->
                    <div class="mb-2">
                        <label for="district" class="form-label me-3  name">District: </label>
                        <select name="district" id="district" class="select-area">
                            <option value=""></option>
                        </select>
                    </div>
                    <!-- upazila  -->
                    <div class="mb-2">
                        <label for="upazila" class="form-label me-3 name">Upazila: </label>
                        <select name="upazila" id="upazila" class="select-area">
                            <option value=""></option>
                        </select>
                    </div>
                    <!-- address  -->
                    <div class="mb-2">
                        <textarea class="form-control me-2" id="address" name="address" rows="4" cols="50" placeholder="Enter Address"></textarea>
                    </div>
                    <!-- register button  -->
                    <button type="submit" class="reg-btn w-100">Register</button>
                    <div class='d-flex mt-2'>
                        <p>Already have an account? </p>
                        <a href="login.php" class='link ms-1'>Login Now</a>
                    </div>
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
                        } else {
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

            function loadClass() {
                $.ajax({
                    url: 'load-cs.php',
                    type: 'POST',
                    data: {
                        type: "classData"
                    },
                    success: function(data) {
                        $("#class").append(data)
                    }
                });
            }
            loadClass();
        })
    </script>
</body>

</html>