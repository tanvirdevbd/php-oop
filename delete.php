<?php
include 'connect.php';

$id = $_POST['id'];

$sql1 = "SELECT * FROM registration WHERE id=$id";
$stmt1 = $pdo->prepare($sql1);
$stmt1->execute();
$res1 = $stmt1->fetch(PDO::FETCH_ASSOC);

if ($res1['std_img'] !== "") {
    unlink($res1['std_img']);
}

if ($res1['gallery_images'] !== "") {
    $allGlryImages = explode(',', $res1['gallery_images']);
    foreach ($allGlryImages as $allGlryImage) {
        unlink("photo_gallery/" . $allGlryImage);
    }
}

$sql = "DELETE FROM registration WHERE id=$id";
$stmt = $pdo->prepare($sql);
$data = $stmt->execute();
if ($data) {
    echo 1;
} else {
    echo 0;
}
