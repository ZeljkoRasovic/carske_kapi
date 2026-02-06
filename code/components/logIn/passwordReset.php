<?php
 require_once __DIR__.'/../logIn/passwordResetErrorPrinter.php';
?>
   <section class="flexCenter">
    <div class="elementBg">
     <div class="flexCenter">
      <h2>Password reset</h2>
     </div>
     <form method="POST" action="../components/logIn/passwordResetHandler.php">
      <label for="Email">Email:</label>
      <br>
      <input type="email" name="email" placeholder="Enter your email" required id="Email" autocomplete="on" class="input">
      <br>
      <br>
      <div class="flexCenter">
       <input type="submit" name="submit" value="Send" class="button">
      </div>
     </form>
     <br>
     <div class="line"></div>
     <br>
     <div class="flexCenter">
      <a class="button" href="login.php">Log in</a>
     </div>
     <br>
    </div>
   </section>
   <section>
    <br>
    <?php
     checkPasswordResetErrors();
    ?>
   </section>
