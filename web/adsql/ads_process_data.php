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
include("include/sanit.php");

if(($session->logged_in) && ($session->userlevel >= 2))
{
	//Grab the info sent	
	$connection = mysql_connect(DB_SERVER, DB_USER, DB_PASS) or die('Could not connect: ' . mysql_error());
	
	mysql_select_db(DB_NAME) or die('Sorry just could not connect: ' . mysql_error());

	if (db_setutf8($connection) === false)

		printf("\n<-- ads_process_data: db_setutf8() failed!  -->\n");
				
	if (isset($_POST['adtext']) && isset($_POST['adtype'])) 
	{
		
		if ($_POST['adtype'] == "hint")
			$adtype = "H";
		elseif ($_POST['adtype'] == "center")
			$adtype = "C";
		elseif ($_POST['adtype'] == "say")
			$adtype = "S";
		elseif ($_POST['adtype'] == "top")
			$adtype = "T";
			
		if ($_POST['adflags'] == "all")
			$adflags = "none";
		elseif ($_POST['adflags'] == "noadmins")
			$adflags = "a";
		elseif ($_POST['adflags'] == "onlyadmins")
			$adflags = "";
		
		if ($_POST['adtype'] == "top")
			$adtext = str_replace("}", "} ", $_POST['adtext']);
		else
			$adtext = $_POST['adtext'];
		$adtext = htmlspecialchars(trim(escape_value($adtext,$connection)));
		/* If they have selected All Games, wipe out the Server ID's field
		if ($_POST['adgame'] == "All")
		{
			$_POST['gamesrvid'] = '';
		}
		 */

		/* Trim the gamesrvid textarea input, it is going to have a lot of spaces
		 * at the end	*/
		//$gamesrvidlist = trim($_POST['gamesrvid']);
		$gamesrvidlist = htmlspecialchars(trim(escape_value($_POST['gamesrvid'],$connection)));
		$adgamecsv = implode(",", $_POST['adgame']);

		//$adgametrimmed = trim($adgamecsv);
		$adgametrimmed = htmlspecialchars(trim(escape_value($adgamecsv,$connection)));

		$post_newid = htmlspecialchars(trim(escape_value($_POST['newid'],$connection)));
		$post_admin = htmlspecialchars(trim(escape_value($_POST['admin'],$connection)));
		$values = sprintf("'%s', '%s', '%s', '%s', '%s', '%s', '%s'", $post_newid, $adtext, $adtype, $adflags, $adgametrimmed, $gamesrvidlist, $post_admin );
		
		$querystring = "INSERT INTO adsmysql (id, text, type, flags, game, gamesrvid, name) VALUES (". $values . ");";
		$result = mysql_query($querystring, $connection);
	}
	else if (isset($_POST['Edit']))
	{
			
		$text = $_POST["edittext"];
		
		if ($_POST['edittype'] == "hint")
			$adtype = "H";
		elseif ($_POST['edittype'] == "center")
			$adtype = "C";
		elseif ($_POST['edittype'] == "say")
			$adtype = "S";
		elseif ($_POST['edittype'] == "top")
			$adtype = "T";
			
		if ($_POST['editflags'] == "all")
			$adflags = "none";
		elseif ($_POST['editflags'] == "noadmins")
			$adflags = "a";
		elseif ($_POST['editflags'] == "onlyadmins")
			$adflags = "";
			
		if ($_POST['edittype'] == "top")
			$text = str_replace("}", "} ", $text);

		/* If they have selected All Games, wipe out the Server ID's field
		if ($_POST['editgame'] == "All")
		{
			$_POST['editsrvid'] = '';
		}
		 */

		/* Trim the editsrvid textarea input, it is going to have a lot of spaces
		 * at the end	*/
		$text = htmlspecialchars(trim(escape_value($text,$connection)));
		
		//$editsrvidlist = trim($_POST['editsrvid']);
		$editsrvidlist = htmlspecialchars(trim(escape_value($_POST['editsrvid'],$connection)));

		$editgamecsv = implode(",", $_POST['editgame']);
		
		//$editgametrimmed = trim($editgamecsv);
		$editgametrimmed = htmlspecialchars(trim(escape_value($editgamecsv,$connection)));
		
		//$time = strtotime($_POST["edittime"]);
		$time = strtotime(htmlspecialchars(trim(escape_value($_POST["edittime"],$connection))));
		$post_editid = htmlspecialchars(trim(escape_value($_POST['editid'],$connection)));
		$post_admin = htmlspecialchars(trim(escape_value($_POST['admin'],$connection)));
		$querystring = sprintf("UPDATE adsmysql SET text='%s', type='%s', flags='%s', game='%s', gamesrvid='%s', name='%s' WHERE id='%s'", $text, $adtype, $adflags, $editgametrimmed, $editsrvidlist, $post_admin, $post_editid);
		
		$result = mysql_query($querystring, $connection);
	}

	// if IE would comply with established HTML standards and just send us
	// the VALUE of an image type button name like every other browser known 
	// to civilized man, we would not have to give a rip about the NAME of 
	// the button to figure out which button was clicked, and we would not
	// be forced to run freaking iterative loops on $_POST data.
	//
	// If this problem was a new bug in the latest version of Internet Exploder,
	// that would be one thing.  However this is a problem that has been KNOWN
	// by Microsoft since AT LEAST 2006, and they literally have refused to fix
	// it.  Therefore they deserve to be mocked and ridiculed.
	//
	// So here's my take:
	//
	// Hey Microsoft, eat a bag of... gummi lighthouses...
	//
	// http://www.joeydevilla.com/2008/06/11/gummi-lighthouses-when-candy-design-goes-terribly-hilariously-wrong/
	//
	else if (isset($_POST['loopmax']))
	{
		for ($loopyloops = $_POST['loopstart']; $loopyloops <= $_POST['loopmax']; $loopyloops++)
		{ 
			if (isset($_POST['moveup-' . $loopyloops . '_x']))
			{
				//$current_id	= $loopyloops;
				$current_id = htmlspecialchars(trim(escape_value($loopyloops,$connection)));
				//$new_id		= $loopyloops - 1;
				$new_id		= htmlspecialchars(trim(escape_value(($loopyloops-1),$connection)));
				$querystring	= sprintf("UPDATE adsmysql SET id=-1 WHERE id='%s'", $current_id);
				$result = mysql_query($querystring, $connection);
				$querystring = sprintf("UPDATE adsmysql SET id='%s' WHERE id='%s'", $current_id, $new_id);
				$result = mysql_query($querystring, $connection);
				$querystring = sprintf("UPDATE adsmysql SET id='%s' WHERE id=-1", $new_id);
				$result = mysql_query($querystring, $connection);
			}
			else if (isset($_POST['movedown-' . $loopyloops . '_x']))
			{	
				//$current_id	= $loopyloops;
				$current_id = htmlspecialchars(trim(escape_value($loopyloops,$connection)));
				//$new_id		= $loopyloops + 1;
				$new_id		= htmlspecialchars(trim(escape_value(($loopyloops+1),$connection)));
				$querystring	= sprintf("UPDATE adsmysql SET id=-1 WHERE id='%s'", $current_id);
				$result = mysql_query($querystring, $connection);
				$querystring = sprintf("UPDATE adsmysql SET id='%s' WHERE id='%s'", $current_id, $new_id);
				$result = mysql_query($querystring, $connection);
				$querystring = sprintf("UPDATE adsmysql SET id='%s' WHERE id=-1", $new_id);
				$result = mysql_query($querystring, $connection);
			}
			// we would put our else if tests for deletion right 'cheer... and get rid of the $_POST['set']
			// check block above this
			else if (isset($_POST['remove-' . $loopyloops . '_x']))
			{
				$loopyloops		= htmlspecialchars(trim(escape_value($loopyloops,$connection)));
				$querystring = "DELETE FROM adsmysql WHERE id = '" . $loopyloops . "' LIMIT 1;";
				$result = mysql_query($querystring, $connection);
			}
		}
	}
	print "<meta http-equiv=\"refresh\" content=\"0;url=ads.php\">";
	mysql_close($connection);
}
else
{
	header( 'Location: index.php' ) ;
}
?>
