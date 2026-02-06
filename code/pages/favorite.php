<?php
 require_once __DIR__.'/../components/default/session.php';

 if(!isset($_SESSION["id"]))
 {
  header("Location: ../pages/index.php");
  exit();
 }
 $title="Favorite page";
 $favorite=1;
 require __DIR__.'/../components/head/head.php';
?>
<style>
*
{
 box-sizing:border-box;
 max-width:100%;
}

.table-container
{
 width:100%;
 overflow-x:auto;
 -webkit-overflow-scrolling:touch;
 margin-bottom:1.5rem;
}

.table-container table
{
 width:100%;
 border-collapse:collapse;
 min-width:400px;
 font-size:0.9rem;
}

table th,table td
{
 padding:0.5rem;
 border:1px solid #ccc;
 text-align:left;
 white-space:nowrap;
}

table td
{
 word-break:break-word;
}

.responsive-form
{
 display:flex;
 flex-wrap:wrap;
 gap:1rem;
 justify-content:space-between;
 padding:0.2rem;
}

.responsive-form .form-group
{
 flex:1 1 30%;
 min-width:200px;
 display:flex;
 margin-top:1rem;
 flex-direction:column;
}

.responsive-form .form-buttons
{
 flex:1 1 100%;
 display:flex;
 flex-wrap:wrap;
 justify-content:flex-end;
 gap:0.5rem;
 margin:1rem 0;
}

.input
{
 padding:0.2rem;
 border-radius:1rem;
 border:1px solid #ccc;
 width:100%;
 font-size:1rem;
}

.button,a.button
{
 display:inline-block;
 padding:0.5rem 1rem;
 border-radius:1rem;
 background-color:#e9e9e9;
 color:#18191f;
 border:none;
 cursor:pointer;
 text-decoration:none;
 text-align:center;
}

.button:hover,a.button:hover
{
 background-color:#3d3f4f;
}

.elementBg h2
{
 font-size:1.3rem;
 word-wrap:break-word;
}

.flexCenter
{
 display:flex;
 flex-wrap:wrap;
 justify-content:center;
 align-items:center;
 text-align:center;
}

@media (max-width:600px)
{
 table th,table td
 {
  font-size:0.85rem;
  padding:0.4rem;
 }

 .responsive-form .form-group
 {
  flex:1 1 100%;
  min-width:0;
 }
}

@media (max-width:300px)
{
 html,body
 {
  font-size:14px;
  padding:0;
  margin:0;
 }

 .responsive-form
 {
  flex-direction:column;
  padding:0.4rem;
  gap:0.6rem;
 }

 .responsive-form .form-group
 {
  flex:1 1 100%;
  min-width:0;
 }

 .form-buttons
 {
  flex-direction:column;
  align-items:stretch;
 }

 .button,a.button
 {
  width:100%;
  padding:0.6rem;
  font-size:0.9rem;
 }

 table th,table td
 {
  font-size:0.8rem;
  padding:0.3rem;
 }

 h2
 {
  font-size:1.1rem;
 }

 select.input,input.input
 {
  font-size: 0.9rem;
 }
}
</style>
 <body>
 <?php require __DIR__.'/../components/nav/nav.php';?>
  <main class="bg">
  <?php
   require __DIR__."/../components/default/db.php";

   $userID=$_SESSION["id"];
   $msg="";

   if(isset($_POST['add_fav']))
   {
    $stmt=$pdo->prepare("INSERT INTO favoriteLocations (userID, destinationID) VALUES (?, ?)");
    $stmt->execute([$userID, $_POST['destinationID']]);
    $msg="Dodato u omiljene!";
   }

   if(isset($_POST['update_fav']))
   {
    $stmt=$pdo->prepare("UPDATE favoriteLocations SET destinationID=? WHERE favoriteLocationsID=? AND userID=?");
    $stmt->execute([$_POST['destinationID'], $_POST['favoriteLocationsID'], $userID]);
    $msg="Omiljena lokacija izmenjena!";
   }

   if(isset($_GET['delete_fav']))
   {
    $stmt=$pdo->prepare("DELETE FROM favoriteLocations WHERE favoriteLocationsID=? AND userID=?");
    $stmt->execute([$_GET['delete_fav'], $userID]);
    $msg="Omiljena lokacija obrisana!";
   }

   $detail=null;
   if(isset($_GET['detail']))
   {
    $stmt=$pdo->prepare("SELECT d.*, c.cityName, co.countryName FROM destinations d LEFT JOIN cities c ON d.cityID=c.cityID LEFT JOIN countries co ON c.countryID=co.countryID WHERE d.destinationID=?");
    $stmt->execute([$_GET['detail']]);
    $detail=$stmt->fetch();
    $images=$pdo->prepare("SELECT * FROM destinationImages WHERE destinationID=?");
    $images->execute([$_GET['detail']]);
    $detailImages=$images->fetchAll();
   }

   $destinations=$pdo->query("SELECT d.destinationID, d.destinationName, c.cityName, co.countryName FROM destinations d LEFT JOIN cities c ON d.cityID=c.cityID LEFT JOIN countries co ON c.countryID=co.countryID ORDER BY d.destinationName")->fetchAll();

   $favorites=$pdo->prepare("SELECT f.favoriteLocationsID, d.destinationID, d.destinationName, c.cityName, co.countryName FROM favoriteLocations f JOIN destinations d ON f.destinationID=d.destinationID LEFT JOIN cities c ON d.cityID=c.cityID LEFT JOIN countries co ON c.countryID=co.countryID WHERE f.userID=? ORDER BY f.favoriteLocationDateTimeAdded DESC");
   $favorites->execute([$userID]);
   $favorites=$favorites->fetchAll();
  ?>
  <div class="flexCenter">
   <h2 class="elementBg">Omiljene destinacije</h2>
  </div>
  <?php if($msg) echo "<p><b>$msg</b></p>"; ?>

  <div class="flexCenter">
   <form method="post" class="elementBg">
    <br>
    <select name="destinationID" class="input" required>
     <option value="">--Izaberi destinaciju--</option>
     <?php foreach($destinations as $d): ?>
     <option value="<?= $d['destinationID'] ?>">
      <?= htmlspecialchars($d['destinationName']." (".$d['cityName'].", ".$d['countryName'].")") ?>
     </option>
     <?php endforeach; ?>
    </select>
    <br>
    <br>
    <div class="flexCenter">
     <button type="submit" class="button" name="add_fav">Dodaj u omiljene</button>
    </div>
    <br>
   </form>
  </div>
  <br>
