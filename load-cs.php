<?php
include 'database.php';
$Obj = new Database();

if ($_POST['type'] == "classData") {
    $classRes  = $Obj->loadClassData();
    echo $classRes;
    // $sql = "SELECT * FROM class_tb";

    // $stmt = $pdo->prepare($sql);

    // $stmt->execute();

    // $str = "";
    // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    //     $str .= "<option value='{$row['id']}'>{$row['name']}</option>";
    // }
} else if ($_POST['type'] == "") {
    $divisionRes  = $Obj->loadDivisionOption();
    echo $divisionRes;
    // $sql = "SELECT * FROM division_tb";

    // $stmt = $pdo->prepare($sql);

    // $stmt->execute();

    // $str = "";
    // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    //     $str .= "<option value='{$row['id']}'>{$row['name']}</option>";
    // }
} else if ($_POST['type'] == "districtData") {
    $divisionId = $_POST['id'];
    $divisionRes  = $Obj->loadDistrictOptions($divisionId);
    echo $divisionRes;

    // $sql = "SELECT * FROM district_tb WHERE division_id = {$_POST['id']}";

    // $stmt = $pdo->prepare($sql);

    // $stmt->execute();

    // $str = "";
    // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    //     $str .= "<option value='{$row['id']}'>{$row['name']}</option>";
    // }
} else if ($_POST['type'] == "upazilaData") {
    $districtId = $_POST['id'];
    $upazilaRes  = $Obj->loadUpazilaOptions($districtId);
    echo $upazilaRes;
    // $sql = "SELECT * FROM upazila_tb WHERE district_id = {$_POST['id']}";

    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();

    // $str = "";
    // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    //     $str .= "<option value='{$row['id']}'>{$row['name']}</option>";
    // }
}
echo $str;
