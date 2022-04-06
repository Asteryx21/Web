<?php include('functions.php') ?>
<!DOCTYPE html>
<html>
<head>
	<title>Register Page</title>
	<link rel="stylesheet" type="text/css" href="register_style.css">
</head>
<body>

	<div class="login-box">
				  <img src="avatar.png" class="avatar">
	<h1>Sign Up</h1>
		<form method="post" action="register.php">

				<p>Username</p>
				<input type="text" placeholder="Enter username" name="username" value="<?php echo $username; ?>">

				<p>Email</p>
				<input type="email" placeholder="Enter email" name="email" value="<?php echo $email; ?>">

				<p>Password</p>
				<input type="password" placeholder="Enter password" name="password_1" pattern="(?=.*\d)(?=.*[#$*&@])(?=.*[A-Z]).{8,}" title="Must contain at least one number, one uppercase letter, one character [E.g. #$*&@], and at least 8 or more characters" required>


				<p>Confirm password</p>
				<input type="password" placeholder="Confirm password" name="password_2" pattern="(?=.*\d)(?=.*[#$*&@])(?=.*[A-Z]).{8,}" title="Must contain at least one number, one uppercase letter, one character [E.g. #$*&@], and at least 8 or more characters" required>

				<button type="submit" class="btn" name="register_btn">Register</button>
				<?php echo display_error(); ?>
			<p class="one">
				Already a member? <a href="login.php">Sign in!</a>
			</p>
		</form>
	</div>

</body>
</html>
