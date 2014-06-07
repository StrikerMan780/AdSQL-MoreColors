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
include("include/sanit.php");

$numberofrecords = 25;
?>

<table border=0 width="100%">
	<tr>
		<td align='right' class='imgbg'><a href="index.php"><img src="images/default.jpg" alt="toplogo"></a></td>
	</tr>
	<tr>
		<td class='main' align='right'><?php


//Open the Data from the database
$connection = mysql_connect(DB_SERVER, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());

mysql_select_db(DB_NAME) or die('Sorry just could not connect: ' . mysql_error());

if (db_setutf8($connection) === false)

	printf("\n<!-- db_setutf8 returned false :( -->\n");

/* just for testing - delete once we know what kind of output it gives us */
//printf("\n<!-- MySQL server version: '%s' -->\n", mysql_get_server_info());

$sql = "SELECT COUNT(id) FROM adsmysql"; 
$rs_result = mysql_query($sql, $connection); 
$row = mysql_fetch_row($rs_result); 
$total_records = $row[0]; 

$newid = $total_records+1;

$sql = "SELECT id FROM adsmysql ORDER BY id ASC"; 
$rs_result = mysql_query($sql, $connection); 

$idcheck = 0;
while ($row = mysql_fetch_assoc($rs_result)) 
{
	$idcheck++;
	if ($row['id'] != $idcheck)
	{
		$newid = $idcheck;
		break;
	}
}

if (isset($_GET["page"])) 
{ 
	//$page  = (int)($_GET["page"]);
	$page  = (int)(htmlspecialchars(trim(escape_value($_GET["page"],$connection))));
} 
else 
{ 
	$page = 1; 
}

$start_from = ($page-1) * $numberofrecords; 

//$sql = "SELECT * FROM adsmysql ORDER BY id DESC LIMIT $start_from, $numberofrecords"; 
$sql = "SELECT * FROM adsmysql ORDER BY id ASC LIMIT $start_from, $numberofrecords"; 

$rs_result = mysql_query ($sql, $connection); 

if($session->userlevel >= 2)
{
?>
			<form  action="ads_process_data.php" method="POST">
				<?php
}
?>
				<table width="100%" border=1>
					<tr>
						<th>Ad<br>
							ID</th>
						<th>Advertisement Text</th>
						<th>Ad Is<br>
							Visible By</th>
						<th>Ad<br>
							Type</th>
						<th>Game<br>
							Type(s)</th>
						<th>Server<br>
							ID(s)</th>
						<?php
if($session->userlevel >= 3)
{
	print "<th>Edit<br>Ad</th>";
}
if($session->userlevel >= 6)
{
	print "<th>Del<br>Ad</th>";
}
if($session->userlevel >= 3)
{
	print "<th>Added/Last<br>Edited by</th>";
}
if($session->userlevel >= 4)
{
	print "<th>&nbsp;</th>";
}

print "</tr>";

$count = ($page * $numberofrecords) - $numberofrecords;

$idcheck = 0;

$lowerbound = 0;
$upperbound = 0;

