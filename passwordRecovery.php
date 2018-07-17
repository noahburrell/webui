<?php
include_once('conn.php');
include_once('libs/common.php');
include_once ('libs/userAccount.php');

//Don't let the user see this page if they are logged in
if(loginCheck($mysql)){
    header("Location: networks.php");
    exit();
}

$recoveryPage = false;
$error = false;
$acc = new userAccount($mysql);

$errMsg = null;
$notification = null;

if(isset($_GET['email']) && isset($_GET['token'])){
    //Validate GET variables
    if($acc->validateResetToken($_GET['email'], $_GET['token'])) {
        $recoveryPage = true;

        //Check for POST data to reset password. If not POST data, probably safe to assume user just arrived
        if(isset($_POST['password']) && isset($_POST['cpassword'])){
            $pass = $_POST['password'];
            $cpass = $_POST['cpassword'];

            //Do basic checks on user input
            if(strlen($pass) < 8 || strlen(preg_replace('![^A-Z]+!', '', $pass)) < 1)
                $errMsg = "Password must be at least 8 characters long and contain at least 1 upper case letter!";
            if($pass != $cpass)
                $errMsg = "Passwords do not match!";
            if(empty($cpass))
                $errMsg = "Password confirmation is empty!";
            if(empty($pass))
                $errMsg = "Password is empty!";

            //check if any errors occured. If none, reset the password.
            if(empty($errMsg)){
                if($acc->resetPassword($_GET['email'], $_GET['token'], $pass)){
                    header("Location: index.php");
                    exit();
                } else {
                    $errMsg = "An unknown error occurred!";
                }
            }

        }
    } else {
        $recoveryPage = false;
        header('Location: passwordRecovery.php');
        exit();
    }

} else if(isset($_POST['user']) && filter_var($_POST['user'], FILTER_VALIDATE_EMAIL)) {
    //Validate ReCAPTCHA
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    //Post data
    $data = array('secret' => $recaptcha_key, 'response' => $_POST['g-recaptcha-response']);
    //Build post request
    $options = array(
        'http' => array(
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'method' => 'POST',
            'content' => http_build_query($data)
        )
    );
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    //Handle reCAPTCHA API response
    if ($result === FALSE) {
        $errMsg = "reCAPTCHA connection error, please try again!";
        $error = true;
    }

    //Convert API JSON data into an associative array
    $result = json_decode($result, true);
    //Check if reCAPTCHA validated user
    if ($result['success'] === FALSE){
        $errMsg = "reCAPTCHA was unable to validate input, please try again!";
        $error = true;
    }

    //Check for ReCAPTCHA errors
    if(!$error){
        //Attempt to send a recovery email
        if($acc->recoverPasswordEmail($mailjet, $_POST['user'])){
            $notification = "A recovery link has been sent, please check your Email.";
        } else {
            $errMsg = "An unknown error occurred, please confirm that the email address is correct and that it is associated an account.";
        }
    }
}

?>

<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="css/login.css">
        <link rel="stylesheet" type="text/css" href="css/tds.css">
        <title>TELUS: Private Networks - Recover Password</title>
        <script src='https://www.google.com/recaptcha/api.js'></script>
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
                        <?php throwSuccess($notification); ?>
                        <?php throwError($errMsg); ?>
                        <div class="form">
                            <?php
                                if(!$recoveryPage){
                                    echo '
                                    <form method="post">
                                        <fieldset class="field">
                                            <label for="username">Email</label>
                                            <input type="text" name="user" id="username"><br>
                                        </fieldset>
                                        <div align="center" class="g-recaptcha" data-sitekey="6LdE3FoUAAAAAAsp2tVhL8WWf_ELiO4YHz5ZOzkN"></div>
                                        <button style="width: 100%" class="button button--primary" type="submit" name="recovery">Send Recovery Email</button>
                                    </form>
                                    ';
                                } else {
                                    echo '
                                    <form method="post">
                                        <fieldset class="field">
                                            <label for="password">Password</label>
                                            <input type="password" name="password" id="password"><br>
                                        </fieldset>
                                        
                                        <fieldset class="field">
                                            <label for="cpassword">Confirm Password</label>
                                            <input type="password" name="cpassword" id="cpassword"><br>
                                        </fieldset>
                                        <button style="width: 100%" class="button button--primary" type="submit" name="recovery">Reset Password</button>
                                    </form>
                                    ';
                                }
                            ?>
                            <div align="center">
                                <a class="button button--secondary button--link" role="button" href="<?php getURL("login.php") ?>">
                                    Back to login
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </body>
</html>
