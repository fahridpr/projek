<?php
// connection details
$servername = "localhost";
$username = "root";
$password = "";
$database = "zresepsionis";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $database);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}   
?>