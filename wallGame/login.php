<?php
session_save_path("sesh");
session_start();

$dbconn = pg_connect("host=cslinux.utm.utoronto.ca port=5432 dbname=sansonem user=sansonem password=69447")
				or die('Could not connect: ' . pg_last_error());

if(isset($_REQUEST['username'])){
	setcookie("loggedin", 0);
	pg_prepare($dbconn, "passCheck", "SELECT name FROM login WHERE name = $1 AND password = $2");
	$user = pg_escape_string($_REQUEST['username']);
	$pass = pg_escape_string($_REQUEST['password']);
	$sql=pg_execute($dbconn, "passCheck", array($user, $pass));
	if(pg_num_rows($sql) > 0){ // Login
		setcookie("loggedin", 1);
		$_SESSION['username'] = $_REQUEST['username'];
		header('Location: https://cs.utm.utoronto.ca/~sansonem/a1/game.php', true, 303);
		die();
	}else{
		$loginerrormessage = "Username or Password is incorrect.";
	}
} elseif(isset($_POST['createName']))
{
	setcookie("loggedin", 0);
	pg_prepare($dbconn, "addUser","INSERT INTO login (name, password) VALUES ($1, $2)");
	$createName = pg_escape_string($_POST['createName']);
	$createPassword = pg_escape_string($_POST['createPassword']);
	 
	if ($createPassword == "" || $createName=="" || $createName=="Nickname" || $createPassword =="Password") {
		$errormessage = "Not a Valid Username/Password";
	}
	else {
		$sql = pg_execute($dbconn, "addUser", array($createName, $createPassword));
		if (!$sql) { 
			$errormessage = "Username already taken.";
		}else { // Login
			setcookie("loggedin", 1);
			$_SESSION['username'] = $createName;
			header('Location: https://cs.utm.utoronto.ca/~sansonem/a1/game.php', true, 303);
			die();
		}
	}
}
pg_close($dbconn);
?>

<html>
<head> <meta charset="utf-8">
<title> Login to THE GAME </title>
<link rel="stylesheet" type="text/css" href="css.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="https://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
<script type="text/javascript" src="scripts.js"></script>
</head>
    <body background="img/background.png">
	
	<!-- LOGIN FORM -->
	<form name="input" action=<?php echo '"' . $_SERVER['PHP_SELF'] . '"';?> method="post">
		<div id="content">
			<?php if(isset($loginerrormessage))
				echo "<span style=\"position:absolute;text-align:center;color:#ff0000;\">" . $loginerrormessage . "</span>"; ?>
			<div id="innercontent">
				<input type="text" name="username" value="Nickname" id="nameAndPw" onfocus="inputFocus(this)" onblur="inputBlur(this)" size="22"></input>
			    <input type="password" name="password" value="password" id="nameAndPw" onfocus="inputFocus(this)" onblur="inputBlur(this)" size="22"></input>
			     
			    <div>
					<img onclick="showDiv()" class="hoverImages" src="img/signup.png"> </img>
					<input class="classname" style="float:right; margin-top:8px;" type="submit" value="Sign In"></input>
				</div>

			</div>
		</div>
	</form>
	<!-- LOGIN FORM -->

	<!-- REGISTRATION FORM -->
	<div id="signUp" class=<?php if(isset($_REQUEST['createName'])) echo "\"signUpAlreadyUp\""; else echo "\"signUp\""; ?>>
		<div style="margin-top:10px; margin-left:34px;">
			<?php if(isset($errormessage))
				echo "<span style=\"text-align:center;color:#ff0000;\">" . $errormessage . "</span>"; ?>
			<form  action=<?php echo '"' . $_SERVER['PHP_SELF'] . '"';?> method="POST" >
				<table id="tableMustFade" style=<?php if(isset($_REQUEST['createName'])) echo "\"display:block\"";
														else echo "\"display:none\""; ?>>
					<tr><td align="right" width="455"><img id="x" onclick="hideDiv()" src="img/x-button.png" alt="X"/> </td></tr><tr>
						<td><input id="nameAndPw" type="text" name="createName" value="Nickname"  onfocus="inputFocus(this)" onblur="inputBlur(this)" size="25"></td></tr><tr> 
						<td><input id="nameAndPw" type="password" name="createPassword" value="Password" onfocus="inputFocus(this)" onblur="inputBlur(this)" size="25"></td></tr><tr> 
						<td align="center"><input class="classname" type="submit" name="submit" value="Sign Up"></input>
						</td>
						<input type="hidden" value="step2" name="step2">
					</tr>
				</table>
		   </form>
		</div>
	</div>
	<!-- REGISTRATION FORM -->
 
	<div style="position:absolute; right:0; bottom:0; color:#818384;"> Copyright © 2015 Sansone/Marcucci Inc. All rights reserved.</div>
	 
    </body>
</html>









