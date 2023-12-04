<?php
include 'database.php';
$userObj = new Database();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>

    <form id="insert-form" method="post" enctype="multipart/form-data">
        <div class='left'>
            <!-- firstname  -->
            <div class="mb-2 me-2">
                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="Enter First Name" required>
            </div>
            <!-- middlename  -->
            <div class="mb-2 me-2">
                <input type="text" class="form-control" id="middlename" name="middlename" placeholder="Enter Middle Name">
            </div>
            <!-- lastname  -->
            <div class="mb-2 me-2">
                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter Last Name" required>
            </div>
            <!-- email -->
            <div class="mb-2  me-2">
                <input type="email" class="form-control" id="email" name="email" required placeholder="Enter Email" required>
            </div>
            <!-- password -->
            <div class="mb-2  me-2">
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password" required>
            </div>
            <!-- re type password -->
            <div class="mb-2 me-2">
                <input type="password" class="form-control" id="retypepassword" name="retypepassword" placeholder="Re Enter Password" required>
            </div>
            <!-- phone  -->
            <div class="mb-2 me-2">
                <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Phone Number" required>
            </div>
            <!-- image  -->
            <div class="mb-4 me-2">
                <label for="image" class="form-label name">Profile Picture: </label>
                <input type="file" name="uploadfile" id="" class="ms-0">
            </div>
        </div>
        <div class='right'>
            <!-- gender  -->
            <div class="mb-2">
                <label for="gender" class="form-label me-3 name">Gender: </label>
                <input type="radio" id="male" name="gender" class="gender" value="MALE" required>
                <label for="html" class='mx-1'>Male</label>
                <input type="radio" id="female" name="gender" value="FEMALE" class="gender" required>
                <label for="html" class='mx-1'>Female</label>
                <input type="radio" id="others" name="gender" value="OTHERS" class="gender" required>
                <label for="html" class='mx-1'>Others</label>
            </div>
            <!-- user type  -->
            <div class='mb-2'>
                <label for='user_type' class='form-label me-4 name'>User: </label>
                <select name='user_type' id='user_type' class='select-area' required>
                    <option value="">Select User Type</option>
                    <option value='0'>Student </option>
                    <option value='1'>Admin </option>
                </select>
            </div>
            <!-- class  -->
            <div class="mb-2">
                <label for="class" class="form-label me-4 name">Class: </label>
                <select name="class" id="class" class="select-area" required>
                    <option value="">Select Class</option>
                    <?php
                    $classRes = $userObj->loadClassData();
                    echo $classRes;
                    ?>
                </select>
            </div>
            <!-- division  -->
            <div class="mb-2">
                <label for="division" class="form-label me-2 name">Division: </label>
                <select name="division" id="division" class="select-area">
                    <option value="">Select division</option>
                    <?php
                    $divisionRes = $userObj->loadDivisionOption();
                    echo $divisionRes;
                    ?>
                </select>
            </div>
            <!-- district  -->
            <div class="mb-2">
                <label for="district" class="form-label me-3  name">District: </label>
                <select name="district" id="district" class="select-area">
                    <option value=""></option>
                </select>
            </div>
            <!-- upazila  -->
            <div class="mb-2">
                <label for="upazila" class="form-label me-3 name">Upazila: </label>
                <select name="upazila" id="upazila" class="select-area">
                    <option value=""></option>
                </select>
            </div>
            <!-- address  -->
            <div class="mb-2">
                <textarea class="form-control me-2" id="address" name="address" rows="4" cols="50" placeholder="Enter Address"></textarea>
            </div>
            <!-- register button  -->
            <button type="submit" id="save-button" class="btn btn-primary">Add User</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

            <div class="mt-3" style="width: 100%;">
                <p id='user-add-error-modal' style="background-color: red;
  color: white;"></p>
                <p id='user-add-success-modal' style="background-color: green;
  color: white;"></p>
            </div>

        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
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
                        if (data) {
                            $("#registered_students").html(data);
                        }
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

            $("form#insert-form").on('submit', function(e) {
                e.preventDefault();
                var formData = new FormData(this);
                $.ajax({
                    url: 'ajax-insert.php',
                    type: 'POST',
                    data: formData,
                    success: function(data) {
                        let toastMessage = JSON.parse(data);
                        if (toastMessage.successMessage) {
                            $("#user-add-error-modal").hide().slideUp();
                            $("#user-add-success-modal").html(toastMessage.successMessage).show().slideDown();
                            setTimeout(function() {
                                $("#user-add-success-modal").hide().slideUp();
                            }, 3000)
                            // TODO: modal hide not working after add user
                            $("#addUserModal").modal("hide");
                            loadData();
                        } else {
                            $("#user-add-error-modal").html(toastMessage.errorMessage).show().slideDown();
                            $("#user-add-success-modal").hide().slideUp();
                            setTimeout(function() {
                                $("#user-add-error-modal").hide().slideUp();
                            }, 3000)
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