<?php
/*
AUTHOR: Messiz Qin
GITHUB: https://github.com/Weilory
PROJECT: PetsKeepers
*/

// ajax action

	require_once('../php/db_connect.php');
	require_once('../php/users.php');
	require_once('../php/PHPMailerAutoload.php');
	require_once('../php/credential.php');
	require_once('../php/inverse.php');
	require_once('../php/sess.php');

	if(isset($_POST['action']) && $_POST['action'] == 'checkCookie'){
		if(isset($_COOKIE['PetKeepersemail'], $_COOKIE['PetKeeperspassword'])){
			$data = ['email' => $_COOKIE['PetKeepersemail'], 'password' => base64_decode($_COOKIE['PetKeeperspassword'])];
			echo json_encode($data);
		}else{
			echo json_encode(array('email'=>'', 'password'=>''));
		}
	}

	if(isset($_POST['action']) && $_POST['action'] == 'register'){
		$db = new DbConnect();
		if(!$db->validate_existing_email(dequote(json_encode($_POST['email'])))){
			echo 'Email Already Registered';
			exit();
		}
		
		$objUser = new Users();
		$objUser->setFirst_name(dequote(json_encode($_POST['first_name'])));
		$objUser->setLast_name(dequote(json_encode($_POST['last_name'])));
		$objUser->setEmail(dequote(json_encode($_POST['email'])));
		$objUser->setPassword(md5(dequote(json_encode($_POST['password']))));
		$objUser->setPhone(dequote(json_encode($_POST['phone'])));
		$objUser->setActivated(0);
		$objUser->setToken(NULL);
		$objUser->setCreated_on(date('Y-m-d'));
		if($objUser->save()){
			$lastId = $objUser->conn->lastInsertId();
			$token = sha1($lastId);

			$url = Inverse::phpd('verify') . '?id=' . $lastId . '&token=' . $token;

			$html = "<div>Thank you for registering with PetKeepers, please click following link to complete registration: <br />" . $url . "</div>";

			$mail = new PHPMailer;

			$mail->isSMTP();                                      // Set mailer to use SMTP
			$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
			$mail->SMTPAuth = true;                               // Enable SMTP authentication
			$mail->Username = EMAIL;                 // SMTP username
			$mail->Password = PASS;                           // SMTP password
			$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
			$mail->Port = 587;                                    // TCP port to connect to

			$mail->setFrom(EMAIL, 'PetKeepers');
			$mail->addAddress($objUser->getEmail());     // Add a recipient

			$mail->addReplyTo(EMAIL);
			
			$mail->isHTML(true);                                  // Set email format to HTML

			$mail->Subject = 'Confirm your email';
			$mail->Body    = $html;

			if(!$mail->send()) {
			    echo 'Message could not be sent.';
			    echo 'Mailer Error: ' . $mail->ErrorInfo;
			} else {
			    echo "Succeeded, please verify through your email";
			}

		}else{
			echo "Failed";
		}
	}

	if(isset($_POST['action']) && $_POST['action'] == 'login'){
		$objUser = new Users();
		$objUser->setEmail(dequote(json_encode($_POST['email'])));
		$objUser->setPassword(md5(dequote(json_encode($_POST['password']))));
		$userData = $objUser->getUserByEmail();
		if(is_array($userData) && count($userData) > 0){
			if($userData['password'] == $objUser->getPassword()){
				if($userData['activated'] == 1){
					if($userData['admin'] == 1){
						// this part is called by jQuery.ajax in services.js.php
						// following code will be only executed if the user is verified as an administer. 
						// in order to to prevent direct url access by non admin user, I set the session here as the user is verified. 
						// in corresponding, in admin.php, if there is no session set, display an forbidden error;  
						$session_handler = new Sess();
						session_start();
						$_SESSION['admin'] = array('redirect' => 1);
						// return an absolute url to services.js.php, for redirecting. 
						echo json_encode(['status' => 1, 'msg' => 'redirect', 'path' => Inverse::root() . '/php/admin.php']);
					}else{
						setcookie('PetKeepersemail', $objUser->getEmail());
						setcookie('PetKeeperspassword', base64_encode(dequote(json_encode($_POST['password']))));
						echo json_encode(['status' => 1, 'msg' => 'login successful', 'id' => $userData['id']]);
					}
				}else{
					echo json_encode(['status' => 0, 'msg' => "Please verify your email to activate your account"]);
				}
			}else{
				echo json_encode(['status' => 0, 'msg' => "Email or Password incorrect"]);
			}
		}else{
			echo json_encode(['status' => 0, 'msg' => "User does not exit"]);
		}
	}

	if(isset($_POST['action']) && $_POST['action'] == 'resetPass'){
		$db = new DBConnect();
		$ext = $db->validate_existing_email($_POST['email']);
		if(!$ext){
			$users_obj = new Users();
			$users_obj->setEmail($_POST['email']);
			$userData = $users_obj->getUserByEmail();
			$data['id'] = $userData['id'];
			$data['token'] = sha1($userData['email']);
			// setting timezone is extremely important, since server may have different location
			date_default_timezone_set("Australia/Melbourne");
			// password reset link valid for two hours
			$data['expTime'] = date('d-m-Y h:i:s A', time() + (60*60*2));
			$urlToken = base64_encode(json_encode($data));
			$users_obj->setId($userData['id']);
			$users_obj->setToken($data['token']);
			if($users_obj->updateToken()){
				$url = Inverse::phpd('reset') . '?token=' . $urlToken;
				$html = '<div>You have requested a password reset for your user account at the PetsKeepers, please click the following link:<hr />' . $url . '<br /><br />note this link is only valid for <b>2 hours</b></div>';

				$mail = new PHPMailer;

				$mail->isSMTP();                                      // Set mailer to use SMTP
				$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
				$mail->SMTPAuth = true;                               // Enable SMTP authentication

				$mail->Username = EMAIL;                 // SMTP username
				$mail->Password = PASS;                           // SMTP password
				$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
				$mail->Port = 587;                                    // TCP port to connect to

				$mail->setFrom(EMAIL, 'PetKeepers');
				$mail->addAddress($users_obj->getEmail());     // Add a recipient

				$mail->addReplyTo(EMAIL);
				
				$mail->isHTML(true);                                  // Set email format to HTML

				$mail->Subject = 'PetsKeepers Password Reset';
				$mail->Body    = $html;

				if(!$mail->send()) {
				    echo json_encode(['status' => 0, 'msg' => 'Mailer Error: ' . $mail->ErrorInfo]);
				} else {
				    echo json_encode(['status' => 1, 'msg' => "Succeed, please check your email"]);
				}

			}else{
				// echo json_encode(['status' => 0, 'msg' => "Failed to set token"]);
				echo json_encode(['status' => 0, 'msg' => "Sorry, verify failed"]);
			}
		}else{
			echo json_encode(['status' => 0, 'msg' => "This account doesn't exist"]);
		}
	}
?>
