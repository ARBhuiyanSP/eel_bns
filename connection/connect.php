<?php
/*
 * Mysql Database connection string:
 */
global $conn;
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "eel_bns";

// Create connection
$conn       = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
