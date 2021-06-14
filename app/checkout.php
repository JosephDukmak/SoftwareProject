<?php
		require '../config/config.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
  
    require '../auth/PHPMailer/src/Exception.php';
    require '../auth/PHPMailer/src/PHPMailer.php';
    require '../auth/PHPMailer/src/SMTP.php';
	if(empty($_SESSION['username']))
		header('Location: login.php');
    $rent = '';
    if ( isset($_GET['id'])) {
			$id = $_REQUEST['id'];
		}	

		if ( isset($_GET['act'])) {
			$active = $_REQUEST['act'];
    
    
			if ($active == 'Room Reservation') {
				
				try {
					$stmt = $connect->prepare('SELECT * FROM room_rental_registrations_apartment where id = :id');
					$stmt->execute(array(
						':id' => $id
					));
          $data2 = $connect->prepare("SELECT rent FROM room_rental_registrations_apartment where id = :id");
          $data2 ->bindValue(':id', $id);
          $data2->execute();
          $rent = $data2->fetch();
          if (!empty($rent)){
            echo 'NA';
          }

					$data = $stmt->fetch();				
				}catch(PDOException $e) {
					$errMsg = $e->getMessage();
				}
			}else{
				try{
					$stmt = $connect->prepare('SELECT * FROM room_rental_registrations where id = :id');
					$stmt->execute(array(
						':id' => $id
					));
          $data2 = $connect->prepare("SELECT rent FROM room_rental_registrations where id = :id");
          $data2 ->bindValue(':id', $id);
          $data2->execute();
          $rent = $data2->fetch();
          if (!empty($rent)){
            echo 'NA';
          }
    
					$data = $stmt->fetch(PDO::FETCH_ASSOC);
				}catch(PDOException $e) {
					echo $e->getMessage();
				}			
			}
		}

    if(isset($_POST['checkout'])) {
	
      $errMsg = '';

      $fullname = $_POST['fullname'];
      $username = $_POST['username'];
      
      if ( isset($_GET['id'])) {
        $id = $_REQUEST['id'];
      }
      
		// check username if exists
		$query = $connect -> prepare("SELECT * from users where username = ?");
		$query->execute([$username]);
		$result = $query->rowCount();
		if ($result < 1){
			header('Location: checkout.php?id='.$value['id'].'&act=&action=InvalidUsername');
			$errMsg = "Username does not exists! please enter a valid username.";
		}

    $mobile = $_POST['mobile'];
		$email = $_POST['email'];
    $address = $_POST['address'];
    $address2 = $_POST['address2'];
    $country = $_POST['country'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];
    $cin = $_POST['cin'];
    $cout = $_POST['cout'];

    if (empty($errMsg)){
      
      $stmt = $connect->prepare('INSERT INTO booked_rooms (fullname, username, mobile, email, address, address2, country, state, zip, cin, cout) VALUES (:fullname, :username, :mobile, :email, :address, :address2, :country, :state, :zip, :cin, :cout)');
      $stmt->execute(array(
        ':fullname' => $fullname,
        ':username' => $username,
        ':mobile' => $mobile,
        ':email' => $email,
        ':address' => $address,
        ':address2' => $address2,
        ':country' => $country,
        ':state' => $state,
        ':zip' => $zip,
        ':cin' => $cin,
        ':cout' => $cout ));
        header('Location: checkout.php?action=registered&id='.$value['id'].'&act=');
        $id = $_POST['id'];
    }

    if(isset($_GET['action']) && $_GET['action'] == 'registered') {

      if ( isset($_GET['id'])) {
        $id = $_REQUEST['id'];
      }
        
      if ($active == 'Room Reservation') {
          
          try {
           

            $data3 = $connect->prepare('UPDATE room_rental_registrations_apartment SET vacant = 0 WHERE id = :id');
            $data3 ->bindvalue(':id', $id);
            $data3->execute();
          				
          }catch(PDOException $e) {
            $errMsg = $e->getMessage();
          }
        }
        else{
          try{

            $data3 = $connect->prepare('UPDATE room_rental_registrations SET vacant = 0 WHERE id = :id');
            $data3 ->bindValue(':id', $id);
            $data3->execute();

          }catch(PDOException $e) {
            echo $e->getMessage();
          }			
        }
      
      
      } 
  }
    
     

