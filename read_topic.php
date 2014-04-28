<?php
error_reporting(E_ALL ^ E_DEPRECATED);
//This page display a topic
include('config.php');
if(isset($_GET['id']))
{
	$id = intval($_GET['id']);
	$dn1 = mysql_fetch_array(mysql_query('select count(t.id) as nb1, t.title, t.parent, count(t2.id) as nb2, c.name from topics as t, topics as t2, categories as c where t.id="'.$id.'" and t.id2=1 and t2.id="'.$id.'" and c.id=t.parent group by t.id'));
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
        <title><?php echo htmlentities($dn1['title'], ENT_QUOTES, 'UTF-8'); ?> - <?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?> - Forum</title>
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
    	<a href="<?php echo $url_home; ?>">Home</a> &gt; <a href="list_topics.php?parent=<?php echo $dn1['parent']; ?>"><?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?></a> &gt; <a href="read_topic.php?id=<?php echo $id; ?>"><?php echo htmlentities($dn1['title'], ENT_QUOTES, 'UTF-8'); ?></a> &gt; Read the Mind Thought
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
<div class="panel panel-info">
	<div class="panel panel-info">
    	<a href="<?php echo $url_home; ?>">Home</a> &gt; <a href="list_topics.php?parent=<?php echo $dn1['parent']; ?>"><?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?></a> &gt; <a href="read_topic.php?id=<?php echo $id; ?>"><?php echo htmlentities($dn1['title'], ENT_QUOTES, 'UTF-8'); ?></a> &gt; Read the Mind Thought
    </div>
	<div class="panel panel-info">
    	<a href="signup.php">Sign Up</a> - <a href="login.php">Login</a>
    </div>
    <div class="panel panel-info"></div>
</div>
<?php
}
?>
<h1><?php echo $dn1['title']; ?></h1>
<?php
if(isset($_SESSION['username']))
{
?>
	<a href="new_reply.php?id=<?php echo $id; ?>" class="btn btn-success">Reply</a>
<?php
}
$dn2 = mysql_query('select t.id2, t.authorid, t.message, t.timestamp, u.username as author, u.avatar from topics as t, users as u where t.id="'.$id.'" and u.id=t.authorid order by t.timestamp asc');
?>
<table class="messages_table">
	<tr>
    	<th class="author">Author</th>
    	<th>Message</th>
	</tr>
<?php
while($dnn2 = mysql_fetch_array($dn2))
{
?>
	<tr>
    	<td class="author center"><?php
if($dnn2['avatar']!='')
{
	echo '<img src="'.htmlentities($dnn2['avatar']).'" alt="Image Perso" style="max-width:100px;max-height:100px;" />';
}
?><br /><a href="profile.php?id=<?php echo $dnn2['authorid']; ?>"><?php echo $dnn2['author']; ?></a></td>
    	<td class="left"><?php if(isset($_SESSION['username']) and ($_SESSION['username']==$dnn2['author'] or $_SESSION['username']==$admin)){ ?><div class="edit"><a href="edit_message.php?id=<?php echo $id; ?>&id2=<?php echo $dnn2['id2']; ?>"><img src="<?php echo $design; ?>/images/edit.png" alt="Edit" /></a></div><?php } ?><div class="date">Date sent: <?php echo date('Y/m/d H:i:s' ,$dnn2['timestamp']); ?></div>
        <div class="clean"></div>
    	<?php echo $dnn2['message']; ?></td>
    </tr>
<?php
}
?>
</table>
<?php
if(isset($_SESSION['username']))
{
?>
	<a href="new_reply.php?id=<?php echo $id; ?>" class="btn btn-success">Reply</a>
<?php
}
else
{
?>

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
	echo '<h2>This Mind Thought doesn\'t exist.</h2>';
}
}
else
{
	echo '<h2>The ID of this Mind Thought Post is not defined.</h2>';
}
?>
 <div id="footer">
      <div class="container">
        <p class="text-muted">Speak Your Mind</p>
      </div>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="js/bootstrap.min.js"></script>