<?php
//error_reporting(0);
session_start();
include_once('libs/userAccount.php');
$data = new userAccount("127.0.0.1", "3306", "root", "qDt3j5qJsg", "loginInfo", "loginInfo");

// initializing variables
$username = "";
$errors = array();

// connect to the database
$db = mysqli_connect('127.0.0.1', 'root', 'qDt3j5qJsg', 'loginInfo', "3306");

if(!$db) {
    header('location:userProfile.html');
}
if(!$data) {
    header('location:userProfile.html');
}
// REGISTER USER
if (isset($_POST['reg_user'])) {

    $username = $_POST['username'];
    $password_1 = $_POST['password_1'];
    $password_2 = $_POST['password_2'];
    $firstName = $_POST['fname'];
    $lastName = $_POST['lname'];

    if (empty($username)) { array_push($errors, "Username is required"); }
    if (empty($password_1)) { array_push($errors, "Password is required"); }
    if ($password_1 != $password_2) {
        array_push($errors, "The two passwords do not match");
    }
    //Check if username already in database
    $user_check_query = "SELECT * FROM loginInfo WHERE username='$username' LIMIT 1";
    $result = mysqli_query($db, $user_check_query);
    $user = mysqli_fetch_assoc($result);

    if ($user) { // if user exists
        if ($user['username'] === $username) {
            array_push($errors, "Username already exists");
        }
    }
    echo count($errors);
    // Finally, register user if there are no errors in the form
    if (count($errors) == 0) {
        /*
        $password_1 = array();
        $password_1 =  $data->register($username, $password_2, $firstName, $lastName);

        echo  $password_1[1]."<br>";
        echo  $password_1[0];
        $query = "INSERT INTO loginInfo (username, hash, salt, fname, lname)
  			      VALUES('$username', '$password_1[0]', '$password_1[1]', '$firstName', '$lastName');";
        if(!mysqli_query($db, $query))
	        echo $query;
        */
        $data->register($username, $password_2, $firstName, $lastName);
        $_SESSION['username'] = $username;
        $_SESSION['success'] = "You are now logged in";
        //header('location:userProfile.html');
    }
}



if (isset($_POST['login_user'])) {
    $username = mysqli_real_escape_string($db, $_POST['user']);
    $password = mysqli_real_escape_string($db, $_POST['pass']);
    if (empty($username)) {
        array_push($errors, "Username is required");
    }
    if (empty($password)) {
        array_push($errors, "Password is required");
    }

    if (count($errors) == 0) {
        $checker = $data->login($username, $password);


        if ($checker != null) {
            $_SESSION["name"] = $checker["fname"]." ".$checker["lname"];
            $_SESSION['username'] = $username;
            $_SESSION['success'] = "You are now logged in";
           header('location: networks.php');
       //     echo $_SESSION["name"];
         //   die();
        }else {
            array_push($errors, "Wrong username/password combination");
        }
    }
}