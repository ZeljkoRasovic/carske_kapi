<?php

if($_SERVER["REQUEST_METHOD"]==="POST")
{
 $email=trim($_POST["email"]);
 $password=trim($_POST["password"]);

 try
 {
  $errors=[];

  require_once __DIR__.'/../default/db.php';
  require_once __DIR__.'/../default/session.php';

  if(emptyInputLogin($email,$password))
   $errors["emptyInput"]="Fill in all fields!";

  if(invalidEmail($email))
   $errors["invalidEmail"]="Choose a proper email!";

  $result=getUserData($email);

  if($result)
  {
   if(isPasswordWrong($password,$result["userPassword"]))
    $errors["incorrectLogin"]="Incorrect login info!";
  }
  else
  {
   $errors["incorrectLogin"]="Incorrect login info!";
  }

  if($errors)
  {
   $_SESSION["errorsAtLogin"]=$errors;
   header("Location: ../../pages/login.php");
   exit();
  }

  if($result["userStatus"]==="pending")
  {
   header("Location: ../../pages/login.php?login=notActivated");
   exit();
  }
  else if($result["userStatus"]==="deleted")
  {
   header("Location: ../../pages/login.php?login=deleted");
   exit();
  }
  else if($result["userStatus"]==="banned")
  {
   header("Location: ../../pages/login.php?login=banned");
   exit();
  }

  $_SESSION["id"]=htmlspecialchars($result["userID"]);
  $_SESSION["firstname"]=htmlspecialchars($result["userFirstname"]);
  $_SESSION["lastname"]=htmlspecialchars($result["userLastname"]);
  $_SESSION["birthdate"]=htmlspecialchars($result["userBirthDate"]);
  list($_SESSION["year"], $_SESSION["month"], $_SESSION["day"]) = explode('-', $_SESSION["birthdate"]);
  $_SESSION["countryID"]=htmlspecialchars($result["countryID"]);
  $_SESSION["cityID"]=htmlspecialchars($result["cityID"]);
  $_SESSION["address"]=htmlspecialchars($result["userAddress"]);
  $_SESSION["phone"]=htmlspecialchars($result["userPhone"]);
  $_SESSION["email"]=htmlspecialchars($result["userEmail"]);
  $_SESSION["token"]=htmlspecialchars($result["userToken"]);
  $_SESSION["role"]=htmlspecialchars($result["userRole"]);

  $answer=getImage($result["userID"]);
  $_SESSION["imgstatus"]=htmlspecialchars($answer["userImageStatus"]);

  if($answer["userImageStatus"]==="empty")
  {
   $_SESSION["imgName"]="DEFAULTpROFILEpICTURE.svg";
  }
  else
  {
   $_SESSION["imgName"]=$answer["userImageName"];
  }

  if($_SESSION["role"]==="agency")
  {
   header("Location: ../../pages/controlcenter.php");
   exit();
  }
  if($_SESSION["role"]==="admin")
  {
   header("Location: ../../pages/admin.php");
   exit();
  }
  else
  {
   header("Location: ../../pages/index.php");
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

function getUserData($email)
{
 $sql='SELECT * FROM users WHERE userEmail=:email LIMIT 1;';

 $stmt=$GLOBALS["pdo"]->prepare($sql);
 $stmt->bindParam(":email",$email);
 $stmt->execute();

 $result=$stmt->fetch();
 return $result;
}

function getImage($id)
{
 $sql='SELECT * FROM profileImages WHERE userID=:id LIMIT 1;';

 $stmt=$GLOBALS["pdo"]->prepare($sql);
 $stmt->bindParam(":id",$id);
 $stmt->execute();

 $result=$stmt->fetch();
 return $result;
}

function emptyInputLogin($email,$password)
{
 $result=true;
 
 if(empty(trim($email)) || empty(trim($password)))
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

function isPasswordWrong($password,$hashedPass)
{
 $result=true;

 if(!password_verify($password,$hashedPass))
  $result=true;
 else
  $result=false;

 return $result;
}
?>
