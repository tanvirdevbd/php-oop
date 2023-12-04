<?php
class Database
{
    private $username = 'root';
    private $password = '';
    public $conn;

    //database connection
    public function __construct()
    {
        try {
            $this->conn = new PDO('mysql:host=localhost;dbname=studentforms', $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection Failed: " . $e->getMessage();
        }
    }

    //Insert Student registration data into registration table
    public function insertData($user_type, $std_img, $firstname, $middlename, $lastname, $phone, $email, $password, $retypepassword, $class, $gender, $division, $district, $upazila, $address)
    {
        $sql = "INSERT INTO `registration`(user_type, std_img, firstname, middlename, lastname, phone, email, password, retypepassword, class, gender, division, district, upazila, address) VALUES(:user_type, :std_img, :firstname, :middlename, :lastname, :phone, :email, :password, :retypepassword, :class, :gender, :division, :district, :upazila,  :address)";

        $stmt = $this->conn->prepare($sql);

        $result = $stmt->execute(['user_type' => $user_type, 'std_img' => $std_img, 'firstname' => $firstname, 'middlename' => $middlename, 'lastname' => $lastname, 'phone' => $phone,  'email' => $email,  'password' => $password,  'retypepassword' => $retypepassword, 'class' => $class, 'gender' => $gender, 'division' => $division, 'district' => $district, 'upazila' => $upazila, 'address' => $address]);

        if ($result) {
            return 1;
        } else {
            return 0;
        }
    }

    //Data one record read Function
    public function fetchAllRecord()
    {
        $sql = "SELECT * from `registration`";
        $stmt = $this->conn->prepare($sql);
        return $stmt;
    }
    public function fetchOneRecordByEmail($email)
    {
        $sql = "SELECT * from `registration` WHERE email=:email";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['email' => $email]);
        $oneEmailResult = $stmt->fetch(PDO::FETCH_ASSOC);
        return $oneEmailResult;
    }
    public function fetchOneRecordById($id)
    {
        $sql = "SELECT * from `registration` WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['id' => $id]);
        $oneIdResult = $stmt->fetch(PDO::FETCH_ASSOC);
        return $oneIdResult;
    }

    public function recordEmailPass($email, $password)
    {
        $sql = "SELECT * FROM `registration` WHERE email = :email AND password = :password";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['email' => $email, 'password' => $password]);
        $recordEamilPass = $stmt->fetch(PDO::FETCH_ASSOC);
        return $recordEamilPass;
    }

    public function nameOrPhoneSearch($searchedTerm)
    {
        $sql = "SELECT * FROM `registration` WHERE firstname LIKE '%$searchedTerm%' OR phone LIKE '%$searchedTerm%'";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $searchRes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $searchRes;
    }

    public function loadClassData()
    {
        $sql = "SELECT * FROM class_tb";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $str = "";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $str .= "<option value='{$row['id']}'>{$row['name']}</option>";
        }
        return $str;
    }
    public function loadDivisionOption()
    {
        $sql = "SELECT * FROM division_tb";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $str = "";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $str .= "<option value='{$row['id']}'>{$row['name']}</option>";
        }
        return $str;
    }

    public function loadDivisionOptionById($userDivision)
    {
        $sql1 = "SELECT name from division_tb where id=:id";
        $stmt1 = $this->conn->prepare($sql1);
        $stmt1->execute(['id' => $userDivision]);
        $divisionRes = $stmt1->fetch(PDO::FETCH_ASSOC);
        return $divisionRes;
    }

    public function loadDistrictOptions($divisionId)
    {
        $sql = "SELECT * FROM district_tb WHERE division_id = {$divisionId}";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $str = "";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $str .= "<option value='{$row['id']}'>{$row['name']}</option>";
        }
        return $str;
    }

    public function loadDistrictDataById($userDistrict)
    {
        $sql2 = "SELECT name from district_tb where id=:id";
        $stmt2 = $this->conn->prepare($sql2);
        $stmt2->execute(['id' => $userDistrict]);
        $districtRes = $stmt2->fetch(PDO::FETCH_ASSOC);
        return $districtRes;
    }

    public function loadUpazilaOptions($districtId)
    {
        $sql = "SELECT * FROM upazila_tb WHERE district_id = {$districtId}";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        $str = "";
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $str .= "<option value='{$row['id']}'>{$row['name']}</option>";
        }
        return $str;
    }

    public function loadUpazilaDataById($userUpazila)
    {
        $sql3 = "SELECT name from upazila_tb where id=:id";
        $stmt3 = $this->conn->prepare($sql3);
        $stmt3->execute(['id' => $userUpazila]);
        $upazilaRes = $stmt3->fetch(PDO::FETCH_ASSOC);
        return $upazilaRes;
    }
}


// var_dump($gallery_images);
// echo "<pre>";
// print_r($gallery_images);
// echo "</pre>";
// die;