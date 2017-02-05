<?php // users.php :: Handles user account functions.

include('lib.php');
$link = opendb();

if (isset($_GET["do"])) {
    
    $do = $_GET["do"];
    if ($do == "register") { register(); }
    
}

function register() { // Register a new account.
    
    $controlquery = doquery("SELECT * FROM {{table}} WHERE id='1' LIMIT 1", "control");
    $controlrow = mysql_fetch_array($controlquery);
    
    if (isset($_POST["submit"])) {
        
        extract($_POST);
        
        $errors = 0; $errorlist = "";
        
        // Process username.
        if ($username == "") { $errors++; $errorlist .= "Username field is required.<br />"; }
        if (preg_match("/[^A-z0-9_\-]/", $username)==1) { $errors++; $errorlist .= "Username must be alphanumeric.<br />"; } // Thanks to "Carlos Pires" from php.net!
        $usernamequery = mysql_query("SELECT username FROM dk_users WHERE username='$username' LIMIT 1");
        if (mysql_num_rows($usernamequery) > 0) { $errors++; $errorlist .= "Username already taken - unique username required.<br />"; }
    
        // Process email address.
        if ($email1 == "" || $email2 == "") { $errors++; $errorlist .= "Email fields are required.<br />"; }
        if ($email1 != $email2) { $errors++; $errorlist .= "Emails don't match.<br />"; }
        if (! is_email($email1)) { $errors++; $errorlist .= "Email isn't valid.<br />"; }
        $emailquery = mysql_query("SELECT email FROM dk_users WHERE email='$email1' LIMIT 1");
        if (mysql_num_rows($emailquery) > 0) { $errors++; $errorlist .= "Email already taken - unique email address required.<br />"; }
        
        // Process password.
        if ($password1 != $password2) { $errors++; $errorlist .= "Passwords don't match.<br />"; }
        $password = md5($password1);
        
        if ($errors == 0) {
            
            $verifycode = "";
            for ($i=0; $i<8; $i++) {
                $verifycode .= chr(rand(65,90));
            }
            
            $query = doquery("INSERT INTO {{table}} SET id='',regdate=NOW(),verify='$verifycode',username='$username',password='$password',email='$email1',charname='$charname',charclass='$charclass',difficulty='$difficulty'", "users") or die(mysql_error());
            //if (sendregmail($email1, $verifycode)) {
                $page = "Your account was created successfully.<br /><br />You may now continue to the <a href=\"login.php?do=login\">Log In</a> page and start playing ".$controlrow["gamename"]."!";
            //} else {
            //    $page = "Your account was created successfully.<br /><br />However, there was a problem sending your verification email. Please check with the game administrator to help resolve this problem.<br /><br />You may now continue to the <a href=\"login.php?do=login\">Log In</a> page and start playing ".$controlrow["gamename"]."!";
            //}
            
        } else {
            
            $page = "The following error(s) occurred when your account was being made:<br /><span style=\"color:red;\">$errorlist</span><br />Please go back and try again.";
            
        }
        
    } else {
        
        $page = gettemplate("register");
        $page = parsetemplate($page, $controlrow);
        
    }
    
    $topnav = "<a href=\"login.php?do=login\"><img src=\"images/button_login.gif\" alt=\"Log In\" border=\"0\" /></a><a href=\"users.php?do=register\"><img src=\"images/button_register.gif\" alt=\"Register\" border=\"0\" /></a><a href=\"help.php\"><img src=\"images/button_help.gif\" alt=\"Help\" border=\"0\" /></a>";
    display($page, "Register", false, false, false);
    
}

function sendregmail($emailaddress, $vercode) {
    
    include('config.php');
    extract($appsettings);
    
    $headers = "";
    $headers .= "From: $adminname<$adminemail>\n";
    $headers .= "X-Sender: <$adminemail>\n";
    $headers .= "Return-Path: $adminname<$adminemail>\n";
    $headers .= "X-Mailer: PHP\n";

$email = <<<END
You or someone using your email address recently signed up for an account on the $gamename server, located at $gameurl.

This email is sent to verify your registration email. Next time you log into the game, please visit the User Settings page and enter the following code into the Account Verification field:
$vercode

If you were not the person who signed up for the game, please disregard this message. You will not be emailed again.
END;

    $status = mail($emailaddress, "$gamename Account Verification", $email, $headers);
    return $status;
    
}


?>