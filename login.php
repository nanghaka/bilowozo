
<?php
error_reporting(E_ALL ^ E_DEPRECATED);
//This page let log in
include('config.php');
if(isset($_SESSION['username']))
{
	unset($_SESSION['username'], $_SESSION['userid']);
	setcookie('username', '', time()-100);
	setcookie('password', '', time()-100);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <link rel="stylesheet" href="blog.css" type="text/css">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <title>Speak Your Mind</title>
        <title>Login - Speak Your Mind</title>
    </head>
    <body>
<!-- Fixed navbar -->
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Speak Your Mind</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="register">Register</a></li>
            <li><a href="login">Login</a></li>
            <li class="active"><a href="#">Home</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>


<div class="alert alert-info">You have successfully been logged out.<br />
<a href="<?php echo $url_home; ?>">Home</a></div>
<?php
}
else
{
	$ousername = '';
	if(isset($_POST['username'], $_POST['password']))
	{
		if(get_magic_quotes_gpc())
		{
			$ousername = stripslashes($_POST['username']);
			$username = mysql_real_escape_string(stripslashes($_POST['username']));
			$password = stripslashes($_POST['password']);
		}
		else
		{
			$username = mysql_real_escape_string($_POST['username']);
			$password = $_POST['password'];
		}
		$req = mysql_query('select password,id from users where username="'.$username.'"');
		$dn = mysql_fetch_array($req);
		if($dn['password']==sha1($password) and mysql_num_rows($req)>0)
		{
			$form = false;
			$_SESSION['username'] = $_POST['username'];
			$_SESSION['userid'] = $dn['id'];
			if(isset($_POST['memorize']) and $_POST['memorize']=='yes')
			{
				$one_year = time()+(60*60*24*365);
				setcookie('username', $_POST['username'], $one_year);
				setcookie('password', sha1($password), $one_year);
			}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <link rel="stylesheet" href="blog.css" type="text/css">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <title>Login</title>
    </head>
    <body>
    	<div class="header">
        	<a href="<?php echo $url_home; ?>"><img src="<?php echo $design; ?>/images/logo.png" alt="Forum" /></a>
	    </div>
<div class="alert alert-success">You have successfully been logged in<br />
<a href="<?php echo $url_home; ?>">Home</a></div>
<?php
		}
		else
		{
			$form = true;
			$message = 'The username or password you have entered is incorrect.';
		}
	}
	else
	{
		$form = true;
	}
	if($form)
	{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <link rel="stylesheet" href="blog.css" type="text/css">
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <title>Login</title>
    </head>
    <body>

    	    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Speak Your Mind</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="register">Register</a></li>
            <li><a href="login">Login</a></li>
            <li class="active"><a href="#">Home</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>
<form class="form-signin" role="form" action="login.php" method="post">
        <h2 class="form-signin-heading">Login in</h2>
        <input type="text" class="form-control" placeholder="User Name" name="username" id="username" required autofocus>
        <input type="password" class="form-control" placeholder="Password" name="password" id="password" required>
        <label class="checkbox">
          <input type="checkbox" value="remember-me" name="memorize" id="memorize" value="yes"> Remember me</label>
        <button class="btn btn-lg btn-primary btn-block" type="submit" value="Login">Login</button>
        <p><h3>Or Register</p>
        <a href="signup.php"><button class="btn btn-lg btn-primary btn-block">Register</button></a>
              </form>
<?php
	}
}
?>
 <div id="footer">
      <div class="container">
        <p class="text-muted">Speak Your Mind</p>
      </div>
    </div>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
            </body>
		
	</body>
</html>