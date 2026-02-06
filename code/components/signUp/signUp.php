   <?php
    require __DIR__.'/../signUp/signUpErrorPrinter.php';
    require_once __DIR__.'/../default/session.php';
   ?>
   <section class="flexCenter">
    <div class="elementBg">
     <div class="flexCenter">
      <h2>Sign up</h2>
     </div>
     <form method="POST" action="../components/signUp/signUpHandler.php">
      <?php
       signupInputs();
      ?>
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
      <a href="resendverificationemail.php" class="small">Resend verification email?</a>
      <br>
      <br>
      <div class="flexCenter">
       <input type="submit" name="submit" value="Sign up" class="button">
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
     checkSignupErrors();
    ?>
   <section>
   <script defer>
    document.addEventListener("DOMContentLoaded",function()
    {
     let date=document.getElementById("BirthdateDate");
     let day=document.getElementById("BirthdateDayText");
     let month=document.getElementById("BirthdateMonthText");
     let year=document.getElementById("BirthdateYearText");
     let toggle=document.getElementById("togglePassword");
     let password=document.getElementById("Password");
     let passwordRepeat=document.getElementById("PasswordRepeat");

     const currentDate=new Date();
     currentDate.setFullYear(currentDate.getFullYear()-18);
     const dateFotmated=currentDate.toISOString().split('T')[0];

     date.setAttribute('max',dateFotmated);
     year.setAttribute('max',currentDate.getFullYear());

     function updateMax()
     {
      let d=parseInt(day.value);
      let m=parseInt(month.value);
      let y=parseInt(year.value);
      let max=31;

      if(!isNaN(m) && m>0 && m<13)
      {
       if(m===2)
       {
        if(!isNaN(y) && (y%4===0 && y%100!==0) || (y%400===0))
        {
         max=29;
        }
        else
        {
         max=28;
        }
       }
       else if(m===4 || m===6 || m===9 || m===11)
       {
        max=30;
       }
       else
       {
        max=31;
       }
      }

      day.setAttribute("max", max);

      if(!isNaN(d) && d>max)
      {
       day.value=max;
      }
     }

     day.addEventListener("input", updateMax);
     month.addEventListener("input", updateMax);
     year.addEventListener("input", updateMax);
     toggle.addEventListener('change',togglePassword);
     updateMax();

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
   <script src="../components/signUp/dinamicSelect.js" defer></script>
