<?php

require __DIR__.'/../libraries/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail=new PHPMailer(true);

if($_SERVER["REQUEST_METHOD"]==="POST")
{
 $email=trim($_POST["email"]);

 try
 {
  $errors=[];

  require_once __DIR__.'/../default/db.php';
  require_once __DIR__.'/../default/session.php';

  if(emptyInput($email))
   $errors["emptyInput"]="Fill in a email field!";

  if(invalidEmail($email))
   $errors["invalidEmail"]="Choose a proper email!";

  if(!(existingEmail($email)))
   $errors["emailTaken"]="Email is not registered!";

  if($errors)
  {
   $_SESSION["errorsAtResendVerificationEmail"]=$errors;

   header("Location: ../../pages/resendVerificationEmail.php");
   exit();
  }
  $sql="SELECT * FROM users WHERE userEmail=:email LIMIT 1;";

  $stmt=$GLOBALS["pdo"]->prepare($sql);
  $stmt->bindParam(":email",$email);
  $stmt->execute();

  $result=$stmt->fetch();

  if($result)
  {
   if(!($result["userStatus"]==="deleted"))
   {
    if($result["userStatus"]==="activated")
    {
     header("Location: ../../pages/resendverificationemail.php?resendVerificationEmail=alreadyVerified");
     exit();
    }
    else
    {
     $firstname=$result["userFirstname"];
     $lastname=$result["userLastname"];
     $token=$result["userToken"];

     $updatedResults=resendEmailVerification($email,$firstname,$lastname,$token,$mail);

     if($updatedResults)
     {
      header("Location: ../../pages/resendverificationemail.php?resendVerificationEmail=success");
      exit();
     }
     else
     {
      header("Location: ../../pages/resendverificationemail.php?resendVerificationEmail=faileddas1");
      exit();
     }
    }
   }
   else
   {
    header("Location: ../../pages/resendverificationemail.php?resendVerificationEmail=emailIsDeleted");
    exit();
   }
  }
  else
  {
   header("Location: ../../pages/resendverificationemail.php?resendVerificationEmail=fdasdailed");
   exit();
  }
 }
 catch(PDOException $e)
 {
  exit("Query failed: ".$e->getMessage());
 }
}
else
{
 header("Location: ../pages/resendverificationemail.php");
 exit();
}

function emptyInput($email)
{
 $result=true;

 if(empty(trim($email)))
  $result=true;
 else
  $result=false;

 return $result;
}

function invalidEmail($email)
{
 $result=true;
 
 if(!(filter_var($email,FILTER_VALIDATE_EMAIL)))
  $result=true;
 else
  $result=false;

 return $result;
}

function existingEmail($email)
{
 $result=true;
 
 $sql="SELECT * FROM users WHERE userEmail=:email LIMIT 1;";

 $stmt=$GLOBALS["pdo"]->prepare($sql);
 $stmt->bindParam(":email",$email);
 $stmt->execute();
 $answer=$stmt->fetch();

 if($answer)
  $result=true;
 else
  $result=false;

 return $result;
}
function resendEmailVerification($email,$firstname,$lastname,$token,$mail)
{
 $result=false;
 $subject="Resend a email verification";
 $url="http://127.0.0.1/touristAgency/code/components/signUp/verifyEmail.php?token=";
 $HTMLContent=
  '
   <div style="background:#18191f;">
    <br>
    <div style="background:#3d3f4f;">
     <div style="display:flex;justify-content:center;">
      <h3 style="color:#e9e9e9;padding:1rem;">Hi '.htmlspecialchars($firstname).' '.htmlspecialchars($lastname).', you have signed up.</h3>
     </div>
     <br>
     <div style="display:flex;justify-content:center;">
      <p style="color:#e9e9e9;">Please, verify your email address with the link given below.</p>
     </div>
     <br>
     <div style="display:flex;justify-content:center;">
      <a href="'.$url.$token.'" style="color:gold;">Activation link</a>
     </div>
     <br>
    </div>
    <br>
   </div>
  ';
 $content="Hi ".$firstname." ".$lastname.", you have signed up.\r\nPlease, verify your email address with the link given below.\r\n\r\nActivation link: ".$url.$token;
 try
 {
  $mail->isSMTP();
  $mail->Host='smtp.gmail.com';
  $mail->SMTPAuth=true;
  $mail->SMTPSecure=PHPMailer::ENCRYPTION_STARTTLS;
  $mail->Port=587;
  $mail->Username='zeljkorasovicskola@gmail.com';
  $mail->Password='vzps icea dkvm ffkz';
  $mail->Priority=1;
  $mail->CharSet='UTF-8';
  $mail->Subject=$subject;
  $mail->setFrom('zeljkorasovicskola@gmail.com',$firstname." ".$lastname);
  $mail->addAddress($email);
  $mail->isHTML(true);
  $mail->Body=$HTMLContent;
  $mail->AltBody=$content;
  $mail->send();
  $result=true;
  return $result;
 }
 catch(Exception $e)
 {
   $result=false;
   header("Location: ../../pages/resendverificationemail.php?resendVerificationEmail=failedSend");
   exit();
 }
}
?>
