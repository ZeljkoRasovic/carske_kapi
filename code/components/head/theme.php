<?php
$themes=['light','dark'];

if(isset($_GET['theme']) && in_array($_GET['theme'],$themes,true))
{
 setcookie('theme',$_GET['theme'],time()+31536000,'/','', false,true);
 header('Location: '.strtok($_SERVER['REQUEST_URI'],'?'));
 exit();
}

if(isset($_COOKIE['theme']) && in_array($_COOKIE['theme'],$themes,true))
{
 $theme=$_COOKIE['theme'];
}
else
{
 $theme='auto';
}
?>
