<?php
/**
 * AdminProcess.php
 * 
 * The AdminProcess class is meant to simplify the task of processing
 * admin submitted forms from the admin center, these deal with
 * member system adjustments.
 *
 * Written by: Jpmaster77 a.k.a. The Grandmaster of C++ (GMC)
 * Last Updated: August 15, 2004
 */
include("../include/session.php");

class AdminProcess
{
   /* Class constructor */
   function AdminProcess(){
      global $session;
      /* Make sure administrator is accessing page */
      if(!$session->isAdmin()){
         header("Location: ../index.php");
         return;
      }
	   /* Admin added a user*/
      if(isset($_POST['subadduser'])){
         $this->procAddUser();
      }
      /* Admin submitted update user level form */
      else if(isset($_POST['subupdlevel'])){
         $this->procUpdateLevel();
      }
      /* Admin submitted delete user form */
      else if(isset($_POST['subdeluser'])){
         $this->procDeleteUser();
      }
      /* Admin submitted delete inactive users form */
      else if(isset($_POST['subdelinact'])){
         $this->procDeleteInactive();
      }
      /* Should not get here, redirect to home page */
      else{
         header("Location: ../index.php");
      }
   }
   
   /**
    * procAddUser- If the submitted username is correct,
    * the user is added
    */
   function procAddUser(){
    global $session, $database, $form;

	$retval = $session->register($_POST['user'], $_POST['pass'], $_POST['email']);
	header("Location: ".htmlspecialchars(trim($session->referrer)));
   }
   
   /**
    * procUpdateLevel - If the submitted username is correct,
    * their user level is updated according to the admin's
    * request.
    */
   function procUpdateLevel(){
      global $session, $database, $form;
      /* Username error checking */
      $subuser = $this->checkUsername("upduser");
      
      /* Errors exist, have user correct them */
      if($form->num_errors > 0){
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".htmlspecialchars(trim($session->referrer)));
      }
      /* Update user level */
      else{
         $database->updateUserField($subuser, "userlevel", (int)$_POST['updlevel']);
         header("Location: ".htmlspecialchars(trim($session->referrer)));
      }
   }
   
   /**
    * procDeleteUser - If the submitted username is correct,
    * the user is deleted from the database.
    */
   function procDeleteUser(){
      global $session, $database, $form;
      /* Username error checking */
      //$subuser = $this->checkUsername("deluser");
	  $subuser = htmlspecialchars(trim( $this->checkUsername("deluser")));
      
      /* Errors exist, have user correct them */
      if($form->num_errors > 0){
         $_SESSION['value_array'] = $_POST;
         $_SESSION['error_array'] = $form->getErrorArray();
         header("Location: ".htmlspecialchars(trim($session->referrer)));
      }
      /* Delete user from database */
      else{
         $q = "DELETE FROM ".TBL_USERS." WHERE username = '$subuser'";
         $database->query($q);
         header("Location: ".htmlspecialchars(trim($session->referrer)));
      }
   }
   
   /**
    * procDeleteInactive - All inactive users are deleted from
    * the database, not including administrators. Inactivity
    * is defined by the number of days specified that have
    * gone by that the user has not logged in.
    */
   function procDeleteInactive(){
      global $session, $database;
      $inact_time = (int)( ((int)(htmlspecialchars(trim($session->time)))) - ((int)(htmlspecialchars(trim($_POST['inactdays']))))*24*60*60);
      $inact_time = $database->escape_value($inact_time);
      $q = "DELETE FROM ".TBL_USERS." WHERE timestamp < $inact_time "
          ."AND userlevel != ".ADMIN_LEVEL;
      $database->query($q);
      header("Location: ".htmlspecialchars(trim($session->referrer)));
   }
   

   
   
   /**
    * checkUsername - Helper function for the above processing,
    * it makes sure the submitted username is valid, if not,
    * it adds the appropritate error to the form.
    */
   function checkUsername($uname, $ban=false){
      global $database, $form;
      /* Username error checking */
      $subuser = $_POST[$uname];
      $field = $uname;  //Use field name for username
      if(!$subuser || strlen($subuser = trim($subuser)) == 0){
         $form->setError($field, "* Username not entered<br>");
      }
      else{
      	
      	
      	
      	
      	
      	
         /* Make sure username is in database */
         $subuser = stripslashes($subuser);
         if(strlen($subuser) < 4 || strlen($subuser) > 30 ||
            !eregi("^([0-9a-z])+$", $subuser) ||
            (!$ban && !$database->usernameTaken($subuser))){
            $form->setError($field, "* Username does not exist<br>");
         }
      }
      return $subuser;
   }
};



/* Initialize process */
$adminprocess = new AdminProcess;

?>
