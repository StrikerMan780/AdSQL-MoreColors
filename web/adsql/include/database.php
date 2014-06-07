<?php
/**
 *
 * Written by: Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * Last Updated: August 15, 2004
 */
 
include("constants.php");
include("db_utf8.php");

class MySQLDB
{
   var $connection;       
   var $num_active_users;   
   var $num_active_guests;  
   var $num_members;  
   
   /////////////////////
   ///FOR ESCAPE FUNC///
   /////////////////////
   private $_magic_quotes_active;
   private $_real_escape_string_exists;
   /////////////////////
   /////////////////////
   /////////////////////
   
   function MySQLDB(){
     
      $this->connection = mysql_connect(DB_SERVER, DB_USER, DB_PASS) or die(mysql_error());
      mysql_select_db(DB_NAME, $this->connection) or die(mysql_error());

      if (db_setutf8($this->connection) === false)

	printf("\n<!-- database.php - failed to set UTF8 mode on db connection! -->\n");
      
      $this->num_members = -1;
      
      if(TRACK_VISITORS){
         /* Calculate number of users at site */
         $this->calcNumActiveUsers();
      
         /* Calculate number of guests at site */
         $this->calcNumActiveGuests();
      }
      /////////////////////
      ///FOR ESCAPE FUNC///
      /////////////////////
      $this->_magic_quotes_active = get_magic_quotes_gpc();
      $this->_real_escape_string_exists = function_exists( "mysql_real_escape_string" );
      /////////////////////
      /////////////////////
      /////////////////////
   }
   /////////////////////
   ///FOR ESCAPE FUNC///
   /////////////////////
   public function escape_value( $value ) {
   	if (!is_array($value)) {
   		if( $this->_real_escape_string_exists ) { // PHP v4.3.0 or higher
   			// undo any magic quote effects so mysql_real_escape_string can do the work
   			if( $this->_magic_quotes_active ) {
   				$value = stripslashes( $value );
   			}
   			$value = mysql_real_escape_string( $value );
   		} else { // before PHP v4.3.0
   			// if magic quotes aren't already on then add slashes manually
   			if( !$this->_magic_quotes_active ) {
   				$value = addslashes( $value );
   			}
   			// if magic quotes are active, then the slashes already exist
   		}
   	} else {
   		foreach ($value as $key => $val) {
   			$value[$key] = $this->escape_value($val);
   		}
   	}
   	return $value;
   }
   /////////////////////
   /////////////////////
   /////////////////////
   function confirmUserPass($username, $password){
// password comes in md5 no need to sanitize
   	// if(!get_magic_quotes_gpc()) {
	 //     $username = addslashes($username);
     // }
   	$username =  htmlspecialchars(trim($this->escape_value($username)));
      $q = "SELECT password FROM ".TBL_USERS." WHERE username = '$username'";
      $result = mysql_query($q, $this->connection);
      if(!$result || (mysql_numrows($result) < 1)){
         return 1; 
      }

      $dbarray = mysql_fetch_array($result);
      $dbarray['password'] = stripslashes($dbarray['password']);
      $password = stripslashes($password);

      if($password == $dbarray['password']){
         return 0;
      }
      else{
         return 2;
      }
   }

   function confirmUserID($username, $userid){
//      if(!get_magic_quotes_gpc()) {
//	      $username = addslashes($username);
//      }
	      	$username =  htmlspecialchars(trim($this->escape_value($username)));
      

      $q = "SELECT userid FROM ".TBL_USERS." WHERE username = '$username'";
      $result = mysql_query($q, $this->connection);
      if(!$result || (mysql_numrows($result) < 1)){
         return 1;
      }

      $dbarray = mysql_fetch_array($result);
      $dbarray['userid'] = stripslashes($dbarray['userid']);
      $userid = stripslashes($userid);

      if($userid == $dbarray['userid']){
         return 0;
      }
      else{
         return 2; 
      }
   }
   
   function usernameTaken($username){
  //    if(!get_magic_quotes_gpc()){
  //       $username = addslashes($username);
  //    }
   	$username =  htmlspecialchars(trim($this->escape_value($username)));
      $q = "SELECT username FROM ".TBL_USERS." WHERE username = '$username'";
      $result = mysql_query($q, $this->connection);
      return (mysql_numrows($result) > 0);
   }
   
