<?php
include "database.php";
$userObj = new Database();

$successValue = 0;
$errorValue = 0;
$message = array("errorMessage" => "", "successMessage" => "");

$firstname = $_POST["firstname"];
$middlename = $_POST['middlename'];
$lastname = $_POST['lastname'];

$email = $_POST['email'];
$row = $userObj->fetchOneRecordByEmail($email);
// $sql = "SELECT * from `registration` WHERE email=:email";
// $stmt = $pdo->prepare($sql);
// $stmt->execute(['email' => $email]);
// $row = $stmt->fetch(PDO::FETCH_ASSOC);
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
    $user_type = $_POST['user_type'];
    $class = $_POST['class'];
    $division = $_POST['division'];
    $district = $_POST['district'];
    $upazila = $_POST['upazila'];
    $address = $_POST['address'];

    if (!$errorValue) {
        $insertRes = $userObj->insertData($user_type, $std_img, $firstname, $middlename, $lastname, $phone, $email, $password, $retypepassword, $class, $gender, $division, $district, $upazila, $address);

        if ($insertRes) {
            $successValue = 1;
            $message["successMessage"] = "User added successfully";
        } else {
            $errorValue = 1;
            $message["errorMessage"] = "User not added";
        }
        // $sql = "INSERT INTO `registration`(firstname, middlename, lastname, email, password, retypepassword, phone, std_img, gender, user_type, class, division, district, upazila, address) VALUES(:firstname, :middlename, :lastname, :email, :password, :retypepassword, :phone, :std_img, :gender, :user_type, :class, :division, :district, :upazila, :address)";

        // $stmt = $pdo->prepare($sql);

        // $result = $stmt->execute(['firstname' => $firstname, 'middlename' => $middlename, 'lastname' => $lastname, 'email' => $email,  'password' => $password,  'retypepassword' => $retypepassword, 'phone' => $phone, 'std_img' => $std_img, 'gender' => $gender,  'user_type' => $user_type, 'class' => $class, 'division' => $division, 'district' => $district, 'upazila' => $upazila, 'address' => $address]);

        // if ($result) {
        //     $message["successMessage"] = "User added successfully";
        // } else {
        //     $message["errorMessage"] = "User not added";
        // }
    }
}

echo json_encode($message);
