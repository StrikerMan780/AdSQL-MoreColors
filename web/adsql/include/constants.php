<?php

define("TBL_USERS", "users");
define("TBL_ACTIVE_USERS",  "active_users");
define("TBL_ACTIVE_GUESTS", "active_guests");
define("TBL_BANNED_USERS",  "banned_users");

define("ADMIN_NAME", "Admin");
define("GUEST_NAME", "Guest");
define("ADMIN_LEVEL", 9);
define("USER_LEVEL",  1);
define("GUEST_LEVEL", 0);

define("TRACK_VISITORS", true);

define("USER_TIMEOUT", 10);
define("GUEST_TIMEOUT", 5);

define("COOKIE_EXPIRE", 60*60*24*100);  //100 days by default
define("COOKIE_PATH", "/adsmysql/");  //Avaible in whole domain

define("ALL_LOWERCASE", false);
?>
