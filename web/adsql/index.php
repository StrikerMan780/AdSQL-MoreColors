<?php

/*
AdsqL - Server Advertisements System
by PharaohsPaw
http://www.pwng.net
2011

a FORK from original work by:


CommTools
by Gareth Clarke
June 2008

*/

include("include/session.php");
include("include/header.php");

?>

<table>
	<tr>
		<td><img src="images/spacer001.gif" height=150 width=1 alt="spacer"></td>
		<td><img src="images/spacer001.gif" height=1 width=100 alt="spacer"></td>
	</tr>

	<tr>
		<td align='right'><a href="index.php"><img src="images/default.jpg" alt="toplogo"></a></td><td rowspan=2>&nbsp;&nbsp;<img src="images/spacer002.gif" height=300 width=1 alt="spacer"></td>
	</tr>
	
	<tr>
		<td class='main' align='right' valign='top'>
			
<?php
/**
 * User has already logged in, so display relavent links, including
 * a link to the admin center if the user is an administrator.
 */
if($session->logged_in)
{
   echo "<h1>" . COMMUNITYNAME . "</h1>";
   echo "<h1>AdsQL Advertisements Manager</h1>";
   echo "Welcome <b>" . htmlspecialchars(trim($session->username)) . "</b>, you are logged in. <br><br>";

   echo "<span class='dsa'> [<a href=\"ads.php\">Advertisements</a>] </span>";
   
     
   echo "<br><br>";

   echo "<span class='account'>[<a href=\"userinfo.php?user=" . htmlspecialchars(trim($session->username)) . "\">My Account</a>] &nbsp;&nbsp;" .
        "[<a href=\"useredit.php\">Edit Account</a>] &nbsp;&nbsp;";
		
   if($session->userlevel == 9)
   {
      echo "[<a href=\"admin/admin.php\">User Management</a>] &nbsp;&nbsp;";
   }
	  echo "[<a href=\"about.php\">About</a>] &nbsp;&nbsp;";
      
	  echo "[<a href=\"process.php\">Logout</a>] </span>";
}
else
{

   echo "<h1>" . COMMUNITYNAME . "</h1>";
   echo "<h1>AdsQL Advertisements Manager</h1>";
	/**
	 * User not logged in, display the login form.
	 * If user has already tried to login, but errors were
	 * found, display the total number of errors.
	 * If errors occurred, they will be displayed.
	 */
	if($form->num_errors > 0)
	{
	   echo "<font size=\"2\" color=\"#ff0000\">".$form->num_errors." error(s) found</font>";
	}
?>
			<span class='dsa'>[<a href="ads.php">View Ads Database</a>] &nbsp;&nbsp;</span><br>
			<span class='account'>[<a href="about.php">About</a>] &nbsp;&nbsp;</span><br>
			<br>
			<img src="images/spacer002.gif" height=1 width=800 alt="spacer"><br>
			<br>
 
			<form action="process.php" method="POST">
				<table border="0" cellspacing="0" cellpadding="3">
					<tr>
						<td>Admin Username:</td>
						<td><input type="text" name="user" maxlength="30" value="<?php echo $form->value("user"); ?>"></td>
						<td><?php echo $form->error("user"); ?></td>
					</tr>
					
					<tr>
						<td>Password:</td>
						<td><input type="password" name="pass" maxlength="30" value="<?php echo $form->value("pass"); ?>"></td>
						<td><?php echo $form->error("pass"); ?></td>
					</tr>
					
					<tr>
						<td colspan="2" align="left">
							<input type="checkbox" name="remember" <?php if($form->value("remember") != ""){ echo "checked"; } ?>><font size="2">Remember me next time &nbsp;&nbsp;&nbsp;&nbsp;</font>
							<input type="hidden" name="sublogin" value="1">
							<input type="submit" value="Login">
						</td>
					</tr>
				</table>
			</form>

<?php
}
?>

		</td>
	</tr>
</table>
</body>
</html>
