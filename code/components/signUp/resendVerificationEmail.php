   <?php
    require __DIR__.'/resendVerificationEmailErrorPrinter.php';
   ?>
   <section class="flexCenter">
    <div class="elementBg">
     <div class="flexCenter">
      <h2>Resend verification email</h2>
     </div>
     <form method="POST" action="../components/signUp/resendVerificationEmailHandler.php">
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
      <a class="button" href="signup.php">Sign up</a>
     </div>
     <br>
    </div>
   </section>
   <br>
   <section>
    <?php
     checkResendVerificationEmailErrors();
    ?>
   </section>
