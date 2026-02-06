<?php
 if(!isset($title))
 {
  $title="Carske kapi";
 }
 if(!isset($slider))
 {
  $slider=0;
 }
 if(!isset($log_in))
 {
  $log_in=0;
 }
 if(!isset($sign_up))
 {
  $sign_up=0;
 }
 if(!isset($profile))
 {
  $profile=0;
 }
 if(!isset($admin))
 {
  $admin=0;
 }
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?=htmlspecialchars($theme)?>">
 <head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex,nofollow,noimageindex,nosnippet,noarchive,notranslate">
  <meta name="author" content="Destilerija Carske Kapi">
  <meta name="description" content="Destilerija Carske Kapi">
  <meta name="keywords" content="Destilerija carske kapi">
  <title><?php echo $title;?></title>
  <link rel="stylesheet" href="../components/nav/nav.css">
  <link rel="icon" type="image/x-icon" href="../../img/logos/logo_small.png">
  <?php
   if($slider)
   {
    echo'<link rel="stylesheet" href="../components/slider/slider.css">';
   }
   if($sign_up)
   {
    echo'<link rel="stylesheet" href="../components/sign_up/sign_up.css">';
   }
   if($log_in)
   {
    echo'<link rel="stylesheet" href="../components/log_in/log_in.css">';
   }
   if($profile)
   {
    echo'<link rel="stylesheet" href="../components/profile/profile.css">';
   }
   if($admin)
   {
    echo'<link rel="stylesheet" href="../components/admin/admin.css">';
   }
  ?>
 </head>
