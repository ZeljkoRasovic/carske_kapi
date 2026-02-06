<?php
 ini_set('session.use_cookies',1);
 ini_set('session.use_only_cookies',1);
 ini_set('session.cookie_secure',1);
 ini_set('session.cookie_httponly',1);
 ini_set('session.cookie_samesite','None');
 ini_set('session.use_strict_mode',1);
 ini_set('session.name','session');

 session_start();

 if(!isset($_SESSION["last_regeneration"]))
 {
  session_regenerate_id(true);
  $_SESSION["last_regeneration"]=time();
 }
 else
 {
  if(time()-$_SESSION["last_regeneration"]>=1800)
  {
   session_regenerate_id(true);
   $_SESSION["last_regeneration"]=time();
  }
 }
?>
