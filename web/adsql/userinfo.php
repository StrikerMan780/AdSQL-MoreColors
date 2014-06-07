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
		<td align='right'><a href="index.php"><img src="images/default.jpg" alt="toplogo"></a></td><td>&nbsp;&nbsp;</td>
	</tr>
	
	<tr>
		<td class='main' align='right'>

<?php

$req_user = trim($_GET['user']);
if(!$req_user || strlen($req_user) == 0 ||
   !eregi("^([0-9a-z])+$", $req_user) ||
   !$database->usernameTaken($req_user))
   {
	die("Username not registered</td></tr></table>");
   }

if(strcmp($session->username,$req_user) == 0)
{
   echo "<h1>My Account</h1>";
}

else
{
   echo "<h1>User Info</h1>";
}
$req_user_info = $database->getUserInfo($req_user);
echo "<b>Username: ".htmlspecialchars(trim($req_user_info['username']))."</b><br>";
echo "<b>Email:</b> ".htmlspecialchars(trim($req_user_info['email']))."<br>";

?>
			<br>
			<span class='account'>
<?php
if(strcmp($session->username,$req_user) == 0)
{
   echo "[<a href=\"useredit.php\">Edit Account Information</a>] &nbsp;&nbsp;";
}

echo "[<a href=\"index.php\">Index</a>]</span><br>";

?>
		</td>
	</tr>
</table>
</body>
</html>
