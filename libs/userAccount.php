<?php
class userAccount {
    private $conn;
    private $table;
    private $recovery;
    private $persistent;
    private $pepper = "telus";

    function __construct($mysql) {
        $this->conn = new mysqli($mysql["host"], $mysql["user"], $mysql['password'], $mysql['db'], $mysql['port']);
        $this->table = mysqli_real_escape_string($this->conn, $mysql['loginTable']);
        $this->recovery = mysqli_real_escape_string($this->conn, $mysql['recoveryTable']);
        $this->persistent = mysqli_real_escape_string($this->conn, $mysql['persistentLogin']);
    }

    function __destruct() {
        $this->conn->close();
    }

    function login($uname, $pass){
        $uname = mysqli_real_escape_string($this->conn, $uname);
        $pass = mysqli_real_escape_string($this->conn, $pass);
        $query = "SELECT * FROM $this->table WHERE username = '$uname' LIMIT 1;";

        $result = $this->conn->query($query);

        if($result->num_rows == 1){
            $userInfo = $result->fetch_assoc();
            if(password_verify($this->pepper.$pass.$userInfo["salt"], $userInfo["hash"])){
                return $userInfo;
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    function register($uname, $pass, $fname, $lname, $emailKeys){
        $uname = mysqli_real_escape_string($this->conn, $uname);
        $pass = mysqli_real_escape_string($this->conn, $pass);
        $fname = mysqli_real_escape_string($this->conn, ucwords($fname));
        $lname = mysqli_real_escape_string($this->conn, ucwords($lname));
        $salt = uniqid(mt_rand(), true);

        $hash = password_hash($this->pepper.$pass.$salt, PASSWORD_BCRYPT);

        $confirm_string  = md5($hash);

        $query = "INSERT INTO $this->table (username, hash, salt, fname, lname, conf_str) VALUES ('$uname', '$hash', '$salt', '$fname', '$lname', '$confirm_string');";

        if ($this->conn->query($query) === TRUE) {
            //Send an email
            $this->sendConfEmail($emailKeys, $uname, $confirm_string);
            return true;
        } else {
            //return mysqli_error($this->conn);
            return false;
        }
    }

    function emailConf($email, $token){
        $email = mysqli_real_escape_string($this->conn, $email);
        $token = mysqli_real_escape_string($this->conn, $token);

        $query = "SELECT id, username, conf_str, confirmed FROM $this->table WHERE username = '$email' LIMIT 1;";
        $result = $this->conn->query($query);

        if($result->num_rows == 1){
            $result = $result->fetch_assoc();
            $id = $result['id'];

            if($result['confirmed'] == 1)
                return 2; //return 2 for an already confirmed email

            if($result['conf_str'] == $token){
                $query = "UPDATE  $this->table SET confirmed=1 WHERE id='$id';";
                if ($this->conn->query($query) === TRUE)
                    return 1; //return 1 for success
                else
                    return 0; //return 0 for failure
            }
        } else {
            return 0; //return 0 for failure
        }
    }

    function sendConfEmail($keys, $email, $token){
        include_once('common.php');
        $url = getURL("confirm.php?email=$email&token=$token", true);

        $body = "
            <div align='center'><img src='https://static.telus.com/common/images/header/TELUS-logo.svg' width='200px' alt='Telus Logo'<br />
            <h3>Activate Your Account</h3><br />We just need to validate your email address to activate your account. Simply click the following button: <br />
            <a href='$url'>Activate My Account</a><br /><br />
            If the link doesn't work, copy this URL into your browser:<br />
            <a href='$url'>$url</a><br /><br />
            Welcome aboard!<br />
            The Telus Team<br /><br /><br />
            <h6>This email was sent to you by Telus because you signed up for an account. Please let us know if you feel that this email was sent to you in error.</h6>
            </div>
        ";

        return sendMail($keys, "noreply@burrelln.tech", "Telus", $email, "", $body, "Telus - Activate Your Account");

    }

    function recoverPasswordEmail($keys, $email){
        include_once('common.php');
        $email=mysqli_real_escape_string($this->conn, $email);

        $query = "SELECT $this->table.id, $this->table.username, $this->recovery.token
                  FROM $this->table
                  LEFT OUTER JOIN $this->recovery ON $this->table.id = $this->recovery.uid
                  WHERE $this->table.username = '$email' LIMIT 1";
        $result = $this->conn->query($query);

        if($result->num_rows == 1){
            $result = $result->fetch_assoc();

            //Generate token
            $userID = $result['id'];
            $newToken = md5(random_bytes(256 ));

            //Generate insert or update query depending on if a row in the recovery table already exists
            if($result['token'] == null){
                //If no token exists, insert one
                $query = "INSERT INTO $this->recovery (uid, token) VALUES ($userID, '$newToken');";
            } else {
                //If a token does exist, update it
                $query = "UPDATE $this->recovery SET token = '$newToken' WHERE uid = $userID;";
            }

            //Execute and confirm the query executed correctly, then send email
            if ($this->conn->query($query) === TRUE) {
                //Send an email
                //Generate recovery URL
                $url = getURL("passwordRecovery.php?email=$email&token=$newToken", true);
                //Generate email body
                $body = "
                    <div align='center'><img src='https://static.telus.com/common/images/header/TELUS-logo.svg' width='200px' alt='Telus Logo'<br />
                    <h3>Password Reset</h3><br />Someone has tried to reset your password. If this wasn't you, please disregard this email.
                    Otherwise, please click the button below to continue to reset your password<br />
                    <a href='$url'>Reset My Password</a><br /><br />
                    If the link doesn't work, copy this URL into your browser:<br />
                    <a href='$url'>$url</a><br /><br />
                    The Telus Team<br /><br /><br />
                    <h6>This email was sent to you by Telus because you have tried to reset your password. Please let us know if you feel that this email was sent to you in error.</h6>
                    </div>
                ";

                sendMail($keys, "noreply@burrelln.tech", "Telus", $email, "", $body, "Telus - Password Reset");
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function validateResetToken($email, $token, $returnID = false){
        $email = mysqli_real_escape_string($this->conn, $email);
        $token = mysqli_real_escape_string($this->conn, $token);

        $query = "SELECT $this->table.username, $this->recovery.token, $this->recovery.id
                  FROM $this->table
                  INNER JOIN $this->recovery ON $this->table.id = $this->recovery.uid
                  WHERE $this->table.username = '$email' AND $this->recovery.token = '$token' LIMIT 1";

        $result = $this->conn->query($query);

        if($result->num_rows == 1){
            if(!$returnID)
                return true;
            else
                return ($result->fetch_assoc())['id'];
        } else {
            return false;
        }
    }

    function resetPassword($email, $token, $password){
        $email = mysqli_real_escape_string($this->conn, $email);
        $password = mysqli_real_escape_string($this->conn, $password);
        $token=  mysqli_real_escape_string($this->conn, $token);

        //Update password
        $salt = uniqid(mt_rand(), true);
        $hash = password_hash($this->pepper.$password.$salt, PASSWORD_BCRYPT);

        $query = "UPDATE $this->table SET hash = '$hash', salt = '$salt' WHERE username = '$email';";

        if ($this->conn->query($query) === TRUE) {
            //Revalidate the token and return the ID of the row the token is in
            $recoveryID = $this->validateResetToken($email, $token, true);
            if($recoveryID){
                //Delete the row the token is in
                $query = "DELETE FROM $this->recovery WHERE id=$recoveryID;";
                $this->conn->query($query);
                if ($this->conn->query($query) === TRUE)
                    return true;
                else
                    return false;
            } else
                return false;
        } else {
            return false;
        }
    }

    function setPersistentLogin($userID){
        $userID = mysqli_real_escape_string($this->conn, $userID);

        $selector = bin2hex(random_bytes(12));
        $validator = bin2hex(random_bytes(128));
        $hashedValidator = hash("sha256", $validator);
        $selectorValidator = $selector.":".$validator;
        $expires = time()+31536000;

        if(isset($_COOKIE['remember'])){
            unset($_COOKIE['remember']);
            setcookie('remember', null, -1, "/");
        }

        setcookie("remember", $selectorValidator, $expires, "/");

        $query = "INSERT INTO $this->persistent (selector, hashedValidator, userID, expires) VALUES ('$selector', '$hashedValidator', '$userID', '$expires');";

        if ($this->conn->query($query) === TRUE) {
            return true;
        } else {
            return false;
        }
    }

    function getNetworks($uid){
        $query = "SELECT * FROM routerTable INNER JOIN subnetTable ON routerTable.id = subnetTable.rid WHERE routerTable.uid = '".$uid."';";
        $result = $this->conn->query($query);
        return $result->fetch_all();
    }
}