   function addNewUser($username, $password, $email){
   	//password comes in md5 no need to escape
   	$username = htmlspecialchars(trim($this->escape_value($username)));
   	$email = htmlspecialchars(trim($this->escape_value($email)));
      $time = time();
      if(strcasecmp($username, ADMIN_NAME) == 0){
         $ulevel = ADMIN_LEVEL;
      }else{
         $ulevel = USER_LEVEL;
      }
      $q = "INSERT INTO ".TBL_USERS." VALUES ('$username', '$password', '0', $ulevel, '$email', $time)";
      return mysql_query($q, $this->connection);
   }

   function updateUserField($username, $field, $value){
   	$username = htmlspecialchars(trim($this->escape_value($username)));
   	$field = htmlspecialchars(trim($this->escape_value($field)));
   	$value = htmlspecialchars(trim($this->escape_value($value)));
      $q = "UPDATE ".TBL_USERS." SET `".$field."` = '$value' WHERE username = '$username'";
      return mysql_query($q, $this->connection);
   }
   
   function getUserInfo($username){
   	$username = htmlspecialchars(trim($this->escape_value($username)));
      $q = "SELECT * FROM ".TBL_USERS." WHERE username = '$username'";
      $result = mysql_query($q, $this->connection);
      
      if(!$result || (mysql_numrows($result) < 1)){
         return NULL;
      }
      $dbarray = mysql_fetch_array($result);
      return $dbarray;
   }
   
   function getNumMembers(){
      if($this->num_members < 0){
         $q = "SELECT * FROM ".TBL_USERS;
         $result = mysql_query($q, $this->connection);
         $this->num_members = mysql_numrows($result);
      }
      return $this->num_members;
   }
   
   function calcNumActiveUsers(){
      /* Calculate number of users at site */
      $q = "SELECT * FROM ".TBL_ACTIVE_USERS;
      $result = mysql_query($q, $this->connection);
      $this->num_active_users = mysql_numrows($result);
   }

   function calcNumActiveGuests(){
      /* Calculate number of guests at site */
      $q = "SELECT * FROM ".TBL_ACTIVE_GUESTS;
      $result = mysql_query($q, $this->connection);
      $this->num_active_guests = mysql_numrows($result);
   }
   
   function addActiveUser($username, $time){
   	$username = htmlspecialchars(trim($this->escape_value($username)));
      $q = "UPDATE ".TBL_USERS." SET timestamp = '$time' WHERE username = '$username'";
      mysql_query($q, $this->connection);
      
      if(!TRACK_VISITORS) return;
      $q = "REPLACE INTO ".TBL_ACTIVE_USERS." VALUES ('$username', '$time')";
      mysql_query($q, $this->connection);
      $this->calcNumActiveUsers();
   }
   
   function addActiveGuest($ip, $time){
   	$ip = htmlspecialchars(trim($this->escape_value($ip)));
      if(!TRACK_VISITORS) return;
      $q = "REPLACE INTO ".TBL_ACTIVE_GUESTS." VALUES ('$ip', '$time')";
      mysql_query($q, $this->connection);
      $this->calcNumActiveGuests();
   }
     
   function removeActiveUser($username){
   	$username = htmlspecialchars(trim($this->escape_value($username)));
      if(!TRACK_VISITORS) return;
      $q = "DELETE FROM ".TBL_ACTIVE_USERS." WHERE username = '$username'";
      mysql_query($q, $this->connection);
      $this->calcNumActiveUsers();
   }

   function removeActiveGuest($ip){
   	$ip = htmlspecialchars(trim($this->escape_value($ip)));
      if(!TRACK_VISITORS) return;
      $q = "DELETE FROM ".TBL_ACTIVE_GUESTS." WHERE ip = '$ip'";
      mysql_query($q, $this->connection);
      $this->calcNumActiveGuests();
   }
   
   function removeInactiveUsers(){
      if(!TRACK_VISITORS) return;
      $timeout = time()-USER_TIMEOUT*60;
      $q = "DELETE FROM ".TBL_ACTIVE_USERS." WHERE timestamp < $timeout";
      mysql_query($q, $this->connection);
      $this->calcNumActiveUsers();
   }
   
   function removeInactiveGuests(){
      if(!TRACK_VISITORS) return;
      $timeout = time()-GUEST_TIMEOUT*60;
      $q = "DELETE FROM ".TBL_ACTIVE_GUESTS." WHERE timestamp < $timeout";
      mysql_query($q, $this->connection);
      $this->calcNumActiveGuests();
   }
   
   function query($query){
      return mysql_query($query, $this->connection);
   }
};

$database = new MySQLDB;

?>
