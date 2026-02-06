<?php

if($_SERVER["REQUEST_METHOD"]==="POST")
{
 require_once __DIR__.'/../default/db.php';
 require_once __DIR__.'/../default/session.php';
 
 $id=$_SESSION["id"];

 $result=deleteProfileImage($id);
 $answer=deleteUser($id);

 if($result && $answer)
 {
  require_once __DIR__.'/../default/session.php';

  session_unset();
  session_destroy();
  header("Location: ../../pages/index.php");
  exit();
 }
 else
 {
  header("Location: ../../pages/profile.php?deleteProfile=failed");
  exit();
 }
}
else
{
 header("Location: ../../pages/index.php");
 exit();
}

function deleteProfileImage($id)
{
 $sql='UPDATE profileImages SET userImageStatus="deleted" WHERE userID=:id;';

 $stmt=$GLOBALS["pdo"]->prepare($sql);
 $stmt->bindParam(":id",$id);
 
 if($stmt->execute())
  $result=true;
 else
  $result=false;
 
 return $result;
}

function deleteUser($id)
{
 /*$sql='DELETE FROM users WHERE userID=:id;';*/
 $sql='UPDATE users SET userStatus="deleted" WHERE userID=:id;';

 $stmt=$GLOBALS["pdo"]->prepare($sql);
 $stmt->bindParam(":id",$id);
 
 if($stmt->execute())
  $result=true;
 else
  $result=false;
 
 return $result;
}
?>
