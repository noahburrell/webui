<?php
session_start();
session_unset();
include_once('conn.php');
if(isset($_COOKIE['remember'])){
    //Delete token from DB
    $conn = new mysqli($mysql["host"], $mysql["user"], $mysql['password'], $mysql['db'], $mysql['port']);
    // [0]=selector, [1] = raw validator
    $selectorValidator = explode(":", $_COOKIE['remember']);
    $selector = mysqli_real_escape_string($conn, $selectorValidator[0]);
    $persistentTable = $mysql['persistentLogin'];

    $query = "DELETE FROM $persistentTable WHERE selector='$selector';";
    $conn->query($query);

    //Unset cookie
    unset($_COOKIE['remember']);
    setcookie('remember', null, -1, "/");
}
header('Location: index.php');
exit();