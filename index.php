<?php
	session_start();
	$NoNavbar = '';
	if(isset($_SESSION['Username'])){
			
			header('Location: dashboard.php');
			exit();

	}
	include "includes/templates/header.php";
	include "includes/templates/nonavbar.php";

	if($_SERVER['REQUEST_METHOD'] == "POST"){

		$username = $_POST['uname'];
		$password = $_POST['upass'];
		$hashpass = sha1($password);

		// Check If Input Correct 

		$stmt = $con->prepare("SELECT Full_Name,RoleID,UserID,Email,Password From users WHERE Email = ? AND Password = ? LIMIT 1");

		$stmt->execute(array($username, $hashpass));
		$row = $stmt->fetch();
		$count = $stmt->rowCount();

		if($count > 0){
			$_SESSION['Username'] = $username;
			$_SESSION['ID'] = $row['UserID'];
			$_SESSION['R_ID'] = $row['RoleID'];
			$_SESSION['Name'] = $row['Full_Name'];
			header('Location: dashboard.php');
			exit();
		}

	}
?>

	<form class="login" action="<?php echo $_SERVER['PHP_SELF'] ?>" Method="POST">	
	<h4 class="text-center">Supervisors Login Page</h4>
	<input class="form-control input-lg" type="text" name="uname" placeholder="Username" autocomplete="off" />	
	<input class="form-control input-lg" type="password" name="upass" placeholder="Password" autocomplete="new-password" />
	<input class="btn btn-primary btn-block input-lg" type="submit" value="Login" />
	</form>

<?php	
	include "includes/templates/footer.php";
?>