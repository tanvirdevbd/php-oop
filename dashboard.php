<?php
session_start();

if (!$_SESSION["id"]) {
    header("Location: login.php");
}

include 'database.php';
$userObj = new Database();

$updateAfterSearchInfo = 0;
$updateAfterSearchValue = '';
if (isset($_GET['search'])) {
    $updateAfterSearchInfo = 1;
    $updateAfterSearchValue = $_GET['search'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <link rel="stylesheet" href="dashboard.css">
</head>

<body>
    <?php
    include 'menu.php';

    if ($_SESSION['user_type'] == 1 || $_SESSION['user_type'] == 0) {
    ?>
        <div style="width: 40%;">
            <p id='error-modal' style="background-color: red;
  color: white;"></p>
            <p id='success-modal' style="background-color: green;
  color: white;"></p>
        </div>
        <div class="d-flex justify-content-end">
            <!-- edit Modal start-->
            <div id="editUserModal" class="modal modal-tall fade modal-xl" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editUserModalLabel">Update User Info</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="modal-body-edit">
                        </div>
                    </div>
                </div>
            </div>
            <!-- edit Modal end-->
        <?php
    }
    if ($_SESSION['user_type'] == 1) {
        ?>
            <!-- add Modal start-->
            <button type="button" class="btn btn-primary mt-1 me-2 add-btn" data-bs-toggle="modal" data-bs-target="#addUserModal">
                Add New User
            </button>

            <div class="modal modal-tall fade modal-xl" id="addUserModal" tabindex="-1" aria-labelledby="addUserModalLabel" aria-hidden="true">
                <div class="modal-dialog  modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addUserModalLabel">User Info</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" id="modal-body-add-user">

                        </div>
                    </div>
                </div>
            </div>

            <!-- add Modal end-->
            <input type="text" id="search-info" name="search-info" class="my-2 p-2 me-3 search-dashboard" placeholder="Type name or phone to Search">
        </div>
    <?php
    }
    ?>
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col" style="width: 6%;">#</th>
                    <th scope="col" style="width: 6%;">Image</th>
                    <th scope="col" style="width: 6%;">Gallery images</th>
                    <th scope="col" style="width: 6%;">First name</th>
                    <th scope="col" style="width: 6%;">Middle name</th>
                    <th scope="col" style="width: 6%;">Last Name</th>
                    <th scope="col" style="width: 6%;">Phone</th>
                    <th scope="col" style="width: 6%;">Class</th>
                    <th scope="col" style="width: 6%;">Gender</th>
                    <th scope="col" style="width: 6%;">Division</th>
                    <th scope="col" style="width: 6%;">District</th>
                    <th scope="col" style="width: 6%;">Upazila</th>
                    <th scope="col" style="width: 6%;">Address</th>
                    <th scope="col" style="width: 6%;">Email</th>
                    <th scope="col" style="width: 6%;">Password</th>
                    <?php
                    $sessionId = $_SESSION['id'];
                    $userRes = $userObj->fetchOneRecordById($_SESSION['id']);

                    // $sql1 = "SELECT * FROM registration WHERE id=:id";
                    // $stmt1 = $pdo->prepare($sql1);
                    // $stmt1->execute(['id' => $sessionId]);
                    // $userRes = $stmt1->fetch(PDO::FETCH_ASSOC);


                    if ($userRes['user_type'] == 1) {
                        echo '<th scope="col">User Type</th>';
                    }
                    ?>
                    <th scope="col" style="width: 6%;">Action</th>
                </tr>
            </thead>
            <tbody id='registered_students'>
            </tbody>
        </table>
    </div>

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
                        $("#registered_students").html(data);
                    }
                });
            }
            loadData();

            $("#search-info").on('keyup', function() {
                var searchInfo = $("#search-info").val();
                if (searchInfo.length) {
                    loadData("search", searchInfo);
                } else {
                    loadData();
                }
            })

            $(document).on('click', '.add-btn', function() {
                $.ajax({
                    url: 'load-insert.php',
                    type: 'POST',
                    success: function(data) {
                        $("#modal-body-add-user").html(data);
                    }
                });
            })

            $(document).on('click', '.edit-btn', function() {
                var userId = $(this).data("eid");
                $.ajax({
                    url: 'load-up.php',
                    type: 'POST',
                    data: {
                        type: "edit",
                        id: userId
                    },
                    success: function(data) {
                        $("#editUserModal").modal("show");
                        $("#modal-body-edit").html(data);
                    }
                });
            })

            $(document).on('click', '.delete-btn', function() {
                let deleteResponse = confirm(`Do you really want to delete?`);
                if (deleteResponse) {
                    let userId = $(this).data("did");
                    $.ajax({
                        url: 'delete.php',
                        type: 'POST',
                        data: {
                            id: userId
                        },
                        success: function(data) {
                            if (data) {
                                $("#error-modal").hide().slideUp();
                                $("#success-modal").html("Successfully deleted User").show().slideDown();
                                setTimeout(function() {
                                    $("#success-modal").hide().slideUp();
                                }, 3000)
                                loadData();
                            } else {
                                $("#error-modal").html("Delete failed").show().slideDown();
                                $("#success-modal").hide().slideUp();
                                setTimeout(function() {
                                    $("#error-modal").hide().slideUp();
                                }, 3000)
                            }
                        }
                    })
                }
            })
        })
    </script>
</body>

</html>