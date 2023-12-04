<?php
include 'connect.php';
$sql = "SELECT * FROM registration";

$stmt = $pdo->prepare($sql);

$stmt->execute();
$str = "";
$rowNum = 0;
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $rowNum++;
    $trSingle = "
    <tr>
        <th scope='row'>{$rowNum}</th>
        <td>{$row['firstname']}</td>
        <td>{$row['middlename']}</td>
        <td>{$row['lastname']}</td>
        <td>{$row['phone']}</td>
        <td>{$row['class']}</td>
        <td>{$row['gender']}</td>
        <td>{$row['division']}</td>
        <td>{$row['district']}</td>
        <td>{$row['upazila']}</td>
        <td>{$row['address']}</td>
        <td>{$row['email']}</td>
        <td>{$row['password']}</td>
        <td>
            <a href='update-registered-students.php'>Edit</a>
        </td>    
    </tr>
    ";
    $str .= $trSingle;
}
echo $str;
