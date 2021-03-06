<?php
	require '../config/config.php';

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

	require 'PHPMailer/src/Exception.php';
	require 'PHPMailer/src/PHPMailer.php';
	require 'PHPMailer/src/SMTP.php';

	if(isset($_POST['register'])) {
	
		$errMsg = '';

		// Get data from FROM

		$username = $_POST['username'];

		// check username if exists
		$query = $connect -> prepare("SELECT * from users where username = ?");
		$query->execute([$username]);
		$result = $query->rowCount();
		if ($result > 0){
			header('Location: register.php?action=TakenUsername');
			$errMsg = "Username already exists! please choose another one.";
		}

		$mobile = $_POST['mobile'];

		$email = $_POST['email'];

			// check email if exists
			$query = $connect -> prepare("SELECT * from users where email = ?");
			$query->execute([$email]);
			$result = $query->rowCount();
			if ($result > 0){
				header('Location: register.php?action=TakenEmail');
				$errMsg = "Email already exists! please choose another one.";
			}

		$password = $_POST['password'];
		$c_password = $_POST['c_password'];
		if ($password != $c_password){
			header('Location: register.php?action=PasswordsDontMatch');
			$errMsg = "Both passwords don't match, please try again!";
		  }

		$fullname = $_POST['fullname'];

			if (empty($errMsg)){
				header('Location: register.php?action=joined');
				$stmt = $connect->prepare('INSERT INTO users (fullname, mobile, username, email, password) VALUES (:fullname, :mobile, :username, :email, :password)');
				$stmt->execute(array(
					':fullname' => $fullname,
					':username' => $username,
					':password' => md5($password),
					':email' => $email,
					':mobile' => $mobile,
					));

				
				exit;
			}
			//catch(PDOException $e) {
			//	echo $e->getMessage();
			//}
	}

	if(isset($_GET['action']) && $_GET['action'] == 'joined') {
		$errMsg = 'Registration successfull. Now you can login';
	}
	else if (isset($_GET['action']) && $_GET['action'] == 'TakenUsername'){
		$errMsg = "Username already exists! please choose another one.";
	}
	else if (isset($_GET['action']) && $_GET['action'] == 'TakenEmail'){
		$errMsg = "Email already exists! please choose another one.";
	}
	else if (isset($_GET['action']) && $_GET['action'] == 'PasswordsDontMatch'){
		$errMsg = "Both passwords don't match, please try again!";
	}
?>

<?php include '../include/header.php';?>
	<!-- Services -->
	<nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#212529;" id="mainNav">
      <div class="container">
	  <a class="navbar-brand js-scroll-trigger" href="../index.php"><img src="../imgs/logo00.png" alt="logo" width="80" height="80"/></a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          Menu
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav text-uppercase ml-auto">
            <li class="nav-item">
              <a class="nav-link" href="login.php">Login</a>
            </li>
            <li class="nav-item"> 
            </li>
          </ul>
        </div>
      </div>
    </nav>
<!-- <section> --><br>
	<div class="container">
		<div class="row">				
			  <div class="col-md-8 mx-auto">
			  	<div class="alert alert-dark" role="alert">
			  		<?php
						if(isset($errMsg)){
							echo '<div style="color:#FF0000;text-align:center;font-size:17px;">'.$errMsg.'</div>';
						}
					?>
			  		<h2 class="text-center">Register</h2>
				  	<form action="" method="post">
				  		<div class="row">
					  	    <div class="col-6">
						  	  <div class="form-group">
							    <label for="fullname">Full Name</label>
							    <input type="text" class="form-control" id="fullname" placeholder="Full Name" name="fullname" required>
							  </div>
							</div>
							<div class="col-6">
							  <div class="form-group">
							    <label for="username">Username</label>
							    <input type="text" class="form-control" id="username" placeholder="Username" name="username" required>
							  </div>
						    </div>
					   </div>
					   <div class="row">
					  	    <div class="col-6">
							  <div class="form-group">
							    <label for="mobile">Mobile</label>
							    <input type="text" class="form-control" pattern="^(\d{10})$" id="mobile" title="10 digit mobile number" placeholder="10-digit mobile number" name="mobile" required>
							  </div>
							 </div>
							<div class="col-6">					  
							  <div class="form-group">
							    <label for="email">Email</label>
							    <input type="email" class="form-control" id="email" placeholder="Email" name="email" required>
							  </div>
							 </div>
						</div>

					  <div class="form-group">
					    <label for="password">Password</label>
					    <input type="password" class="form-control" id="password" placeholder="Password" name="password" required>
					  </div>

					  <div class="form-group">
					    <label for="c_password">Confirm Password</label>
					    <input type="password" class="form-control" id="c_password" placeholder="Confirm Password" name="c_password" required>
					  </div>

					  <button type="submit" class="btn btn-primary" name='register' value="register">Submit</button>
					</form>				
				</div>
			</div>
		</div>
	</div>
<!-- </section> -->
<?php include '../include/footer.php';?>
