<?php
include_once('libs/common.php');
include_once('libs/userAccount.php');
include_once('conn.php');

$userInfo = null;
$error = null;
$success = null;

if(!empty($_REQUEST))
    $acc = new userAccount($mysql);

//Check if the user is trying to resend their email verification
if(isset($_GET['resend']) && filter_var($_GET['resend'], FILTER_VALIDATE_EMAIL)){
    $conn = new mysqli($mysql["host"], $mysql["user"], $mysql['password'], $mysql['db'], $mysql['port']);
    $email = mysqli_real_escape_string($conn, $_GET['resend']);
    $loginTable = $mysql['loginTable'];

    $query = "SELECT username, conf_str, confirmed FROM $loginTable WHERE username = '$email' LIMIT 1;";

    $result = $conn->query($query);
    if($result->num_rows == 1){
        $result = $result->fetch_assoc();
        if($result['confirmed'] == 0){
            $acc->sendConfEmail($mailjet, $email, $result['conf_str']);
        }
    }

    $success = "If the email '".$email."' is registered and not yet confirmed, a confirmation Email has been resent.";
}

//Check if login attempt is being made. Ensure username and password are set and current login status false
if(isset($_POST['user']) && isset($_POST['pass']) && !loginCheck($mysql)){
    //Attempt to log user in
    if(!empty($userInfo = $acc->login($_POST['user'], $_POST['pass']))){
        //User authenticated successfully, set session variables and redirect
        if($userInfo['confirmed'] == 1) {
            $userInfo['loggedin'] = true;
            $userInfo['lastActivity'] = time();
            $_SESSION = $userInfo;
            if(isset($_POST['remember']))
                if(!$acc->setPersistentLogin($_SESSION['id'])){
                    die("An error occured when trying to set the login to persistent");
                }
                //setcookie("remember", true, time()+31536000, "/");
        } else
            $url = getURL('login.php?resend='.$userInfo['username'], true);
            $error = "Please confirm your Email address before logging in! Can't find the email? <u><a href=\"".$url."\" class=\"link link--secondary link--descent\">Resend it.</a></u>";
    } else {
        //User did not authenticate successfully, display error
        $error = "Authentication failure, check your Email and password!";
    }
}

//If the user is already logged in redirect to networks page.
if(loginCheck($mysql)){
    header("Location: networks.php");
    exit();
}

?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="css/login.css">
        <link rel="stylesheet" type="text/css" href="css/tds.css">
        <link rel="icon" type="image/favicon" href="/favicon.ico">
		<title>TELUS: Private Networks - Login</title>
	</head>
	<body>
		<div class="container">
            <table>
                <tr>
                    <td>
                        <div align="center">
                            <img src="images/icons/telusLogo.png" alt="Telus" />
                            <h1 class="display-heading-1">Telus</h1>
                            <h1>Private Networks</h1>
                        </div>
                        <?php throwSuccess($success); ?>
                        <?php throwError($error); ?>
                        <div class="form">
                            <form method="post" action="login.php">
                                <fieldset class="field">
                                    <label for="username">Email</label>
                                    <input id="username" name="user" type="email" required>
                                </fieldset>

                                <fieldset class="field">
                                    <label for="password">Password</label>
                                    <input id="password" name="pass" type="password" required>
                                </fieldset>

                                <label for="remember" class="choice">
                                    <input type="checkbox" name="remember" id="remember" >
                                    <span class="choice__text">Remember me</span>
                                </label>

                                <button style="width: 100%" class="button button--primary" type="submit" name="login_user">Login</button>
                            </form>

                            <a style="width: 100%" class="button button--secondary" role="button" href="register.php">
                                Register
                            </a>
                            <div align="center">
                                <a class="button button--secondary button--link" role="button" href="passwordRecovery.php">
                                    Forgot password?
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
	</body>
</html>
