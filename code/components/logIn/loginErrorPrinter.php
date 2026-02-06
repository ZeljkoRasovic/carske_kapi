<?php

require_once __DIR__.'/../default/session.php';

function checkLoginErrors()
{
 if(isset($_SESSION["errorsAtLogin"]))
 {
  $errors=$_SESSION["errorsAtLogin"];

  foreach($errors as $error)
  {
   echo'<div class="flexCenter">';
   echo' <div class="elementBg">'.$error.'</div>';
   echo'</div>';
  }

  unset($_SESSION['errorsAtLogin']);
 }
 else if(isset($_GET["login"]) && $_GET["login"]==="notActivated")
 {
  echo'<div class="flexCenter">';
  echo' <div class="elementBg">Your profile is not activated!</div>';
  echo'</div>';
 }
 else if(isset($_GET["login"]) && $_GET["login"]==="deleted")
 {
  echo'<div class="flexCenter">';
  echo' <div class="elementBg">Your profile is deleted!</div>';
  echo'</div>';
 }
 else if(isset($_GET["login"]) && $_GET["login"]==="banned")
 {
  echo'<div class="flexCenter">';
  echo' <div class="elementBg">Your profile is banned!</div>';
  echo'</div>';
 }
 else if(isset($_GET["passwordChange"]) && $_GET["passwordChange"]==="passwordIsUpdated")
 {
  echo'<div class="flexCenter">';
  echo' <div class="elementBg">Your password is successfully updated!</div>';
  echo'</div>';
 }
 else if(isset($_GET["forgotAPassword"]) && $_GET["forgotAPassword"]==="emailIsSented")
 {
  echo'<div class="flexCenter">';
  echo' <div class="elementBg">We send you a password reset link!</div>';
  echo'</div>';
 }
}

?>
