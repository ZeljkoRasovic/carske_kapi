<?php

function checkResendVerificationEmailErrors()
{
 if(isset($_SESSION["errorsAtResendVerificationEmail"]))
 {
  $errors=$_SESSION['errorsAtResendVerificationEmail'];

  foreach($errors as $error)
  {
   echo'<div class="flexCenter">';
   echo' <div class="elementBg">'.$error.'</div>';
   echo'</div>';
  }

  unset($_SESSION['errorsAtResendVerificationEmail']);
 }
 else if(isset($_GET["resendVerificationEmail"]) && $_GET["resendVerificationEmail"]==="failed")
 {
  echo'<div class="flexCenter">';
  echo' <div class="elementBg">Resending of your verification email failed!</div>';
  echo'</div>';
 }
 else if(isset($_GET["resendVerificationEmail"]) && $_GET["resendVerificationEmail"]==="emailIsDeleted")
 {
  echo'<div class="flexCenter">';
  echo' <div class="elementBg">Resending of your verification email failed!</div>';
  echo'</div>';
 }
 else if(isset($_GET["resendVerificationEmail"]) && $_GET["resendVerificationEmail"]==="failedSend")
 {
  echo'<div class="flexCenter">';
  echo' <div class="elementBg">We failed to send you a verification email failed!</div>';
  echo'</div>';
 }
 else if(isset($_GET["resendVerificationEmail"]) && $_GET["resendVerificationEmail"]==="alreadyVerified")
 {
  echo'<div class="flexCenter">';
  echo' <div class="elementBg">Your profile is already verified!</div>';
  echo'</div>';
 }
 else if(isset($_GET["resendVerificationEmail"]) && $_GET["resendVerificationEmail"]==="success")
 {
  echo'<div class="flexCenter">';
  echo' <div class="elementBg">Please check your emails, because we resend you a activation link!</div>';
  echo'</div>';
 }
}

?>
