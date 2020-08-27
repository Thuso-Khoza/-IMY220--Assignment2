<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	$server = "localhost";
	$username = "root";
	$password = "";
	$database = "dbUser";
	$mysqli = mysqli_connect($server, $username, $password, $database);

	$email = isset($_POST["loginEmail"]) ? $_POST["loginEmail"] : false;
	$pass = isset($_POST["loginPass"]) ? $_POST["loginPass"] : false;	
	// if email and/or pass POST values are set, set the variables to those values, otherwise make them false
?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 2</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Name Surname">
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

							//checking the upload
							if(isset($_FILES["picToUpload"]))
							{
								$uploadFile = $_FILES["picToUpload"];
								$numFiles = count($uploadFile["name"]);
								for($i = 0; $i < $numFiles; $i++){
								 if(($uploadFile["type"][$i] == "image/jpeg" || $uploadFile["type"][$i] == "image/jpg") && $uploadFile["size"][$i] < 1048576)
								 	{
								 	
								 	$target_dir = "gallery/";
								 	$target_file = $target_dir . basename($uploadFile["name"][$i]);
								 	$imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

								 	if(move_uploaded_file($uploadFile["tmp_name"][$i], $target_file))
								 	{
								 		$q2 = "SELECT * FROM tbgallery WHERE filename ='".$uploadFile["name"][$i]."'";
										$r = $mysqli->query($q2);
										
								 		if($r->num_rows==0){
								 		$q="INSERT INTO tbgallery ( user_id,filename	)
								 		 VALUES ('". $row['user_id'] ."','".$uploadFile["name"][$i]."')";
										$result = $mysqli->query( $q);
										if($q===false){echo"insert failed";}
										}
								 	} 
								 	else 
								 		{
								 			echo "Sorry, there was an error uploading your file.";
								 		}
								 }
								 else{
								 	//echo("invalid");
								 }}
								// For example: $name = $uploadFile["name"][$i];
										
							}
							

				
					echo 	"<form action='' method='post' enctype='multipart/form-data'>

								<div class='form-group'>
									<input type='hidden' name='loginEmail' value='" .$_POST["loginEmail"]. "' />
									<input type='hidden' name='loginPass' value='".$_POST["loginPass"]. "' />
									<input type='file' class='form-control' name='picToUpload[]' id='picToUpload' multiple='multiple' /><br/>
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
								</div>
						  	</form>";
						  	$query2 = "SELECT * FROM tbgallery WHERE user_id ='".$row['user_id']."'";
							$res2 = $mysqli->query($query2);
							$rows = [];
							// if($row= mysqli_fetch_array($res2)){
							// 	$rows[] = $row;
							// 		echo count($rows);
							// }
							echo"<div class='card'>";
							echo "<div class='card-header'> Image Gallery</div>";
							echo "<div class='row imageGallery card-body'>";
							 while($row=mysqli_fetch_array($res2))
	    						{
	    						 // $dept[]=$row['name'];
	    						 // echo $row['filename'];
	    						  $t="gallery/".str_replace(' ', '', $row['filename']);
	    						  
	    						  echo "<div class='col-3 ' style='background-image: url(".$t.")' style='width:100%' style='height:100%'></div>";
	    							 }
	    							echo "</div></div>";
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