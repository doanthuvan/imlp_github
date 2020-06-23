<?php
class BaseModel
{
    public $conn;

    public function __construct()
    {
        $this->conn = mysqli_connect(ServerName, UserName, Password, DB);
        if (!$this->conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
    }
}
