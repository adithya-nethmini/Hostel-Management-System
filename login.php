<!-- Starting a session -->
<?php
    session_start();
?>

<!-- Connecting database -->
<?php include('./dbconnection.php') ?>

<!-- Handleing post data -->
<?php
    function loginUser($RollNo, $password){

        $conn = mysqli_connect();
        $RollNo = trim($RollNo);
        $password = trim($password);

        if($RollNo == "" || $password == ""){
            return  "All fields are required";
        }

        $RollNo = filter_var($RollNo, FILTER_SANITIZE_STRING);
        $password = filter_var($password, FILTER_SANITIZE_STRING);

        $loginquery = "select * from studentdata where RollNo = ? ";

        $statement = $conn->prepare($loginquery);
        $statement->bind_param("s", $RollNo);
        $statement->execute();
        $result = $statement->get_result();
        $data = $result->fetch_assoc();

        if($data == NULL){
            return "Wrong username or password";
        }
        if(password_verify($password, $data['password']) == FALSE){
            echo "<br>UserName/Password incorrect.<br>If not registered, plz register <a href='index.php'>here</a>";
            header("Location: "."http://localhost/HostelManagementSystem/user.php");
            die();
        }
    }

    if(!isset($_POST["token"]) || !isset($_SESSION["token"])){
exit("Token not exit");
    }

    if($_POST["token"] == $_SESSION["token"]){
        if(time() >= $_SESSION["token-expire"]){
            exit ("Token expired. Reload the form");
        }
        return "ok";
        unset($_SESSION["token"]);
        unset($_SESSION["token-expire"]);
    }
    else{
        exit ("Invalid token");
    }
?>