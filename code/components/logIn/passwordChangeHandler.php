<?php

if($_SERVER["REQUEST_METHOD"]==="POST")
{
 $email=trim($_POST["email"]);
 $password=trim($_POST["password"]);
 $passwordRepeat=trim($_POST["passwordRepeat"]);
 $token=trim($_POST["token"]);

 try
 {
  $errors=[];

  require_once __DIR__.'/../default/db.php';
  require_once __DIR__.'/../default/session.php';

  if(emptyInputs($password,$passwordRepeat,$email,$token))
   $errors["emptyInput"]="Fill in all fields!";

  if(invalidEmail($email))
   $errors["invalidEmail"]="Choose a proper email!";

  if(!(existingEmail($email)))
   $errors["emailTaken"]="That email does not exist!";

  if(invalidPasswordLength($password))
   $errors["passwordLength"]="Passwords is to short!";

  if(passwordMatch($password,$passwordRepeat))
   $errors["passwordMatch"]="Passwords do not match!";

  if($errors)
  {
   $_SESSION["errorsAtPasswordChange"]=$errors;

   header("Location: ../../pages/passwordchange.php?email=$email&token=$token");
   exit();
  }

  $answer=tokenVerification($email,$token);

  if($answer)
  {
   $result=passwordUpdate($password,$email,$token);

   if($result)
   {
    $newToken=md5(rand());
    $update=tokenUpdate($email,$token,$newToken);
    if($update)
    {
     if(isset($_SESSION["id"]))
     {
      header("Location: ../../pages/profile.php?passwordChange=passwordIsUpdated");
      exit();
     }
     else
     {
      header("Location: ../../pages/login.php?passwordChange=passwordIsUpdated");
      exit();
     }
    }
    else
    {
     header("Location: ../../pages/passwordchange.php?passwordChange=tokenIsNotUpdated");
     exit();
    }
   }
   else
   {
    header("Location: ../../pages/passwordchange.php?passwordChange=passwordIsNotUpdated&email=$email&token=$token");
    exit();
   }
  }
  else
  {
   header("Location: ../../pages/passwordchange.php?passwordChange=InvalidTokenOrEmail&email=$email&token=$token");
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
 header("Location: ../../pages/login.php");
 exit();
}


function emptyInputs($password,$passwordRepeat,$email,$token)
{
 $result=true;
 
 if(empty($password) && empty($passwordRepeat) && empty($email) && empty($token))
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

function invalidPasswordLength($password)
{
 $result=true;

 if(!(preg_match('/^[\S]{8,64}$/u',$password)))
  $result=true;
 else
  $result=false;
 
 return $result;
}

function passwordMatch($password,$passwordRepeat)
{
 $result=true;

 if($password!==$passwordRepeat)
  $result=true;

 else
  $result=false;
 
 return $result;
}

function existingEmail($email)
{
 $sql="SELECT * FROM users WHERE userEmail=:email LIMIT 1;";

 $stmt=$GLOBALS["pdo"]->prepare($sql);
 $stmt->bindParam(":email",$email);
 $stmt->execute();

 $result=$stmt->fetch();
 return $result;
}

function tokenVerification($email,$token)
{
 $sql='SELECT * FROM users WHERE userEmail=:email AND userToken=:token LIMIT 1;';

 $stmt=$GLOBALS["pdo"]->prepare($sql);
 $stmt->bindParam(":email",$email);
 $stmt->bindParam(":token",$token);
 $stmt->execute();

 $result=$stmt->fetch();
 return $result;
}

function passwordUpdate($password,$email,$token)
{
 $sql='UPDATE users SET userPassword=:pass WHERE userEmail=:email AND userToken=:token LIMIT 1;';

 $options=['cost'=>12];
 $hashedPass=password_hash($password,PASSWORD_BCRYPT,$options);

 $stmt=$GLOBALS["pdo"]->prepare($sql);
 $stmt->bindParam(":pass",$hashedPass);
 $stmt->bindParam(":email",$email);
 $stmt->bindParam(":token",$token);

 if($stmt->execute())
  $result=true;
 else
  $result=false;

 return $result;
}

function tokenUpdate($email,$token,$newToken)
{
 $sql='UPDATE users SET userToken=:newToken WHERE userEmail=:email AND userToken=:token LIMIT 1;';

 $stmt=$GLOBALS["pdo"]->prepare($sql);
 $stmt->bindParam(":newToken",$newToken);
 $stmt->bindParam(":email",$email);
 $stmt->bindParam(":token",$token);

 if($stmt->execute())
  $result=true;
 else
  $result=false;

 return $result;
}

?>
