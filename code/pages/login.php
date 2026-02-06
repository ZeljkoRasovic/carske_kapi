<?php
 $title="Log in page";
 require __DIR__.'/../components/head/head.php';
?>
 <body>
  <?php require __DIR__.'/../components/nav/nav.php';?>
  <main class="bg">
   <?php
    require __DIR__.'/../components/logIn/logIn.php';
    require_once __DIR__.'/../components/logIn/loginErrorPrinter.php';
   ?>
   <section>
    <br>
    <?php
     checkLoginErrors();
    ?>
  </main>
 </body>
</html>
