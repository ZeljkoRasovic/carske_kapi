<?php

if($_SERVER["REQUEST_METHOD"]==="POST")
{
 try
 {
  require_once __DIR__.'/../default/db.php';
  require_once __DIR__.'/../default/session.php';

  $fileName='../../upload/users/'.$_SESSION["imgName"];

  if(file_exists($fileName))
  {
   if($_SESSION["imgName"]="DEFAULTpROFILEpICTURE.svg")
   {
    header("Location: ../../pages/profile.php");
    exit();
   }

   if(!unlink($file))
   {
    header("Location: ../../pages/profile.php?image=fileIsNotDeleted");
    exit();
   }
   else
   {
    $result=deleteProfileImage($_SESSION["id"]);

    if($result)
    {
     $_SESSION["imgName"]="DEFAULTpROFILEpICTURE.svg";
     header("Location: ../../pages/profile.php?image=fileIsDeleted");
     exit();
    }
    else
    {
     header("Location: ../../pages/profile.php?image=fileIsDeletedButProfileImageTableIsNotUpdated");
     exit();
    }
   }
  }
  else
  {
   header("Location: ../../pages/profile.php?image=fileWithThatNameDoesNotExist");
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
 header("Location: ../../pages/profile.php");
 exit();
}

function deleteProfileImage($id)
{
 $sql='UPDATE profileImages SET userImageStatus="empty", userImageName="DEFAULTpROFILEpICTURE.svg" WHERE userID=:id;';
 
 $stmt=$GLOBALS["pdo"]->prepare($sql);
 $stmt->bindParam(":id",$id);

 if($stmt->execute())
  $result=true;
 else
  $result=false;
 
 return $result;
}

?>
