<?php

	session_start();

	if(isset($_SESSION['Username'])){
	
	include "includes/templates/header.php";
	include "includes/templates/nonavbar.php";


	$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';
	if ($do == 'Manage'){ 

		$stmt = $con->prepare("SELECT * FROM users");

		$stmt->execute();

		$rows = $stmt->fetchAll();
	 ?>

			<h1 class="text-center">Manage Users</h1>
			<div class="container">
				<div class="table-responsive">
					
					<table class="main-table text-center table">
						
						<tr>
							<td>#ID</td>
							<td>Username</td>
							<td>Email</td>
							<td>ID Number</td>
							<td>Permission</td>
							<td>Control</td>
						</tr>	

						<?php

							foreach ($rows as $row) {
								
								echo "<tr>";
									echo "<td>" . $row['UserID'] . "</td>";
									echo "<td>" . $row['Full_Name'] . "</td>";
									echo "<td>" . $row['Email'] . "</td>";
									echo "<td>" . $row['IDNO'] . "</td>";
									if($row['RoleID'] == 0){

										echo "<td>Admin</td>";
									} elseif ($row['RoleID'] == 1) {

										echo "<td>User</td>";
									}
									elseif ($row['RoleID'] == 2) {
										echo "<td>Mod</td>";
									}
									echo "<td>
										<a href='users.php?do=Edit&userid=". $row['UserID'] ."'class='btn btn-success'><i class='fa fa-edit'></i>Edit</a>

										<a href='users.php?do=Delete&userid=". $row['UserID'] ."' class='btn btn-danger confirm'><i class='fa fa-close'></i>Delete</a>

										</td>";

								echo "</tr>";

							}
						?>
						<tr>
					</table>

				</div>
				<a href="users.php?do=Add" class="btn btn-primary"><i class="fa fa-plus"></i> Add New User </a>
			</div>

	<?php }elseif ($do == 'Add') { ?>


			<h1 class="text-center">Add New User</h1>
		<div class="container">
			<form class="form-horizontal" action="?do=Insert" method="POST">
			<div class="form-group form-group-lg">
				<label class="col-sm-2 control-label">Email</label>
				<div class="col-sm-10 col-md-4">
				<input type="email" name="email" class="form-control" required="" autocomplete="off" placeholder="Enter User Email" />
				</div>
			</div>
			<div class="form-group form-group-lg">
				<label class="col-sm-2 control-label">Full Name</label>
				<div class="col-sm-10 col-md-4">
				<input type="text" name="fname" class="form-control" required="" placeholder="Enter User Full Name" />
				</div>
			</div>
			<div class="form-group form-group-lg">
				<label class="col-sm-2 control-label">ID Number</label>
				<div class="col-sm-10 col-md-4">
				<input type="text" name="idno" class="form-control" required="" placeholder="Enter User ID Number" />
				</div>
			</div>

			<div class="form-group form-group-lg">
				<label class="col-sm-2 control-label">Password</label>
				<div class="col-sm-10 col-md-4">
				<input type="password" name="password" class="form-control" autocomplete="new-password" required="required" placeholder="Enter User Password Number"/>
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
				<input type="submit" value="Update" class="btn btn-primary btn-lg" />
				</div>
		</div>
		</form>
		</div>	




	<?php 



		}elseif($do == 'Insert'){


		if($_SERVER['REQUEST_METHOD'] == 'POST'){

			echo "<h1 class='text-center'>Update User</h1>";
			echo "<div class='container'>";
			//GET USR INFORMATION
			$email = $_POST['email'];
			$fname = $_POST['fname'];
			$idno = $_POST['idno'];
			$password = $_POST['password'];
			$hashpass = sha1($_POST['password']);

			$formerror=array();

			if(strlen($fname) < 4){

			$formerror[] = 'This Is Not A real Full Name Because they <strong>Less than 4 characters</strong>';


			}
			if(strlen($email) < 6){

			$formerror[] = 'Email Cant Be <strong>Less than 6 characters</strong>';
			}

			if(strlen($idno) < 9){

			$formerror[] = 'ID Number Cant Be <strong>Less than 9 Numbers</strong>';
			}
			if(empty($fname)){

				$formerror[] = 'Username Cant <strong>Be Empty</strong>';
			}
 			
			if(empty($idno)){

				$formerror[] = 'ID Number Cant <strong>Be Empty</strong>';
			}


			if(empty($password)){

				$formerror[] = 'Password Cant <strong>Be Empty</strong>';
			}

			if(empty($email)){

				$formerror[] = 'Email Cant <strong>Be Empty</strong>';
			}

			foreach ($formerror as $error) {

				echo '<div class="alert alert-danger">' . $error .'</div>';
				# code...
			}
 
			//UP Databsd

				if(empty($formerror)){

				$stmt = $con->prepare("INSERT INTO users(Full_Name, Password, Email, IDNO) VALUES(:uname, :upass, :umail, :uidno)");

				$stmt->execute(array(

					'uname' =>  $fname,
					'upass' =>  $hashpass,
					'umail' =>  $email,
					'uidno' =>  $idno


				));




			echo  "<div class='alert alert-success'>User Has Been Add</div>";

			echo '<meta http-equiv="refresh" content="3;url=http://localhost/msg/admin/index.php">';
			}


		} else{

			$err = "Sorry You Cant Browse This Page Directly";

			redirectHome($err, 6);

		}
	}

		elseif ($do == 'Edit'){

		$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;


		$stmt = $con->prepare("SELECT * From users WHERE UserID = ? LIMIT 1");

		$stmt->execute(array($userid));
		$row = $stmt->fetch();
		$count = $stmt->rowCount();

		if($stmt->rowCount() > 0){


	?>


	<h1 class="text-center">Edit User</h1>
	<div class="container">
		<form class="form-horizontal" action="?do=Update" method="POST">
			<input type="hidden" name="userid" value="<?php echo $userid; ?>" />
		<div class="form-group form-group-lg">
			<label class="col-sm-2 control-label">Email</label>
			<div class="col-sm-10 col-md-4">
			<input type="email" name="email" class="form-control" value="<?php echo $row['Email']; ?>" required="" autocomplete="off" />
			</div>
		</div>
		<div class="form-group form-group-lg">
			<label class="col-sm-2 control-label">Full Name</label>
			<div class="col-sm-10 col-md-4">
			<input type="text" name="fname" class="form-control" required="" value="<?php echo $row['Full_Name']; ?>" />
			</div>
		</div>

		<div class="form-group form-group-lg">
			<label class="col-sm-2 control-label">Password</label>
			<div class="col-sm-10 col-md-4">
			<input type="hidden" name="oldpassword" value="<?php echo $row['Password'];?>" />
			<input type="password" name="newpassword" class="form-control" autocomplete="new-password" />
			</div>
		</div>

		<div class="form-group">
			<div class="col-sm-offset-2 col-sm-10">
			<input type="submit" value="Update" class="btn btn-primary btn-lg" />
			</div>
		</div>
	</form>
	</div>

	<?php

	}
	else{

		echo "there is no such id";
	}

	}elseif ($do == 'Update'){

		echo "<h1 class='text-center'>Update User</h1>";
		echo "<div class='container'>";

		if($_SERVER['REQUEST_METHOD'] == 'POST'){

			//GET USR INFORMATION
			$id = $_POST['userid'];
			$email = $_POST['email'];
			$password = $_POST['password'];
			$fname = strip_tags($_POST['fname']);

			$pass = empty($_POST['newpassword']) ? $_POST['oldpassword'] : sha1($_POST['newpassword']);

			$formerror=array();
			if(strlen($fname) < 4){

			$formerror[] = 'This Is Not A real Full Name Because they <strong>Less than 4 characters</strong>';


			}
			if(strlen($email) < 6){

			$formerror[] = 'Email Cant Be <strong>Less than 6 characters</strong>';
			}

			if(empty($fname)){

				$formerror[] = 'Username Cant <strong>Be Empty</strong>';
			}
 
			if(empty($email)){

				$formerror[] = 'Email Cant <strong>Be Empty</strong>';
			}

			foreach ($formerror as $error) {

				echo '<div class="alert alert-danger">' . $error .'</div>';
				# code...
			}
 
			//UP Databsd

				if(empty($formerror)){



			$stmt = $con->prepare("UPDATE users SET Email = ?, Password = ? , Full_Name = ? WHERE UserID= ?");

			$stmt->execute(array($email, $pass, $fname , $id));

			echo "<div class='alert alert-success'>Profile Has<strong> Been Saved</div>";

			echo '<meta http-equiv="refresh" content="3;url=http://localhost/msg/admin/index.php">';
			 }	else{

			echo "This RULE IS Warning";
		}

		} else{

			echo "Sorry";
		}

		echo "</div>";

}     elseif ($do == 'Delete') {



		echo "<h1 class='text-center'>Delete Users</h1>";
		echo "<div class='container'>";
	 	
		$userid = isset($_GET['userid']) && is_numeric($_GET['userid']) ? intval($_GET['userid']) : 0;


		$stmt = $con->prepare("SELECT * From users WHERE UserID = ? LIMIT 1");

		$stmt->execute(array($userid));
		$count = $stmt->rowCount();

		if($stmt->rowCount() > 0){

			$stmt = $con->prepare("DELETE FROM users WHERE UserID = :zuser");

			$stmt->bindParam(":zuser", $userid);

			$stmt->execute();

			echo "<div class='alert alert-success'>User Has<strong> Been Delete From Database</div>";
		} else{

			echo "<div class='alert alert-danger'>User ID<strong> Not Exist In Database</div>";
		}

		//echo "</div>";

	}

	}
	else{	

		header('Location: index.php');
		exit();

	}
	include "includes/templates/footer.php";
  ?>