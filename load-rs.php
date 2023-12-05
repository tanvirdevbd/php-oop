<?php
session_start();
include 'database.php';
$userObj = new Database();

$sessionUser = $_SESSION['user_type'];
$sessionId = $_SESSION['id'];
$gallery_imagesArr = [];
$str = "";

if ($sessionUser && $_POST['type'] == "search") {
    $searchedTerm = $_POST['id'];
    $totalRes = $userObj->nameOrPhoneSearch($searchedTerm);

    if (count($totalRes)) {
        $trSingle = "";
        $rowNum = 0;
        foreach ($totalRes as $key => $row) {
            $gallery_imagesArr = explode(',', $row['gallery_images']);
            $glrCount = count($gallery_imagesArr);
            $rowNum++;
            $trSingle = "
        <tr>
            <th scope='row'>{$rowNum}</th>       
            <td style='width: 5%;'>";
            $profileImage  = $row['std_img'] ? $row['std_img'] : "https://rb.gy/b8h6ei";
            $trSingle .= "<img src='{$profileImage}' alt='Profile image' width='80' height='80' style='border-radius: 50%;'>
        </td>
        <td class='d-flex flex-wrap gallery-area' style='width: 5%;'>";
            if ($glrCount > 1) {
                foreach ($gallery_imagesArr as $x => $singleImage) {
                    $trSingle .= "<img src='photo_gallery/{$singleImage}' alt='gallery_images' width='40' height='40' class='single-image'>";
                }
            } else {
                $trSingle .= "<img src='https://rb.gy/tcflqu' alt='gallery_images' width='40' height='40' class='single-image'>";
            }
            $trSingle .= "
        </td>
            <td>{$row['firstname']}</td>
            <td>{$row['middlename']}</td>
            <td>{$row['lastname']}</td>
            <td>{$row['phone']}</td>
            <td>{$row['class']}</td>
            <td>{$row['gender']}</td>
            <td>";
            $userDivision = $row['division'];
            $divisionRes = $userObj->loadDivisionOptionData($userDivision);
            if (!$divisionRes) {
                $trSingle .=  "";
            } else {
                $trSingle .=  $divisionRes['name'];
            }
            $trSingle .= "</td>
    
            <td>";
            $userDistrict = $row['district'];
            $districtRes = $userObj->loadDistrictDataById($userDistrict);
            if (!$districtRes) {
                $trSingle .=  "";
            } else {
                $trSingle .=  $districtRes['name'];
            }
            $trSingle .= "</td>    
    
            <td>";
            $userUpazila = $row['upazila'];
            $upazilaRes = $userObj->loadUpazilaDataById($userUpazila);

            if (!$upazilaRes) {
                $trSingle .=  "";
            } else {
                $trSingle .=  $upazilaRes['name'];
            }

            $trSingle .= "</td>
            <td>{$row['address']}</td>
            <td>{$row['email']}</td>
            <td>{$row['password']}</td>
            <td>";
            if ($row['user_type'] == 1) {
                $trSingle .= 'Admin';
            } else if ($row['user_type'] == 0) {
                $trSingle .= 'Student';
            }
            $trSingle .=  "</td>
            <td>
            <button type='button' class='btn btn-warning mt-1 me-2 edit-btn' data-bs-toggle='modal' data-bs-target='#editUserModal' data-eid='{$row["id"]}'> Update
            </button>
            <button class='btn btn-danger delete-btn' data-did='{$row["id"]}'> Delete </button>
            </td>    
        </tr>
        ";
            $str .= $trSingle;
        }
        echo $str;
    } else {
        $str = "<h3>No data found</h3>";
        echo $str;
    }
} else if ($sessionUser  && $_POST['type'] == "") {
    $stmt = $userObj->fetchAllRecord();
    $stmt->execute();

    $rowNum = 0;
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $gallery_imagesArr = explode(',', $row['gallery_images']);
        $glrCount = count($gallery_imagesArr);
        $rowNum++;
        $trSingle = "
    <tr>
        <th scope='row'>{$rowNum}</th>       
        <td style='width: 5%;'>";
        $profileImage  = $row['std_img'] ? $row['std_img'] : "https://rb.gy/b8h6ei";
        $trSingle .= "<img src='{$profileImage}' alt='Profile image' width='80' height='80' style='border-radius: 50%;'>
        </td>
        <td class='d-flex flex-wrap gallery-area' style='width: 5%;'>";
        if ($glrCount > 1) {
            foreach ($gallery_imagesArr as $x => $singleImage) {
                $trSingle .= "<img src='photo_gallery/{$singleImage}' alt='gallery_images' width='40' height='40' class='single-image'>";
            }
        } else {
            $trSingle .= "<img src='https://rb.gy/tcflqu' alt='gallery_images' width='40' height='40' class='single-image'>";
        }
        $trSingle .= "
        </td>
        <td style='width: 5%;'>{$row['firstname']}</td>
        <td style='width: 5%;'>{$row['middlename']}</td>
        <td style='width: 5%;'>{$row['lastname']}</td>
        <td style='width: 5%;'>{$row['phone']}</td>
        <td style='width: 5%;'>{$row['class']}</td>
        <td style='width: 5%;'>{$row['gender']}</td>

        <td style='width: 5%;'>";
        $userDivision = $row['division'];
        $divisionRes = $userObj->loadDivisionOptionData($userDivision);

        if (!$divisionRes) {
            $trSingle .=  "";
        } else {
            $trSingle .=  $divisionRes['name'];
        }

        $trSingle .= "</td>

        <td style='width: 5%;'>";
        $userDistrict = $row['district'];
        $districtRes = $userObj->loadDistrictDataById($userDistrict);

        if (!$districtRes) {
            $trSingle .=  "";
        } else {
            $trSingle .=  $districtRes['name'];
        }

        $trSingle .= "</td>

        <td style='width: 5%;'>";
        $userUpazila = $row['upazila'];
        $upazilaRes = $userObj->loadUpazilaDataById($userUpazila);

        if (!$upazilaRes) {
            $trSingle .=  "";
        } else {
            $trSingle .=  $upazilaRes['name'];
        }

        $trSingle .= "</td>

        <td style='width: 5%;'>{$row['address']}</td>
        <td style='width: 5%;'>{$row['email']}</td>
        <td style='width: 5%;'>{$row['password']}</td>
        <td style='width: 5%;'>";
        if ($row['user_type'] == 1) {
            $trSingle .= 'Admin';
        } else if ($row['user_type'] == 0) {
            $trSingle .= 'Student';
        }
        $trSingle .=  "</td>
        <td style='width: 5%;'>
        <button type='button' class='btn btn-warning mt-1 me-2 edit-btn' data-eid='{$row["id"]}' data-bs-toggle='modal' data-bs-target='#editUserModal'> Update
        </button>
        <button class='btn btn-danger delete-btn' data-did='{$row["id"]}'> Delete </button>
        </td>    
    </tr>
    ";
        $str .= $trSingle;
    }
    echo $str;
} else {
    $res = $userObj->fetchOneRecordById($sessionId);
    $rowNum = 1;
    $gallery_imagesArr = explode(',', $res['gallery_images']);
    $glrCount = count($gallery_imagesArr);
    $str = "
    <tr>
        <th scope='row'>{$rowNum}</th>       
        <td>";
    $profileImage  = $res['std_img'] ? $res['std_img'] : "https://rb.gy/b8h6ei";
    $str .= "<img src='{$profileImage}' alt='Profile image' width='80' height='80' style='border-radius: 50%;'>
        </td>
        <td class='d-flex flex-wrap gallery-area'>";
    if ($glrCount > 1) {
        foreach ($gallery_imagesArr as $x => $singleImage) {
            $str .= "<img src='photo_gallery/{$singleImage}' alt='gallery_images'  width='40' height='40' class='single-image'>";
        }
    } else {
        $str .= "<img src='https://rb.gy/tcflqu' alt='gallery_images' width='40' height='40' class='single-image'>";
    }
    $str .= "
        </td>
        <td style='width: 5%;'>{$res['firstname']}</td>
        <td  style='width: 5%;'>{$res['middlename']}</td>
        <td  style='width: 5%;'>{$res['lastname']}</td>
        <td  style='width: 5%;'>{$res['phone']}</td>
        <td  style='width: 5%;'>{$res['class']}</td>
        <td  style='width: 5%;'>{$res['gender']}</td>
        <td style='width: 5%;'>";
    $userDivision = $res['division'];
    $divisionRes = $userObj->loadDivisionOptionData($userDivision);
    $str .=  $divisionRes['name'];
    $str .= "</td>
    
    <td style='width: 5%;'>";
    $userDistrict = $res['district'];
    $districtRes = $userObj->loadDistrictDataById($userDistrict);
    $str .=  $districtRes['name'];
    $str .= "</td>   
    
    <td style='width: 5%;'>";
    $userUpazila = $res['upazila'];
    $upazilaRes = $userObj->loadUpazilaDataById($userUpazila);

    if (!$upazilaRes) {
        $str .=  "";
    } else {
        $str .=  $upazilaRes['name'];
    }
    $str .= "</td>
        <td style='width: 5%;'>{$res['address']}</td>
        <td style='width: 5%;'>{$res['email']}</td>
        <td style='width: 5%;'>{$res['password']}</td>
        <td style='width: 5%;'>
        <button type='button' class='btn btn-warning mt-1 me-2 edit-btn' data-eid='{$res["id"]}' data-bs-toggle='modal' data-bs-target='#editUserModal' > Update
        </button>
        </td>    
    </tr>
    ";
    echo $str;
}

if ($_POST['type'] == "classData") {
    $sql = "SELECT * FROM class_tb";

    $stmt = $pdo->prepare($sql);

    $stmt->execute();

    $str = "";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $str .= "<option value='{$row['id']}'>{$row['name']}</option>";
    }
} else if ($_POST['type'] == "divisionData") {
    $sql = "SELECT * FROM division_tb";

    $stmt = $pdo->prepare($sql);

    $stmt->execute();

    $str = "";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $str .= "<option value='{$row['id']}'>{$row['name']}</option>";
    }
} else if ($_POST['type'] == "districtData") {
    $sql = "SELECT * FROM district_tb WHERE division_id = {$_POST['id']}";

    $stmt = $pdo->prepare($sql);

    $stmt->execute();

    $str = "";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $str .= "<option value='{$row['id']}'>{$row['name']}</option>";
    }
} else if ($_POST['type'] == "upazilaData") {
    $sql = "SELECT * FROM upazila_tb WHERE district_id = {$_POST['id']}";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    $str = "";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $str .= "<option value='{$row['id']}'>{$row['name']}</option>";
    }
}