<div class="table-container elementBg">
  <table class="flexCenter">
   <tr><th>ID</th><th>Destinacija</th><th>Grad</th><th>Država</th><th>Akcije</th></tr>
   <?php foreach($favorites as $f): ?>
   <tr>
    <td><?= $f['favoriteLocationsID'] ?></td>
    <td><?= htmlspecialchars($f['destinationName']) ?></td>
    <td><?= htmlspecialchars($f['cityName']) ?></td>
    <td><?= htmlspecialchars($f['countryName']) ?></td>
    <td>
     <form method="post" style="display:inline">
      <input type="hidden" name="favoriteLocationsID" value="<?= $f['favoriteLocationsID'] ?>">
      <select name="destinationID" required>
      <?php foreach($destinations as $d): ?>
      <option value="<?= $d['destinationID'] ?>" <?= ($d['destinationID']==$f['destinationID'])?'selected':'' ?>>
       <?= htmlspecialchars($d['destinationName']." (".$d['cityName'].", ".$d['countryName'].")") ?>
      </option>
      <?php endforeach; ?>
      </select>
      <button type="submit" name="update_fav">Izmeni</button>
     </form> |
     <a href="?delete_fav=<?= $f['favoriteLocationsID'] ?>" onclick="return confirm('Obrisati omiljenu lokaciju?')">Obriši</a> |
     <a href="?detail=<?= $f['destinationID'] ?>">Detalji</a>
    </td>
   </tr>
   <?php endforeach; ?>
  </table>
</div>
  <?php if($detail): ?>
  <hr>
  <h2>Detalji o destinaciji</h2>
  <h3><?= htmlspecialchars($detail['destinationName']) ?></h3>
  <p><b>Grad:</b> <?= htmlspecialchars($detail['cityName']) ?>, <?= htmlspecialchars($detail['countryName']) ?></p>
  <p><b>Opis:</b><br><?= nl2br(htmlspecialchars($detail['destinationDescription'])) ?></p>
  <h4>Slike:</h4>
  <?php if($detailImages): ?>
   <?php foreach($detailImages as $img): ?>
    <img src="uploads/destinations/<?= htmlspecialchars($img['destinationImageName']) ?>" alt="destinationImageName">
    <?php endforeach; ?>
    <?php else: ?>
    <p>Nema slika.</p>
    <?php endif; ?>
    <a href="favorite.php">← Nazad na listu</a>
    <?php endif; ?>
  </main>
 </body>
</html>
