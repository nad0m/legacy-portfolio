<!DOCTYPE html>
<html lang="en">
<head>
	<style>
		div {
				width:900px;
				margin:0px auto;
				border: 1px solid;

				padding:10px;
				font-family: "Trebuchet MS";
				text-align:center;

		}
		h2 {
				text-align:center;
				font-family: "Trebuchet MS";
			
		}
		a {text-decoration: none; }
		fieldset {

  				 margin: auto;
				 width:50%;

  				 text-align:center;


		}
				footer { 	
				text-align: center;
				font-size: .85em;
				background-color: white;
				margin: .5em 0;
				padding: .25em 0;



		}
		
		</style>

<meta charset="utf-8">

<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame
Remove this if you use the .htaccess -->
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

<title>Pokemon Query</title>
<meta name="description" content="">

<meta name="viewport" content="width=device-width; initial-scale=1.0">

<!-- Replace favicon.ico & apple-touch-icon.png in the root of your domain and delete these references -->
<link rel="shortcut icon" href="/favicon.ico">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<style>
form {
display: inline;
}

</style>

</head>

<body>
	<table border ='1px black' align = 'right' font-size='smaller'>
	<tr><td>Username</td><td>Password</td></tr>
	<tr><td>hNguyen</td><td>grass</td></tr>
	<tr><td>lDizon</td><td>fire</td></tr>
	<tr><td>sKhan</td><td>water</td></tr>
	<tr><td>student1</td><td>admin</td></tr>
	<tr><td>student2</td><td>admin</td></tr>
	<tr><td>student3</td><td>admin</td></tr>

</table>
</br></br></br></br></br></br></br></br></br></br></br>
	<h2>One Bit Login</h2>

 <div float = "middle">

 

<form method="post">
Username: <input type="text" name="username" /><br />
<p></p>
Password: <input type="password" name="password" /><br />
<p></p>
<input type="submit" value="Login" />
<p></p>
</form>



<?php

session_start();

if (isset($_POST['username'])){
	
require 'db_connection.php';

$sql = "SELECT *
FROM pokemon_user
WHERE username = :username
AND password = :password";

$stmt = $dbConn -> prepare($sql);
$stmt -> execute(array(":username" => $_POST['username'], ":password" => hash("sha1", $_POST['password'])));

$record = $stmt -> fetch();


if (empty($record)){
echo "Wrong Username/Password!";
} else {
$_SESSION['username'] = $record['username'];
$_SESSION['name'] = $record['firstname'] . " " . $record['lastname'];

$sql = "CREATE TABLE IF NOT EXISTS user_" . $record['username'] ." (
sessionId INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
time datetime NOT NULL)";

$stmt = $dbConn -> prepare($sql);
$stmt -> execute();

$sql = "INSERT INTO user_" . $record['username'] ." (time)
		VALUES (NOW())";
		
$stmt = $dbConn -> prepare($sql);
$stmt -> execute();
		

header("Location: pokemonQuery.php");
}
}

?>
</div></br></br></br></br>


</br></br></br></br>

<footer>
		<fieldset><legend>Team One Bit</legend> <a href= "homePage.html">Huy Nguyen</a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="http://hosting.otterlabs.org/classes/dizo9615/CST336/Labs/index.html">Lawrence Dizon</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<a href="http://hosting.otterlabs.org/khan1736/CST336/Assignments/Assign1/assign1.html">Seema Khan</a></fieldset>

</footer>
</body>
</html>
