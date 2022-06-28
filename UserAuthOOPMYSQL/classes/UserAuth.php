<?php
include_once 'Dbh.php';
session_start();

class UserAuth extends Dbh{
    protected $db;

    public function __construct(){
        $this->db = new Dbh();
    }

    public function register($fullname, $email, $password, $confirmPassword, $country, $gender){
        $conn = $this->db->connect();
        if($this->validatePassword($password, $confirmPassword)){
            $sql = "INSERT INTO Students (`full_names`, `email`, `password`, `country`, `gender`) VALUES ('$fullname','$email', '$password', '$country', '$gender')";
            if($conn->query($sql)){
               echo "Ok";
               $loginlink = 'forms/login.php';
               echo "<br/><br/><a href=".$loginlink.">Back to Login</a>";
            } else {
                echo "Oops". $conn->error;
                $loginlink = 'forms/login.php';
                echo "<br/><br/><a href=".$loginlink.">Back to Login</a>";
            }
        } else {
            echo "Passwords do not match!";
            $loginlink = 'forms/login.php';
            echo "<br/><br/><a href=".$loginlink.">Back to Login</a>";
        }
    }

    public function login($email, $password){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM Students WHERE email='$email' AND `password`='$password'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            $_SESSION['email'] = $email;
            header("Location: dashboard.php");
        } else {
            header("Location: forms/login.php");
        }
    }

    public function getUser($email){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM students WHERE email = '$email'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    public function getAllUsers(){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM Students";
        $result = $conn->query($sql);
        echo"<html>
        <head>
        <link rel='stylesheet' href='https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css' integrity='sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T' crossorigin='anonymous'>
        </head>
        <body>
        <center><h1><u> ZURI PHP STUDENTS </u> </h1>
        <table class='table table-bordered' border='0.5' style='width: 80%; background-color: smoke; border-style: none'; >
        <tr style='height: 20px'>
            <thead class='thead-dark'> <th>ID</th><th>Full Names</th> <th>Email</th> <th>Gender</th> <th>Country</th> <th>Action</th>
        </thead></tr>";
        if($result->num_rows > 0){
            while($data = mysqli_fetch_assoc($result)){
                //show data
                echo "<tr style='height: 20px'>".
                    "<td style='width: 50px; background: grey; text-align: center;'>" . $data['id'] . "</td>
                    <td style='width: 150px; text-align: center;'>" . $data['full_names'] .
                    "</td> <td style='width: 150px; text-align: center;'>" . $data['email'] .
                    "</td> <td style='width: 150px; text-align: center;'>" . $data['gender'] .
                    "</td> <td style='width: 150px; text-align: center;'>" . $data['country'] .
                    "</td>
                    <td style='width: 150px; text-align: center;'>
                    <form action='action.php' method='post'>
                    <input type='hidden' name='id'" .
                     "value=" . $data['id'] . ">".
                    "<button class='btn btn-danger' type='submit', name='delete'> DELETE </button> </form> </td>".
                    "</tr>";
            }
            echo "</table><br /><a href='dashboard.php'>Back</a></center></body></html>";
        }
    }

    public function deleteUser($id){
        $conn = $this->db->connect();
        $sql = "DELETE FROM Students WHERE id = '$id'";
        if($conn->query($sql) === TRUE){
            echo "User deleted Successfully!";
            echo "<br /><a href='dashboard.php'>Back</a>";
        } else {
          echo "Error deleting User!";
          echo "<br /><a href='dashboard.php'>Back</a>";
        }
    }

    public function updateUser($email, $password){
        $emailCheck =  $this->checkEmailExist($email);
        if($emailCheck === true){
            $conn = $this->db->connect();
            $sql = "UPDATE students SET password = '$password' WHERE email = '$email'";
            if($conn->query($sql) === TRUE){
                echo "Password Reset Successful!";
                $loginlink = 'forms/login.php';
                echo "<br/><br/><a href=".$loginlink.">Back to Login</a>";
            } else {
                header("Location: forms/resetpassword.php?error=1");
            }
        } else {
            echo "User does not exist. Check email and password!";
            $loginlink = 'forms/login.php';
            echo "<br/><br/><a href=".$loginlink.">Back to Login</a>";
        }
    }

    public function getUserByEmail($email){
        $conn = $this->db->connect();
        $sql = "SELECT * FROM students WHERE email = '$email'";
        $result = $conn->query($sql);
        if($result->num_rows > 0){
            return $result->fetch_assoc();
        } else {
            return false;
        }
    }

    public function logout($email){
        session_start();
        unset($email);
        session_destroy();
        header('Location:index.php');
    }

    public function validatePassword($password, $confirmPassword){
        if($password === $confirmPassword){
            return true;
        } else {
            return false;
        }
    }

    public function checkEmailExist($email){
      $conn = $this->db->connect();
      $sql = "SELECT * FROM Students WHERE email = '$email'";
      $result = $conn->query($sql);
        if($result->num_rows > 0){
            return true;
        } else {
            return false;
        }
    }
}
