<?php
if($_SERVER["REQUEST_METHOD"]==="POST")
{
 require_once __DIR__.'/../default/session.php';

 session_unset();
 session_destroy();

 header("Location: ../../pages/index.php");
 die();
}
else
{
 header("Location: ../../pages/index.php");
 die();
}
?>
