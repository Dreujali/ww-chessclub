<?php
//when running on your own server setup database here
$dbServername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "chessclub";

$conn = mysqli_connect($dbServername, $dbUsername, $dbPassword, $dbName);

//function that get one value from sql database
function sqlgetvalue($sqlquery, $coulumn)
{
    global $conn;
    $result = mysqli_query($conn, $sqlquery);
    $result = mysqli_fetch_assoc($result);
    return $result["$coulumn"];

}
//function that get one array from sql database
function sqlgetarray($sqlquery)
{
    global $conn;
    $result = mysqli_query($conn, $sqlquery);
    $result = mysqli_fetch_array($result);
    return $result;
}

