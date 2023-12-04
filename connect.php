<?php
$db_username = 'root';
$db_password = '';

try {
  $pdo = new PDO('mysql:host=localhost;dbname=studentforms', $db_username, $db_password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  echo "Connection failed: " . $e->getMessage();
}

// var_dump($gallery_images);
// echo "<pre>";
// print_r($gallery_images);
// echo "</pre>";
// die;