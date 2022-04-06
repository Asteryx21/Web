<?php include('functions.php') ?>
<!DOCTYPE html>
<html>
<head>
	<title>Login Page</title>
	<link rel="stylesheet" type="text/css" href="login_style.css">
	
</head>
    
<body>
  
	 <div class="login-box">
		  <img src="avatar.png" class="avatar">
			<h1>Sign In</h1>			
	
	   <form method="post" action="login.php">
	
			<p>Username</p>
			<input type="text" placeholder ="Enter username" name="username" >
	
			<p>Password</p>
			<input type="password" placeholder ="Enter password" name="password">
		
	       <?php echo display_error(); ?>
			
        <button type="submit" class="btn" name="login_btn">Login</button>
	   
		<p class="one">
			Not yet a member?
            <a href="register.php">Sign up!</a>
		</p>
        </form>	
    </div>
</body>



<script>
var span = document.querySelectorAll('.info');
for (var i = span.length; i--;) {
    (function () {
        var t;
        span[i].onmouseover = function () {
            hideAll();
            clearTimeout(t);
            this.className = 'infoHover';
        };
        span[i].onmouseout = function () {
            var self = this;
            t = setTimeout(function () {
                self.className = 'info';
            }, 300);
        };
    })();
}

function hideAll() {
    for (var i = span.length; i--;) {
        span[i].className = 'info'; 
    }
};
</script>

</html>