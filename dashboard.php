<?php

	session_start();

	if(isset($_SESSION['Username'])){
		include "includes/templates/header.php";
		include "includes/templates/nonavbar.php";


	 ?>

	 <div class="container home-stats">
		<h1>Welcome Mr <?php echo $_SESSION['Name']; ?></h1>
		<div class="row">
			<div class="col-md-3">
				<div class="stat">Total Users
				 <span>5</span>
				</div>
			</div>
			<div class="col-md-3">
				<div class="stat">Total of your messages
				 <span>10</span>
				</div>
			</div>
			<div class="col-md-3">
				<div class="stat">Total member messages
				 <span>22</span>
				</div>
			</div>
			<div class="col-md-3">
				<div class="stat">Messages wait approval
				 <span>12</span>
				</div>
			</div>
		</div>
	 </div>


<?php

	}
	else{	

		echo "You Are Not Authorized To View This Page Your IP Address Is " . strip_tags($_SERVER['REMOTE_ADDR']);

		header('Location: index.php');
		exit();

	}


	include "includes/templates/footer.php";
  ?>