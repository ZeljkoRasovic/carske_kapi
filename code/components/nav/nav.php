<?php
 require_once __DIR__.'/../default/session.php';
 
 if(!isset($_SESSION["role"]))
 {
  $_SESSION["role"]="";
 }
?>
  <header class="header bg">
   <div class="nav_left">
    <a href="index.php" id="logo" class="button_effect">
     <img src="../../img/logos/logo_small.png" alt="Logo" width="90px" height="52px">
    </a>
    <a href="index.php" id="logo_text" class="button_effect">
     <h2>Carske Kapi</h2>
    </a>
   </div>
   <div class="nav_center">
    <form method="GET" action="search_handler.php" class="nav_search">
     <div class="search_wrapper">
      <input type="text" placeholder="Search" name="search" id="nav_search_box" required>
      <input type="reset" value="&#10060;" id="nav_reset_button" aria-label="Clear search" class="button_effect">
      <input type="submit" value="&#128269;" id="nav_search_button" aria-label="Search" class="button_effect">
    </div>
   </form>
   </div>
   <div class="nav_right">
    <form class="theme_picker" method="GET" action="">
     <button name="theme" value="light" aria-label="Light theme">&#127774;</button>
     <button name="theme" value="dark" aria-label="Dark theme">&#127769;</button>
    </form>
    <input type="checkbox" id="nav-toggle">
    <label for="nav-toggle" class="ham_menu button_effect">
     <span></span>
     <span></span>
     <span></span>
    </label>
    <nav class="mobile_nav">
     <form method="GET" action="search_handler.php" class="nav_search">
      <div class="search_wrapper">
       <input type="text" placeholder="Search" name="search" id="nav_search_box" required>
       <input type="reset" value="&#10060;" id="nav_reset_button" aria-label="Clear search" class="button_effect">
       <input type="submit" value="&#128269;" id="nav_search_button" aria-label="Search" class="button_effect">
      </div>
     </form>
     <?php
      if($_SESSION["role"]==="user")
      {
       echo'<a href="profile.php" class="button_effect">Profile</a>';
       echo'<a href="logout.php" class="button_effect">Logout</a>';
      }
      elseif($_SESSION["role"]==="admin")
      {
       echo'<a href="admin.php" class="button_effect">Admin</a>';
       echo'<a href="logout.php" class="button_effect">Logout</a>';
      }
      else
      {
       echo'<a href="login.php" class="button_effect">Log in</a>';
       echo'<a href="signup.php" class="button_effect">Sign up</a>';
      }
     ?>
    </nav>
   </div>
  </header>
