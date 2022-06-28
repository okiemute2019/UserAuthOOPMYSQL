<?php

  class Dbh
  {
    public $hostname = "127.0.0.1";
    public $username = "root";
    public $password = "";
    public $dbname = "zuriphp";

    protected function connect()
    {
      $conn = mysqli_connect($this->hostname, $this->username, $this->password, $this->dbname);
      if(!$conn){
          echo "<script> alert('Error connecting to the database') </script>";
      }
      return $conn;
    }

  }


?>
