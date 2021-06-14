<?php
	require '../config/config.php';
	if(empty($_SESSION['username']))
		header('Location: login.php');	

		try {
			$stmt = $connect->prepare('SELECT * FROM booked_rooms');
			$stmt->execute();
			$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
		}
		catch(PDOException $e) {
			$errMsg = $e->getMessage();
		}	
		// print_r($data);	


?>
<?php include '../include/header.php';?>
	<!-- Header nav -->	
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
              <a class="nav-link" href="login.php"><?php echo $_SESSION['fullname']; ?> <?php if($_SESSION['role'] == 'admin'){ echo "(Admin)"; } ?></a>
            </li>
            <li class="nav-item">
              <a href="../auth/logout.php" class="nav-link">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
	<!-- end header nav -->
<?php include '../include/side-nav.php';?>
<section class="wrapper" style="margin-left:16%;margin-top: -5%;">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<?php
					if(isset($errMsg)){
						echo '<div style="color:#FF0000;text-align:center;font-size:17px;">'.$errMsg.'</div>';
					}
				?>
				<h2>List Of Reservations</h2>
				<br>
				<div class="table-responsive">
					<table class="table table-bordered">
					  <thead>
					    <tr>
					      <th>#</th>
					      <th>Full Name</th>
					      <th>Username</th>
					      <th>Mobile</th>
					      <th>Email</th>
					      <th>Address</th>
						  <th>Address (additional)</th>
						  <th>Country</th>
						  <th>State</th>
						  <th>Zip Code</th>
						  <th>Check-in</th>
						  <th>Check-out</th>
						  <th>Date of Reservation</th>
					    </tr>
					  </thead>
					  <tbody>
					  	<?php 
					  		foreach ($data as $key => $value) {
					  			# code...	
								 			  			
							   echo '<tr>';
							      echo '<th scope="row">'.$key.'</th>';
							      echo '<td>'.$value['fullname'].'</td>';
							      echo '<td>'.$value['username'].'</td>';
							      echo '<td>'.$value['mobile'].'</td>';
								  echo '<td>'.$value['email'].'</td>';
								  echo '<td>'.$value['address'].'</td>';
								  echo '<td>'.$value['address2'].'</td>';
								  echo '<td>'.$value['country'].'</td>';
								  echo '<td>'.$value['state'].'</td>';
								  echo '<td>'.$value['zip'].'</td>';
								  echo '<td>'.$value['cin'].'</td>';
								  echo '<td>'.$value['cout'].'</td>';
								  echo '<td>'.$value['created_at'].'</td>';
								  
								  echo '</tr>';
					  		}
					  	?>
					  </tbody>
					</table>
					
				</div>
			</div>
		</div>
	</div>
</section>
<?php include '../include/footer.php';?>