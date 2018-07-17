<?php
    include_once('conn.php');
    include_once('libs/common.php');
    include_once('libs/userAccount.php');

    $error = array();
    $success = null;

    //Perform initial login check to prevent a logged in user from creating an account
    if(loginCheck($mysql)){
        header("Location: networks.php");
    }

    if(isset($_POST['reg_user']) && isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['username']) && isset($_POST['password_1']) && isset($_POST['password_2'])){
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $user = $_POST['username'];
        $pass = $_POST['password_1'];
        $cpass = $_POST['password_2'];

        //Recaptcha
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        //Post data
        $data = array('secret' => $recaptcha_key, 'response' => $_POST['g-recaptcha-response']);
        //Build post request
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $result = file_get_contents($url, false, $context);

        //Handle reCAPTCHA API response
        if ($result === FALSE)
            array_push($error,"reCAPTCHA connection error, please try again!");

        //Convert API JSON data into an associative array
        $result = json_decode($result, true);
        //Check if reCAPTCHA validated user
        if($result['success'] === FALSE)
            array_push($error,"reCAPTCHA was unable to validate input, please try again!");

        //Do basic checks on user input
        if(strlen($pass) < 8 || strlen(preg_replace('![^A-Z]+!', '', $pass)) < 1)
            array_push($error, "Password must be at least 8 characters long and contain at least 1 upper case letter");
        if($pass != $cpass)
            array_push($error, "Passwords do not match!");
        if (!filter_var($user, FILTER_VALIDATE_EMAIL))
            array_push($error, "Not a valid email!");
        if(empty($cpass))
            array_push($error, "Password confirmation is empty!");
        if(empty($pass))
            array_push($error, "Password is empty!");
        if(empty($user))
            array_push($error, "Email is empty!");
        if(empty($lname))
            array_push($error, "Last name is empty!");
        if(empty($fname))
            array_push($error, "First name is empty!");

        //If errors from basic checks are empty, go ahead and attempt to register the user
        if(empty($error)) {
            //Create account object
            $acc = new userAccount($mysql);
            //If register method returns false, throw error
            if ($acc->register($user, $pass, $fname, $lname, $mailjet) === FALSE) {
                array_push($error, "An error occurred during registration, Email may already exist!");
            } else {
                header("Location: index.php");
                //Automatic login after registration. Should be doing email verification instead
                /*
                $account = $acc->login($user, $pass);
                if (!empty($account))
                    $account['loggedin'] = true;
                    $account['lastActivity'] = time();
                    $_SESSION = $account;
                unset($account);
                //Perform a secondary login check to verify login succeeded and redirect
                if(loginCheck()){
                    header("Location: networks.php");
                    exit();
                } else {
                    $error = "You account was registered, but an error occurred when attempting to log you into your account!";
                }
                */
            }
        }
    }
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
        <link rel="stylesheet" type="text/css" href="css/login.css">
        <link rel="stylesheet" type="text/css" href="css/tds.css">
        <link rel="icon" type="image/favicon" href="/favicon.ico">
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
                        <?php throwSuccess($success); ?>
                        <?php if(!empty($error)){foreach($error as $throw){throwError($throw);}} ?>
                        <div class="form">
                            <form method="post" action="register.php">
                                <fieldset class="field">
                                    <label for="fname">First Name</label>
                                    <input id="fname" name="fname" type="text" required>
                                </fieldset>

                                <fieldset class="field">
                                    <label for="lname">Last Name</label>
                                    <input id="lname" name="lname" type="text" required>
                                </fieldset>

                                <fieldset class="field">
                                    <label for="username">Email</label>
                                    <input id="username" name="username" type="email" required>
                                </fieldset>

                                <fieldset class="field">
                                    <label for="password">Password</label>
                                    <input type="password" name="password_1" id="password"><br>
                                </fieldset>

                                <fieldset class="field">
                                    <label for="cpassword">Confirm Password</label>
                                    <input type="password" name="password_2" id="cpassword"><br>
                                </fieldset>

                                <div align="center" class="g-recaptcha" data-sitekey="6LdE3FoUAAAAAAsp2tVhL8WWf_ELiO4YHz5ZOzkN"></div>
                                <button  style="width: 100%" class="button button--primary" type="submit" name="reg_user">Register</button>
                            </form>
                            <div align="center">
                                <a class="button button--secondary button--link" role="button" href="<?php getURL("login.php"); ?>">
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