while ($row = mysql_fetch_assoc($rs_result)) 
{
	if ($lowerbound == 0)
	{
		$lowerbound = $row['id'];
		print "<!-- lower bound value: " . $lowerbound . " -->";
	}
	
	$ad_text = $row['text'];

	if ($row['flags'] == "none")
		$visibility = "All";
	else if ($row['flags'] == "a")
		$visibility = "No " . ADMIN;
	else if ($row['flags'] == "")
		$visibility = "Only " . ADMIN;
?>
					<tr>
						<td class='small' align=center><?php echo $row['id'] ?></td>
						<td class='small'><?php echo $ad_text; ?></td>
						<td class='small' align=center><?php echo $visibility?></td>
						<?php

	if ($row['type'] == "H")
		$ad_type = "Hint";
	elseif ($row['type'] == "S")
		$ad_type = "Say";
	elseif ($row['type'] == "C")
		$ad_type = "Center";
	elseif ($row['type'] == "T")
		$ad_type = "Top";

	$gameicon = explode(",", $row['game']);
?>
						<td class='small' align=center><?php echo $ad_type ?></td>
						<?php

	print "<td class='small' align='center'>";

	foreach($gameicon as $iconstring)
	{
		print "&nbsp;&nbsp;<img src=\"images/icons/" . $iconstring . ".gif\" alt=\"" . $iconstring . "\" title=\"";

		switch ($iconstring) {

			case "All":
				echo "All Games\">";
				$row['gamesrvid'] = "<b>- N/A -<br>(All Game Types)</b>";
				break;

			case "ageofchivalry":
				echo "Age Of Chivalry\">";
				break;

			case "cssbeta":
				echo "Counterstrike Source Beta\">";
				break;

			case "cstrike":
				echo "Counterstrike Source\">";
				break;
				
			case "csgo":
				echo "Counter-Strike Global Offensive\">";
				break;
			
			case "dod":
				echo "Day of Defeat Source\">";
				break;

			case "hl2mp":
				echo "Half Life 2 Deathmatch\">";
				break;

			case "insurgency":
				echo "Insurgency\">";
				break;

			case "left4dead":
				echo "Left 4 Dead 1\">";
				break;

			case "l4d2":
				echo "Left 4 Dead 2\">";
				break;
			
			case "nucleardawn":
				echo "Nuclear Dawn\">";
				break;
			
			case "pvkii":
				echo "Pirates Vikings &amp; Knights II\">";
				break;

			case "tf":
				echo "Team Fortress 2\">";
				break;

			case "teamfortbeta":
				echo "Team Fortress 2 Beta\">";
				break;

			case "zps":
				echo "Zombie Panic: Source\">";
				break;

			default:
				echo $iconstring . "\">";
				break;

		} /* switch */

	} /* foreach */

	print "&nbsp;</td>";
?>
						<td class='small' align=center><?php echo $row['gamesrvid'] ?></td>
						<?php
	if($session->userlevel >= 3)
	{
		print "<td align='center'><a href='ads_edit.php?id=" . $row["id"] . "'> <img src=\"images/edit.png\" alt=\"Edit\" border=0></a></td>";
	}
	if($session->userlevel >= 6)
	{
		print "<td align='center'><input type=image name=\"remove-" . $row["id"] . "\" value='MicrosoftSucksGummiLighthouses' src=\"images/remove.png\" alt=\"Remove\"></td>";
	}
	if($session->userlevel >= 3)
	{
		print "<td align='center' class='small'> " . $row["name"] . " </td>";
	}
	if($session->userlevel >= 4)
	{
		print "<td align='center'>";
		if ($count != 0)
			print "<input type=image name=\"moveup-" . $row["id"] . "\" value='MicrosoftSucksGummiLighthouses' src=\"images/move-up.gif\" alt=\"Move Up\">" ;
		else
			print "<img src=\"images/move-up-disabled.gif\" alt=\" \">";

		print "<br>";
		if ($count != $total_records-1)
			print "<input type=image name=\"movedown-" . $row["id"] . "\" value='MicrosoftSucksGummiLighthouses' src=\"images/move-down.gif\" alt=\"Move Down\">  </td>";
		else
			print "<img src=\"images/move-down-disabled.gif\" alt=\" \">  </td>";
	}
?>
					</tr>
					<?php 
	$count++;

	/* Keep track of the highest db record id we have seen.
	   Once the while condition fails we exit the loop and
	   the value we have for $upperbound will be the last
	   record's ID in the database.  Besides, I don't know
	   for sure that we will have a value we can use in
	   $row['id'] once we exit the loop.
	 */
	$upperbound = $row['id'];
}

print "<!-- upper bound value: " . $upperbound . " -->";

?>
				</table>
				<?php 
if($session->userlevel >= 2)
{
	/* Set lowerbound and upperbound as hidden values to pass into the form.
	   This is useful since thanks to Micro$oft's IE stupidity, we have to loop
	   through the $_POST data looking at button NAMES to see which one the
	   user clicked.  We can limit the pain by at least restricting the loop
	   bounds to database record ID numbers actually listed on THIS page of
	   ads.
	 */
	echo "<input type='hidden' value='" . $upperbound . "' name='loopmax'>";
	echo "<input type='hidden' value='" . $lowerbound . "' name='loopstart'>";
?>
			</form>
			<?php
}

$sql = "SELECT COUNT(id) FROM adsmysql"; 

