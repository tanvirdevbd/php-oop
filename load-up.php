<?php
session_start();
// include 'connect.php';
include 'database.php';
$userObj = new Database();

$sessionUser = $_SESSION['user_type'];
$sessionId = $_SESSION['id'];
$gallery_imagesArr = [];

if ($_POST['type'] == "edit") {
    $result = $userObj->fetchOneRecordById($_POST['id']);
    // $sql = "SELECT * FROM registration WHERE id={$_POST['id']}";
    // $stmt = $pdo->prepare($sql);
    // $stmt->execute();
    // $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $gallery_imagesArr = explode(',', $result['gallery_images']);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <!-- update form   -->
    <div class='form-section'>
        <form id='edit-form' method='post' enctype='multipart/form-data'>
            <!-- left side   -->
            <div class='left'>
                <!-- firstname  -->
                <div class='mb-2 me-2'>
                    <input type='text' class='form-control' id='firstname' name='firstname' placeholder='Enter First Name' value='<?php echo $result['firstname'] ?>'>

                    <!-- hidden input for get id -->
                    <input hidden type='text' class='form-control' id='user_own_id' name='user_own_id' value='<?php echo $_POST['id'] ?>'>
                </div>

                <!-- middlename  -->
                <div class='mb-2 me-2'>
                    <input type='text' class='form-control' id='middlename' name='middlename' placeholder='Enter Middle Name' value='<?php echo $result['middlename'] ?>'>
                </div>

                <!-- lastname  -->
                <div class=' mb-2 me-2'>
                    <input type='text' class='form-control' id='lastname' name='lastname' placeholder='Enter Middle Name' value='<?php echo $result['lastname'] ?>'>
                </div>

                <!-- email -->
                <div class='mb-2 me-2'>
                    <input type='email' class='form-control' id='email' name='email' disabled placeholder='Enter Email' value='<?php echo $result['email'] ?>'>
                </div>

                <!-- password -->
                <div class='mb-2 me-2'>
                    <input type='password' class='form-control' id='password' name='password' placeholder='Enter Password' required value='<?php echo $result['password'] ?>'>
                </div>

                <!-- re type password -->
                <div class='mb-2 me-2'>
                    <input type='password' class='form-control' id='retypepassword' name='retypepassword' required placeholder='Re Enter Password' value='<?php echo $result['retypepassword'] ?>'>
                </div>

                <!-- phone  -->
                <div class='mb-2 me-2'>
                    <input type='text' class='form-control' id='phone' name='phone' placeholder='Enter Phone Number' value='<?php echo $result['phone'] ?>'>
                </div>

                <!-- image  -->
                <div class='mb-2 me-2'>
                    <label for='image' class='form-label name'>Profile Picture: </label>
                    <input type='file' name='uploadfile' id=''>
                    <img class='ms-0' src='<?php echo $result['std_img'] ?>' alt='profile_image' width='40' height='40' style='border-radius: 50%;'>
                </div>

                <!-- gallery images  -->
                <div class='mb-2 me-2'>
                    <label for='gallery-images' class='form-label name'>Gallery Images: </label>
                    <input type='file' name='files[]' multiple>
                    <div class='d-flex'>
                        <?php if (count($gallery_imagesArr)) {
                            foreach ($gallery_imagesArr as $x => $singleImage) {
                        ?>
                                <img src='photo_gallery/<?php echo $singleImage ?>' alt='gallery_images' width='40' height='40' class='single-image'>
                        <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class='right'>
                <!-- gender  -->
                <div class='mb-2'>
                    <label for='gender' class='form-label me-3 name'>Gender: </label>
                    <input type='radio' id='male' name='gender' value='MALE' <?php echo $result['gender'] == 'MALE' ?  "checked" : "" ?>>
                    <label for='html' class='mx-1'>Male</label>
                    <input type='radio' id='female' name='gender' value='FEMALE' <?php echo $result['gender'] == 'FEMALE' ? "checked" : "" ?>>
                    <label for='html' class='mx-1'>Female</label>
                    <input type='radio' id='others' name='gender' value='OTHERS' <?php echo $result['gender'] == 'OTHERS' ? "checked" : "" ?>>
                    <label for='html' class='mx-1'>Others</label>
                </div>

                <!-- user  -->
                <?php if ($sessionUser) {
                ?>
                    <div class='mb-2'>
                        <label for='user_type' class='form-label me-4 name'>User: </label>
                        <select name='user_type' id='user_type' class='select-area'>
                            <option value='1' <?php echo $result['user_type'] == 1 ? "selected" : "" ?>>Admin
                            </option>
                            <option value='0' <?php echo $result['user_type'] == 0 ? "selected" : ""  ?>>Student
                            </option>
                        </select>
                    </div>
                <?php
                } ?>

                <!-- class  -->
                <div class='mb-2'>
                    <label for='class' class='form-label me-4 name'>Class: </label>
                    <select name='class' id='class' class='select-area'>
                        <?php
                        // TODO: no pdo here so call by oop
                        $sql = "SELECT * FROM class_tb";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute();

                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                            <option value='<?php echo $row['id'] ?>' <?php echo $result['class'] == $row['id'] ? "selected" : "" ?>>
                                <?php echo $row['name'] ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>

                <!-- division  -->
                <div class='mb-2'>
                    <label for='division' class='form-label me-2 name'>Division: </label>
                    <select name='division' id='division' class='select-area'>
                        <option value=''>Select Division</option>
                        <?php
                        $sql1 = "SELECT * FROM division_tb";
                        $stmt1 = $pdo->prepare($sql1);
                        $stmt1->execute();
                        while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                            <option value='<?php echo $row['id'] ?>' <?php echo $result['division'] == $row['id'] ? "selected" : "" ?>>
                                <?php echo $row['name'] ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>

                <!-- district  -->
                <div class='mb-2'>
                    <label for='district' class='form-label me-3  name'>District: </label>
                    <select name='district' id='district' class='select-area'>
                        <option value=''>Select District</option>
                        <?php
                        $sql2 = "SELECT * FROM district_tb";
                        $stmt2 = $pdo->prepare($sql2);
                        $stmt2->execute();
                        while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                            <option value='<?php echo $row['id'] ?>' <?php echo $result['district'] == $row['id'] ? "selected" : "" ?>>
                                <?php echo $row['name'] ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>

                <!-- upazila  -->
                <div class='mb-2'>
                    <label for='upazila' class='form-label me-3 name'>Upazila: </label>
                    <select name='upazila' id='upazila' class='select-area'>
                        <option value=''>Select Upazila</option>
                        <?php
                        $sql3 = "SELECT * FROM upazila_tb";
                        $stmt3 = $pdo->prepare($sql3);
                        $stmt3->execute();
                        while ($row = $stmt3->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                            <option value='<?php echo $row['id'] ?>' <?php echo $result['upazila'] == $row['id'] ? "selected" : "" ?>>
                                <?php echo $row['name'] ?>
                            </option>
                        <?php
                        }
                        ?>
                    </select>
                </div>

                <!-- address  -->
                <div class='mb-2'>
                    <textarea class='form-control me-2' id='address' name='address' rows='4' cols='50'><?php echo $result['address'] ?></textarea>
                </div>
                <!-- edit button  -->
                <button type='submit' class='btn btn-primary w-100'>Update </button>
                <div class='mt-2' style='width: 40%;'>
                    <p id='error-modal-edit' style='background-color: red;
        color: white;'></p>
                    <p id='success-modal-edit' style='background-color: green;
        color: white;'></p>
                </div>
            </div>
        </form>
    </div>



    <script src=" https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    <script type="text/javascript" src="jquery.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            function loadData(type = "", category_id = "") {
                $.ajax({
                    url: 'load-rs.php',
                    type: 'POST',
                    data: {
                        type: type,
                        id: category_id
                    },
                    success: function(data) {
                        $("#registered_students").html(data);
                    }
                });
            }

            function loadLocationData(type = "", category_id = "") {
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
                        }
                    }
                });
            }
            loadLocationData();

            $("#division").on("change", function() {
                var division = $("#division").val();
                if (division != "") {
                    loadLocationData("districtData", division);
                } else {
                    $("#district").html("");
                }
            })

            $("#district").on("change", function() {
                var district = $("#district").val();
                if (district != "") {
                    loadLocationData("upazilaData", district);
                } else {
                    $("#upazila").html("");
                }
            })

            $("form#edit-form").on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: 'ajax-update.php',
                    type: 'POST',
                    data: formData,
                    success: function(data) {
                        console.log(data)
                        let message = JSON.parse(data);
                        if (message.successMessage) {
                            $("#success-modal-edit").html(message.successMessage).show();
                            setTimeout(function() {
                                $("#success-modal-edit").hide();
                            }, 3000);
                            $("#error-modal-edit").hide();
                            // TODO: modal hide not working after add user
                            $("#editUserModal").modal("hide");
                            loadData();
                        } else {
                            $("#error-modal-edit").html(message.errorMessage).show().slideDown();
                            setTimeout(function() {
                                $("#error-modal-edit").hide().slideUp();
                            }, 3000);
                            $("#success-modal-edit").hide().slideUp();
                        }
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                });
            })
        })
    </script>
</body>

</html>