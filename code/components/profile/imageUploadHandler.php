<?php

if($_SERVER["REQUEST_METHOD"]==="POST")
{
 $fileName=$_FILES["image"]["name"];
 $fileTempName=$_FILES["image"]["tmp_name"];
 $fileSize=$_FILES["image"]["size"];
 $fileError=$_FILES["image"]["error"];
 $fileType=$_FILES["image"]["type"];

 $fileExtTemp=pathinfo($fileName,PATHINFO_EXTENSION);
 $fileExt=strtolower($fileExtTemp);

 try
 {
  require_once __DIR__.'/../default/db.php';
  require_once __DIR__.'/../default/session.php';

  if($fileError)
  {
   header("Location: ../../pages/profile.php?image=fileError");
   exit();
  }
  else
  {
   if(is_uploaded_file($fileTempName))
   {
    if(!exif_imagetype($fileTempName))
    {
     header("Location: ../../pages/profile.php?image=fileIsNotAImage");
     exit();
    }
    else
    {
     if($fileSize<4194304)
     {
      $newFileName="profile".$_SESSION["id"].".".$fileExt;
      $destination="../../upload/users/".$newFileName;

      $move=move_uploaded_file($fileTempName,$destination);

      if($move)
      {
       $result=updateProfileImage($newFileName,$_SESSION["id"]);

       if($result)
       {
        $_SESSION["imgName"]=$newFileName;
        header("Location: ../../pages/profile.php?image=uploadSuccess");
        exit();
       }
       else
       {
        header("Location: ../../pages/profile.php?image=profileImageStatusIsNotUpdated");
        exit();
       }
      }
      else
      {
       header("Location: ../../pages/profile.php?image=moveFailed");
       exit();
      }
     }
     else
     {
      header("Location: ../../pages/profile.php?image=fileIsTooBig");
      exit();
     }
    }
   }
   else
   {
    header("Location: ../../pages/profile.php?image=fileIsNotUploaded");
    exit();
   }
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


function updateProfileImage($name,$id)
{
 $sql='UPDATE profileImages SET userImageStatus="uploaded", userImageName=:name WHERE userID=:id;';
 
 $stmt=$GLOBALS["pdo"]->prepare($sql);
 $stmt->bindParam(":name",$name);
 $stmt->bindParam(":id",$id);

 if($stmt->execute())
  $result=true;
 else
  $result=false;
 
 return $result;
}

?>
