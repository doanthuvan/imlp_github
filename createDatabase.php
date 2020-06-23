<?php 
include_once('env.php');
$conn = mysqli_connect(ServerName, UserName, Password);
$sql = "CREATE DATABASE " . DB .";";
if(mysqli_query($conn, $sql)){  
    echo "Database created successfully";  
} else {  
    echo "Database is not created successfully ";  
}
$conn = mysqli_connect(ServerName, UserName, Password, DB);

$sql = "CREATE TABLE forks (
    idUser int(11) NOT NULL,
    idRepo int(11) NOT NULL,
    forkUrl text NOT NULL,
    PRIMARY KEY (idUser,idRepo)
  )";
if(mysqli_query($conn, $sql)){  
    echo "Table forks created successfully";  
} else {  
    echo "Table forks is not created successfully ";  
}  

$sql = "CREATE TABLE repos (
    id int(11) NOT NULL,
    name text NOT NULL,
    clone_url text NOT NULL,
    owner varchar(100) NOT NULL,
    PRIMARY KEY (id)
  )";
if(mysqli_query($conn, $sql)){  
    echo "Table repos created successfully";  
} else {  
    echo "Table repos is not created successfully ";  
}  


?>