<?php
 $title="Change a password";
 require_once __DIR__.'/../components/head/head.php';
?>
 <body>
  <?php
   require_once __DIR__.'/../components/nav/nav.php';
   require_once __DIR__.'/../components/logIn/passwordChangeErrorPrinting.php';

   $token="";
   $email="";

   if(isset($_GET["token"]) && isset($_GET["email"]))
   {
    $token=$_GET["token"];
    $email=$_GET["email"];
   }
  ?>
  <main>
   <section class="bg">
    <div class="flexCenter">
     <div class="elementBg">
      <div class="flexCenter">
       <h2>Change a password</h2>
      </div>
      <div class="flexCenter">
       <form method="POST" action="../components/logIn/passwordChangeHandler.php">
        <input type="hidden" name="email" required value=<?php echo $email; ?>>
        <input type="checkbox" id="togglePassword">
        <div class="flex">
         <div class="flexLeft">
          <label for="Password">Password:</label>
         </div>
         <div class="flexRight">
          <label for="togglePassword"><img src="../../img/svg/eye.svg" width="14px" alt="eye" class="buttonEffect"></label>
         </div>
        </div>
        <div>
         <input type="password" name="password" placeholder="Password" required autocomplete="on" id="Password" class="input">
        </div>
        <br>
        <div class="flex">
         <div class="flexLeft">
          <label for="PasswordRepeat">Password repeat:</label>
         </div>
         <div class="flexRight">
          <label for="togglePassword"><img src="../../img/svg/eye.svg" width="14px" alt="eye" class="buttonEffect"></label>
         </div>
        </div>
        <div>
         <input type="password" name="passwordRepeat" placeholder="Password again" required autocomplete="on" id="PasswordRepeat" class="input">
        </div>
        <input type="hidden" name="token" required value=<?php echo $token; ?>>
        <br>
        <br>
        <div class="flexCenter">
         <input type="submit" class="button" name="submit" value="Send">
        </div>
        <br>
        <?php
         if(isset($_SESSION["id"]))
         {
          echo'<div class="line"></div>';
          echo'<br>';
          echo'<div class="flexCenter">';
          echo' <a href="profile.php" class="button">Profile</a>';
          echo'</div>';
          echo'<br>';
         }
         ?>
       </form>
      </div>
     </div>
    </div>
   </section>
   <section>
    <br>
    <?php
     checkPasswordChangeErrors();
    ?>
   </section>
   <script defer>
    document.addEventListener("DOMContentLoaded",function()
    {
     let toggle=document.getElementById("togglePassword");
     let password=document.getElementById("Password");
     let passwordRepeat=document.getElementById("PasswordRepeat");

     toggle.addEventListener('change',togglePassword);

     function togglePassword()
     { 
      if(password.type==="password")
      {
       password.type="text";
       passwordRepeat.type="text";
      }
      else
      {
       password.type="password";
       passwordRepeat.type="password";
      }
     }
    });
   </script>
  </main>
 </body>
</html>
