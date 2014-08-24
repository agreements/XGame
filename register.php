<?php // 
// INCLUDES
require_once("includes/session.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
// SETTINGS: page
$settingsQ = mysql_query("SELECT * FROM settings WHERE settingsID = '1'", $connection); $settingsR = mysql_fetch_array($settingsQ); 
$pagetitle = $settingsR["pagetitle"];
$errorreporting = $settingsR["errorreporting"]; error_reporting($errorreporting);
// SUBMIT: register
if (isset($_POST['registernow'])) {
	//GET: variables
	$nickname = sql_quote($_POST['nickname']);
	$username = sql_quote($_POST['username']);
	$password = sql_quote($_POST['password']);
	$passwordagain = sql_quote($_POST['passwordagain']);
	$email = sql_quote($_POST['email']);
	$gender = sql_quote($_POST['gender']);
	$countryCODE = sql_quote($_POST['countryCODE']);
	$date = time();
	$ip= $_SERVER['REMOTE_ADDR'];
	// CHECK: submited data
	if (strlen($nickname) < 3 ) {$error[]= "Your nickname must be at least 3 characters long.";}
	if (strlen($password) < 5 ) {$error[]= "Your password must be at least 5 characters long.";}
	if (strlen($username) < 3 ) {$error[]= "Your password must be at least 3 characters long.";}
	if ($password !=  $passwordagain) {$error[]= "Passwords don't match.";}
	if (strlen($email) == 0 ) {$error[] ="Please enter e-mail adress.";}
	$email_restrict = '/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/'; 
	if(!(preg_match($email_restrict, $email))) {$error[]= "Please enter valid e-mail adress.";}
	// CHECK: mail in use
	$emailQ = mysql_query("SELECT * FROM users WHERE email = '$email' "); $emailR = mysql_num_rows($emailQ);
	if($emailR) {$error[]= "E-mail adress is already in use.";}
	// CHECK: username in use
	$usernameQ = mysql_query("SELECT * FROM users WHERE username = '$username' "); $usernameR = mysql_num_rows($usernameQ);
	if($usernameR) {$error[]= "Username is already in use.";}
	// CHECK: register errors
	if(is_array($error)) {foreach($error as $err) {$message .="<h4>&#8226; ".$err."</h4></br>";} echo $message; return;}
	// ACTIVATION (1-auto 2-email 3-admin)
	$url = $settingsR["url"];
	$pagetitle = $settingsR["pagetitle"];
	$adminemail = $settingsR["adminemail"];
	$activation = $settingsR["activation"];
	// ACTIVATION SETTINGS
	if ($activation == '1') {$activationkey = "active";}
	if ($activation == '2') {$activationkey = rand(1000, 9999);}
	if ($activation == '3') {$activationkey = "inactive";}
	$hashed_password = md5($password);
	// INSERT NEW USER
	$userINS = "INSERT INTO users ( username, hashed_password, nickname, email, country, gender, pic, joined, ip, rights, activated
	) VALUES (
	'$username', '$hashed_password', '$nickname', '$email', '$countryCODE', '$gender', 'user.jpg', '$date', '$ip', '1', '$activationkey'
	)";
	// SEND MAIL TO USER
	$ToEmail = $email;
	$ToName = $nickname;
	$header = "Account Information: ".$pagetitle;
	// SEND OPTIONS
	if ($activation == '1') {//auto acctivation
		$Message = 'Hello '.$nickname.'!
		Your registration was successful.
		Nickname: '.$nickname.'
		Password: '.$password.'
		Your account is activated and you can log in.
		Thank you for registration
		'.$pagetitle.' - '.$url;
	} 
	if ($activation == '2') {//user activation
		$Message = 'Hello '.$nickname.'!
		Your registration was successful.
		Nickname: '.$nickname.'
		Password: '.$password.'
		To complete your registration please click on the following link:
		http://'.$url.'/index.php?site=register&key='.$activationkey.'
		Thank you for registration
		'.$pagetitle.' - '.$url;
	} 
	if ($activation == '3') {//admin activation
		$Message = 'Hello '.$nickname.'!
		Your registration was successful.
		Nickname: '.$nickname.'
		Password: '.$password.'
		Your account is waiting for activation.
		Thank you for registration
		'.$pagetitle.' - '.$url;
	}
	// SEND MAIL
	mail($ToEmail,$header, $Message, "From:".$pagetitle."\r\nX-Mailer: PHP/" . phpversion());
	// RESULT
	$userR = mysql_query($userINS, $connection);
	if ($userR) {echo "<h4>&#8226; Your registration was successful!</h4>"; return;}
	else {echo "<h4>&#8226; Your registration was not successful!</h4>"; return;}
}
// ACCOUNT ACTIVATION
elseif($_GET['key']) {
    $activationkey = sql_quote($_GET['key']);
	$userUPD = mysql_query("UPDATE users SET activated = 'active' WHERE activated = '$activationkey'");
	if (mysql_affected_rows()) {echo "<h4>&#8226; Your account have been activated!</h4>"; return;}
	else {echo "<h4>&#8226; Wrong activation key!</h4>"; return;}
}
// OUTPUT: register
else {
	if (!isset($_SESSION['pid'])) {
		eval ("\$register = \"".gettemplate("register")."\";");
		echo $register;
	}
}
?>