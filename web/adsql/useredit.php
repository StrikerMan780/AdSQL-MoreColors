<?php
/**
 *
 * Written by: Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * Last Updated: August 15, 2004
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
		<td align='right'><a href="index.php"><img src="images/default.jpg" alt="toplogo"></a></td>
		<td>&nbsp;&nbsp;</td>
	</tr>
	
	<tr>
		<td class='main' align='right'>
			
<?php
if(isset($_SESSION['useredit']))
{
   unset($_SESSION['useredit']);
   
   echo "<h1>User Account Edit Success!</h1>";
   echo "<p><b>" . htmlspecialchars(trim($session->username)) . "</b>, your account has been successfully updated. "
       ."<a href=\"index.php\">Index</a>.</p>";
}
else
{
	if($session->logged_in)
	{

	?>

			<h1>User Account Edit : <?php echo htmlspecialchars(trim($session->username)); ?></h1>

		<?php

		if($form->num_errors > 0)
		{
		   echo "<td><font size=\"2\" color=\"#ff0000\">".$form->num_errors." error(s) found</font></td>";
		}
		?>

			<form action="process.php" method="POST">
				<table align="right" border="0" cellspacing="0" cellpadding="3">
					<tr>
						<td>Current Password:</td>
						<td><input type="password" name="curpass" maxlength="30" value="<?php echo $form->value("curpass"); ?>"></td>
						<td><?php echo $form->error("curpass"); ?></td>
					</tr>

					<tr>
						<td>New Password:</td>
						<td><input type="password" name="newpass" maxlength="30" value="<?php echo $form->value("newpass"); ?>"></td>
						<td><?php echo $form->error("newpass"); ?></td>
					</tr>

					<tr>
						<td>Email:</td>
						<td><input type="text" name="email" maxlength="50" value="<?php if($form->value("email") == "")

																						{
																							//echo $session->userinfo['email'];
																							echo htmlspecialchars(trim($session->userinfo['email']));
																						}
																						else
																						{
																							echo $form->value("email");
																						}

																						?>">
						</td>

						<td><?php echo $form->error("email"); ?></td>
						
					</tr>

					<tr>
						<td colspan="2" align="right">
							<input type="hidden" name="subedit" value="1">
							<input type="submit" value="Edit Account">
						</td>
					</tr>
					
					<tr>
						<td colspan="2" align="left"></td>
					</tr>
				</table>
			</form>

	<?php
	}

/*
	else
	{
		die("Username not registered</td></tr></table>")
	}
*/
}

?>
		</td>
	</tr>
</table>
</body>
</html>
