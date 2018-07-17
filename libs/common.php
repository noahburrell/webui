<?php
session_start();
session_regenerate_id();

//Check if the user has timed out. Default timeout is 900s.
function timeoutCheck($timeout = 900, $update = true){
    if(isset($_SESSION['lastActivity'])){
        //If last activity is older than current time - timeout, log the user out unless remember cookie set true.
        if($_SESSION['lastActivity'] < (time()-$timeout)){
            if(!isset($_COOKIE['remember']) || $_COOKIE['remember'] == FALSE) {
                header("Location: logout.php");
                exit();
            }
        } else if($update) {
            //If the user has not timed out as of this check, and the update variable true, update the last activity time to right now
            $_SESSION['lastActivity'] = time();
        }
    }
}

function loginCheck($mysql, $autoRedirect = false, $redirect = 'login.php'){

    //check if user is logged in
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
        return true;
    } else {
        //Check if persistent login cookie exists (and validate it)
        if (isset($_COOKIE['remember'])) {
            $table = $mysql['persistentLogin'];
            $loginTable = $mysql['loginTable'];
            $selectorValidator = explode(":", $_COOKIE['remember']);
            $selector = $selectorValidator[0];
            $validator = hash("sha256", $selectorValidator[1]);


            $conn = new mysqli($mysql["host"], $mysql["user"], $mysql['password'], $mysql['db'], $mysql['port']);
            $query = "SELECT * FROM $table WHERE selector='$selector';";

            $result = $conn->query($query);
            if ($result->num_rows == 1) {
                $result = $result->fetch_assoc();
                if ($result['hashedValidator'] == $validator) {
                    //If the hashed validators match, set the session and return true
                    $id = $result['userid'];

                    $query = "SELECT * FROM $loginTable WHERE id = '$id' LIMIT 1;";
                    $result = $conn->query($query);

                    if ($result->num_rows == 1) {
                        $userInfo = $result->fetch_assoc();
                        if ($userInfo['confirmed'] == 1) {
                            $userInfo['loggedin'] = true;
                            $userInfo['lastActivity'] = time();

                            if (!isset($_SESSION))
                                session_start();

                            $_SESSION = $userInfo;
                            return true;
                        }
                    } else {
                        die ("Error associating authentication token with user account");
                    }
                } else {
                    //unset remember cookie if it doesnt match DB entry
                    unset($_COOKIE['remember']);
                    setcookie('remember', null, -1, "/");

                    //if autoRedirect is set, redirect to specified location
                    if ($autoRedirect)
                        header("Location: " . $redirect);
                }
            } else {
                //Log user out immediatly if a remember cookie exists but cannot be looked up
                if ($autoRedirect)
                    header("Location: " . $redirect);
                return false;
            }

        } else {
            //if autoRedirect is set, redirect to specified location
            if ($autoRedirect)
                header("Location: " . $redirect);
            return false;
        }
    }
}

function purgeLoginTokens($mysql, $uid){
    $table = $mysql['persistentLogin'];
    $conn = new mysqli($mysql["host"], $mysql["user"], $mysql['password'], $mysql['db'], $mysql['port']);
    $uid = mysqli_real_escape_string($conn, $uid);
    $query = "DELETE FROM $table WHERE userid='$uid'";
    $conn->query($query);
}

function refreshSessionInfo($mysql){
    if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === TRUE){
        $table = $mysql['loginTable'];
        $id = $_SESSION['id'];
        $conn = new mysqli($mysql["host"], $mysql["user"], $mysql['password'], $mysql['db'], $mysql['port']);
        $query = "SELECT * FROM $table WHERE id = '$id' LIMIT 1;";
        $result = $conn->query($query);
        if($result->num_rows == 1) {
            $_SESSION = $result->fetch_assoc();
            $_SESSION['loggedin'] = true;
            $_SESSION['lastActivity'] = time();
        }
    }
}

function getURL($dest, $suppress = false){
    if(empty($_SERVER['HTTPS']))
        $protocol = "http://";
    else
        $protocol = "https://";
    if(!$suppress)
        echo $protocol.$_SERVER['HTTP_HOST']."/".$dest;

    return $protocol.$_SERVER['HTTP_HOST']."/".$dest;
}

function sendMail($keys, $from, $fromName, $to, $toName, $content, $subject){

    $data = array(
        'Messages' => array(
            array(
                'From' => array(
                    'Email' => "$from",
                    'Name' => $fromName
                ),

                'To' => array(
                    array(
                        'Email' => $to,
                        'Name' => $toName
                    )
                ),

                'Subject' => "$subject",

                'HTMLPart' => "$content"

            )
        )
    );

    $data = json_encode($data);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_USERPWD, $keys['public'].":".$keys['private']);
    curl_setopt($ch, CURLOPT_URL,"https://api.mailjet.com/v3.1/send");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec ($ch);
    curl_close ($ch);

    return $result;
}

function throwError($error){
    if(!empty($error)){
        echo '
            <div class="field field--error">
                <div class="helper helper--error" id="omitted-field-error">
                    <strong>'.$error.'</strong>
                </div>
            </div>
        ';
    }
}

function throwSuccess($msg){
    if(!empty($msg)){
        echo '
            <div class="field field--success">
                <div class="helper helper--success" id="omitted-field-success">
                    <strong>'.$msg.'</strong>
                </div>
            </div>
        ';
    }
}