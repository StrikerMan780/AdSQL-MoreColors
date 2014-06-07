<?php

/*	db_utf8.php
 *	by PharaohsPaw
 *	http://www.pwng.net
 *
 *	An include file to help setting up UTF8 session parameters
 *	with the MySQL database server
 *
 *
 */

function php_versionok()
{

	$WantedPHPVersion = '5.2.3';

	$CurrentPHPVersion = phpversion();

	//print "\n<!-- PHP Version: " . $CurrentPHPVersion . " -->\n";

	switch (version_compare($CurrentPHPVersion, $WantedPHPVersion))
	{
		case -1:
			//print "\n<!-- Server running older PHP Version - can't use mysql_set_charset() -->\n";
			return false;
			break;

		case 0:
			//print "\n<!-- Server running desired PHP version -->\n";
			return true;
			break;

		case 1:
			//print "\n<!-- Server running a newer PHP version -->\n";
			return true;
			break;

	}

}


// get the mysql server version
// we have to have a database connection for this to work

function db_versionok($dbconnection)
{

	$WantedDBVersion = '5.0.7';

	$CurrentDBVersion = mysql_get_server_info($dbconnection);

	//printf("\n<!-- DB Version: '%s' -->\n", $CurrentDBVersion);

	switch (version_compare($CurrentDBVersion, $WantedDBVersion))
	{
		case -1:
			return false;
			break;

		case 0:
		case 1:
			return true;
			break;
	}
			
}


function db_setutf8($dbconnection)
{
	if ( db_versionok($dbconnection) && php_versionok() )
	{
		// we can use mysql_set_charset()
		if (mysql_set_charset('utf8', $dbconnection))
		{
			//printf("\n<!-- mysql_set_charset() call success -->\n");
			return true;
		}
		else
		{
			//printf("\n<!-- mysql_set_charset() call failed! -->\n");
			return false;
		}

	}
	else
	{
		// we have to do things the old-fashioned way

		// everybody needs a little spam in their diet
		//printf("\n<!-- db_setutf8(): MySQL and/or PHP version doesn't support mysql_set_charset - falling back to a SET NAMES 'utf8' db query -->\n");

		if ( mysql_query("SET NAMES 'utf8';", $dbconnection) === false )
		{
			//printf("\n<!-- db_setutf8(): SET NAMES 'utf8' query failed! -->\n");
			return false;
		}
		else
		{
			//printf("\n<!-- db_setutf8(): SET NAMES 'utf8' query succeeded -->\n");
			return true;
		}
	}


}

?>
