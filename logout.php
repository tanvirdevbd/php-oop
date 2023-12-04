<?php
session_start();
unset($_SESSION['id']);
unset($_SESSION['user_type']);
header("Location: login.php");
die();
