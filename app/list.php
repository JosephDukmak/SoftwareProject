<?php
	require '../config/config.php';
	if(empty($_SESSION['username']))
		header('Location: login.php');	

		try {
			if($_SESSION['role'] == 'admin'){
				$stmt = $connect->prepare('SELECT * FROM room_rental_registrations_apartment');
				$stmt->execute();
				$data1 = $stmt->fetchAll (PDO::FETCH_ASSOC);

				$stmt = $connect->prepare('SELECT * FROM room_rental_registrations');
				$stmt->execute();
				$data2 = $stmt->fetchAll (PDO::FETCH_ASSOC);

				$data = array_merge($data1,$data2);
			}

			if($_SESSION['role'] == 'user'){
				$stmt = $connect->prepare('SELECT * FROM room_rental_registrations_apartment WHERE :user_id = user_id ');
				$stmt->execute(array(
					':user_id' => $_SESSION['id']
				));
				$data1 = $stmt->fetchAll (PDO::FETCH_ASSOC);

				$stmt = $connect->prepare('SELECT * FROM room_rental_registrations WHERE :user_id = user_id ');
				$stmt->execute(array(
					':user_id' => $_SESSION['id']
				));
				$data2 = $stmt->fetchAll (PDO::FETCH_ASSOC);

				$data = array_merge($data1,$data2);
			}
		}catch(PDOException $e) {
			$errMsg = $e->getMessage();
		}	
		// print_r($data1);	
		// echo "<br><br><br>";
		// print_r($data2);
		// echo "<br><br><br>";	
		// print_r($data);	

		if(isset($_GET['action']) && $_GET['action'] == 'delete') {
			
			if ( isset($_GET['act'])) {
				$active = $_REQUEST['act'];

				if ( isset($_GET['id'])) {
					$id = $_REQUEST['id'];
				}	

				if ($active === 'ap') { 
					
					$stmt = $connect->prepare("DELETE FROM room_rental_registrations_apartment WHERE id= :id");
					$stmt->bindParam(':id', $id);
					$stmt->execute();
		}	else {
					
					$stmt = $connect->prepare("DELETE FROM room_rental_registrations WHERE id= :id");
					$stmt->bindParam(':id', $id);
					$stmt->execute();
			}
		}
		header('Location: list.php?action=deleted');
			
	}
	if(isset($_GET['action']) && $_GET['action'] == 'deleted') {
		$errMsg = "Entry Deleted Successfully.";
	}
?>
<?php include '../include/header.php';?>

	<!-- Header nav -->	
	<nav class="navbar navbar-expand-lg navbar-dark fixed-top" style="background-color:#212529;" id="mainNav">
      <div class="container">
	  <a class="navbar-brand js-scroll-trigger" href="../index.php"><img src="../imgs/logo00.png" alt="logo" width="80" height="80"/></a>
        <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
          
          <i class="fa fa-bars"></i>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav text-uppercase ml-auto">
		  <li class="nav-item">
			<a href="../index.php" class="nav-link">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#"><?php echo $_SESSION['fullname']; ?> <?php if($_SESSION['role'] == 'admin'){ echo "(Admin)"; } ?></a>
            </li>
            <li class="nav-item">
              <a href="../auth/logout.php" class="nav-link">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
	<!-- end header nav -->
	<section style="padding-left:0px;">
		<?php include '../include/side-nav.php';?>
	</section>

<section class="wrapper" style="margin-left: 16%;margin-top: -15%;">
	<div class="container">
		<div class="row">
			<div class="col-12">
			<?php
				if(isset($errMsg)){
					echo '<div style="color:#FF0000;text-align:center;font-size:17px;">'.$errMsg.'</div>';
				}
			?>
			<h2>List of Apartment Details</h2>
				<?php 
					foreach ($data as $key => $value) {						
						echo '<div class="card card-inverse card-info mb-3" style="padding:1%;">					
								  <div class="card-block">';
								  	echo '<a class="btn btn-warning float-right" href="update.php?id='.$value['id'].'&act=';if(!empty($value['own'])){ echo "ap"; }else{ echo "indi"; } echo '">Edit</a>';
 
									 echo 	'<div class="row">
											<div class="col-4">
											<h4 class="text-center">Owner Details</h4>';
											 	echo '<p><b>Owner Name: </b>'.$value['fullname'].'</p>';
											 	echo '<p><b>Mobile Number: </b>'.$value['mobile'].'</p>';
											 	echo '<p><b>Alternate Number: </b>'.$value['alternate_mobile'].'</p>';
											 	echo '<p><b>Email: </b>'.$value['email'].'</p>';
											 	echo '<p><b>Country: </b>'.$value['country'].'</p><p><b> State: </b>'.$value['state'].'</p><p><b> City: </b>'.$value['city'].'</p>';
											 	if ($value['image'] !== 'uploads/') {
											 		# code...
											 		echo '<img src="'.$value['image'].'" width="100">';
											 	}

											 	echo '<p><b>Address: </b>'.$value['address'].'</p><p><b> Landmark: </b>'.$value['landmark'].'</p>';

										echo '</div>
											<div class="col-5">
											<h4 class="text-center">Room Details</h4>';
												// echo '<p><b>Country: </b>'.$value['country'].'<b> State: </b>'.$value['state'].'<b> City: </b>'.$value['city'].'</p>';
												echo '<p><b>House Number: </b>'.$value['plot_number'].'</p>';

												if(isset($value['sale'])){
													echo '<p><b>Sale: </b>'.$value['sale'].'</p>';
												}										
												
													if(isset($value['apartment_name']))
														echo '<div class="alert alert-success" role="alert"><p><b>Apartment Name: </b>'.$value['apartment_name'].'</p></div>';

													if(isset($value['ap_number_of_plats']))
														echo '<div class="alert alert-success" role="alert"><p><b>Flat Number: </b>'.$value['ap_number_of_plats'].'</p></div>';
												if(isset($value['own'])){
													echo '<p><b>Available Area: </b>'.$value['area'].'</p>';
													echo '<p><b>Floor: </b>'.$value['floor'].'</p>';
													echo '<p><b>Owned/Rented: </b>'.$value['own'].'</p>';
													echo '<p><b>Purpose: </b>'.$value['purpose'].'</p>';
												}
												echo '<p><b>Available Rooms: </b>'.$value['rooms'].' </p>';
											
										echo '</div>
											<div class="col-3">
											<h4>Other Details</h4>';
											echo '<p><b>Accommodation: </b>'.$value['accommodation'].'</p>';
											echo '<p><b>Description: </b>'.$value['description'].'</p>';

											echo '<p><b>Rent/Night: </b>'.$value['rent'].' <b>USD</b></p>';
											echo '<p><b>Deposit: </b>'.$value['deposit'].' <b>USD</b></p>';
												if($value['vacant'] == 0){ 
													echo '<div class="alert alert-danger" role="alert"><p><b>Occupied</b></p></div>';
												}else{
													echo '<div class="alert alert-success" role="alert"><p><b>Vacant</b></p></div>';
												} echo '<br>';echo '<br>';	echo '<br>';	echo '<br>';						
												  echo '<a class="btn btn-danger " style ="float: right;" href="list.php?action=delete&id='.$value['id'].'&act=';if(!empty($value['own'])){ echo "ap"; }else{ echo "indi"; } echo '">Delete</a>';

											echo '</div>
										</div>				      
								   </div>
								   
								</div>';
								echo '<a class="btn btn-warning float-right" href="../app/complaint.php">Complaint</a><br><br>';
								
					}
				?>				
			</div>
		</div>
	</div>	
</section>
<?php include '../include/footer.php';?>