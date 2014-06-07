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
?>

<table border=0 width="100%">
	<tr>
		<td align="center"><a href="index.php"><img src="images/default.jpg" alt="toplogo"></a></td>
		
	</tr>
	<tr>
		<td align="center"><table border="0" width="800">
				<tr>
					<td class='main' align='right' ><br>
						<h1>Edit Advertisement</h1></td>
				</tr>
			</table>
	</tr>
	<?php
	

/**
 * User has already logged in, so display relevant links, including
 * a link to the User Manager if the user is an administrator.
 */
if (($session->logged_in) && ($session->userlevel >= 2))
{
	if (!isset($_GET["id"])) 
	{ 
		header( 'Location: index.php' ) ;
		
	} else {
		$connection = mysql_connect(DB_SERVER, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
		
		mysql_select_db(DB_NAME) or die('Sorry just could not connect: ' . mysql_error());
	
		if (db_setutf8($connection) === false)
	
			printf("\n<!-- db_setutf8 returned false :( -->\n");
		$id  = escape_value(((int)($_GET["id"])),$connection);
		$querystring = "SELECT * FROM adsmysql WHERE id = '" . $id . "' LIMIT 1;";
		$result = mysql_query($querystring, $connection);
		$addetails = mysql_fetch_array($result);
		
		$editid = $addetails['id'];
	}
?>
	<tr>
		<td>
	
		<!-- 80% wrapper for the rest of the page -->
	
			<table width="80%" align="center" border="0" cellspacing="0" cellpadding="3">
			<tr>
				<td>
					<form enctype="multipart/form-data" action="ads_process_data.php" method="POST">
					<table width="100%" align="left" border="0" cellspacing="0" cellpadding="3">
						<tr>					
							<td>
								<table width="100%" align="left" border="0" cellspacing="0" cellpadding="3">
									<tr>
										<td valign="top"><b>Advert Text:</b></td>
										<td valign="top"><textarea name="edittext" class="countable_adtext" cols="50" rows="4"><?php echo htmlspecialchars(trim($addetails['text']));?></textarea></td>
										<td class="small"><b>Variables: </b><br>
											{CURRENTMAP}, {DATE}, {TIME}, {IP}, {PORT}, {TIMELEFT} and {SM_NEXTMAP}<br>
											<b>Say colors</b>:<br>
											{DEFAULT}, {TEAM}, {GREEN}, {LIGHTGREEN}, and {OLIVE}<br>
											<b>Top say colors</b>:<br>
											{RED}, {ORANGE}, {WHITE}, {GREEN}, {BLUE},{YELLOW}, {OLIVE}, {PURPLE}, {CYAN}, {PINK}, {LIGHTBLUE}, {LIME}, {VIOLET}
										</td>
									</tr>
								</table>
							</td>
						</tr>

						<tr>
							<td>
								<table width="100%" align="center" border="0" cellspacing="0" cellpadding="3">
									<tr>
										<td valign="top" colspan="3">
											<table border="0" cellspacing="0" cellpadding="3">
												<tr>
													<td valign="top" width="21%"><b>Advert Type:</b></td>
													<td valign="top" width="20%">
														<table border="0" width="100%" cellspacing="10" cellpadding="0">
															<tr>
																<td>Say text</td>
																<td>
																	<input type="radio" name="edittype" value="say" <?php if ($addetails['type'] == "S") echo "CHECKED"; ?>>
																</td>
															</tr>
															
															<tr> 
																<!-- <td width="92%">Hint text</td> -->
																<td>Hint text</td>
												
																<!-- <td width="8%"><input type="radio" name="edittype" value="hint" <?php if ($addetails['type'] == "H") echo "CHECKED"; ?>></td>  -->
																<td><input type="radio" name="edittype" value="hint" <?php if ($addetails['type'] == "H") echo "CHECKED"; ?>></td>
															</tr>

															<tr>
																<td>Center text</td>
																<td><input type="radio" name="edittype" value="center" <?php if ($addetails['type'] == "C") echo "CHECKED"; ?>></td>
															</tr>

															<tr>
																<td>Top text</td>
																<td><input type="radio" name="edittype" value="top" <?php if ($addetails['type'] == "T") echo "CHECKED"; ?>></td>
															</tr>
														</table>
													</td>

													<td>
														<table border="0" cellspacing="20" cellpadding="0">
															<tr>
																<td class="small">
																	Say - Regular text<br>
																	Hint - Yellow text bottom center on screen - also makes a noise<br>
																	Center - White text centered on the screen<br>
																	Top - Colored text on a black background in top left
																</td>
															</tr>
														</table>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</td>
						</tr>
		
						<tr>
							<td><table width="55%" align="left" border="0" cellspacing="0" cellpadding="3">
					<tr>
						<td valign=top width="25%"><b>Advert Visiblity:</b></td>
						<td valign=top><table border="0" width="50%" cellspacing="0" cellpadding="0">
								<tr> 
									<!-- <td width="92%">All players</td> -->
									<td>All players</td>
									
									<!-- <td width="8%"> used to be below: -->
									<td ><input type="radio" name="editflags" value="all"<?php if ($addetails['flags'] == "none") echo "CHECKED"; ?>></td>
								</tr>
								<tr>
									<td>Hidden from <?php echo ADMIN;?></td>
									<td><input type="radio" name="editflags" value="noadmins" <?php if ($addetails['flags'] == "a") echo "CHECKED"; ?>></td>
								</tr>
								<tr>
									<td>Only <?php echo ADMIN;?></td>
									<td><input type="radio" name="editflags" value="onlyadmins" <?php if ($addetails['flags'] == "") echo "CHECKED"; ?>></td>
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
							<td>	
								<table border="0" width="100%" align="left" cellspacing="0" cellpadding="3">
								<tr>
									<td valign="top" width="15%">
										<b>Game Type:</b><br>
										<select size="13" multiple name="editgame[]">
					<?php

					/* $gamefield = $addetails['game']; */
					print "\n";
					?>

											<option value="All" <?php if (!strcmp($addetails['game'], 'All')) echo "selected"; ?>>All Games</option>
											<option value="ageofchivalry" <?php if (strpos($addetails['game'], 'ageofchivalry') !== false) echo "selected"; ?>>Age Of Chivalry</option>
											<option value="cstrike" <?php if (strpos($addetails['game'], 'cstrike') !== false) echo "selected"; ?>>Counterstrike Source</option>
											<option value="cssbeta" <?php if (strpos($addetails['game'], 'cssbeta') !== false) echo "selected"; ?>>Counterstrike Source Beta</option>
											<option value="csgo" <?php if (strpos($addetails['game'], 'csgo') !== false) echo "selected"; ?>>Counterstrike Global Offensive</option>
											<option value="dod" <?php if (strpos($addetails['game'], 'dod') !== false) echo "selected"; ?>>Day of Defeat Source</option>
											<option value="hl2mp" <?php if (strpos($addetails['game'], 'hl2mp') !== false) echo "selected"; ?>>HL2 Deathmatch</option>
											<option value="insurgency" <?php if (strpos($addetails['game'], 'insurgency') !== false) echo "selected"; ?>>Insurgency</option>
											<option value="left4dead" <?php if (strpos($addetails['game'], 'left4dead') !== false) echo "selected"; ?>>Left 4 Dead 1</option>
											<option value="l4d2" <?php if (strpos($addetails['game'], 'l4d2') !== false) echo "selected"; ?>>Left 4 Dead 2</option>
											<option value="pvkii" <?php if (strpos($addetails['game'], 'pvkii') !== false) echo "selected"; ?>>Pirates, Vikings &amp; Knights II</option>
											<option value="tf" <?php if (strpos($addetails['game'], 'tf') !== false) echo "selected"; ?>>Team Fortress 2</option>
											<option value="nucleardawn" <?php if (strpos($addetails['game'], 'nucleardawn') !== false) echo "selected"; ?>>Nuclear Dawn</option>
											<option value="teamfortbeta" <?php if (strpos($addetails['game'], 'teamfortbeta') !== false) echo "selected"; ?>>Team Fortress 2 Beta</option>
											<option value="zps" <?php if (strpos($addetails['game'], 'zps') !== false) echo "selected"; ?>>Zombie Panic: Source</option>
										</select>
									</td>
						
									<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
						
									<td valign=top>
										<table border="0" width="50%">
											<tr>
												<td>
													<b>Server ID's For This Ad - You MUST either type:</b>
												</td>
											</tr>
								
											<tr>
												<td class="small">
													1) <b>All</b>with NO OTHER INPUT - The ad will be displayed in all servers of the selected <b>Game Type</b>(s) <b>- OR -</b><br>
													<br>
													2) The Server ID('s) of one or more servers of the selected Game Type(s) this ad should run on. When specifying multiple server ID's, separate them with 
													commas using NO SPACES.  Only servers using the specified Server ID('s) will display the ad.<br>
													<br>
													&nbsp;&nbsp;&nbsp;<b>A Valid Example: &nbsp;&nbsp;27015,css001,iceworld</b><br>
													<br>
													<b>NOTE:</b> If you have chosen <b>All Games</b> for the Game Type, the value of the Server ID's input field will be IGNORED by the plugin and the ad will 
													run on ALL games.
												</td>
											</tr>
								
											<tr>
												<td>
													<textarea name="editsrvid" class="countable_adserver" cols="70" rows="2"><?php echo htmlspecialchars(trim($addetails['gamesrvid']));?></textarea>
												</td>
			
											</tr>
			
										</table>
									</td>
		
								</tr>
		
								<tr>
									<td colspan="3"><b>Game Type Selection:</b><br>
										Select <b>All Games</b> by itself to display the ad in all games. <b>OR</b> select the specific game type(s) 
										for this ad to be displayed in. <b><i>CTRL-click to select multiple specific game types</i></b>.  You CAN use 
										Server ID's together with multiple specific game types to limit which of the servers running those games will display the ad.<br>
										<br>
										<b>NEVER</b> select <b>All Games</b> together with other list items, or your ad will only show up in the other specific games 
										you selected!
									</td>
								</tr>
								</table>
							</td>
	
	
	<!--		<td class="small">&nbsp;</td> -->
	
	<?php
	echo "<td><input type='hidden' value='" . htmlspecialchars(trim($session->username)) . "' name='admin'></td>";
	
	$thetime = time();
	echo "<td><input type='hidden' value='" . $thetime . "' name='time'></td>";
	
	echo "<td><input type='hidden' value='" . htmlspecialchars(trim($editid)) . "' name='editid'></td>";
	
?>

						</tr>
						
						<tr>
							<td>
								<table border="0" align="center" cellpadding="0" cellspacing="0" width="800">
									<tr>
										<td><span class='account'>[<a href="index.php">Index</a>]</span><br></td>
										<td>&nbsp;</td>
										<td colspan=1 align=right><input type="submit" value="Change Advertisement" name="Edit"></td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					</form>
				</td>
			</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>
<?php

	mysql_close($connection);
	
}
else
{
	header( 'Location: index.php' ) ;
}
?>
