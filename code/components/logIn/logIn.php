   <section class="flexCenter">
    <div class="elementBg">
     <div class="flexCenter">
      <h2>Log in</h2>
     </div>
     <form method="POST" action="../components/logIn/logInHandler.php">
      <div>
       <label for="Email">Email:</label>
       <br>
       <input type="email" name="email" placeholder="Email" required id="Email" autocomplete="on" class="input">
      </div>
      <br>
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
      <a href="passwordreset.php" class="small">Forgot a password?</a>
      <br>
      <br>
      <div class="flexCenter">
       <input type="submit" name="submit" value="Login" class="button">
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
   <script defer>
    document.addEventListener("DOMContentLoaded",function()
    {
     let toggle=document.getElementById("togglePassword");
     let password=document.getElementById("Password");
     toggle.addEventListener('change',togglePassword);

     function togglePassword()
     { 
      if(password.type==="password")
       password.type="text";

      else
       password.type="password";
     }
    });
   </script>
