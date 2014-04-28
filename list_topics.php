<?php
error_reporting(E_ALL ^ E_DEPRECATED);
//This page let display the list of topics of a category
include('config.php');
if(isset($_GET['parent']))
{
	$id = intval($_GET['parent']);
	$dn1 = mysql_fetch_array(mysql_query('select count(c.id) as nb1, c.name,count(t.id) as topics from categories as c left join topics as t on t.parent="'.$id.'" where c.id="'.$id.'" group by c.id'));
if($dn1['nb1']>0)
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <link rel="stylesheet" href="blog.css" type="text/css">
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <title><?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?> - Forum</title>
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
$nb_new_pm = mysql_fetch_array(mysql_query('select count(*) as nb_new_pm from pm where ((user1="'.$_SESSION['userid'].'" and user1read="no") or (user2="'.$_SESSION['userid'].'" and user2read="no")) and id2="1"'));
$nb_new_pm = $nb_new_pm['nb_new_pm'];
?>
<div class="breadcrumb">
	<div class="box_left">
    	<a href="<?php echo $url_home; ?>">Home</a> &gt; <a href="list_topics.php?parent=<?php echo $id; ?>"><?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?></a>
    </div>
	<div class="box_right">
    	<a href="list_pm.php">Your messages(<?php echo $nb_new_pm; ?>)</a> - <a href="profile.php?id=<?php echo $_SESSION['userid']; ?>"><?php echo htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></a> (<a href="login.php">Logout</a>)
    </div>
	<div class="clean"></div>
</div>
<?php
}
else
{
?>
<div class="box">
	<div class="box_left">
    	<a href="<?php echo $url_home; ?>">Home</a> &gt; <a href="list_topics.php?parent=<?php echo $id; ?>"><?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?></a>
    </div>
	<div class="box_right">
    	<a href="signup.php">Sign Up</a> - <a href="login.php">Login</a>
    </div>
	<div class="clean"></div>
</div>
<?php
}
if(isset($_SESSION['username']))
{
?>
	<a href="new_topic.php?parent=<?php echo $id; ?>" class="btn btn-success">New Mind Thought</a>
<?php
}
$dn2 = mysql_query('select t.id, t.title, t.authorid, u.username as author, count(r.id) as replies from topics as t left join topics as r on r.parent="'.$id.'" and r.id=t.id and r.id2!=1  left join users as u on u.id=t.authorid where t.parent="'.$id.'" and t.id2=1 group by t.id order by t.timestamp2 desc');
if(mysql_num_rows($dn2)>0)
{
?>
<table class="topics_table">
	<tr>
    	<th class="forum_tops">Mind Thought</th>
    	<th class="forum_auth">Author</th>
    	<th class="forum_nrep">Replies</th>
<?php
if(isset($_SESSION['username']) and $_SESSION['username']==$admin)
{
?>
    	<th class="forum_act">Action</th>
<?php
}
?>
	</tr>
<?php
while($dnn2 = mysql_fetch_array($dn2))
{
?>
	<tr>
    	<td class="forum_tops"><a href="read_topic.php?id=<?php echo $dnn2['id']; ?>"><?php echo htmlentities($dnn2['title'], ENT_QUOTES, 'UTF-8'); ?></a></td>
    	<td><a href="profile.php?id=<?php echo $dnn2['authorid']; ?>"><?php echo htmlentities($dnn2['author'], ENT_QUOTES, 'UTF-8'); ?></a></td>
    	<td><?php echo $dnn2['replies']; ?></td>
<?php
if(isset($_SESSION['username']) and $_SESSION['username']==$admin)
{
?>
    	<td><a href="delete_topic.php?id=<?php echo $dnn2['id']; ?>"><img src="<?php echo $design; ?>/images/delete.png" alt="Delete" /></a></td>
<?php
}
?>
    </tr>
<?php
}
?>
</table>
<?php
}
else
{
?>
<div class="message">This category has no Mind Thought.</div>
<?php
}
if(isset($_SESSION['username']))
{
?>
	<a href="new_topic.php?parent=<?php echo $id; ?>" class="btn btn-success">New Topic</a>
<?php
}
else
{
?>
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
		</div>
		</body>
</html>
<?php
}
else
{
	echo '<h2>This category doesn\'t exist.</h2>';
}
}
else
{
	echo '<h2>The ID of the category you want to visit is not defined.</h2>';
}
?>

 <div id="footer">
      <div class="container">
        <p class="text-muted">Speak Your Mind</p>
      </div>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>