<?php
header('Content-Type: text/html; charset=UTF-8');

/**
 *
 * Written by: Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * Last Updated: August 15, 2004
 */
 
include("../include/session.php");
//include("../include/header.php");

date_default_timezone_set('America/Chicago');

/**
 * displayUsers - Displays the users database table in
 * a nicely formatted html table.
 */
function displayUsers()
{
   global $database;
   $q = "SELECT username,userlevel,email,timestamp " 
       ."FROM ".TBL_USERS." ORDER BY userlevel DESC,username";
   $result = $database->query($q);
   /* Error occurred, return given name by default */
   $num_rows = mysql_numrows($result);
   if(!$result || ($num_rows < 0))
   {
      echo "Error displaying info";
      return;
   }
   if($num_rows == 0)
   {
      echo "Database table empty";
      return;
   }
   /* Display table contents */
   echo "<table align=\"left\" border=\"1\" cellspacing=\"0\" cellpadding=\"3\">\n";
   echo "<tr><td><b>Username</b></td><td><b>Level</b></td><td><b>Email</b></td><td><b>Last Active</b></td></tr>\n";
   for($i=0; $i<$num_rows; $i++)
   {
      $uname  = htmlspecialchars(trim(mysql_result($result,$i,"username")));
      $ulevel = htmlspecialchars(trim(mysql_result($result,$i,"userlevel")));
      $email  = htmlspecialchars(trim(mysql_result($result,$i,"email")));
      $time   = date("l, F j, Y, g:i a", mysql_result($result,$i,"timestamp"));

      echo "<tr><td>$uname</td><td>$ulevel</td><td>$email</td><td>$time</td></tr>\n";
   }
   echo "</table><br>\n";
}
   
/**
 * User not an administrator, redirect to main page
 * automatically.
 */
if(!$session->isAdmin())
{
   header("Location: ../index.php");
}
else
{
/**
 * Administrator is viewing page, so display all
 * forms.
 */

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8">
<title><?php echo COMMUNITYNAME . " - Advertisements User Account Manager"; ?></title>
<link rel="stylesheet" type="text/css" href="../style.css">
</head>
<body>
<table>
	<tr>
		<td></td>
		<td><img src="../images/spacer001.gif" height=1 width=100 alt="spacer"></td>
	</tr>
	
	<tr>
		<td align='right'><a href="../index.php"><img src="../images/default.jpg" alt="toplogo"></a></td>
		<td>&nbsp;&nbsp;</td>
	</tr>
	
	<tr>
		<td class='main' align='left'><h1>Advertisements User Account Manager</h1>
		Logged in as <b><?php echo htmlspecialchars(trim($session->username)); ?></b><br>
		<br>
		<span class='account'>[<a href="../index.php">Index</a>]</span><br>
		<br>
		<?php
		
if($form->num_errors > 0){
   echo "<font size=\"4\" color=\"#ff0000\">"
       ."!*** Error with request, please fix</font><br><br>";
}
?>
			<table align="left" border="0" cellspacing="5" cellpadding="5">
				<tr>
			
					<td><hr></td>
				
				</tr>
				
				<tr>
					<td><?php
/**
 * Display Users Table
 */
?>
				<h3>Users Table Contents:</h3>
				<?php
displayUsers();
?>					</td>
				</tr>
				
				<tr>
					<td><br>
				<?php
/**
 * Add a User
 */
?>
						<h3>Add User</h3>
						<?php echo $form->error("adduser"); ?> Usernames must be six alphanumerical characters or more.<br>
						Do not use any other characters...<br>
					</td>
				</tr>
				
				<tr>
					<td><table>
							<tr>
								<td><form action="adminprocess.php" method="POST">
									<table>
										<tr>
											<td> Username:<br>
												<input type="text" name="user" maxlength="30" value="<?php echo $form->value("user"); ?>"></td>
											<td> Password:<br>
												<input type="text" name="pass" maxlength="30" value="<?php echo $form->value("pass"); ?>"></td>
											<td> Email:<br>
												<input type="text" name="email" maxlength="30" value="<?php echo $form->value("email"); ?>"></td>
											<td><br>
												<input type="hidden" name="subadduser" value="1">
												<input type="submit" value="Add User"></td>
										</tr>
									</table>
									</form>
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<td><hr></td>
				</tr>

				<tr>
					<td><?php
/**
 * Update User Level
 */
?>
				<h3>Update User Level</h3>
				<?php echo $form->error("upduser"); ?>
						<form action="adminprocess.php" method="POST">
						<table>
							<tr>
								<td> Username:<br>
									<input type="text" name="upduser" maxlength="30" value="<?php echo $form->value("upduser"); ?>"></td>
								<td> Level:<br>
									<select name="updlevel">
											<option value="1">1
											<option value="2">2
											<option value="3">3
											<option value="4">4
											<option value="5">5
											<option value="6">6
											<option value="7">7
											<option value="8">8
											<option value="9">9
									</select>
								</td>
								
								<td><br>
									<input type="hidden" name="subupdlevel" value="1">
									<input type="submit" value="Update Level"></td>
							</tr>
						</table>
						</form>
					</td>
				</tr>
				
				<tr>

					<td><hr></td>

				</tr>

				<tr>

					<td><?php
/**
 * Delete User
 */
?>
						<h3>Delete User</h3>
						<?php echo $form->error("deluser"); ?>
						<form action="adminprocess.php" method="POST">
							Username:<br>
							<input type="text" name="deluser" maxlength="30" value="<?php echo $form->value("deluser"); ?>">
							<input type="hidden" name="subdeluser" value="1">
							<input type="submit" value="Delete User">
						</form>
					</td>

				</tr>
			
				<tr>
						<td><hr></td>
				</tr>

				<tr>
						<td><?php
/**
 * Delete Inactive Users
 */
?>
							<h3>Delete Inactive Users</h3>
							This will delete all users (not administrators), who have not logged in to the site<br>
							within a certain time period. You specify the days spent inactive.<br>
							<br>
							<form action="adminprocess.php" method="POST">
								<table>
									<tr>
										<td> Days:<br>
											<select name="inactdays">
												<option value="3">3
												<option value="7">7
												<option value="14">14
												<option value="30">30
												<option value="100">100
												<option selected value="365">365
											</select>
										</td>
									
										<td><br>
											<input type="hidden" name="subdelinact" value="1">
											<input type="submit" value="Delete All Inactive">
										</td>
									</tr>
								</table>
							</form>
						</td>

				</tr>

				<tr>
					<td><hr></td>
				</tr>

			</table>
		</tr>
</table>
</body>
</html>
<?php
}
?>
