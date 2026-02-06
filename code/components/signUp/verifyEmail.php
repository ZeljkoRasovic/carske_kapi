<?php

require_once __DIR__.'/../default/db.php';

if(isset($_GET["token"]))
{
 $token=$_GET["token"];
 $sql="SELECT * FROM users WHERE userToken=:token LIMIT 1;";

 $stmt=$GLOBALS["pdo"]->prepare($sql);
 $stmt->bindParam(":token",$token);
 $stmt->execute();

 $result=$stmt->fetch();
 $id=$result["userID"];

 if($result)
 {
  if(!($result["userStatus"]==="deleted"))
  {
   if(!($result["userStatus"]==="activated"))
   {
    if($result["userStatus"]==="pending")
    {
     $sql="UPDATE users SET userStatus='activated' WHERE userID=:id;";

     $stmt=$GLOBALS["pdo"]->prepare($sql);
     $stmt->bindParam(":id",$id);

     if($stmt->execute())
      $updatedResults=true;
     else
      $updatedResults=false;

     if($updatedResults)
     {
      $token=md5(rand());
      $sql="UPDATE users SET userToken=:token WHERE userID=:id;";

      $stmt=$GLOBALS["pdo"]->prepare($sql);
      $stmt->bindParam(":token",$token);
      $stmt->bindParam(":id",$id);

      if($stmt->execute())
       $updatedToken=true;
      else
       $updatedToken=false;

      if($updatedToken)
      {
       header("Location: ../../pages/signup.php?signup=success");
       exit();
      }
      else
      {
       header("Location: ../../pages/signup.php?signup=failedToChangeToken");
       exit();
      }
     }
     else
     {
      header("Location: ../../pages/signup.php?signup=statusUpdateFailed");
      exit();
     }
    }
    else
    {
     header("Location: ../../pages/signup.php?signup=notImplementedYet");
     exit();
    }
   }
   else
   {
    header("Location: ../../pages/signup.php?signup=alreadyVerified");
    exit();
   }
  }
  else
  {
   header("Location: ../../pages/signup.php?signup=emailIsDeleted");
   exit();
  }
 }
 else
 {
  header("Location: ../../pages/signup.php?signup=failed");
  exit();
 }
}
else
{
 header("Location: ../../pages/signup.php");
 exit();
}

?>
