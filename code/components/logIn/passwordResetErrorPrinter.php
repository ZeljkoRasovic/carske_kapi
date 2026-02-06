<?php

function checkPasswordResetErrors()
{
 if(isset($_SESSION["passwordResetErrors"]))
 {
  $errors=$_SESSION['passwordResetErrors'];

  foreach($errors as $error)
  {
   echo'<div class="flexCenter">';
   echo' <div class="elementBg">'.$error.'</div>';
   echo'</div>';
  }

  unset($_SESSION['errorsAtForgotAPassword']);
 }
 else if(isset($_GET["forgotAPassword"]) && $_GET["forgotAPassword"]==="failed")
 {
  echo'<div class="flexCenter">';
  echo' <div class="elementBg">Sending of your verification email failed!</div>';
  echo'</div>';
 }
 else if(isset($_GET["forgotAPassword"]) && $_GET["forgotAPassword"]==="notVerified")
 {
  echo'<div class="flexCenter">';
  echo' <div class="elementBg">This profile is not yet activated!</div>';
  echo'</div>';
 }
 else if(isset($_GET["forgotAPassword"]) && $_GET["forgotAPassword"]==="emailIsDeleted")
 {
  echo'<div class="flexCenter">';
  echo' <div class="elementBg">This profile is deleted!</div>';
  echo'</div>';
 }
 else if(isset($_GET["forgotAPassword"]) && $_GET["forgotAPassword"]==="failedToSendAMail")
 {
  echo'<div class="flexCenter">';
  echo' <div class="elementBg">We failed to send you a password reset email!</div>';
  echo'</div>';
 }
}
?>