$rs_result = mysql_query($sql, $connection); 
$row = mysql_fetch_row($rs_result); 
$total_records = $row[0]; 
$total_pages = ceil($total_records / $numberofrecords); 

echo "<span class='account'>[<a href=\"index.php\">Index</a>] ";

echo "</span><br><br>";

echo "<span class='account'>Page: ";

for ($i=1; $i<=$total_pages; $i++) 
{ 
	echo "<a href='ads.php?page=".$i."'>".$i."</a> "; 
}
echo "</span>";

mysql_close($connection);
?></td>
	</tr>
	<?php
if($session->userlevel < 2)
{
	echo "</table>\n";
}
else if ($session->userlevel >= 3)
{
?>
	<tr>
		<td colspan=3><hr></td>
	</tr>
	<tr>
		<td class='main' align='left'><h1>Add an Advertisement:</h1></td>
	</tr>
	
	<!-- original Add Ad form was starting right here... -->
	
	<tr>
		<td><form enctype="multipart/form-data" action="ads_process_data.php" method="POST">
				<table width="80%" align="center" border="0" cellspacing="0" cellpadding="3">
					<tr> 
						<!--				<td><table width="100%" colspan="3" align="left" border="0" cellspacing="0" cellpadding="3">	-->
						<td><table width="100%" align="left" border="0" cellspacing="0" cellpadding="3">
								<tr>
									<td valign="top"><b>Advert Text:</b></td>
									<td valign="top"><textarea class="countable_adtext" name="adtext" cols="50" rows="4"></textarea></td>
									<td class="small"><b>Variables: </b><br>
										{CURRENTMAP}, {DATE}, {TIME}, {IP}, {PORT}, {TIMELEFT} and {SM_NEXTMAP}<br>
										<b>Say colors</b>:<br>
										{DEFAULT}, {TEAM}, {GREEN}, {LIGHTGREEN}, and {OLIVE}<br>
										<b>Top say colors</b>:<br>
										{RED}, {ORANGE}, {WHITE}, {GREEN}, {BLUE},{YELLOW}, {OLIVE}, {PURPLE}, {CYAN}, {PINK}, {LIGHTBLUE}, {LIME}, {VIOLET} </td>
								</tr>
								
								<!--
<tr>
<td colspan="3">&nbsp;</td>
</tr>
-->
							</table></td>
					</tr>
					<tr>
						<td><table width="100%" align="center" border="0" cellspacing="0" cellpadding="3">
								<tr>
									<td valign="top" colspan="3"><table border="0" cellspacing="0" cellpadding="3">
											<tr>
												<td valign="top" width="21%"><b>Advert Type:</b></td>
												<td valign="top" width="20%"><table border="0" width="100%" cellspacing="10" cellpadding="0">
														<tr>
															<td>Say text</td>
															<td><input type="radio" name="adtype" value="say" CHECKED></td>
														</tr>
														<tr>
															<td>Hint text</td>
															<td><input type="radio" name="adtype" value="hint"></td>
														</tr>
														<tr>
															<td>Center text</td>
															<td><input type="radio" name="adtype" value="center"></td>
														</tr>
														<tr>
															<td>Top text</td>
															<td><input type="radio" name="adtype" value="top"></td>
														</tr>
													</table></td>
												<td><table border="0" cellspacing="20" cellpadding="0">
														<tr>
															<td class="small"> Say - Regular text<br>
																Hint - Yellow text bottom center on screen - also makes a noise<br>
																Center - White text centered on the screen<br>
																Top - Colored text on a black background in top left</td>
														</tr>
													</table></td>
											</tr>
										</table></td>
								</tr>
							</table></td>
					</tr>
					
					<!--	
<tr><td colspan=3>&nbsp;</td></tr>
-->
					
					<tr> 
						<!--				<td><table width="55%" colspan="3" align="left" border="0" cellspacing="0" cellpadding="3"> -->
						<td><table width="55%" align="left" border="0" cellspacing="0" cellpadding="3">
								<tr>
									<td valign=top width="25%"><b>Advert Visiblity:</b></td>
									<td valign=top><table border="0" width="50%" cellspacing="0" cellpadding="0">
											<tr> 
												<!-- <td width="92%">All players</td> -->
												<td>All players</td>
												
												<!-- <td width="8%"> used to be below: -->
												<td ><input type="radio" name="adflags" value="all" CHECKED></td>
											</tr>
											<tr>
												<td>Hidden from <?php echo ADMIN;?></td>
												<td><input type="radio" name="adflags" value="noadmins"></td>
											</tr>
											<tr>
												<td>Only <?php echo ADMIN;?></td>
												<td><input type="radio" name="adflags" value="onlyadmins"></td>
											</tr>
										</table></td>
									<td>&nbsp;</td>
								</tr>
								
								<!--
<tr>
<td colspan=3>&nbsp;</td>
</tr>
-->
							</table></td>
					</tr>
					<tr> 
						<!--			<td><table border="0" colspan="3" width="100%" align="left" cellspacing="0" cellpadding="3">	-->
						<td><table border="0" width="100%" align="left" cellspacing="0" cellpadding="3">
								<tr>
									<td valign="top" width="15%"><b>Game Type:</b><br>
										<select size="13" multiple name="adgame[]">
											<option value="All" selected>All Games</option>
											<option value="ageofchivalry">Age Of Chivalry</option>
											<option value="cstrike">Counterstrike Source</option>
											<option value="cssbeta">Counterstrike Source Beta</option>
											<option value="csgo">Counter-Strike: Global Offensive</option>
											<option value="dod">Day of Defeat Source</option>
											<option value="hl2mp">HL2 Deathmatch</option>
											<option value="insurgency">Insurgency</option>
											<option value="left4dead">Left 4 Dead 1</option>
											<option value="l4d2">Left 4 Dead 2</option>
											<option value="pvkii">Pirates, Vikings &amp; Knights II</option>
											<option value="tf">Team Fortress 2</option>
											<option value="teamfortbeta">Team Fortress 2 Beta</option>
											<option value="zps">Zombie Panic: Source</option>
										</select></td>
									<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
									<td valign=top><table border="0" width="50%">
											<tr>
												<td><b>Server ID's For This Ad - You MUST either type:</b></td>
											</tr>
											<tr>
												<td class="small">1) <b>All</b> with NO OTHER INPUT - The ad will be displayed in all servers of the selected <b>Game Type(s) - OR -</b><br>
													<br>
													2) The Server ID('s) of one or more servers of the selected Game Type(s) this ad should run on. When specifying multiple server ID's, separate them with commas using NO SPACES.  Only servers using the specified Server ID('s) will display the ad.<br>
													<br>
													&nbsp;&nbsp;&nbsp;<b>A Valid Example: &nbsp;&nbsp;27015,css001,iceworld</b><br>
													<br>
													<b>NOTE:</b> If you have chosen <b>All Games</b> for the Game Type, the value of the Server ID's input field will be IGNORED by the plugin and the ad will run on ALL games. </td>
											</tr>
											<tr>
												<td><textarea name="gamesrvid" class="countable_adserver" cols="70" rows="2">All</textarea></td>
											</tr>
										</table></td>
								</tr>
								<tr>
									<td colspan="3"><b>Game Type Selection:</b><br>
										Select <b>All Games</b> by itself to display the ad in all games. <b>OR</b> select the specific game type(s) for this ad to be displayed in. <b><i>CTRL-click to select multiple specific game types</i></b>.  You CAN use Server ID's together with multiple specific game types to limit which of the servers running those games will display the ad.<br>
										<br>
										<b>NEVER</b> select <b>All Games</b> together with other list items, or your ad will only show up in the other specific games you selected! </td>
								</tr>
							</table></td>
						
						<!--		<td class="small">&nbsp;</td> -->
						
<?php
	echo "<td><input type='hidden' value='" . htmlspecialchars(trim($session->username)) . "' name='admin'></td>";

	$thetime = time();
	echo "<td><input type='hidden' value='" . $thetime . "' name='time'></td>";

	echo "<td><input type='hidden' value='" . htmlspecialchars(trim($newid)) . "' name='newid'></td>";

?>
					</tr>
					<tr>
						<td>&nbsp;</td>
						<td colspan="2" align=right><input type="submit" value="Add Advertisement" name="AddAd"></td>
					</tr>
				</table>
			</form></td>
	</tr>
</table>
<?php

}

?>
</body></html>
