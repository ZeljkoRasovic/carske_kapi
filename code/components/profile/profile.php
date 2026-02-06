   <?php
    if(!isset($_SESSION["id"]))
    {
     header("Location: ../pages/index.php");
     exit();
    }
    require __DIR__.'/profileErrorPrinter.php';
    require_once __DIR__.'/../default/session.php';
    $link="passwordchange.php?token=".$_SESSION["token"]."&email=".$_SESSION["email"];
   ?>
   <section class="flexCenter">
    <div class="elementBg">
     <div class="flexCenter">
      <h2>Profile</h2>
     </div>
     <div class="flexCenter">       
      <label for="image" class="bg">
       <?php
         echo'<img src="../upload/users/'.$_SESSION["imgName"].'?'.mt_rand().' alt="avatar" width="128px">';
       ?>
      </label>
     </div>
     <form method="POST" action="../components/profile/imageUploadHandler.php" enctype="multipart/form-data">
      <input type="file" name="image" id="image" class="invisible" onchange="this.form.submit()">
     </form>
     <form method="POST" action="../components/profile/imageDeleteHandler.php">
      <div class="flexCenter">
       <input type="submit" name="submit" class="button" value="Delete a profile image">
      </div>
     </form>
     <br>
     <form method="POST" action="../components/profile/profileHandler.php">
      <?php
       profileInputs();
      ?>
      <div class="flexCenter">
       <input type="submit" name="submit" value="Edit profile" class="button">
      </div>
     </form>
     <br>
     <div class="line"></div>
     <br>
     <a href="<?php echo "$link";?>" class="flexCenter"><input type="submit" class="button" value="Change password"></a>
     <br>
     <div class="line"></div>
     <br>
     <form method="POST" action="../components/profile/deleteProfile.php" class="flexCenter">
      <input type="submit" class="button" value="Delete profile">
     </form>
     <br>
     <div class="line"></div>
     <br>
     <form method="POST" action="../components/profile/logout.php" class="flexCenter">
      <input type="submit" class="button" value="Logout">
     </form>
     <br>
    </div>
   </section>
   <section>
    <br>
    <?php
     checkProfileErrors();
    ?>
   <section>
   <script defer>
    document.addEventListener("DOMContentLoaded",function()
    {
     let date=document.getElementById("BirthdateDate");
     let day=document.getElementById("BirthdateDayText");
     let month=document.getElementById("BirthdateMonthText");
     let year=document.getElementById("BirthdateYearText");

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
      updateMax();
     });
    </script>
    <script src="../components/profile/dinamicSelect.js" defer></script>
