<?php
error_reporting(E_ALL ^ E_DEPRECATED);
//This page let create a new personnal message
include('config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <link rel="stylesheet" href="blog.css" type="text/css">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <title>New PM</title>
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
<?php
if(isset($_SESSION['username']))
{
$form = true;
$otitle = '';
$orecip = '';
$omessage = '';
if(isset($_POST['title'], $_POST['recip'], $_POST['message']))
{
	$otitle = $_POST['title'];
	$orecip = $_POST['recip'];
	$omessage = $_POST['message'];
	if(get_magic_quotes_gpc())
	{
		$otitle = stripslashes($otitle);
		$orecip = stripslashes($orecip);
		$omessage = stripslashes($omessage);
	}
	if($_POST['title']!='' and $_POST['recip']!='' and $_POST['message']!='')
	{
		$title = mysql_real_escape_string($otitle);
		$recip = mysql_real_escape_string($orecip);
		$message = mysql_real_escape_string(nl2br(htmlentities($omessage, ENT_QUOTES, 'UTF-8')));
		$dn1 = mysql_fetch_array(mysql_query('select count(id) as recip, id as recipid, (select count(*) from pm) as npm from users where username="'.$recip.'"'));
		if($dn1['recip']==1)
		{
			if($dn1['recipid']!=$_SESSION['userid'])
			{
				$id = $dn1['npm']+1;
				if(mysql_query('insert into pm (id, id2, title, user1, user2, message, timestamp, user1read, user2read)values("'.$id.'", "1", "'.$title.'", "'.$_SESSION['userid'].'", "'.$dn1['recipid'].'", "'.$message.'", "'.time().'", "yes", "no")'))
				{
	?>
	<div class="message">The PM have successfully been sent.<br />
	<a href="list_pm.php">List of your Personal Messages</a></div>
	<?php
					$form = false;
				}
				else
				{
					$error = 'An error occurred while sending the PM.';
				}
			}
			else
			{
				$error = 'You cannot send a PM to yourself.';
			}
		}
		else
		{
			$error = 'The recipient of your PM doesn\'t exist.';
		}
	}
	else
	{
		$error = 'A field is not filled.';
	}
}
elseif(isset($_GET['recip']))
{
	$orecip = $_GET['recip'];
}
if($form)
{
if(isset($error))
{
	echo '<div class="message">'.$error.'</div>';
}
?>
<div class="content">
<?php
$nb_new_pm = mysql_fetch_array(mysql_query('select count(*) as nb_new_pm from pm where ((user1="'.$_SESSION['userid'].'" and user1read="no") or (user2="'.$_SESSION['userid'].'" and user2read="no")) and id2="1"'));
$nb_new_pm = $nb_new_pm['nb_new_pm'];
?>
<div class="breadcrumb">
	<div class="box_left">
    	<a href="<?php echo $url_home; ?>">Home</a> &gt; <a href="list_pm.php">List of your Messages</a> &gt; New Message
    </div>
	<div class="box_right">
     	<a href="list_pm.php">Your messages(<?php echo $nb_new_pm; ?>)</a> - <a href="profile.php?id=<?php echo $_SESSION['userid']; ?>"><?php echo htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></a> (<a href="login.php">Logout</a>)
    </div>
    <div class="clean"></div>
</div>
	<h1>Send a New Message</h1>
    <form action="new_pm.php" method="post">
		Please fill this form to send a PM:<br />
        <label for="title" class="col-sm-2 control-label">Title</label><input type="text" class="form-control" placeholder="Title" value="<?php echo htmlentities($otitle, ENT_QUOTES, 'UTF-8'); ?>" id="title" name="title" /><br />
        <label for="recip" class="col-sm-2 control-label">Recipient<span class="small">(Username)</span></label><input type="text" class="form-control" placeholder="Username" value="<?php echo htmlentities($orecip, ENT_QUOTES, 'UTF-8'); ?>" id="recip" name="recip" /><br />
        <label for="message"class="col-sm-2 control-label">Message</label><textarea cols="20" rows="5" id="message" name="message" class="form-control"><?php echo htmlentities($omessage, ENT_QUOTES, 'UTF-8'); ?></textarea><br />
        <input type="submit" class="btn btn-default" value="Send" />
    </form>
</div>
<?php
}
}
else
{
?>
<div class="message">You must be logged to access this page.</div>
<form class="form-signin" role="form" action="login.php" method="post">
        <h2 class="form-signin-heading">Login in</h2>
        <input type="text" class="form-control" placeholder="User Name" name="username" id="username" required autofocus>
        <input type="password" class="form-control" placeholder="Password" name="password" id="password" required>
        <label class="checkbox">
          <input type="checkbox" value="remember-me" name="memorize" id="memorize" value="yes"> Remember me</label>
        <button class="btn btn-lg btn-primary btn-block" type="submit" value="Login">Login</button>
        <p><h3>Or Register</p>
        <a href="register"><button class="btn btn-lg btn-primary btn-block">Register</button></a>
              </form>
<?php
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
</html>