<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	//Server Details
	$server = "127.0.0.1";
	$username = "root";
	$password = "";
	$database = "dbUser";

	//Make Connection
	$mysqli = mysqli_connect($server, $username, $password);
	if (!$mysqli)
	{
		die("Connection failed: " . mysqli_connect_error());
	}

	//Make DB
	$sql = "CREATE DATABASE dbUser";
	if ($mysqli->query($sql) === TRUE)
	{
	  echo "Database created successfully";
	}
	else
	{
	  echo "Error creating database: " . $mysqli->error;
	}

	//Make Connection to DB
	$mysqli = mysqli_connect($server, $username, $password, $database);

	//Create Tables
	$sql = "CREATE TABLE tbusers (
	user_id INT AUTO_INCREMENT PRIMARY KEY,
	name CHAR(100),
	surname CHAR(100),
	password CHAR(100)
	email CHAR(100),
	birthday DATE
	)";
	if (mysqli_query($mysqli, $sql))
	{
		echo "Table tbusers created successfully";
	}
	else
	{
		echo "Error creating table: " . mysqli_error($mysqli);
	}

	$sql = "CREATE TABLE tbgallery (
	image_id INT AUTO_INCREMENT PRIMARY KEY,
	user_id INT,
	filename VARCHAR(50),
	)";
	if (mysqli_query($mysqli, $sql))
	{
		echo "Table tbusers created successfully";
	}
	else
	{
		echo "Error creating table: " . mysqli_error($mysqli);
	}

	$email = isset($_POST["loginEmail"]) ? $_POST["loginEmail"] : false;
	$pass = isset($_POST["loginPass"]) ? $_POST["loginPass"] : false;	
	// if email and/or pass POST values are set, set the variables to those values, otherwise make them false

	$target_dir = "gallery/";
	$uploadFile = $_FILES["file"];
	$target_file = $target_dir . basename($uploadFile["name"]);
	$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
	
	if(isset($_POST["submit"]))
	{
		$check = getimagesize($uploadFile["tmp_name"]);
		if($check !== false)
		{
			echo "File is an image – " . $check["mime"] . ".";
		}
		else
		{
			echo "File is not an image.";
		}
	}

	if(($uploadFile["type"] == "image/jpeg" || $uploadFile["type"] == "image/jpg") && $uploadFile["size"] < 1000000)
	{
		if($uploadFile["error"] > 0)
		{
			echo "Error: " . $uploadFile["error"] . "<br/>";
		}
		else
		{
			echo "Upload: " . $uploadFile["name"] . "<br/>";
			echo "Type: " . $uploadFile["type"] . "<br/>";
			echo "Size: " . ($uploadFile["size"] / 1024) . "Kb<br/>";
			echo "Stored in: " . $uploadFile["tmp_name"];
		}
	}
	else
	{
		echo "Invalid file";
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 2</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Christof Steyn">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php
			if($email && $pass){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);
				if($row = mysqli_fetch_array($res)){
					echo 	"<table class='table table-bordered mt-3'>
								<tr>
									<td>Name</td>
									<td>" . $row['name'] . "</td>
								<tr>
								<tr>
									<td>Surname</td>
									<td>" . $row['surname'] . "</td>
								<tr>
								<tr>
									<td>Email Address</td>
									<td>" . $row['email'] . "</td>
								<tr>
								<tr>
									<td>Birthday</td>
									<td>" . $row['birthday'] . "</td>
								<tr>
							</table>";
				
					echo 	"<form>
								<div class='form-group' action='login.php' method='post' enctype='multipart/form-data'>
									<input type='file' class='form-control' name='picToUpload' id='picToUpload' /><br/>
									<input id='loginEmail' name='loginEmail' type='hidden' value='$email'>
									<input id='loginPass' name='loginPass' type='hidden' value='$pass'>
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
								</div>
						  	</form>";
				}
				else{
					echo 	'<div class="alert alert-danger mt-3" role="alert">
	  							You are not registered on this site!
	  						</div>';
				}
			} 
			else{
				echo 	'<div class="alert alert-danger mt-3" role="alert">
	  						Could not log you in
	  					</div>';
			}
		?>
	</div>
</body>
</html>