<?php
include 'database.php';
$Obj = new Database();

if ($_POST['type'] == "classData") {
    $classRes  = $Obj->loadClassDataOptions();
    echo $classRes;
} else if ($_POST['type'] == "") {
    $divisionRes  = $Obj->loadDivisionOption();
    echo $divisionRes;
} else if ($_POST['type'] == "districtData") {
    $divisionId = $_POST['id'];
    $divisionRes  = $Obj->loadDistrictOptions($divisionId);
    echo $divisionRes;
} else if ($_POST['type'] == "upazilaData") {
    $districtId = $_POST['id'];
    $upazilaRes  = $Obj->loadUpazilaOptions($districtId);
    echo $upazilaRes;
}
echo $str;
