<?php
session_start();

include "connect.php";

$sessionUser = $_SESSION['user_type'];

$successValue = 0;
$errorValue = 0;
$message = array("errorMessage" => "", "successMessage" => "");

$user_own_id = $_POST['user_own_id'];

$sql = "SELECT * FROM registration WHERE id=$user_own_id";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$firstname = "";
if ($result['firstname'] != $_POST['firstname']) {
    $firstname = $_POST['firstname'];
} else {
    $firstname = $result['firstname'];
}

$middlename = '';
if ($result['middlename'] != $_POST['middlename']) {
    $middlename = $_POST['middlename'];
} else {
    $middlename = $result['middlename'];
}

$lastname = '';
if ($result['lastname'] != $_POST['lastname']) {
    $lastname = $_POST['lastname'];
} else {
    $lastname = $result['lastname'];
}

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
    $errorValue = 1;
    $message["errorMessage"] = "New Password & Confirmed password not matched";
}

$phone = $_POST['phone'];
$pattern = "/^(?:\+88|88)?(01[3-9]\d{8})$/";
if (!preg_match($pattern, $phone)) {
    $errorValue = 1;
    $message["errorMessage"] = "Phone number is not valid BD number";
}

// profile image 
$std_img = $result['std_img'];
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

//gallery image
$gallery_images = $result['gallery_images'];
$error = array();
$extension = array("jpeg", "jpg", "png", "gif");
$maxsize = 120 * 1024;
$allImages = "";
$firstImageTempName = $_FILES["files"]["tmp_name"][0];

if (strlen($firstImageTempName)) {
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
                    $message["errorMessage"] = "File size is larger than 120KB. Uplaod size limit 120KB";
                }
            } else {
                $errorValue = 1;
                $message["errorMessage"] = "Less than 4 images selected";
            }
        } else {
            $errorValue = 1;
            $message["errorMessage"] = "Only jpeg jpg png gif type image support for gallery";
        }
    }
    $gallery_images = $allImages;
}

$gender = $_POST['gender'];
$user_type = 0;
if ($sessionUser) {
    $user_type = $_POST['user_type'];
}
$class = $_POST['class'];
$division = $_POST['division'];
$district = $_POST['district'];
$upazila = $_POST['upazila'];
$address = $_POST['address'];

if (!$errorValue && $sessionUser) {
    $sql = "UPDATE `registration`
                    SET firstname=:firstname,
                    middlename=:middlename,
                    lastname=:lastname,
                    phone=:phone,
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
                    WHERE id=$user_own_id";

    $stmt = $pdo->prepare($sql);

    $res = $stmt->execute(['firstname' => $firstname, 'middlename' => $middlename, 'lastname' => $lastname, 'phone' => $phone,  'password' => $password, 'retypepassword' => $retypepassword, 'class' => $class, 'gender' => $gender, 'division' => $division, 'district' => $district, 'upazila' => $upazila, 'address' => $address, 'std_img' => $std_img, 'user_type' => $user_type, 'gallery_images' => $gallery_images]);

    if ($res) {
        $message["successMessage"] = "Successfully updated";
    } else {
        $message["errorMessage"] = "Update failed";
    }
    echo json_encode($message);
} else if (!$sessionUser) {
    $sql = "UPDATE `registration`
                    SET firstname=:firstname,
                    middlename=:middlename,
                    lastname=:lastname,
                    phone=:phone,
                    password=:password,
                    retypepassword=:retypepassword,
                    class=:class,
                    gender=:gender,
                    division=:division,
                    district=:district,
                    upazila=:upazila,
                    address=:address,
                    std_img=:std_img,
                    gallery_images=:gallery_images
                    WHERE id=$user_own_id";

    $stmt = $pdo->prepare($sql);

    $res = $stmt->execute(['firstname' => $firstname, 'middlename' => $middlename, 'lastname' => $lastname, 'phone' => $phone,  'password' => $password, 'retypepassword' => $retypepassword, 'class' => $class, 'gender' => $gender, 'division' => $division, 'district' => $district, 'upazila' => $upazila, 'address' => $address, 'std_img' => $std_img, 'gallery_images' => $gallery_images]);

    if ($res) {
        $message["successMessage"] = "Successfully updated";
    } else {
        $message["errorMessage"] = "Update failed";
    }
    echo json_encode($message);
} else {
    echo json_encode($message);
}