if(isset($_GET['action']) && $_GET['action'] == 'joined') {
  $errMsg = 'Reservation successful. Please contact the owner before arrival.';
}
  

else if (isset($_GET['action']) && $_GET['action'] == 'InvalidUsername'){
  $errMsg = "Username does not exists! please enter a valid username.";
}


    ?>
<?php include '../include/header.php';?>
 
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Checkout</title>
    <link rel = "icon" href = "../imgs/logo00.png" type = "image/x-icon">
    <link rel="canonical" href="https://getbootstrap.com/docs/5.0/examples/checkout/">
    <!-- Bootstrap core CSS -->
    <link href="../assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <style>
      .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
      }

      @media (min-width: 768px) {
        .bd-placeholder-img-lg {
          font-size: 3.5rem;
        }
      }
      
    </style>


    <!-- Custom styles for this template -->
    <link href="../assets/css/form-validation.css" rel="stylesheet">
    <script defer src = "../assets/js/form-validation.js"></script>
  </head>
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
  <body class="bg-light">
    
<div class="container">
  <main>
    <div class="py-5 text-center">
    <hr class="my-4">
    <hr class="my-4">
      <h2>Checkout</h2>
      <p class="lead">You are one step away from booking your room <?php echo $_SESSION['fullname']; ?> <br> Please fill up the payment form to process your reservation.</p>
      
      <?php
						if(isset($errMsg)){
							echo '<div style="color:#FF0000;text-align:center;font-size:17px;">'.$errMsg.'</div>';
						}
					?>
    </div>

    <div class="row g-5">
      <div class="col-md-5 col-lg-4 order-md-last">
        <h4 class="d-flex justify-content-between align-items-center mb-3">
          <span class="text-primary">Your Reservation</span>
        </h4>
        <ul class="list-group mb-3">
          <li class="list-group-item d-flex justify-content-between lh-sm">
            <div>
              <h5 class="my-0">Place type</h5>
              <h6 class="my-1"> <?php if(isset($value['act'])=='Room Reservation')
                          { echo "Individual House/Apartment Reservation"; }
                          else
                          { echo "Room Reservation"; } ?> </h6>
              
            </div>
            <span class="text-muted"><?php print_r ($rent[0]); ?> USD</span>
          </li>

      </div>
      <div class="col-md-7 col-lg-8">
        <h4 class="mb-3">Billing address</h4>
        <form class="needs-validation" action="" method="post">
          <div class="row g-3">
            <div class="col-12" style="margin-bottom: 10px;">
              <label for="fullName" class="form-label">Full Name</label>
              <input type="text" class="form-control" id="fullName" name="fullname" placeholder="" value="" required>
              <div class="invalid-feedback">
                Valid first name is required.
              </div>
            </div>

            <div class="col-6" style="margin-bottom: 10px;">
              <label for="username" class="form-label">Username</label>
              <div class="input-group has-validation">
                <input type="text" class="form-control" id="username"  name="username" placeholder="Username" required>
              <div class="invalid-feedback">
                  Your username is required.
                </div>
              </div>
            </div>

            <div class="col-md-6">
            <label for="mobile" class="form-label">Mobile</label><span class="text-muted"> (Use country code as well)</span>
              <input type="text" class="form-control" id="mobile" placeholder="00970..."  name="mobile" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" required>
              <div class="invalid-feedback">
                Mobile phone number required.
              </div>
            </div>

            <div class="col-12" style="margin-bottom: 10px;">
              <label for="email" class="form-label">Email </label>
              <input type="email" class="form-control" id="email" placeholder="you@example.com" name="email"  pattern="[a-zA-Z]{3,}@[a-zA-Z]{3,}[.]{1}[a-zA-Z]{2,}" required>
              <div class="invalid-feedback">
                Please enter a valid email address for shipping updates.
              </div>
            </div>

            <div class="col-12" style="margin-bottom: 10px;">
              <label for="address" class="form-label">Address</label>
              <input type="text" class="form-control" id="address"  name="address" placeholder="1234 Main St" required>
              <div class="invalid-feedback">
                Please enter your shipping address.
              </div>
            </div>

            <div class="col-12" style="margin-bottom: 10px;">
              <label for="address2" class="form-label">Address 2 <span class="text-muted">(Optional)</span></label>
              <input type="text" class="form-control" id="address2"  name="address2" placeholder="Apartment or suite">
            </div>

            <div class="col-md-5">
            <label for="country" class="form-label">Country</label>
            
            <select id="country" name="country">
                <option value="Afganistan">Afghanistan</option>
                <option value="Albania">Albania</option>
                <option value="Algeria">Algeria</option>
                <option value="American Samoa">American Samoa</option>
                <option value="Andorra">Andorra</option>
                <option value="Angola">Angola</option>
                <option value="Anguilla">Anguilla</option>
                <option value="Antigua & Barbuda">Antigua & Barbuda</option>
                <option value="Argentina">Argentina</option>
                <option value="Armenia">Armenia</option>
                <option value="Aruba">Aruba</option>
                <option value="Australia">Australia</option>
                <option value="Austria">Austria</option>
                <option value="Azerbaijan">Azerbaijan</option>
                <option value="Bahamas">Bahamas</option>
                <option value="Bahrain">Bahrain</option>
                <option value="Bangladesh">Bangladesh</option>
                <option value="Barbados">Barbados</option>
                <option value="Belarus">Belarus</option>
                <option value="Belgium">Belgium</option>
                <option value="Belize">Belize</option>
                <option value="Benin">Benin</option>
                <option value="Bermuda">Bermuda</option>
                <option value="Bhutan">Bhutan</option>
                <option value="Bolivia">Bolivia</option>
                <option value="Bonaire">Bonaire</option>
                <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
                <option value="Botswana">Botswana</option>
                <option value="Brazil">Brazil</option>
                <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                <option value="Brunei">Brunei</option>
                <option value="Bulgaria">Bulgaria</option>
                <option value="Burkina Faso">Burkina Faso</option>
                <option value="Burundi">Burundi</option>
                <option value="Cambodia">Cambodia</option>
                <option value="Cameroon">Cameroon</option>
                <option value="Canada">Canada</option>
                <option value="Canary Islands">Canary Islands</option>
                <option value="Cape Verde">Cape Verde</option>
                <option value="Cayman Islands">Cayman Islands</option>
                <option value="Central African Republic">Central African Republic</option>
                <option value="Chad">Chad</option>
                <option value="Channel Islands">Channel Islands</option>
                <option value="Chile">Chile</option>
                <option value="China">China</option>
                <option value="Christmas Island">Christmas Island</option>
                <option value="Cocos Island">Cocos Island</option>
                <option value="Colombia">Colombia</option>
                <option value="Comoros">Comoros</option>
                <option value="Congo">Congo</option>
                <option value="Cook Islands">Cook Islands</option>
                <option value="Costa Rica">Costa Rica</option>
                <option value="Cote DIvoire">Cote DIvoire</option>
                <option value="Croatia">Croatia</option>
                <option value="Cuba">Cuba</option>
                <option value="Curaco">Curacao</option>
                <option value="Cyprus">Cyprus</option>
                <option value="Czech Republic">Czech Republic</option>
                <option value="Denmark">Denmark</option>
                <option value="Djibouti">Djibouti</option>
                <option value="Dominica">Dominica</option>
                <option value="Dominican Republic">Dominican Republic</option>
                <option value="East Timor">East Timor</option>
                <option value="Ecuador">Ecuador</option>
                <option value="Egypt">Egypt</option>
                <option value="El Salvador">El Salvador</option>
                <option value="Equatorial Guinea">Equatorial Guinea</option>
                <option value="Eritrea">Eritrea</option>
                <option value="Estonia">Estonia</option>
                <option value="Ethiopia">Ethiopia</option>
                <option value="Falkland Islands">Falkland Islands</option>
                <option value="Faroe Islands">Faroe Islands</option>
                <option value="Fiji">Fiji</option>
                <option value="Finland">Finland</option>
                <option value="France">France</option>
                <option value="French Guiana">French Guiana</option>
                <option value="French Polynesia">French Polynesia</option>
                <option value="French Southern Ter">French Southern Ter</option>
                <option value="Gabon">Gabon</option>
                <option value="Gambia">Gambia</option>
                <option value="Georgia">Georgia</option>
                <option value="Germany">Germany</option>
                <option value="Ghana">Ghana</option>
                <option value="Gibraltar">Gibraltar</option>
                <option value="Great Britain">Great Britain</option>
                <option value="Greece">Greece</option>
                <option value="Greenland">Greenland</option>
                <option value="Grenada">Grenada</option>
                <option value="Guadeloupe">Guadeloupe</option>
                <option value="Guam">Guam</option>
                <option value="Guatemala">Guatemala</option>
                <option value="Guinea">Guinea</option>
                <option value="Guyana">Guyana</option>
                <option value="Haiti">Haiti</option>
                <option value="Hawaii">Hawaii</option>
                <option value="Honduras">Honduras</option>
                <option value="Hong Kong">Hong Kong</option>
                <option value="Hungary">Hungary</option>
                <option value="Iceland">Iceland</option>
                <option value="Indonesia">Indonesia</option>
                <option value="India">India</option>
                <option value="Iran">Iran</option>
                <option value="Iraq">Iraq</option>
                <option value="Ireland">Ireland</option>
                <option value="Isle of Man">Isle of Man</option>
                <option value="Italy">Italy</option>
                <option value="Jamaica">Jamaica</option>
                <option value="Japan">Japan</option>
                <option value="Jordan">Jordan</option>
                <option value="Kazakhstan">Kazakhstan</option>
                <option value="Kenya">Kenya</option>
                <option value="Kiribati">Kiribati</option>
                <option value="Korea North">Korea North</option>
                <option value="Korea Sout">Korea South</option>
                <option value="Kuwait">Kuwait</option>
                <option value="Kyrgyzstan">Kyrgyzstan</option>
                <option value="Laos">Laos</option>
                <option value="Latvia">Latvia</option>
                <option value="Lebanon">Lebanon</option>
                <option value="Lesotho">Lesotho</option>
                <option value="Liberia">Liberia</option>
                <option value="Libya">Libya</option>
                <option value="Liechtenstein">Liechtenstein</option>
                <option value="Lithuania">Lithuania</option>
                <option value="Luxembourg">Luxembourg</option>
                <option value="Macau">Macau</option>
                <option value="Macedonia">Macedonia</option>
                <option value="Madagascar">Madagascar</option>
                <option value="Malaysia">Malaysia</option>
                <option value="Malawi">Malawi</option>
                <option value="Maldives">Maldives</option>
                <option value="Mali">Mali</option>
                <option value="Malta">Malta</option>
                <option value="Marshall Islands">Marshall Islands</option>
                <option value="Martinique">Martinique</option>
                <option value="Mauritania">Mauritania</option>
                <option value="Mauritius">Mauritius</option>
                <option value="Mayotte">Mayotte</option>
                <option value="Mexico">Mexico</option>
                <option value="Midway Islands">Midway Islands</option>
                <option value="Moldova">Moldova</option>
                <option value="Monaco">Monaco</option>
                <option value="Mongolia">Mongolia</option>
                <option value="Montserrat">Montserrat</option>
                <option value="Morocco">Morocco</option>
                <option value="Mozambique">Mozambique</option>
                <option value="Myanmar">Myanmar</option>
                <option value="Nambia">Nambia</option>
                <option value="Nauru">Nauru</option>
                <option value="Nepal">Nepal</option>
                <option value="Netherland Antilles">Netherland Antilles</option>
                <option value="Netherlands">Netherlands (Holland, Europe)</option>
                <option value="Nevis">Nevis</option>
                <option value="New Caledonia">New Caledonia</option>
                <option value="New Zealand">New Zealand</option>
                <option value="Nicaragua">Nicaragua</option>
                <option value="Niger">Niger</option>
                <option value="Nigeria">Nigeria</option>
                <option value="Niue">Niue</option>
                <option value="Norfolk Island">Norfolk Island</option>
                <option value="Norway">Norway</option>
                <option value="Oman">Oman</option>
                <option value="Pakistan">Pakistan</option>
                <option value="Palau Island">Palau Island</option>
                <option value="Palestine">Palestine</option>
                <option value="Panama">Panama</option>
                <option value="Papua New Guinea">Papua New Guinea</option>
                <option value="Paraguay">Paraguay</option>
                <option value="Peru">Peru</option>
                <option value="Phillipines">Philippines</option>
                <option value="Pitcairn Island">Pitcairn Island</option>
                <option value="Poland">Poland</option>
                <option value="Portugal">Portugal</option>
                <option value="Puerto Rico">Puerto Rico</option>
                <option value="Qatar">Qatar</option>
                <option value="Republic of Montenegro">Republic of Montenegro</option>
                <option value="Republic of Serbia">Republic of Serbia</option>
                <option value="Reunion">Reunion</option>
                <option value="Romania">Romania</option>
                <option value="Russia">Russia</option>
                <option value="Rwanda">Rwanda</option>
                <option value="St Barthelemy">St Barthelemy</option>
                <option value="St Eustatius">St Eustatius</option>
                <option value="St Helena">St Helena</option>
                <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                <option value="St Lucia">St Lucia</option>
                <option value="St Maarten">St Maarten</option>
                <option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
                <option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
                <option value="Saipan">Saipan</option>
                <option value="Samoa">Samoa</option>
                <option value="Samoa American">Samoa American</option>
                <option value="San Marino">San Marino</option>
                <option value="Sao Tome & Principe">Sao Tome & Principe</option>
                <option value="Saudi Arabia">Saudi Arabia</option>
                <option value="Senegal">Senegal</option>
                <option value="Seychelles">Seychelles</option>
                <option value="Sierra Leone">Sierra Leone</option>
                <option value="Singapore">Singapore</option>
                <option value="Slovakia">Slovakia</option>
                <option value="Slovenia">Slovenia</option>
                <option value="Solomon Islands">Solomon Islands</option>
                <option value="Somalia">Somalia</option>
                <option value="South Africa">South Africa</option>
                <option value="Spain">Spain</option>
                <option value="Sri Lanka">Sri Lanka</option>
                <option value="Sudan">Sudan</option>
                <option value="Suriname">Suriname</option>
                <option value="Swaziland">Swaziland</option>
                <option value="Sweden">Sweden</option>
                <option value="Switzerland">Switzerland</option>
                <option value="Syria">Syria</option>
                <option value="Tahiti">Tahiti</option>
                <option value="Taiwan">Taiwan</option>
                <option value="Tajikistan">Tajikistan</option>
                <option value="Tanzania">Tanzania</option>
                <option value="Thailand">Thailand</option>
                <option value="Togo">Togo</option>
                <option value="Tokelau">Tokelau</option>
                <option value="Tonga">Tonga</option>
                <option value="Trinidad & Tobago">Trinidad & Tobago</option>
                <option value="Tunisia">Tunisia</option>
                <option value="Turkey">Turkey</option>
                <option value="Turkmenistan">Turkmenistan</option>
                <option value="Turks & Caicos Is">Turks & Caicos Is</option>
                <option value="Tuvalu">Tuvalu</option>
                <option value="Uganda">Uganda</option>
                <option value="United Kingdom">United Kingdom</option>
                <option value="Ukraine">Ukraine</option>
                <option value="United Arab Erimates">United Arab Emirates</option>
                <option value="United States of America">United States of America</option>
                <option value="Uraguay">Uruguay</option>
                <option value="Uzbekistan">Uzbekistan</option>
                <option value="Vanuatu">Vanuatu</option>
                <option value="Vatican City State">Vatican City State</option>
                <option value="Venezuela">Venezuela</option>
                <option value="Vietnam">Vietnam</option>
                <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                <option value="Wake Island">Wake Island</option>
                <option value="Wallis & Futana Is">Wallis & Futana Is</option>
                <option value="Yemen">Yemen</option>
                <option value="Zaire">Zaire</option>
                <option value="Zambia">Zambia</option>
                <option value="Zimbabwe">Zimbabwe</option>
              </select>
            </div>

            <div class="col-md-4">
            <label for="state" class="form-label">State</label>
              <input type="text" class="form-control" id="state"  name="state" placeholder="" required>
              <div class="invalid-feedback">
                State Required
              </div>
            </div>

            <div class="col-md-3">
              <label for="zip" class="form-label">Zip</label>
              <input type="text" class="form-control" id="zip"  placeholder=""  name="zip"  oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" minlength="5" maxlength="7" required>
              <div class="invalid-feedback">
                Zip code required.
              </div>
            </div>
          </div>

          <hr class="my-4">

          <div class="col-6">
          <div class="form-group">
              <label>Check-In</label>
              <input name="cin" id="cin" type ="date" class="form-control">
          </div>
          </div>
					<div class="col-md-6">		  
          <div class="form-group">
                  <label>Check-Out</label>
                  <input name="cout" type ="date" id="cout" class="form-control">
              </div>
              </div>

          <hr class="my-4">

          <h4 class="mb-3">Payment</h4>

          <div class="my-3">
            <div class="form-check">
              <input id="credit" name="paymentMethod" type="radio" class="form-check-input" checked required>
              <label class="form-check-label" for="credit">Credit card</label>
            </div>
            <div class="form-check">
              <input id="debit" name="paymentMethod" type="radio" class="form-check-input" required>
              <label class="form-check-label" for="debit">Debit card</label>
            </div>
          </div>

          <div class="row gy-3">
            <div class="col-md-6"  style="margin-bottom: 20px;">
              <label for="cc-name" class="form-label">Name on card</label>
              <input type="text" class="form-control" id="cc-name" placeholder="" required>
              <small class="text-muted">Full name as displayed on card</small>
              <div class="invalid-feedback">
                Name on card is required
              </div>
            </div>

            <div class="col-md-6">
              <label for="cc-number" class="form-label">Credit card number</label>
              <input type="text" class="form-control" id="cc-number" minlength="16" maxlength="16" placeholder="Enter 16 digits" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" pattern="[0-9]{4}[0-9]{4}[0-9]{4}[0-9]{4}" required>
              <div class="invalid-feedback">
                Credit card number is invalid
              </div>
            </div>
            <br> 
            <br>
            <div class="col-md-4" id="expiration-date">
                <label>Expiration Date</label>
                <select>
                    <option value="01">January</option>
                    <option value="02">February </option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
                <select>
                    <option value="16"> 2021</option>
                    <option value="17"> 2022</option>
                    <option value="18"> 2023</option>
                    <option value="19"> 2024</option>
                    <option value="20"> 2025</option>
                    <option value="21"> 2026</option>
                </select>
            </div>

            <div class="col-md-3">
              <label for="cc-cvv" class="form-label">CVV</label>
              <input type="password" class="form-control" id="cc-cvv" minlength="3" maxlength="4" placeholder="Enter 3-4 digits" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" pattern="[0-9]{3,4}" required>
              <div class="invalid-feedback">
                Security code required
              </div>
            </div>
          </div>

          <hr class="my-4">

            <button class="w-100 btn btn-primary btn-lg" type="submit" name='checkout' value="checkout" >Checkout</button>
         </form>
          
      </div>
    </div>
  </main>

  <footer style="background-color: #ccc;">
      <div class="container">
        <div class="row">
          <div class="col-md-4">
            <span class="copyright">Copyright &copy; Crash In 2021</span>
          </div>
          <div class="col-md-4">
            <ul class="list-inline social-buttons">
              <li class="list-inline-item">
                <a href="#">
                  <i class="fa fa-twitter"></i>
                </a>
              </li>
              <li class="list-inline-item">
                <a href="#">
                  <i class="fa fa-facebook"></i>
                </a>
              </li>
              <li class="list-inline-item">
                <a href="#">
                  <i class="fa fa-linkedin"></i>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </footer>
</div>


    <script src="../assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

      <script src="../assets/js/form-validation.js"></script>
  </body>
</html>

<?php include '../include/footer.php';?>
